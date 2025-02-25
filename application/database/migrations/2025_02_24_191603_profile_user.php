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
        Schema::create('profileUser', function (Blueprint $table) {
            $table->id();
            $table->integer('idUser');
            $table->integer('idProfile');
        });

        DB::table('profileUser')->insert([
            'idUser' => '1', 
            'idProfile' => '1',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profileUser');
    }
};
