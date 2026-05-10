<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->nullOnDelete();

            $table->enum('jenis', ['income', 'expense']);
            $table->decimal('jumlah', 15, 2);
            $table->string('category', 50)->default('Lainnya');
            $table->string('keterangan');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};