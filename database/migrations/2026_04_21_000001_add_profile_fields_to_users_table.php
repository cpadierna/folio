<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('email');
            $table->string('location', 100)->nullable()->after('bio');
            $table->string('favorite_genre', 100)->nullable()->after('location');
            $table->string('website', 255)->nullable()->after('favorite_genre');
            $table->unsignedSmallInteger('reading_goal')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'location', 'favorite_genre', 'website', 'reading_goal']);
        });
    }
};
