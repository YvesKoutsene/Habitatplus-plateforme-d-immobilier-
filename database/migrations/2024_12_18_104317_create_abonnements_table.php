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
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->string('keyabonnement')->unique();
            $table->integer('duree');
            $table->dateTime('date_debut')->default(now());
            $table->dateTime('date_fin')->nullable();
            $table->string('statut');
            $table->unsignedBigInteger('createdby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('modele_id')->constrained('modele_abonnements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
