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
        Schema::create('artikel', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('judul', 100);
            $table->string('label', 50)->nullable();
            $table->text('ringkasan')->nullable();
            $table->longText('konten')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->float('css_width')->default(100);
            $table->float('css_height')->default(100);
            $table->float('css_left')->default(0);
            $table->float('css_top')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
