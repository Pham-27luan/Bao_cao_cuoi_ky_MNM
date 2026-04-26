<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ghe extends Model
{
    protected $table = 'vitrighe';
    protected $primaryKey = 'maghe';
    public $timestamps = false;

    protected $fillable = [
        'tenghe',
        'trangthai',
        'maxe',
    ];

    public function xe()
    {
        return $this->belongsTo(Xe::class, 'maxe', 'maxe');
    }
}

