<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Xe extends Model
{
    protected $table = 'xe';
    protected $primaryKey = 'maxe';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'biensoxe',
        'loaixe',
        'soghe',
        'nhaxe',
        'trangthai',
    ];

    public function ghes()
    {
        return $this->hasMany(Ghe::class, 'maxe', 'maxe');
    }

    public function tuyenXes()
    {
        return $this->hasMany(TuyenXe::class, 'maxe', 'maxe');
    }
}

