<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_http', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('status')->unsigned();
            $table->string('method', 10);
            $table->text('url');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->longText('headers')->nullable();
            $table->longText('cookies')->nullable();
            $table->integer('time')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable();
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
        Schema::drop('logs_http');
    }
};
