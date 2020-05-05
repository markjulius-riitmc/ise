<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManageEquipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_equipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manage_equipment_id')->constrained('manage_equipment');
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->string('property_no')->nullable();
            $table->string('brand')->nullable();
            $table->string('model_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('sku')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers');
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
        Schema::dropIfExists('manage_equipment_items');
    }
}
