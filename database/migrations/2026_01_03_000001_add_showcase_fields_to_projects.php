<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('before_image')->nullable()->after('cover');
            $table->string('video')->nullable()->after('before_image');
            $table->decimal('lat', 9, 6)->nullable()->after('location');
            $table->decimal('lng', 9, 6)->nullable()->after('lat');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['before_image', 'video', 'lat', 'lng']);
        });
    }
};
