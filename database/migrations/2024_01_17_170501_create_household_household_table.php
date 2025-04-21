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
        Schema::create('household_household', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_household_id');
            $table->foreign('requested_household_id')->references('id')->on('households');
            $table->unsignedBigInteger('household_id');
            $table->foreign('household_id')->references('id')->on('households');
            $table->integer('status')->default(1)->comment('0 => Pending, 1 => Approved, 2 => Rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_household');
    }
};
