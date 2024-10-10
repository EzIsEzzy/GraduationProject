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
        Schema::table('comments_like', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('comment_id'); // Create the comment_id column
            $table->foreign('comment_id')->references('id')->on('comment')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments_like', function (Blueprint $table) {
            //
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');
        });
    }
};
