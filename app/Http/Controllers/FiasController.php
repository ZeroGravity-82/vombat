<?php

namespace Vombat\Http\Controllers;

use Vombat\WebServices\Fias;

class FiasController extends Controller
{
    public function show()
    {
        $fias = new Fias\Fias();
        dd($fias->getAllDownloadFileInfo());
    }
}
