<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManageEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('par_no')->nullable();
            $table->string('fund_cluster')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('site_id')->constrained('sites');
            $table->foreignId('division_id')->constrained('divisions');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('manage_equipment');
    }
}
