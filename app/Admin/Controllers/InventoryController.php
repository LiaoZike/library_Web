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
            return redirect()->route('admin.inventory.floor',$lastTime);
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
        // 取得書櫃格－書本編碼範圍
        [$startnum, $endnum] = $this->getBookRange($floor, $floormap, $caseno);
        $DBbooks = BookInfo::whereBetween('local', [$startnum, $endnum])->orderBy('local')->get()->toArray();
        // dd($DBbooks);
        // 取得實際辨識結果
        $YOLO_books=InventoryResult::whereIn('link_id', $filter_timesID)
            ->where('floor', $floor)
            ->where('floormap', $floormap)
            ->where('bookcaseord', $caseno)
            ->orderBy('matchord', 'asc')->get()->toArray();
        // 1.事先把DB書本＋YOLO書本做聯集合併
        $results = $this->mergeBooks($YOLO_books,$DBbooks);
        $results = $this->adjust_ADDBooks_ByOrdBlocks($results);
        $results = $this->adjust_REPLACEBooks_ByOrdBlocks($results);
        // dd($results);
        // 2.將YOLO多書本移動到 正確出現位置(match_id=-1)
        // foreach( )

        
        
        /*
        $DBbooks_ct=0; $mybooks_ct=0;
        $results=[];
        while($DBbooks_ct<sizeof($DBbooks) || $mybooks_ct<sizeof($mybooks_ishere_1_2)){
            if($DBbooks_ct==sizeof($DBbooks)){
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],null];
                $mybooks_ct++;
            }else if($mybooks_ct==sizeof($mybooks_ishere_1_2)){
                $results[]=[null,$DBbooks[$DBbooks_ct]];
                $DBbooks_ct++;
            }else if($mybooks_ishere_1_2[$mybooks_ct][0]['matchid']==-1){ //以前是找不到的，現在處理了~
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],null];
                $mybooks_ct++;
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
        }*/

        // 書櫃圖片
        $bookcaseimg = InventoryBookcaseimg::whereIn('link_id', $filter_timesID)
        ->where('floor', $floor)
        ->where('floormap', $floormap)
        ->where('bookcaseord', $caseno)
        ->orderBy('id', 'asc')
        ->get();

        return view("admin.inventory.end", [
        'timesname' => $timesname,
        'gotopid' => $gotopid,
        'floor' => $floor,
        'floormap' => $floormap,
        'caseno' => $caseno,
        'results' => $results,
        'DBbooks' => $DBbooks,
        'bookcaseimg' => $bookcaseimg
        ]);


    }
    /*********************/
    /*   END子function   */
    /* 取得書櫃格的書籍範圍 */
    /*********************/
    private function getBookRange($floor, $floormap, $caseno){
        try {
            $floorRecord = Floor::where('name', $floor)->first();
            if (!$floorRecord) return ["", ""];

            $map = FloorMap::where('bookcaseName', $floormap)
                ->where('store_id', $floorRecord->id)->first();
            if (!$map) return ["", ""];

            $bookcase = BookCaseNo::where('ord', $caseno)
                ->where('link_id', $map->id)->first();

            return $bookcase ? [$bookcase->startnum, $bookcase->endnum] : ["", ""];
        } catch (Exception $e) {
            Log::error("Book range error", ['floor' => $floor, 'floormap' => $floormap, 'caseno' => $caseno, 'err' => $e->getMessage()]);
            return ["", ""];
        }
    }

    // 依 ishere 分類盤點結果 正確/錯位/不見
    private function classifyMyBooks($filter_timesID, $floor, $floormap, $caseno){
        $raw = InventoryResult::whereIn('link_id', $filter_timesID)
            ->where('floor', $floor)
            ->where('floormap', $floormap)
            ->where('bookcaseord', $caseno)
            ->orderBy('matchord', 'asc')->get()->toArray();

        $correct = [];   // ishere = 1
        $displaced = []; // ishere = 2
        $missing = [];   // ishere = -1
        foreach ($raw as $item) {
            if ($item['ishere'] == 1 && $item['matchid'] != -1) {
                $correct[$item['matchord']][] = $item;
            } elseif ($item['ishere'] == 2 && $item['matchid'] != -1) {
                $displaced[$item['matchord']][] = $item;
            } elseif ($item['ishere'] == -1) {
                $missing[] = $item;
            }
        }

        return [
            'raw' => $raw,
            'correct' => array_values($correct),
            'displaced' => array_values($displaced),
            'missing' => $missing
        ];
    }
    // 合併書籍（DB 與實際盤點）
    private function mergeBooks($YOLO_books, $DBbooks){
        $results = [];

        // DB書本與辨識書本做聯集
        foreach ($DBbooks as $dbBook) {
            $match = collect($YOLO_books)->firstWhere('matchid', $dbBook['id']);
            $results[] = [$match ?? null,$dbBook,];
        }
        $dbIds = array_column($DBbooks, 'id');
        $extraYolo = collect($YOLO_books)->filter(function ($y) use ($dbIds) {
            return !in_array($y['matchid'], $dbIds);
        });
        foreach ($extraYolo as $yoloBook) {
            $results[] = [$yoloBook,null];
        }
        return $results;
    }

    private function adjust_ADDBooks_ByOrdBlocks(array $results): array{
        // 依書櫃拆組處理，避免跨書櫃插入
        $groups = []; // bookcaseord => ['anchors'=>[], 'orphans'=>[]]
        foreach ($results as $idx => $pair) {
            $y = $pair[0] ?? null;
            $cab = $y['bookcaseord'] ?? ($pair[1]['bookcaseord'] ?? '__NO_CAB__');

            if (!isset($groups[$cab])) $groups[$cab] = ['anchors' => [], 'orphans' => []];

            if ($y && isset($y['matchid']) && (int)$y['matchid'] === -1) {
                // 孤兒
                $groups[$cab]['orphans'][] = ['pair' => $pair, 'idx' => $idx, 'ord' => $y['ord'] ?? null];
            } else {
                // 錨點（含 DB-only；但優先使用有 yolo 且 matchid!=-1 的作為「強錨點」）
                $anchorOrd = $y['ord'] ?? null; // 若無 yolo，就無 ord；之後當弱錨點
                $groups[$cab]['anchors'][] = ['pair' => $pair, 'idx' => $idx, 'ord' => $anchorOrd, 'hasYolo' => (bool)$y];
            }
        }

        // 若沒有孤兒，直接回傳
        $hasOrphan = false;
        foreach ($groups as $g) { if (!empty($g['orphans'])) { $hasOrphan = true; break; } }
        if (!$hasOrphan) return $results;

        // 先把原本不是孤兒的順序撈出來（之後將孤兒塊插到這裡面）
        $filtered = [];
        foreach ($results as $pair) {
            $y = $pair[0] ?? null;
            if (!($y && isset($y['matchid']) && (int)$y['matchid'] === -1)) {
                $filtered[] = $pair;
            }
        }

        // 針對每個書櫃做區塊插入
        foreach ($groups as $cab => $g) {
            if (empty($g['orphans'])) continue;

            // 依 ord 排序孤兒（沒有 ord 的放最後）
            usort($g['orphans'], function ($a, $b) {
                $ao = $a['ord']; $bo = $b['ord'];
                if ($ao === null && $bo === null) return 0;
                if ($ao === null) return 1;
                if ($bo === null) return -1;
                return $ao <=> $bo;
            });

            // 切成連續 ord 區塊（差1視為連續），ord 為 null 的各自成塊
            $blocks = [];
            $curr = [];
            foreach ($g['orphans'] as $o) {
                if (empty($curr)) {
                    $curr = [$o];
                } else {
                    $prevOrd = end($curr)['ord'];
                    if ($prevOrd !== null && $o['ord'] !== null && $o['ord'] === $prevOrd + 1) {
                        $curr[] = $o;
                    } else {
                        $blocks[] = $curr;
                        $curr = [$o];
                    }
                }
            }
            if (!empty($curr)) $blocks[] = $curr;

            // 取得本書櫃的錨點（先強錨點：有 yolo 且 matchid!=-1，無則用所有非孤兒項目）
            $anchors = array_values(array_filter($g['anchors'], fn($a) => $a['hasYolo'] && $a['ord'] !== null));
            if (empty($anchors)) {
                // 沒有強錨點，退化成用所有 filtered 裡、同書櫃的項目當弱錨點，順序即 index
                $anchors = [];
                foreach ($filtered as $i => $p) {
                    $y = $p[0] ?? null;
                    $pCab = $y['bookcaseord'] ?? ($p[1]['bookcaseord'] ?? '__NO_CAB__');
                    if ($pCab === $cab) {
                        $anchors[] = ['pair' => $p, 'idx' => $i, 'ord' => $y['ord'] ?? null, 'hasYolo' => (bool)$y];
                    }
                }
            }

            // 若完全沒有錨點（該櫃全是孤兒或整櫃都 DB-only 無 ord），直接依 ord 區塊排序後，附加到該櫃區段尾端
            if (empty($anchors)) {
                foreach ($blocks as $block) {
                    // 直接加到 filtered 尾端（或你可選擇加到該櫃的第一個位置，視 UI 需求）
                    foreach ($block as $o) $filtered[] = $o['pair'];
                }
                continue;
            }

            // 建立「索引 -> 實際 filtered 位置」的映射，方便算插入位置
            // 這裡用即時查找避免在插入後索引移動錯亂
            $findFilteredIndex = function ($pair) use (&$filtered): int {
                // 找第一個嚴格相等的元素（指標相等；若拷貝則需用特徵比對）
                foreach ($filtered as $i => $p) {
                    if ($p === $pair) return $i;
                }
                return -1;
            };

            // 依每一個 block 計算插入點，再一次性插入
            foreach ($blocks as $block) {
                // 計算區塊的「中位 ord」（null 則以現存 anchor ord 最近者處理）
                $ords = array_values(array_filter(array_map(fn($o) => $o['ord'], $block), fn($v) => $v !== null));
                $midOrd = !empty($ords) ? $ords[(int)floor((count($ords) - 1) / 2)] : null;

                // 找最近的 anchor（以 ord 距離為主；若 anchor 沒 ord，當作無限遠）
                $best = null;
                $bestDist = PHP_INT_MAX;
                foreach ($anchors as $a) {
                    $aIdx = $findFilteredIndex($a['pair']);
                    if ($aIdx < 0) continue; // 可能已被前面的操作影響，跳過
                    $aOrd = $a['ord'];
                    $dist = ($midOrd !== null && $aOrd !== null) ? abs($midOrd - $aOrd) : PHP_INT_MAX - 1;
                    if ($dist < $bestDist) {
                        $bestDist = $dist;
                        $best = ['pair' => $a['pair'], 'idx' => $aIdx, 'ord' => $aOrd];
                    }
                }

                // 若沒有任何 anchor 有有效索引，就把區塊加到尾端
                if ($best === null) {
                    foreach ($block as $o) $filtered[] = $o['pair'];
                    continue;
                }

                // 決定插前/後：midOrd < anchorOrd → 插前；否則插後
                $insertIndex = $best['idx'];
                if ($midOrd !== null && $best['ord'] !== null && $midOrd > $best['ord']) {
                    $insertIndex = $best['idx'] + 1;
                }
                // 按 block 原本 ord 順序插入（維持區塊內順序）
                $toInsert = array_map(fn($o) => $o['pair'], $block);
                array_splice($filtered, $insertIndex, 0, $toInsert);
            }
        }
        return $filtered;
    }
    
    /**
     * 處理錯位書本 (ishere = 2)
     * 將原格改為 [null, db]，並在實際 ord 位置插入 [yolo, null]
     */
    private function adjust_REPLACEBooks_ByOrdBlocks(array $results): array{
        $replaced = [];  // 收集要處理的錯位書
        $output   = [];  // 輸出最終結果

        // 先掃描所有錯位書
        foreach ($results as $pair) {
            $y = $pair[0] ?? null;
            if ($y && isset($y['ishere']) && (int)$y['ishere'] === 2) {
                $replaced[] = $y;
            }
        }

        // 若沒有錯位書，直接回傳
        if (empty($replaced)) return $results;

        // 保留原順序，逐筆處理
        foreach ($results as $pair) {
            $yolo = $pair[0] ?? null;
            $db   = $pair[1] ?? null;

            // 若此格是錯位書，先替換成 [null, db]
            if ($yolo && isset($yolo['ishere']) && (int)$yolo['ishere'] === 2) {
                $output[] = ["my_orange", $db]; // 原格改成只有 DB
                continue;
            }

            $output[] = $pair; // 其他維持原樣
        }
        // 插入實際出現位置的 [yolo, null]
        foreach ($replaced as $book) {
            $ord = $book['ord'] ?? null;
            $cab = $book['bookcaseord'] ?? null;
            if ($ord === null) {
                // 沒有 ord，直接附加到尾端
                $output[] = [$book, null];
                continue;
            }

            // 找同書櫃中 ord 最接近的 anchor 位置
            $bestIdx = 0;
            $bestDiff = INF;
            $bestOrd = null;

            foreach ($output as $i => $pair) {
                $y = $pair[0] ?? null;
                $yOrd = $y['ord'] ?? null;
                $yCab = $y['bookcaseord'] ?? null;

                if ($yOrd === null) continue;
                if ($cab !== null && $yCab !== null && $cab != $yCab) continue;

                $diff = abs($ord - $yOrd);
                if ($diff < $bestDiff) {
                    $bestDiff = $diff;
                    $bestIdx = $i;
                    $bestOrd = $yOrd;
                }
            }

            // 判斷插前或後
            if ($bestOrd !== null && $ord < $bestOrd) {
                array_splice($output, $bestIdx, 0, [[ $book, "DB_orange" ]]);
            } else {
                array_splice($output, $bestIdx + 1, 0, [[ $book, "DB_orange" ]]);
            }
        }

        return $output;
    }










    //{floor}/{floormap}/{caseno}
    public function end2($timesname,$floor,$floormap,$caseno,$gotopid,Content $content){
        $filter_times = explode(',', $timesname);
        $filter_timesID = InventoryTime::whereIn('inventory_time', $filter_times)->pluck('id')->toArray();
        dd($filter_timesID);
        // 取得書櫃範圍

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


        if (!empty($mybooks_ishere_0)) {
            $finalArray = array_shift($mybooks_ishere_0);
            if (!is_array($finalArray)) $finalArray = [];
            foreach ($mybooks_ishere_0 as $subArray) {
                if (isset($subArray[0])) $finalArray[] = $subArray[0];
            }
            $mybooks_ishere_0 = $finalArray;
        }
        
        // 保留索引為0的子數組，其他索引的內容合併為一個數組
        $finalArray = array_shift($mybooks_ishere_0);
        foreach ($mybooks_ishere_0 as $subArray) {
            $finalArray[] = $subArray[0];
        }
        $mybooks_ishere_0=$finalArray;
        /* mybooks_ishere_0依序處理有問題的書本 透過ord反查matchord塞前面 */
        if($mybooks_ishere_0!=[]){
            
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
                $best_to_insert_ord=0; $temp_best_index=0;
                for($i=0;$i<sizeof($mybooks_ishere_1_2);$i++){
                    // 越小越好
                    if(abs($best_to_insert_ord-$error_ord) > abs($mybooks_ishere_1_2[$i][0]['ord']-$error_ord)){
                        $best_to_insert_ord=$mybooks_ishere_1_2[$i][0]['ord'];
                        $temp_best_index=$i;
                    }
                }



                //error_ord:拍攝時的書本順序
                if($best_to_insert_ord<=$error_ord){ //小(插前面)
                    if(isset($mybooks_ishere_1_2[$best_to_insert_ord][0])){
                        $temp_matchord=$mybooks_ishere_1_2[$best_to_insert_ord][0]['matchord'];
                        for($j=0;$j<sizeof($mybooks_ishere_1_2);$j++){
                            if($mybooks_ishere_1_2[$j][0]['matchord']==$temp_matchord){
                                array_splice($mybooks_ishere_1_2, $j, 0, [[$mybook_error]]);
                                break;
                            }
                        }
                    }else{
                        array_splice($mybooks_ishere_1_2, $temp_best_index+1, 0, [[$mybook_error]]);
                    }
                }else if($best_to_insert_ord>$error_ord){ //大
                    if(isset($mybooks_ishere_1_2[$best_to_insert_ord][0])){
                        $temp_matchord=$mybooks_ishere_1_2[$best_to_insert_ord][0]['matchord'];
                        for($j=0;$j<sizeof($mybooks_ishere_1_2);$j++){
                            if($mybooks_ishere_1_2[$j][0]['matchord']==$temp_matchord){
                                array_splice($mybooks_ishere_1_2, $j+1, 0, [[$mybook_error]]);
                                break;
                            }
                        }
                    }else{
                        array_splice($mybooks_ishere_1_2,  $temp_best_index+2 , 0, [[$mybook_error]]);
                    }
                }


            }
        }


        $DBbooks_ct=0; $mybooks_ct=0;
        $results=[];
        while($DBbooks_ct<sizeof($DBbooks) || $mybooks_ct<sizeof($mybooks_ishere_1_2)){
            if($DBbooks_ct==sizeof($DBbooks)){
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],null];
                $mybooks_ct++;
            }else if($mybooks_ct==sizeof($mybooks_ishere_1_2)){
                $results[]=[null,$DBbooks[$DBbooks_ct]];
                $DBbooks_ct++;
            }else if($mybooks_ishere_1_2[$mybooks_ct][0]['matchid']==-1){ //以前是找不到的，現在處理了~
                $results[]=[$mybooks_ishere_1_2[$mybooks_ct],null];
                $mybooks_ct++;
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
            }else if($book->matchid==-1){ //不在這櫃的書本，只能改錯誤或正確
                if($ishere==-1 || $ishere==2) $ishere=0; //禁止改為虛擬或錯位
                $book->update(['ishere' => $ishere]);
            }elseif($book->ord==-1){ //虛擬書本，只能改虛擬或正確
                if($ishere==0 || $ishere==2) $ishere=-1; //禁止改為錯誤或錯位
                $book->update(['ishere' => $ishere]);
            }
            else{
                if($ishere==-1) $ishere=0; //正常書本不能改成虛擬書本
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





    public function searchlocal($number,Content $content){
        $result = BookCaseNo::where('startnum', '<=', $number)
            ->where('endnum', '>=', $number)
            ->get()
            ->toarray();
        if($result!=[]){
            for($i=0;$i<sizeof($result);++$i){
                $Floormap=FloorMap::find($result[$i]['link_id']);
                $Floor=Floor::find($Floormap['store_id']);
                $result[$i]['Floormap']=$Floormap['bookcaseName'];
                $result[$i]['Floor']=$Floor['name'];
            }
        }
        return $result;

    }

}
