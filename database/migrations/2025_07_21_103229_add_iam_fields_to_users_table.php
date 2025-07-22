<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'utype')) {
            $table->string('utype')->default('user')->after('name');
        }
        if (!Schema::hasColumn('users', 'status')) {
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('remember_token');
        }
        if (!Schema::hasColumn('users', 'consent_accepted_at')) {
            $table->timestamp('consent_accepted_at')->nullable()->after('consent');
        }
        if (!Schema::hasColumn('users', 'deleted_at')) {
            $table->softDeletes();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'utype')) {
            $table->dropColumn('utype');
        }
        if (Schema::hasColumn('users', 'status')) {
            $table->dropColumn('status');
        }
        if (Schema::hasColumn('users', 'consent_accepted_at')) {
            $table->dropColumn('consent_accepted_at');
        }
        if (Schema::hasColumn('users', 'deleted_at')) {
            $table->dropSoftDeletes();
        }
    });
}
};
