<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('block_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_page_id')->constrained();
            $table->longText('content');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }
};
