<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCaseNo extends Model
{
    use HasFactory;
    protected $table='bookcasenos';
    protected $fillable=[
        'ord',
        'startnum',
        'endnum',
        'link_id'
    ];
}
