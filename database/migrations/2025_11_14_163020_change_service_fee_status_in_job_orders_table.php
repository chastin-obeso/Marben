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
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn('service_fee_status');
            $table->integer('service_fee_bill_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->string('service_fee_status')->default('unbilled');
            $table->dropColumn('service_fee_bill_id');
        });
    }
};
