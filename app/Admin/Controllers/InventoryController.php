<?php

namespace App\Admin\Controllers;
use App\Models\BookCaseNo;
use App\Models\BookInfo;
use App\Models\InventoryBookcaseimg;
use App\Models\InventoryResult;
use App\Models\InventoryTime;
use Encore\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\FloorMap;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use http\Exception;
use Illuminate\Http\Request;
use mysql_xdevapi\Result;
use function PHPUnit\Framework\isNull;

class InventoryController extends Controller{
    public function index(Content $content){
        return $content
            ->title('空的')
            ->description('Description...');
    }

    /************************************/
    /*********  預設進入給盤點時間  ********/
    /************************************/
    public function default(Content $content){
        (new \Encore\Admin\Admin)->disablePjax();

        $DBtimes=InventoryTime::all();
        $lastTime = $DBtimes->last();
        if(!isset($lastTime)){
            $lastTime="null";
        }
        $lastTime=$lastTime->inventory_time;
        return redirect()->route('admin.inventory.floor',$lastTime);
    }








    /************************************/
    /*********  圖書館樓層顯示/處理  ********/
    /************************************/
    public function floor($timesname,Content $content){//顯示樓層頁面
        (new \Encore\Admin\Admin)->disablePjax();
        $filter_times = explode(',', $timesname);
        $filter_timesID = InventoryTime::whereIn('inventory_time', $filter_times)->pluck('id')->toArray();

        $DBtimes=InventoryTime::orderBy('id','desc')->get();

        $floors=Floor::orderBy('ord','desc')->get();

        if (!empty($floors)) {
            $ErrorInfo=[];
            for ($i = 0; $i < sizeof($floors); $i++) {
                $duplicateCounts = [];
                $temp=InventoryResult::whereIn('link_id', $filter_timesID)
                    ->where('floor','=',$floors[$i]->name)
                    ->whereIn('ishere',[0,2,-1])
                    ->get();
                $currectct=InventoryResult::whereIn('link_id', $filter_timesID)
                    ->where('floor','=',$floors[$i]->name)
                    ->whereIn('ishere',[1])
                    ->count();

                $counts = $temp->countBy(function ($item) {
                    return $item->floormap; //單看有幾櫃
                });

                // 更新 $duplicateCounts 陣列
                foreach ($counts as $key => $count) {
                    if(FloorMap::where('store_id','=',$floors[$i]->id)->where('bookcaseName','=',$key)->count()!=0){
                        // 檢查是否已經有重複計算，若無則加入計算結果
                        if (!isset($duplicateCounts[$key])) {
                            $duplicateCounts[$key] = $count;
                        }
                        $duplicateCounts[$key] = 0;
                    }else{
                        array_push($ErrorInfo,$floors[$i]->name."樓層 - ".$key."書櫃，實際書櫃不存在，無併入本次盤點 (資料錯誤)");
                    }
                }
                $floors[$i]['errorcount'] = count($duplicateCounts);
                $floors[$i]['currectct'] = $currectct;
                $floors[$i]['designcount'] = FloorMap::where('store_id','=',$floors[$i]->id)->count();

            }
        }
        $data=[
            'floors'=>$floors,
            'ErrorInfos'=>$ErrorInfo,
            'timesname'=>$timesname,
            'DBtimes'=>$DBtimes,
            'filter_times'=>$filter_times
        ];
        return $content
            ->title('圖書館書籍盤點系統')
            ->description('樓層資訊')
            ->view("admin.inventory.floor",$data);
    }


    /************************************/
    /***********  地圖顯示/處理  **********/
    /************************************/
    //顯示樓層書櫃頁面
    public function floormap($timesname,$floorid,Content $content){
        (new \Encore\Admin\Admin)->disablePjax();
        $filter_times = explode(',', $timesname);
        $filter_timesID = InventoryTime::whereIn('inventory_time', $filter_times)->pluck('id')->toArray();

        $DBtimes=InventoryTime::orderBy('id','desc')->get();

        $id=$floorid;
        $checks=Floor::find($floorid);
        $floorname=$checks->name;
        if($checks==null){
            abort(404);
        }

        $floors=Floor::orderBy('ord','desc')->get();
        if (!empty($floors)) {
            $ErrorInfo=[];
            for ($i = 0; $i < sizeof($floors); $i++) {
                $duplicateCounts = [];
                $temp=InventoryResult::whereIn('link_id', $filter_timesID)
                    ->where('floor','=',$floors[$i]->name)
                    ->whereIn('ishere',[0,2,-1])
                    ->get();
                $currectct=InventoryResult::whereIn('link_id', $filter_timesID)
                    ->where('floor','=',$floors[$i]->name)
                    ->whereIn('ishere',[1])
                    ->count();

                $counts = $temp->countBy(function ($item) {
                    return $item->floormap; //單看有幾櫃
                });

                // 更新 $duplicateCounts 陣列
                foreach ($counts as $key => $count) {
                    if(FloorMap::where('store_id','=',$floors[$i]->id)->where('bookcaseName','=',$key)->count()!=0){
                        // 檢查是否已經有重複計算，若無則加入計算結果
                        if (!isset($duplicateCounts[$key])) {
                            $duplicateCounts[$key] = $count;
                        }
                        $duplicateCounts[$key] = 0;
                    }else{
                        array_push($ErrorInfo,$floors[$i]->name."樓層 - ".$key."書櫃，實際書櫃不存在，無併入本次盤點 (資料錯誤)");
                    }
                }
                $floors[$i]['errorcount'] = count($duplicateCounts);
                $floors[$i]['currectct'] = $currectct;
                $floors[$i]['designcount'] = FloorMap::where('store_id','=',$floors[$i]->id)->count();

            }
        }

        $desheight=Floor::find($floorid)->desheight; //設計框高度
        $deswidth=Floor::find($floorid)->deswidth; //設計框寬度





        //task1-撈出樓層所有書櫃:之後要顯示在畫面上。 task-2-撈出每個書櫃是否有問題
        $floormaps=FloorMap::where('store_id','=',$floorid)->get();
        if(!empty($floormaps)){
            for($i=0; $i<sizeof($floormaps);$i++){
                $errorct=InventoryResult::wherein('link_id',$filter_timesID)
                                        ->where('floor','=',$floorname)
                                        ->where('floormap','=',$floormaps[$i]->bookcaseName)
                                        ->wherein('ishere',[0,2,-1])
                                        ->count();
                $correct=InventoryResult::wherein('link_id',$filter_timesID)
                    ->where('floor','=',$floorname)
                    ->where('floormap','=',$floormaps[$i]->bookcaseName)
                    ->wherein('ishere',[1])
                    ->count();
                $floormaps[$i]['errorcount']=['errorct'=>$errorct,'correct'=>$correct];
            }
        }

        $data=[
            'id'=>$id,
            'floors'=>$floors,
            'floormaps'=>$floormaps,
            'desheight'=>$desheight,
            'deswidth'=>$deswidth,
            'timesname'=>$timesname,
            'DBtimes'=>$DBtimes,
            'filter_times'=>$filter_times
        ];
        return $content
            ->title('圖書館書籍盤點系統')
            ->description('選擇書櫃')
            ->view("admin.inventory.floormap",$data);
    }



    /************************************/
    /***********  書櫃顯示/處理  **********/
    /************************************/
    //顯示樓層書櫃頁面
    public function bookcase($timesname,$floormapid,Content $content){
        $filter_times = explode(',', $timesname);
        $filter_timesID = InventoryTime::whereIn('inventory_time', $filter_times)->pluck('id')->toArray();

        $floormap=FloorMap::find($floormapid);
        if(!isset($floormap)) abort(404);
        $floorid=FloorMap::find($floormapid)->store_id;
        $floorname=Floor::find($floorid)->name;

        $BookCaseNos = BookCaseNo::where('link_id', $floormapid)->get()->groupBy('ord')->map(function ($group) {
            return $group->first();
        });
        if(!empty($BookCaseNos)){
            $bookcase=FloorMap::find($floormapid);
            $bookcaseName=$bookcase->bookcaseName; //書櫃名稱
            $floorname=Floor::find($bookcase->store_id)->name; //樓層名稱
//            dump($floorname);
//            dump($bookcaseName);
            foreach ($BookCaseNos as $key => $BookCaseNo) {
                $bookcaseord=BookCaseNo::find($BookCaseNos[$key]['id'])->ord;
                $errorct=InventoryResult::wherein('link_id',$filter_timesID)
                                        ->where('floor','=',$floorname)
                                        ->where('floormap','=',$bookcaseName)
                                        ->where('bookcaseord','=',$bookcaseord)
                                        ->whereIn('ishere', [0,2,-1])
                                        ->count();

                $correct=InventoryResult::wherein('link_id',$filter_timesID)
                    ->where('floor','=',$floorname)
                    ->where('floormap','=',$bookcaseName)
                    ->where('bookcaseord','=',$bookcaseord)
                    ->whereIn('ishere', [1])
                    ->count();
                $BookCaseNos[$key]['errorcount']=['errorct'=>$errorct,'correct'=>$correct];
            }
        }
        $data=[
            'timesname'=>$timesname,
            'floorname'=>$floorname,
            'floormapname'=>$floormap->bookcaseName,
            'floormapid'=>$floormapid,
            'bookcaseName'=>$floormap->bookcaseName,
            'bookcaseNote'=>$floormap->bookcaseNote,
            'severalrows'=>$floormap->severalrows,
            'severalcols'=>$floormap->severalcols,
            'BookCaseNos'=>$BookCaseNos
        ];
        return view("admin.inventory.bookcase",$data);
    }


    //{floor}/{floormap}/{caseno}
    public function end($timesname,$floor,$floormap,$caseno,$gotopid,Content $content){
        $filter_times = explode(',', $timesname);
        $filter_timesID = InventoryTime::whereIn('inventory_time', $filter_times)->pluck('id')->toArray();

        // 使用 Floor 模型進行查詢
        $startnum=""; $endnum="";
        $floorRecord = Floor::where('name','=',$floor)->select('id')->first();
        try{
            if ($floorRecord) {
                $s_floormap = FloorMap::where('bookcaseName','=',$floormap)->where('store_id','=',$floorRecord->id)->select("id")->first();
                if ($s_floormap) {
                    // 使用 Bookcaseno 模型進行查詢

                    $bookcaseno = BookCaseNo::where('ord', $caseno)->where('link_id','=',$s_floormap->id)->select("startnum","endnum")->first();
                    if ($bookcaseno) {
                        $startnum=$bookcaseno->startnum;
                        $endnum=$bookcaseno->endnum;
                    }
                }
            }
        }catch (Exception $e){
            $startnum=""; $endnum="";
        }
        $DBbooks = BookInfo::whereBetween('local', [$startnum, $endnum])->orderBy('local')->get()->toArray();

        $mybooks=InventoryResult::whereIn('link_id', $filter_timesID)->where('floor','=',$floor)->where('floormap','=',$floormap)->where('bookcaseord','=',$caseno)->orderBy('matchord','asc')->get()->toArray();
        $mybooks_ishere_1_2=[];  $mybooks_ishere_0=[]; $virtual_books=[];
        // 區分該櫃 / 不屬於該櫃的書本
        foreach ($mybooks as $item) {
            if(($item['ishere']==1 || $item['ishere']==2) &&$item['matchid']!=-1){ //原本錯位修改成正確
                $mybooks_ishere_1_2[$item['matchord']][] = $item;
            }else if($item['ishere']==1){ //正確書本
                //$mybooks_ishere_1_nomatchid[$item['matchord']][] = $item;
                $mybooks_ishere_0[$item['matchord']][] = $item;
            }else if($item['ishere']==-1){ //不見的虛擬書本
                $virtual_books[] = $item;
            }else if($item['ishere']==-0){
                $mybooks_ishere_0[$item['matchord']][] = $item;
            }
        }
        $mybooks_ishere_1_2 = array_values($mybooks_ishere_1_2);


        // 保留索引為0的子數組，其他索引的內容合併為一個數組
        $finalArray = array_shift($mybooks_ishere_0);
        foreach ($mybooks_ishere_0 as $subArray) {
            $finalArray[] = $subArray[0];
        }
        $mybooks_ishere_0=$finalArray;
        /* 依序處理有問題的書本 透過ord反查matchord塞前面 */
        foreach($mybooks_ishere_0 as $mybook_error){
            if($mybook_error['ord']==1) {
                array_splice($mybooks_ishere_1_2, 0, 0, [[$mybook_error]]);
                continue;
            } //解決前面沒資料

            $error_ord=$mybook_error['ord'];
            $do_continue=false;
            $best_to_insert_ord=-1;
            for($i=0;$i<sizeof($mybooks_ishere_1_2);$i++){
                if(isset($mybooks_ishere_1_2[$i+1][0]) && $mybooks_ishere_1_2[$i+1][0]['ord']==$error_ord+1 &&$mybooks_ishere_1_2[$i+1][0]['ishere']==1){
                    //優先找他的ord後面的(無錯位的)，塞到他的前面
                    $temp_matchord=$mybooks_ishere_1_2[$i+1][0]['matchord'];
                    for($j=0;$j<sizeof($mybooks_ishere_1_2);$j++){
                        if($mybooks_ishere_1_2[$j][0]['matchord']==$temp_matchord){
                            array_splice($mybooks_ishere_1_2, $j, 0, [[$mybook_error]]);
                            $do_continue=true;
                            break;
                        }
                    }
                    break;
                }else if(isset($mybooks_ishere_1_2[$i][0]) && $mybooks_ishere_1_2[$i][0]['ord']==$error_ord-1 &&$mybooks_ishere_1_2[$i][0]['ishere']==1){
                    //優先找他的ord前面的(無錯位的)，塞到他的後面
                    $temp_matchord=$mybooks_ishere_1_2[$i][0]['matchord'];
                    for($j=0;$j<sizeof($mybooks_ishere_1_2);$j++){
                        if($mybooks_ishere_1_2[$j][0]['matchord']==$temp_matchord){
                            array_splice($mybooks_ishere_1_2, $j+1, 0, [[$mybook_error]]);
                            $do_continue=true;
                            break;
                        }
                    }
                    break;
                }
            }
            if($do_continue==true) continue;
            $best_to_insert_ord=0;
            for($i=0;$i<sizeof($mybooks_ishere_1_2);$i++){
                // 越小越好
                if(abs($best_to_insert_ord-$error_ord) < abs($mybooks_ishere_1_2[$i][0]['ord']-$error_ord)){
                    $best_to_insert_ord=$mybooks_ishere_1_2[$i][0]['ord'];
                }
            }

            if($best_to_insert_ord<=$error_ord){ //小
                if(isset($mybooks_ishere_1_2[$best_to_insert_ord][0])){
                    $temp_matchord=$mybooks_ishere_1_2[$best_to_insert_ord][0]['matchord'];
                    for($j=0;$j<sizeof($mybooks_ishere_1_2);$j++){
                        if($mybooks_ishere_1_2[$j][0]['matchord']==$temp_matchord){
                            array_splice($mybooks_ishere_1_2, $j, 0, [[$mybook_error]]);
                            break;
                        }
                    }
                    break;
                }else{
                    array_splice($mybooks_ishere_1_2, 0, 0, [[$mybook_error]]);
                }
            }else if($best_to_insert_ord<=$error_ord){ //大
                if(isset($mybooks_ishere_1_2[$best_to_insert_ord][0])){
                    $temp_matchord=$mybooks_ishere_1_2[$best_to_insert_ord][0]['matchord'];
                    for($j=0;$j<sizeof($mybooks_ishere_1_2);$j++){
                        if($mybooks_ishere_1_2[$j][0]['matchord']==$temp_matchord){
                            array_splice($mybooks_ishere_1_2, $j+1, 0, [[$mybook_error]]);
                            break;
                        }
                    }
                    break;
                }else{
                    array_splice($mybooks_ishere_1_2, 0, 0, [[$mybook_error]]);
                }
            }
        }


        $DBbooks_ct=0; $mybooks_ct=0;
        $results=[];
        while($DBbooks_ct<sizeof($DBbooks) || $mybooks_ct<sizeof($mybooks_ishere_1_2)){
            if($mybooks_ishere_1_2[$mybooks_ct][0]['matchid']==-1){ //以前是找不到的，現在處理了~
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],null];
                $mybooks_ct++;
            }
            if($DBbooks_ct==sizeof($DBbooks)){
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],null];
                $mybooks_ct++;
            }else if($mybooks_ct==sizeof($mybooks_ishere_1_2)){
                $results[]=[null,$DBbooks[$DBbooks_ct]];
                $DBbooks_ct++;
            }
            else if($mybooks_ishere_1_2[$mybooks_ct][0]['matchid']==$DBbooks[$DBbooks_ct]['id'] &&$mybooks_ishere_1_2[$mybooks_ct][0]['ishere']!=0){
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],$DBbooks[$DBbooks_ct]];
                $DBbooks_ct++;
                $mybooks_ct++;
            }else if($mybooks_ishere_1_2[$mybooks_ct][0]['ishere']==1){
                $results[]=[null,$DBbooks[$DBbooks_ct]];
                $DBbooks_ct++;
            }else{
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],null];
                $mybooks_ct++;
            }
        }
        for($i=0;$i<sizeof($results);$i++){
            if($results[$i][0]!='my_orange' &&isset($results[$i][0][0]) && $results[$i][0][0]['ishere']==2 && !is_null($results[$i][0][0]) && !is_null($results[$i][1])){
                if(($results[$i][1]=='DB_black')){
                    continue;
                }
                $realmyord=$results[$i][0][0]['ord'];
                $temp_data=$results[$i][0];
                $results[$i][0]='my_orange';
                for($j=0;$j<sizeof($results);$j++){
                    if($results[$j][0]!='my_orange' && isset($results[$j][0][0]) && $results[$j][0][0]['ord']>$realmyord){ //找到插前面
                        array_splice($results, max($j,0), 0,  [array_merge([$temp_data], ['DB_black'])]);
                        break;
                    }
                }
            }
        }
        //將虛擬的書本加入進去
        foreach ($virtual_books as $virtual_book){
            for($i=0;$i<sizeof($results);$i++){
                if(!is_null($results[$i][1]) && $results[$i][1]!="DB_black"&& $virtual_book['matchid']==$results[$i][1]['id']){
                    $results[$i][0][0]=$virtual_book;
                    break;
                }

            }
        }


        $bookcaseimg=InventoryBookcaseimg::whereIn('link_id', $filter_timesID)->where('floor','=',$floor)->where('floormap','=',$floormap)->where('bookcaseord','=',$caseno)->orderBy('id','asc')->get();
        $data=[
            'timesname'=>$timesname,
            'gotopid'=>$gotopid,
            'floor'=>$floor,
            'floormap'=>$floormap,
            'caseno'=>$caseno,
            'results'=>$results,
            'DBbooks'=>$DBbooks,
            'bookcaseimg'=>$bookcaseimg
        ];
        return view("admin.inventory.end",$data);


    }


    public function small($timesname,$results_id,$DBbooksID,Content $content){
        $filter_times = explode(',', $timesname);
        $filter_timesID = InventoryTime::whereIn('inventory_time', $filter_times)->pluck('id')->toArray();
        if($results_id!="null"){
            $book=InventoryResult::find($results_id);
            $mybooks=InventoryResult::whereIn('link_id', $filter_timesID)
                ->where('floor','=',$book->floor)
                ->where('floormap','=',$book->floormap)
                ->where('bookcaseord','=',$book->bookcaseord)
                ->where('matchid','=',$book->matchid)
                ->where('ord','=',$book->ord)
                ->get()
                ->toarray();

            $book_matchid=$book->matchid;
            if($book_matchid!=-1){
                $DBbook=BookInfo::find($book_matchid)->toarray();
                $data=[
                    'mybooks'=>$mybooks,
                    'DBbook'=>$DBbook
                ];
                return view("admin.inventory.small",$data);
            }else{
                $data=[
                    'mybooks'=>$mybooks,
                    'DBbook'=>null
                ];
                return view("admin.inventory.small",$data);
            }
        }

    }

    public function smallPatch($results_id,$DBbooksID,$ishere,Content $content){
        if($results_id!="null"){
            $book=InventoryResult::find($results_id);
            if($DBbooksID==-1){ //在這櫃出現多餘的書本
                $book->update(['ishere' => $ishere]);
            }else if($book->ord==-1){ //虛擬書本
                if($ishere==0) $ishere=-1;
                $book->update(['ishere' => $ishere]);

            }

            else{
                $otherbooks=InventoryResult::where('link_id', $book->link_id)
                    ->where('floor','=',$book->floor)
                    ->where('floormap','=',$book->floormap)
                    ->where('bookcaseord','=',$book->bookcaseord)
                    ->where('matchid','=',$book->matchid)
                    ->where('ord','=',$book->ord)
                    ->update(['ishere' => $ishere]);
            }

        } //完善好 ，有一起刪除的BUG

        return 200;
    }
}
