<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_checks', function (Blueprint $table) {
            $table->foreignId('website_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });

        // Populate website_id for existing records
        DB::statement('
            UPDATE page_checks
            INNER JOIN pages ON page_checks.page_id = pages.id
            SET page_checks.website_id = pages.website_id
        ');

        // Make website_id non-nullable after population
        Schema::table('page_checks', function (Blueprint $table) {
            $table->foreignId('website_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_checks', function (Blueprint $table) {
            $table->dropForeign(['website_id']);
            $table->dropColumn('website_id');
        });
    }
};
