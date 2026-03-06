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
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('home_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->foreignId('home_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });
        Schema::table('measurements', function (Blueprint $table) {
            $table->foreignId('home_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            $table->dropForeign(['home_id']);
            $table->dropColumn('home_id');
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['home_id']);
            $table->dropColumn('home_id');
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['home_id']);
            $table->dropColumn('home_id');
        });
    }
};
