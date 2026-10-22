<?php

use App\FormatCategory;
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
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('category', FormatCategory::cases());
            $table->boolean('is_eternal')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formats');
    }
};
