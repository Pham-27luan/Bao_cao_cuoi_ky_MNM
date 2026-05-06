<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('nguoidung');
        Schema::dropIfExists('taixe');
        Schema::dropIfExists('tuyen_xes');
    }

    public function down(): void
    {
        if (!Schema::hasTable('nguoidung')) {
            Schema::create('nguoidung', function (Blueprint $table) {
                $table->increments('mand');
                $table->string('phone');
                $table->unsignedInteger('mataikhoan')->nullable();
                $table->unsignedInteger('mave')->nullable();
            });
        }

        if (!Schema::hasTable('taixe')) {
            Schema::create('taixe', function (Blueprint $table) {
                $table->increments('mataixe');
                $table->string('tentaixe');
                $table->string('sodienthoai');
                $table->unsignedInteger('mataikhoan')->nullable();
                $table->unsignedInteger('matuyen')->nullable();
                $table->unsignedInteger('maxe')->nullable();
            });
        }

        if (!Schema::hasTable('tuyen_xes')) {
            Schema::create('tuyen_xes', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }
};
