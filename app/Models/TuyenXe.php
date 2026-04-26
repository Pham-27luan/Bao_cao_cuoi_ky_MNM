<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TuyenXe extends Model
{
    protected $table = 'tuyenxe';
    protected $primaryKey = 'matuyen';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'tentuyen',
        'diemdi',
        'diemden',
        'thoigiandukien',
        'khoangcach',
        'giatien',
        'trangthai',
        'maxe',
    ];

    // Quan hệ với bảng xe
    public function xe()
    {
        return $this->belongsTo(Xe::class, 'maxe', 'maxe');
    }

    // Lấy biển số xe
    public function getBienSoXeAttribute()
    {
        if (!$this->maxe) {
            return 'Chưa cập nhật';
        }

        $xe = DB::table('xe')->where('maxe', $this->maxe)->first();
        return $xe ? $xe->biensoxe : 'Không tìm thấy';
    }

    // Lấy số ghế
    public function getGhesAttribute()
    {
        if (!$this->maxe) {
            return collect([]);
        }

        return Ghe::where('maxe', $this->maxe)->get();
    }

    public function chuyenXes()
    {
        return $this->hasMany(ChuyenXe::class, 'matuyen', 'matuyen');
    }
}
