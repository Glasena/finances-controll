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
        Schema::create('bank-transactions', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('id_bank-account'); // Coluna para a chave estrangeira
            $table->string('description');
            $table->float('value');
            $table->unsignedBigInteger('id_transaction-category'); // Coluna para a chave estrangeira
            $table->enum('type', ['+', '-'])->default('+');
            $table->timestamps();

            $table->foreign('id_bank-account') // Definindo a chave estrangeira
                  ->references('id') // Referência à coluna 'id' na tabela 'bank'
                  ->on('bank-accounts') // Nome da tabela de referência
                  ->onDelete('cascade'); // Ação a ser executada ao excluir o registro pai (opcional, pode ser 'cascade', 'set null', 'no action', etc.)

            $table->foreign('id_transaction-category') 
                  ->references('id') 
                  ->on('transactions-categories') 
                  ->onDelete('cascade');
                  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank-transactions');
    }
};
