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
        Schema::table('job_applies', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('applied_job');
            $table->foreign('applied_job')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applies', function (Blueprint $table) {
            //
            $table->dropForeign(['applied_job']);
            $table->dropColumn('applied_job');
        });
    }
};
