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
        Schema::create('abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('champion_id')->constrained('champions')->cascadeOnDelete();
            $table->string('slot', 10); // P (Pasiva), Q, W, E, R
            $table->string('name');
            $table->text('description');
            $table->string('icon_url')->nullable();
            $table->string('cooldown')->nullable();
            $table->string('cost')->nullable();
            $table->timestamps();

            $table->index(['champion_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abilities');
    }
};
