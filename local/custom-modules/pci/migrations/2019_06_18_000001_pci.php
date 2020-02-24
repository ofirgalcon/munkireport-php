<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Pci extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('pci', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('device_id')->nullable();
            $table->string('device_type')->nullable();
            $table->boolean('driver_installed')->nullable();
            $table->string('link_speed')->nullable();
            $table->string('link_width')->nullable();
            $table->boolean('msi')->nullable();
            $table->string('device_name')->nullable();
            $table->string('revision_id')->nullable();
            $table->string('slot_name')->nullable();
            $table->string('subsystem_id')->nullable();
            $table->string('subsystem_vendor_id')->nullable();
            $table->string('vendor_id')->nullable();
            
            $table->index('serial_number');
            $table->index('name');
            $table->index('device_id');
            $table->index('device_type');
            $table->index('driver_installed');
            $table->index('link_speed');
            $table->index('link_width');
            $table->index('device_name');
            $table->index('revision_id');
            $table->index('slot_name');
            $table->index('subsystem_id');
            $table->index('subsystem_vendor_id');
            $table->index('vendor_id');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('pci');
    }
}
