<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('new_data_final', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('mobile', 15);
            $table->string('alternate_mobile', 15)->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('address', 500)->nullable();
            $table->string('guardian_name', 255)->nullable();
            $table->string('relation', 100)->nullable();
            $table->string('guardian_mobile', 15)->nullable();
            $table->string('g_address', 500)->nullable();
            $table->string('image')->nullable();
            $table->string('id_prove');
            $table->date('dob');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_data_final');
    }
};
