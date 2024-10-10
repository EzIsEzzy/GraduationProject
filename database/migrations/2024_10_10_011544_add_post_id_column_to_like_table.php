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
        Schema::table('like', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('post')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('like', function (Blueprint $table) {
            //drops the actual foreign link
            $table->dropForeign(['post_id']);
            //drops the column itself
            $table->dropColumn('post_id');
        });
    }
};
