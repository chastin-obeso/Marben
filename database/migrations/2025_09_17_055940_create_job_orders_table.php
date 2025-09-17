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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('job_order_number');
            $table->string('service_type');
            $table->string('description')->nullable();
            $table->date('date_requested')->nullable();
            $table->date('date_started')->nullable();
            $table->date('date_targed')->nullable();
            $table->date('date_finished')->nullable();
            $table->string('status'); // e.g., pending, in-progress, completed
            $table->boolean('is_archived')->default(false); // Soft delete simulation

            $table->foreignId('re_job_order_id')
                ->nullable()
                ->constrained('job_orders')
                ->onDelete('set null');

            // if customers table is created
            $table->foreignId('customer_id')->onDelete('cascade');
        });

        Schema::create('job_order_logs', function (Blueprint $table) {
            $table->id();
            $table->text('details'); // e.g., created, updated, status changed
            $table->date('date')->useCurrent();

            $table->foreignId('job_order_id')->constrained('job_orders')->onDelete('cascade');
        });

        Schema::create('job_order_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->string('status');
            
            $table->foreignId('job_order_id')->constrained('job_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_order_logs');
        Schema::dropIfExists('job_order_parts');
        Schema::dropIfExists('job_orders');
    }
};
