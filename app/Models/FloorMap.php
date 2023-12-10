<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorMap extends Model{
    protected $table='floors_maps';
    protected $fillable=[
        'linkid',
        'bookcaseName',
        'bookcaseNote',
        'top',
        'left',
        'height',
        'width',
    ];
}
