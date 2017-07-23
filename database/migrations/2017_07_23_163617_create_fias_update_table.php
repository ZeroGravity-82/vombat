<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiasUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fias_update', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('version_id')->unique();
            $table->string('text_version', 100);
            $table->string('fias_complete_xml_url', 255);
            $table->string('fias_delta_xml_url', 255);
            $table->boolean('downloaded')->default(0);
            $table->boolean('installed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fias_update');
    }
}
