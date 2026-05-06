<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('taikhoan')) {
            Schema::create('taikhoan', function (Blueprint $table) {
                $table->increments('id');
                $table->string('phone')->unique();
                $table->string('password');
                $table->string('role')->default('khach_hang');
                $table->string('email')->nullable()->unique();
                $table->string('hoten')->nullable();
            });
        }

        if (!Schema::hasTable('xe')) {
            Schema::create('xe', function (Blueprint $table) {
                $table->increments('maxe');
                $table->string('biensoxe')->unique();
                $table->string('loaixe');
                $table->unsignedInteger('soghe')->default(0);
                $table->string('nhaxe');
                $table->string('trangthai')->default('Dang hoat dong');
            });
        }

        if (!Schema::hasTable('tuyenxe')) {
            Schema::create('tuyenxe', function (Blueprint $table) {
                $table->increments('matuyen');
                $table->string('tentuyen');
                $table->string('diemdi');
                $table->string('diemden');
                $table->string('thoigiandukien')->nullable();
                $table->decimal('khoangcach', 10, 2)->default(0);
                $table->decimal('giatien', 12, 2)->default(0);
                $table->string('trangthai')->default('Dang hoat dong');
                $table->unsignedInteger('maxe')->nullable();

                $table->foreign('maxe')->references('maxe')->on('xe')->nullOnDelete();
            });
        }

        if (!Schema::hasTable('vitrighe')) {
            Schema::create('vitrighe', function (Blueprint $table) {
                $table->increments('maghe');
                $table->string('tenghe');
                $table->string('trangthai')->nullable();
                $table->unsignedInteger('maxe');

                $table->foreign('maxe')->references('maxe')->on('xe')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('chuyenxe')) {
            Schema::create('chuyenxe', function (Blueprint $table) {
                $table->increments('machuyen');
                $table->unsignedInteger('matuyen');
                $table->unsignedInteger('maxe');
                $table->date('ngaydi');
                $table->string('giodi');
                $table->decimal('giave', 12, 2)->default(0);
                $table->unsignedInteger('ghe_trong')->default(0);

                $table->foreign('matuyen')->references('matuyen')->on('tuyenxe')->cascadeOnDelete();
                $table->foreign('maxe')->references('maxe')->on('xe')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('ve')) {
            Schema::create('ve', function (Blueprint $table) {
                $table->increments('mave');
                $table->unsignedInteger('maghe');
                $table->unsignedInteger('mataikhoan');
                $table->dateTime('ngaydat');
                $table->string('hinhthucthanhtoan')->nullable();
                $table->decimal('tongsotien', 12, 2)->default(0);
                $table->string('trangthai')->default('cho_don');

                $table->foreign('maghe')->references('maghe')->on('vitrighe')->cascadeOnDelete();
                $table->foreign('mataikhoan')->references('id')->on('taikhoan')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ve');
        Schema::dropIfExists('chuyenxe');
        Schema::dropIfExists('vitrighe');
        Schema::dropIfExists('tuyenxe');
        Schema::dropIfExists('xe');
        Schema::dropIfExists('taikhoan');
    }
};
