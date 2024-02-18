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
        Schema::create('credit_card_bills', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('bank_account_id'); // Coluna para a chave estrangeira
            $table->foreign('bank_account_id') // Definindo a chave estrangeira
                  ->references('id') // Referência à coluna 'id' na tabela 'bank'
                  ->on('bank_accounts') // Nome da tabela de referência
                  ->onDelete('cascade'); // Ação a ser executada ao excluir o registro pai (opcional, pode ser 'cascade', 'set null', 'no action', etc.)
        });

        Schema::table('bank_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('credit_card_bills_id')->nullable(); // Adiciona uma coluna chamada 'nova_coluna' à tabela 'exemplo'
            $table->foreign('credit_card_bills_id') // Definindo a chave estrangeira
                  ->references('id') // Referência à coluna 'id' na tabela 'bank'
                  ->on('credit_card_bills') // Nome da tabela de referência
                  ->onDelete('cascade'); // Ação a ser executada ao excluir o registro pai (opcional, pode ser 'cascade', 'set null', 'no action', etc.)

        });

        Schema::create('credit_card_bills_items', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->dateTime('date')->nullable();
            $table->float('value');
            $table->timestamps();
            $table->unsignedBigInteger('credit_card_bills_id')->nullable(); // Adiciona uma coluna chamada 'nova_coluna' à tabela 'exemplo'
            $table->foreign('credit_card_bills_id') // Definindo a chave estrangeira
                  ->references('id') // Referência à coluna 'id' na tabela 'bank'
                  ->on('credit_card_bills') // Nome da tabela de referência
                  ->onDelete('cascade'); // Ação a ser executada ao excluir o registro pai (opcional, pode ser 'cascade', 'set null', 'no action', etc.)
        });        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_card_bills_items');
        Schema::dropIfExists('credit_card_bills');
        Schema::table('bank_transactions', function (Blueprint $table) {
            $table->dropForeign(['credit_card_bills_id']);
            $table->dropColumn('credit_card_bills_id');
        });
    }
};
