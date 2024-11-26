<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('admin_loginid', function (Blueprint $table) {
            $table->string('reset_key')->nullable();
            $table->timestamp('reset_key_expires_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('admin_loginid', function (Blueprint $table) {
            $table->dropColumn(['reset_key', 'reset_key_expires_at']);
        });
    }
    

};
