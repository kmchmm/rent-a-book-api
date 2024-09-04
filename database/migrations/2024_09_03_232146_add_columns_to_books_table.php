<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Adding new columns
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('random_number_13')->nullable()->unique(); // 13-digit random number starting with 9
            $table->string('random_number_10')->nullable()->unique(); // 10-digit random number starting with 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Dropping the columns if rolling back
            $table->dropColumn('title');
            $table->dropColumn('image');
            $table->dropColumn('random_number_13');
            $table->dropColumn('random_number_10');
        });
    }
};
