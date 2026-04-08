<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Tabel Users


        // 2. Tabel Members
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('division', 100)->nullable();
            $table->string('angkatan', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
        });

        // 3. Tabel Income
        Schema::create('income', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        // 4. Tabel Expense
        Schema::create('expense', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        // 5. Tabel Kas
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('status', ['lunas', 'belum'])->default('belum');
            $table->unsignedBigInteger('income_id')->nullable();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('income_id')->references('id')->on('income')->onDelete('set null');

            $table->unique(['member_id', 'month', 'year'], 'unique_kas');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kas');
        Schema::dropIfExists('expense');
        Schema::dropIfExists('income');
        Schema::dropIfExists('members');
        Schema::dropIfExists('users');
    }
};