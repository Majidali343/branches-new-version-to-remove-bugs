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
        Schema::table('household_user', function (Blueprint $table) {
            $table->integer('status')->default(1)->comment('0 => Pending, 1 => Approved, 2 => Rejected, 3 => Cancelled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('household_user', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
