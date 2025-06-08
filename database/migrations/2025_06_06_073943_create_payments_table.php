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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents');
            $table->foreignId('house_id')->constrained('houses');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('note')->nullable();
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
