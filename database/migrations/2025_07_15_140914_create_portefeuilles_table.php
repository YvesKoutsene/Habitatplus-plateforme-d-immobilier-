<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortefeuillesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portefeuilles', function (Blueprint $table) {
            $table->id();
            $table->string('keyportefeuille')->unique();
            $table->decimal('solde', 10, 2);
            $table->string('statut');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('createdby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portefeuilles');
    }
}
