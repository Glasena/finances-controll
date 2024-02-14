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
        Schema::create('bank_accounts', function (Blueprint $table) {

            $table->id();
            $table->string('account_number');
            $table->unsignedBigInteger('bank_id'); // Coluna para a chave estrangeira
            $table->unsignedBigInteger('user_id'); // Coluna para a chave estrangeira
            $table->unsignedBigInteger('integration_type_id');
            $table->timestamps();

            $table->foreign('bank_id') // Definindo a chave estrangeira
                  ->references('id') // Referência à coluna 'id' na tabela 'bank'
                  ->on('banks') // Nome da tabela de referência
                  ->onDelete('cascade'); // Ação a ser executada ao excluir o registro pai (opcional, pode ser 'cascade', 'set null', 'no action', etc.)

            $table->foreign('user_id') 
                  ->references('id') 
                  ->on('users') 
                  ->onDelete('cascade');

            $table->foreign('integration_type_id') 
                  ->references('id') 
                  ->on('integration_types') 
                  ->onDelete('cascade'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
