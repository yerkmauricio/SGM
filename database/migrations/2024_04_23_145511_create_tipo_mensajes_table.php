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
        Schema::create('tipo_mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30);
            $table->text('descripcion');
            $table->string('foto', 100)->nullable();
            $table->boolean('estado')->default(true);
            $table->date('fecha');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_mensajes');
    }
};
