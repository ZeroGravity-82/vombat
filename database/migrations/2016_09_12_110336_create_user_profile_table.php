<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name')->default('');
            $table->unsignedTinyInteger('gender')->nullable();
            $table->decimal('reputation', 5)->default(0);
            $table->string('status_message', 100)->default('');
            $table->date('birth_day')->nullable();
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamps();

            $table->integer('user_account_id')->unsigned();
            $table->foreign('user_account_id')->references('id')->on('user_account')->
                    onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
