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
    Schema::create('patients', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->string('name')->nullable();
      $table->string('phone')->nullable();
      $table->enum('gender', ['L', 'P'])->nullable();
      $table->date('birth_date')->nullable();
      $table->string('address')->nullable();
      $table->string('rt')->nullable();
      $table->string('rw')->nullable();
      $table->foreignId('subdistrict_id')->nullable()->constrained('subdistricts')->onDelete('cascade')->onUpdate('cascade');
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
    Schema::dropIfExists('patients');
  }
};
