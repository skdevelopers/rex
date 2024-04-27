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
            $table->unsignedBigInteger('id')->primary();
            if (Schema::hasTable('users')) {
                $table->unsignedBigInteger('user_id')->comment('Party');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            }
            // Make sure products table exists before adding the foreign key
            if (Schema::hasTable('products')) {
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            }
            $table->date('date');
            $table->integer('quantity');
            $table->decimal('rate', 10, 2);
            $table->decimal('percent', 5, 2);
            $table->decimal('amount', 10, 2);
            $table->decimal('previous_balance', 10, 2);
            $table->timestamps();
            $table->softDeletes();
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


