<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ve')) {
            return;
        }

        Schema::table('ve', function (Blueprint $table) {
            if (!Schema::hasColumn('ve', 'machuyen')) {
                $table->unsignedInteger('machuyen')->nullable()->after('mave');
            }
        });

        Schema::table('ve', function (Blueprint $table) {
            $table->foreign('machuyen')->references('machuyen')->on('chuyenxe')->cascadeOnDelete();
            $table->unique(['machuyen', 'maghe']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('ve')) {
            return;
        }

        Schema::table('ve', function (Blueprint $table) {
            $table->dropUnique(['machuyen', 'maghe']);
            $table->dropForeign(['machuyen']);
        });

        Schema::table('ve', function (Blueprint $table) {
            if (Schema::hasColumn('ve', 'machuyen')) {
                $table->dropColumn('machuyen');
            }
        });
    }
};
