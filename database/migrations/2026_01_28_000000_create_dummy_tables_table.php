<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dummy_tables', function (Blueprint $table) {
            $table->id();

            // Add your actual columns here, for example:
            // $table->string('name');
            // $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dummy_tables');
    }
};

