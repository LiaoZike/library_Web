<?php

namespace App\Http\Controllers;

use App\Models\Virtual_library;
use Illuminate\Http\Request;

class APIController extends Controller{
    /* 查詢API給圖片辨識 */
    public function searchBookcase($bookcaseID){
        $books = Virtual_library::where("book_shelf","=",$bookcaseID)->get();
        return $books;
    }
}
