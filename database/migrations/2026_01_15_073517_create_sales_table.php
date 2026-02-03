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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->dateTime('sale_date');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('amount_paid', 12, 2)->nullable(); //
            $table->decimal('change', 12, 2)->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->timestamps();

            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
