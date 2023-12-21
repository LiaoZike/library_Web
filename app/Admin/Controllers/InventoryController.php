<?php

namespace App\Admin\Controllers;
use Encore\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\FloorMap;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;

class InventoryController extends Controller{
    public function index(Content $content){
        return $content
            ->title('空的')
            ->description('Description...');
    }
    /************************************/
    /*********  圖書館樓層顯示/處理  ********/
    /************************************/
    public function floor(Content $content){//顯示樓層頁面
        $floors=Floor::orderBy('ord','desc')->get();
        if (!empty($floors)) {
            for ($i = 0; $i < sizeof($floors); $i++) {
                $floorcount=FloorMap::where('linkid','=',$floors[$i]->id)->get();
                if (empty($floors)) {
                    $floors[$i]->count=0;
                }else{
                    $floors[$i]->count=count($floorcount);
                }
            }
        }
        $data=[
            'floors'=>$floors,
        ];
        return $content
            ->title('圖書館書籍盤點系統')
            ->description('選擇樓層')
            ->view("admin.inventory.floor",$data);
    }


    /************************************/
    /***********  地圖顯示/處理  **********/
    /************************************/
    //顯示樓層書櫃頁面
    public function floormap($floorid,Content $content){
        $id=$floorid;
        $checks=Floor::find($floorid);
        if($checks==null){
            abort(404);
        }
        $floors=Floor::orderBy('ord','desc')->get();

        if(!empty($floors)){ //計算櫃數
            for($i=0;$i<sizeof($floors);$i++){
                $maps=FloorMap::where('linkid','=',$floors[$i]->id)->get();
                if(!empty($maps)) {
                    $floors[$i]->sizeofobj=count($maps);
                }else{
                    $floors[$i]->sizeofobj=0;
                }
            }
        }
        $desheight=Floor::find($floorid)->desheight; //設計框高度
        $deswidth=Floor::find($floorid)->deswidth; //設計框寬度
        $floormaps=FloorMap::where('linkid','=',$floorid)->get(); //該樓層所有書櫃
        $data=[
            'id'=>$id,
            'floors'=>$floors,
            'floormaps'=>$floormaps,
            'desheight'=>$desheight,
            'deswidth'=>$deswidth
        ];
        return $content
            ->title('圖書館書籍盤點系統')
            ->description('選擇書櫃')
            ->view("admin.inventory.floormap",$data);
    }



}
