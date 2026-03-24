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
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
            $table->decimal('overdraft_limit', 12, 2)->default(0.00);
            $table->decimal('monthly_fee', 12, 2)->default(0.00);
            $table->decimal('interest_rate', 5, 4)->default(0.0000);
            $table->decimal('default_daily_transaction_limit', 12, 2)->default(10000.00);
            $table->smallInteger('default_monthly_withdrawal_limit')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
