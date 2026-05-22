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
        Schema::table('site_pages', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')->default(0)->after('site_id');
        });

        Schema::table('site_pages', function (Blueprint $table) {
            $table->renameColumn('page', 'slug');
        });

        DB::table('site_pages')->where('slug', 'home')->update(['sort_order' => 0]);
        DB::table('site_pages')->where('slug', 'about')->update(['sort_order' => 1]);
        DB::table('site_pages')->where('slug', 'contact')->update(['sort_order' => 2]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_pages', function (Blueprint $table) {
            $table->renameColumn('slug', 'page');
        });

        Schema::table('site_pages', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
