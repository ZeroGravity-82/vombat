<?php

namespace Vombat\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Illuminate\Support\Facades\Storage;
use Plank\Mediable\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class FileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FileController
    |--------------------------------------------------------------------------
    |
    | Управление файлами, загружаемыми пользователем.
    |
    */

    /**
     * Загружает файл на сервер файл.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return \Plank\Mediable\Media
     */
    public function uploadMedia(UploadedFile $file): Media
    {
        $disk = config('mediable.default_disk');
        list($directory, $filename) = $this->generateUniquePath($disk, $file);
        return MediaUploader::fromSource($file)
            ->useFilename($filename)
            ->toDestination($disk, $directory)
            ->upload();
    }

    /**
     * Загружает несколько файлов на сервер.
     *
     * @param \Illuminate\Http\UploadedFile[] $files
     * @return \Plank\Mediable\Media[]
     */
    public function uploadMultiple(array $files): array
    {
        $medias = [];
        foreach ($files as $file) {
            $medias[] = $this->uploadMedia($file);
        }
        return $medias;
    }

    /**
     * Отдать файл по GET-запросу.
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUploadedMedia(string $filename): Response
    {
        $media = Media::whereBasename($filename)->firstOrFail();
        $mediaDisk = $media->disk;
        $mediaDiskPath = $media->getDiskPath();
        $mediaFile = Storage::disk($mediaDisk)->get(DIRECTORY_SEPARATOR . $mediaDiskPath);
        return response($mediaFile, 200)
            ->header('Content-type', $media->mime_type);
    }

    /**
     * Сформировать уникальное имя для заданного файла на указанном диске.
     * Метод возвращает массив, первый элемент которого - папка, в которой будет храниться файл, второй элемент
     * массива - уникальное имя файла.
     *
     * @param string $disk
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    private function generateUniquePath(string $disk, UploadedFile $file): array
    {
        $storage = Storage::disk($disk);
        do {
            $filename = str_replace('.', '', uniqid(rand(), true));
            $directory = $this->generateDirectoryName($filename);
            $extension = $file->getClientOriginalExtension();
            $diskPath = "$directory/$filename.$extension";
        } while ($storage->exists($diskPath));
        return [$directory, $filename];
    }

    /**
     * Сформировать путь к файлу с указанным именем. Путь должен состоять из папок двойной вложенности.
     * Имена папок образованы из первых символов имени файла (имя папки первого уровня - первые два символа имени
     * файла, имя папки второго уровня - следующие два символа имени файла).
     *
     * @param string $filename
     * @return string
     */
    private function generateDirectoryName(string $filename): string
    {
        $splittedFilename = str_split($filename, 2);
        $firstTwoPieces = array_slice($splittedFilename, 0, 2);
        return implode(DIRECTORY_SEPARATOR, $firstTwoPieces);
    }
}