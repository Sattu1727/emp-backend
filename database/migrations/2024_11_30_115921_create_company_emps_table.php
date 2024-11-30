<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEmpsTable extends Migration
{
    public function up()
    {
        Schema::create('company_emps', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 255);
            $table->string('email', 255)->unique();
            $table->string('mobile', 10);
            $table->string('alternate_mobile', 10)->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('address', 500);
            $table->string('guardian_name', 255);
            $table->string('relation', 100);
            $table->string('guardian_mobile', 10);
            $table->string('p_address', 500)->nullable();
            $table->string('image')->nullable();
            $table->string('id_prove')->nullable();
            $table->date('dob');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_emps');
    }
}
