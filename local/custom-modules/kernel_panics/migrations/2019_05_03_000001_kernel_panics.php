<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class KernelPanics extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('kernel_panics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('anonymous_uuid')->nullable();
            $table->string('type')->nullable();
            $table->text('full_text')->nullable();
            $table->string('crash_file')->nullable();
            $table->bigInteger('date')->nullable();
            $table->text('caller')->nullable();
            $table->string('process_name')->nullable();
            $table->string('macos_version')->nullable();
            $table->string('kernel_version')->nullable();
            $table->string('model_id')->nullable();
            $table->text('extensions_backtrace')->nullable();
            $table->text('non_apple_loaded_kexts')->nullable();

            $table->index('serial_number');
            $table->index('anonymous_uuid');
            $table->index('type');
            $table->index('crash_file');
            $table->index('process_name');
            $table->index('macos_version');
            $table->index('kernel_version');
            $table->index('model_id');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('kernel_panics');
    }
}
