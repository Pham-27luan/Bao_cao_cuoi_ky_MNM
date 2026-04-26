<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ve extends Model
{
    protected $table = 've';
    protected $primaryKey = 'mave';
    public $timestamps = false;

    protected $fillable = [
        'mave',
        'maghe',
        'mataikhoan',
        'ngaydat',
        'hinhthucthanhtoan',
        'tongsotien',
        'trangthai',
    ];

    public function ghe()
    {
        return $this->belongsTo(Ghe::class, 'maghe', 'maghe');
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'mataikhoan', 'id');
    }

    public function getTenKhachAttribute()
    {
        return $this->taiKhoan ? $this->taiKhoan->hoten : 'Không xác định';
    }

    public function getSoDienThoaiAttribute()
    {
        return $this->taiKhoan ? $this->taiKhoan->phone : 'Không xác định';
    }

    public function getNgayDatFormattedAttribute()
    {
        return $this->ngaydat ? date('d/m/Y', strtotime($this->ngaydat)) : '';
    }

    public function getTongSoTienFormattedAttribute()
    {
        return number_format($this->tongsotien, 0, ',', '.') . 'đ';
    }

    public function getTrangThaiBadgeAttribute()
    {
        switch ($this->trangthai) {
            case 'cho_don':
                return '<span class="badge-warning"><i class="fas fa-clock"></i> Chờ đón</span>';
            case 'da_di':
                return '<span class="badge-success"><i class="fas fa-check-circle"></i> Đã đi</span>';
            default:
                return '<span class="badge-info">' . $this->trangthai . '</span>';
        }
    }
}
