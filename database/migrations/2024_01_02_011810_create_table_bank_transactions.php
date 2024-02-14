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
        Schema::create('bank_transactions', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('bank_account_id'); // Coluna para a chave estrangeira
            $table->string('description');
            $table->dateTime('date');
            $table->float('value');
            $table->unsignedBigInteger('transaction_category_id')->nullable(); // Coluna para a chave estrangeira
            $table->enum('type', ['+', '-'])->default('+');
            $table->timestamps();

            $table->foreign('bank_account_id') // Definindo a chave estrangeira
                  ->references('id') // Referência à coluna 'id' na tabela 'bank'
                  ->on('bank_accounts') // Nome da tabela de referência
                  ->onDelete('cascade'); // Ação a ser executada ao excluir o registro pai (opcional, pode ser 'cascade', 'set null', 'no action', etc.)

            $table->foreign('transaction_category_id') 
                  ->references('id') 
                  ->on('transaction_categories') 
                  ->onDelete('set null');
                  
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
