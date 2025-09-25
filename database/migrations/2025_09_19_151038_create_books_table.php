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
        Schema::create('books', function (Blueprint $table) {
              $table->id();
    $table->unsignedBigInteger('classification_id'); // relasi ke book_classifications
    $table->string('book_title');
    $table->string('file_name');
    $table->string('book_description');
    $table->timestamps();

    // definisikan foreign key
    $table->foreign('classification_id')
          ->references('id')
          ->on('book_classifications')
          ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
