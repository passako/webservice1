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
    Schema::create('work_attendances', function (Blueprint $table) {
        $table->id();
        $table->string('employee_name');
        $table->date('work_date');
        $table->time('check_in')->nullable();
        $table->time('check_out')->nullable();
        $table->string('status')->default('กำลังทำงาน');
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('work_attendances');
}
};
