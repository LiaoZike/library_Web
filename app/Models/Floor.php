<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model{
    use HasFactory;
    protected $table='floors';
    protected $fillable=[
        'ord',
        'name',
        'note',
        'desheight',
        'deswidth'
    ];
    public function maps(){
        return $this->hasMany('App\Models\FloorMap','store_id','id');
    }
}
