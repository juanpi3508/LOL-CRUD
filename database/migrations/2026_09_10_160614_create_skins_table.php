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
        Schema::create('skins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('champion_id')->constrained('champions')->cascadeOnDelete();
            $table->string('name');
            $table->string('splash_art_url')->nullable();
            $table->string('tier')->default('Clásica'); // Clásica, Épica, Legendaria, Mítica, Definitiva
            $table->unsignedInteger('price_rp')->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skins');
    }
};
