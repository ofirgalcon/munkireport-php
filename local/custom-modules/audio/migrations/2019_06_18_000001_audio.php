<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Audio extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('audio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('default_audio_output')->nullable();
            $table->string('default_audio_input')->nullable();
            $table->Integer('device_input')->nullable();
            $table->Integer('device_output')->nullable();
            $table->string('device_manufacturer')->nullable();
            $table->Integer('device_srate')->nullable();
            $table->string('device_transport')->nullable();
            $table->string('input_source')->nullable();
            $table->string('output_source')->nullable();
            
            $table->index('serial_number');
            $table->index('name');
            $table->index('default_audio_output');
            $table->index('default_audio_input');
            $table->index('device_input');
            $table->index('device_output');
            $table->index('device_manufacturer');
            $table->index('device_srate');
            $table->index('device_transport');
            $table->index('input_source');
            $table->index('output_source');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('audio');
    }
}
