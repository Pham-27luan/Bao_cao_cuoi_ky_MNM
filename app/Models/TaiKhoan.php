<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Model
{
    protected $table = 'taikhoan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'phone',
        'password',
        'role',
        'email',
        'hoten',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}
