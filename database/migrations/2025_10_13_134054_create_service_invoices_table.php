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
        Schema::create('service_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('service_invoice_number')->unique();
            $table->integer('service_invoice_series')->unique();
            $table->float('amount_paid')->default(0);
            $table->string('payment_type');
            $table->string('reference_number')->default('');
            $table->date('payment_date');
            $table->foreignId('bill_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_invoices');
    }
};
