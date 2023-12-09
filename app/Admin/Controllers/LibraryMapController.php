<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Floor;
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
        $ordArray = $request->input('ord');
        $nameArray = $request->input('name');
        $noteArray = $request->input('note');
        if (sizeof($ordArray) != sizeof($nameArray) || sizeof($nameArray) != sizeof($noteArray)){
            abort(404);
        }

        $data = [];
        // 組合每一行的資料
        Floor::truncate();
        foreach ($ordArray as $index => $ord) {
            $data= [
                'ord' => $ord ?: 0,
                'name' => $nameArray[$index]?: '未命名樓層',
                'note' => $noteArray[$index]?: '無',
            ];
            Floor::create($data);
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

    public function editmap(Content $content){//編輯樓層資訊

        return "123";
    }

}
