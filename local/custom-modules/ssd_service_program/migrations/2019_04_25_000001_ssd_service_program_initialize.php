<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SSDServiceProgramInitialize extends Migration
{    

    public function up()
    {
    
        $capsule = new Capsule();

        $capsule::schema()->create('ssd_service_program', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('needs_service')->nullable();
            $table->string('ssd_model')->nullable();
            $table->string('ssd_revision')->nullable();
           

            $table->index('needs_service');
            $table->index('ssd_model');
            $table->index('ssd_revision');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('ssd_service_program');
    }


}
