<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorMap extends Model{
    protected $table='floors_maps';
    protected $fillable=[
        'store_id',
        'bookcaseName',
        'bookcaseNote',
        'top',
        'left',
        'height',
        'width',
        'rotate',
        'severalrows',
        'severalcols'
    ];
    public function bookcaseno(){
        return $this->hasMany('App\Models\BookCaseNo','link_id','id');
    }
}
