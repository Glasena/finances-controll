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
        Schema::create('bank-accounts', function (Blueprint $table) {

            $table->id();
            $table->string('account-number');
            $table->unsignedBigInteger('id_bank'); // Coluna para a chave estrangeira
            $table->unsignedBigInteger('id_user'); // Coluna para a chave estrangeira
            $table->unsignedBigInteger('id_integration-type');
            $table->timestamps();

            $table->foreign('id_bank') // Definindo a chave estrangeira
                  ->references('id') // Referência à coluna 'id' na tabela 'bank'
                  ->on('banks') // Nome da tabela de referência
                  ->onDelete('cascade'); // Ação a ser executada ao excluir o registro pai (opcional, pode ser 'cascade', 'set null', 'no action', etc.)

            $table->foreign('id_user') 
                  ->references('id') 
                  ->on('users') 
                  ->onDelete('cascade');

            $table->foreign('id_integration-type') 
                  ->references('id') 
                  ->on('integration_type') 
                  ->onDelete('cascade'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank-accounts');
    }
};
