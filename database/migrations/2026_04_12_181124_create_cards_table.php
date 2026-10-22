<?php

use App\Rarity;
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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('text');
            $table->string('image_url')->nullable();
            $table->smallInteger('power')->nullable();
            $table->smallInteger('toughness')->nullable();
            $table->integer('mana_cost', unsigned: true)->nullable();
            $table->string('flavor_text')->nullable();
            $table->smallInteger('collector_number', unsigned: true)->nullable();
            $table->enum('rarity', Rarity::cases())->nullable();
            $table->uuid('oracle_id')->nullable();
            // subcards ie adventure
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
