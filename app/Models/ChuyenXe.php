<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuyenXe extends Model
{
    protected $table = 'chuyenxe';
    protected $primaryKey = 'machuyen';
    public $timestamps = false;

    protected $fillable = [
        'matuyen',
        'maxe',
        'ngaydi',
        'giodi',
        'giave',
        'ghe_trong',
    ];

    // Quan hệ với tuyến xe
    public function tuyenXe()
    {
        return $this->belongsTo(TuyenXe::class, 'matuyen', 'matuyen');
    }

    // Quan hệ với xe
    public function xe()
    {
        return $this->belongsTo(Xe::class, 'maxe', 'maxe');
    }

    // Lấy tên tuyến đường
    public function getTenTuyenAttribute()
    {
        return $this->tuyenXe ? $this->tuyenXe->tentuyen : 'Không xác định';
    }

    public function getDiemDiAttribute()
    {
        return $this->tuyenXe ? $this->tuyenXe->diemdi : 'Không xác định';
    }

    public function getDiemDenAttribute()
    {
        return $this->tuyenXe ? $this->tuyenXe->diemden : 'Không xác định';
    }

    // Lấy biển số xe
    public function getBienSoXeAttribute()
    {
        return $this->xe ? $this->xe->biensoxe : 'Không xác định';
    }

    // Format ngày đi
    public function getNgayDiFormattedAttribute()
    {
        return $this->ngaydi ? date('d/m/Y', strtotime($this->ngaydi)) : '';
    }

    // Format giá vé
    public function getGiaVeFormattedAttribute()
    {
        return number_format($this->giave, 0, ',', '.') . 'đ';
    }

    // Lấy số ghế hiển thị
    public function getGheTrongDisplayAttribute()
    {
        $tongGhe = $this->xe ? $this->xe->soghe : 0;
        return $this->ghe_trong . '/' . $tongGhe;
    }
}
