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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->integer('bill_series')->unique();
            $table->float('total_amount')->default(0);
            $table->float('amount_due')->default(0);
            $table->date('bill_date');
            $table->date('due_date');
            $table->string('particulars');
            $table->string('status')->default('Awaiting Downpayment');
            $table->foreignId('job_order_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
