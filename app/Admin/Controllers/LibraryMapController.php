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

class LibraryMapController extends Controller{
    public function index(Content $content){
        return $content
            ->title('空的')
            ->description('Description...');
    }

    public function floor(Content $content){ //顯示編輯樓層頁面
        (new \Encore\Admin\Admin)->disablePjax();
        $floors = Floor::orderBy('ord','desc')->get();

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

        if (!empty($idArray) && !empty($ordArray) && !empty($nameArray) && !empty($noteArray)) {
            if (count($idArray) != count($ordArray) || count($ordArray) != count($nameArray) || count($nameArray) != count($noteArray)) {
                abort(404);
            }
        }
        // 組合每一行的資料
        if (!empty($idArray)) {
            for ($i=0;$i<count($idArray);$i++){
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
                for($j=0;$j<count($ids);$j++){
                    if($ids[$j]==(int)$idArray[$i]){
                        $ids[$j]=-1;
                    }
                }
            }
        }
        for($i=0;$i<sizeof($ids);$i++){
            if($ids[$i]!=-1){ //刪除沒有送來的ID
                $floormaps_deldatas=FloorMap::where('linkid','=',$ids[$i])->get();
                foreach ($floormaps_deldatas as $floormaps_deldata){
                    FloorMap::destroy($floormaps_deldata['id']);
                }
                Floor::destroy($ids[$i]);
            }
        }
        admin_toastr('儲存成功', 'success');
        return redirect()->route("admin.librarymap.floor");
    }

    public function map(Content $content){//顯示樓層頁面
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
            ->title('平面圖編輯')
            ->description('樓層設定與檢視，點選樓層可顯示該樓層書櫃平面圖')
            ->view("admin.librarymap.show",$data);
    }

    public function editmap($floorid,Content $content){//顯示編輯樓層書櫃頁面
        (new \Encore\Admin\Admin)->disablePjax();
        $id=$floorid;
        $checks=Floor::find($floorid);
        if($checks==null){
            abort(404);
        }
        $floors=Floor::orderBy('ord','desc')->get();
        $desheight=Floor::find($floorid)->desheight;
        $deswidth=Floor::find($floorid)->deswidth;
        $floormaps=FloorMap::where('linkid','=',$floorid)->get();
        $data=[
            'id'=>$id,
            'floors'=>$floors,
            'floormaps'=>$floormaps,
            'desheight'=>$desheight,
            'deswidth'=>$deswidth
        ];
        return $content
            ->title('平面圖編輯')
            ->description('請先選擇要編輯平面圖的樓層')
            ->view("admin.librarymap.showedit",$data);
    }

    public function editmapsave($floorid,Request $request,Content $content){//儲存樓層地圖資訊
        $updfloorpid=Floor::find($floorid);
        $updfloorpid->update([
            'desheight'=>(int)$request->all()['designsize_H'],
            'deswidth'=>(int)$request->all()['designsize_W']
        ]);
        $db_floormaps=FloorMap::where('linkid','=',$floorid)->get();
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
        if (!empty($idArray) && !empty($nameArray) && !empty($noteArray) && !empty($topArray) && !empty($leftArray) && !empty($heightArray) &&!empty($widthArray)) {
            if (count($idArray) != count($nameArray) || count($nameArray) != count($noteArray) || count($noteArray) != count($topArray) ||
            count($topArray) != count($leftArray) || count($leftArray) != count($heightArray)|| count($heightArray) != count($widthArray)) {
                abort(404);
            }
        }
        admin_toastr('儲存成功', 'success');

        // 組合每一行的資料
        if (!empty($idArray)) {
            for ($i = 0; $i < sizeof($idArray); $i++) {
                if ($topArray[$i] == null || $leftArray[$i] == null || $heightArray[$i] == null || $widthArray[$i] == null) {
                    admin_toastr('有資料為空值', 'error');
                    continue;
                }
                $data = [
                    'linkid' => $floorid,
                    'bookcaseName' => $nameArray[$i] ?: '未命名書櫃',
                    'bookcaseNote' => $noteArray[$i] ?: '無備註',
                    'top' => (int)$topArray[$i] ?: '0',
                    'left' => (int)$leftArray[$i] ?: '0',
                    'height' => (int)$heightArray[$i] ?: '0',
                    'width' => (int)$widthArray[$i] ?: '0',
                ];
                $temp = FloorMap::find((int)$idArray[$i]);
                if ($temp == null || $idArray[$i] == -1) {
                    //找不到要新增
                    FloorMap::create($data);
                } else {
                    $temp->update($data);
                }
                for ($j = 0; $j < sizeof($ids); $j++) {
                    if ($ids[$j] == (int)$idArray[$i]) {
                        $ids[$j] = -1;
                    }
                }
            }
        }
        for($i=0;$i<sizeof($ids);$i++){
            if($ids[$i]!=-1){
                FloorMap::destroy($ids[$i]);
            }
        }
        return redirect()->route("admin.librarymap.editmap",$floorid);
    }
}
