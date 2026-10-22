<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Board;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deck_card', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained()->onDelete('cascade');
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->enum('board', [Board::Main->value, Board::Side->value])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deck_card');
    }
};
