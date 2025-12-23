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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tmdb_id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->year('year'); 
            $table->text('description');
            $table->text('language');
            $table->integer('duration')->nullable(); // Runtime in minutes
            $table->decimal('tmdb_rating', 3, 1)->nullable(); 
            $table->decimal('rating', 3, 1)->nullable(); 
            $table->string('poster_url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
