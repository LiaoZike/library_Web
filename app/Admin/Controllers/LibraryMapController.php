<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\FloorMap;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;

class LibraryMapController extends Controller{
    public function index(Content $content){
        return $content
            ->title('空的')
            ->description('Description...');
    }
    public function floor(Content $content){ //顯示編輯樓層頁面
        //$floors=Floor::all();
        $floors = Floor::orderBy('ord','desc')->get();

        $data=[
            'floors'=>$floors,
        ];
        return $content
            ->title('樓層編輯')
            ->description('新增/修改/刪除圖書館樓層...')
            ->view("admin.librarymap.index",$data);
    }
    public function floorSave(Request $request){//編輯樓層資訊
        $db_floors=Floor::all();
        $ids = [];
        foreach ($db_floors as $floor) {
            $ids[] = $floor->id;
        }
        $idArray = $request->input('id');
        $ordArray = $request->input('ord');
        $nameArray = $request->input('name');
        $noteArray = $request->input('note');
        if (sizeof($idArray) != sizeof($ordArray) || sizeof($ordArray) != sizeof($nameArray) || sizeof($nameArray) != sizeof($noteArray)){
            abort(404);
        }
        // 組合每一行的資料
        for ($i=0;$i<sizeof($idArray);$i++){
            $data= [
                'ord' => (int)$ordArray[$i] ?: 0,
                'name' => $nameArray[$i]?: '未命名樓層',
                'note' => $noteArray[$i]?: '無備註',
            ];
            $temp=Floor::find((int)$idArray[$i]);
            if($temp==null||$idArray[$i]==-1){
                //找不到要新增
                Floor::create($data);
            }else{
                $temp->update($data);
            }
            for($j=0;$j<sizeof($ids);$j++){
                if($ids[$j]==(int)$idArray[$i]){
                    $ids[$j]=-1;
                }
            }
        }
        for($i=0;$i<sizeof($ids);$i++){
            if($ids[$i]!=-1){
                Floor::destroy($ids[$i]);
            }
        }
        admin_toastr('儲存成功', 'success');
        return redirect()->route("admin.librarymap.floor");
    }

    public function map(Content $content){//編輯樓層資訊
        $floors=Floor::orderBy('ord','desc')->get();
        $data=[
            'floors'=>$floors,
        ];
        return $content
            ->title('平面圖編輯')
            ->description('請先選擇要編輯平面圖的樓層')
            ->view("admin.librarymap.show",$data);
    }

    public function editmap($floorid,Content $content){//編輯樓層地圖資訊
        $id=$floorid;
        $floors=Floor::orderBy('ord','desc')->get();
        $floormaps=FloorMap::where('linkid','=',$floorid)->get();
        $data=[
            'id'=>$id,
            'floors'=>$floors,
            'floormaps'=>$floormaps,
        ];
        return $content
            ->title('平面圖編輯')
            ->description('請先選擇要編輯平面圖的樓層')
            ->view("admin.librarymap.showedit",$data);
    }

    public function editmapsave($floorid,Request $request,Content $content){//儲存樓層地圖資訊
        $db_floormaps=FloorMap::all();
        $ids = [];
        foreach ($db_floormaps as $maps) {
            $ids[] = $maps->id;
        }

        $idArray = $request->input('id');
        $nameArray = $request->input('name');
        $noteArray = $request->input('note');
        $topArray = $request->input('top');
        $leftArray = $request->input('left');
        $heightArray = $request->input('height');
        $widthArray = $request->input('width');
        if (sizeof($idArray) != sizeof($nameArray)  || sizeof($nameArray) != sizeof($noteArray) || sizeof($noteArray) != sizeof($topArray)
            || sizeof($topArray) != sizeof($leftArray) ||sizeof($leftArray) != sizeof($heightArray) ||sizeof($heightArray) != sizeof($widthArray) ){
            abort(404);
        }

        admin_toastr('儲存成功', 'success');
        // 組合每一行的資料
        for ($i=0;$i<sizeof($idArray);$i++){
            if($topArray[$i]==null || $leftArray[$i]==null || $heightArray[$i]==null || $widthArray[$i]==null){
                admin_toastr('有資料為空值', 'error');
                continue;
            }
            $data= [
                'linkid' => $floorid,
                'bookcaseName' => $nameArray[$i]?: '未命名書櫃',
                'bookcaseNote' => $noteArray[$i]?: '無備註',
                'top' => $topArray[$i]?: '0',
                'left' => $leftArray[$i]?: '0',
                'height' => $heightArray[$i]?: '0',
                'width' => $widthArray[$i]?: '0',
            ];
            $temp=FloorMap::find((int)$idArray[$i]);
            if($temp==null||$idArray[$i]==-1){
                //找不到要新增
                FloorMap::create($data);
            }else{
                $temp->update($data);
            }
            for($j=0;$j<sizeof($ids);$j++){
                if($ids[$j]==(int)$idArray[$i]){
                    $ids[$j]=-1;
                }
            }
        }

        return redirect()->route("admin.librarymap.editmap",$floorid);
    }
}
