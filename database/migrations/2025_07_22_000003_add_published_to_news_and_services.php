<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
    Schema::table('news', function (Blueprint $table) {
        if (!Schema::hasColumn('news', 'published')) {
            $table->boolean('published')->default(false);
        }
        if (!Schema::hasColumn('news', 'published_at')) {
            $table->timestamp('published_at')->nullable();
        }
    });

    Schema::table('services', function (Blueprint $table) {
        if (!Schema::hasColumn('services', 'published')) {
            $table->boolean('published')->default(false);
        }
        if (!Schema::hasColumn('services', 'published_at')) {
            $table->timestamp('published_at')->nullable();
        }
    });
}

    public function down() {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['published', 'published_at']);
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['published', 'published_at']);
        });
    }
};
