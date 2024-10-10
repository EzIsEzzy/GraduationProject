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
        Schema::table('post', function (Blueprint $table) {
            // adding publisherID to the table, a foreign key linked to the users table, on cascade delete
            $table->unsignedBigInteger('publisher_id'); // Create the publisher_id column
            $table->foreign('publisher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post', function (Blueprint $table) {
            //dropping the foreign key link and then the column
            $table->dropForeign(['publisher_id']); // Drop the foreign key
            $table->dropColumn('publisher_id');     // Drop the column itself
        });
    }
};
