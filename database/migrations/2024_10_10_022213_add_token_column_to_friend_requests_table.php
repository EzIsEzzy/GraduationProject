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
        Schema::table('friend_requests', function (Blueprint $table) {
            //This should be a mix of sender_id and receiver_id, added with some salting to make a unique token
            //nullable due to that when the request is accepted, the is_accepted becomes true, accepted_at becomes the current timestamp, and the token then will be generated
            $table->string('token',64)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('friend_requests', function (Blueprint $table) {
            //drop the uniqueness and then the column
            $table->dropUnique(['token']);
            $table->dropColumn('token');
        });
    }
};
