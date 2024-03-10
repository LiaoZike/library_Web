<link rel="stylesheet" href="{{asset('admin_css/shared.css')}}">
<link rel="stylesheet" href="{{asset('admin_css/librarymap/floormapindex.css')}}">
<script src="{{asset('admin_js/inventory/multiselect-dropdown.js')}}"></script>
<div class="container-fluid mt-2">

    <div class="row">
        <select name="field2" id="field2" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3" onchange="refreshurl()">
            @foreach($DBtimes as $DBtime)
                <option @if(in_array($DBtime->inventory_time,$filter_times)) selected @endif value="{{$DBtime->inventory_time}}">{{$DBtime->inventory_time}}</option>
            @endforeach
        </select>
    </div>
    <br>

    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="row title">
                <div class="col-12 col-sm-2">順序</div>
                <div class="col-12 col-sm-3">樓層代碼</div>
                <div class="col-12 col-sm-3">備註</div>
                <div class="col-12 col-sm-2">有問題櫃數</div>
                <div class="col-12 col-sm-2">有設計櫃數</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.inventory.floormap",['timesname'=>$timesname,'floorid'=>$floor->id])}}" class="row floor @if($floor->errorcount!=0) book_notice_red @elseif($floor->currectct!=0) book_notice_green @else book_notice_gray @endif"  @if($floor->id==$id) style="background-color: #a47878 !important;" @endif>
                    <div class="col-12 col-sm-1 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-1"></div>
                    <div class="col-12 col-sm-3 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-3">{{$floor->note}}</div>
                    <div class="col-12 col-sm-2">{{$floor->errorcount}}</div>
                    <div class="col-12 col-sm-2">{{$floor->designcount}}</div>
                </a>
            @endforeach
        </div>

    </div>
    <div class="designblockrow">
        <div class="designblock mt-3" style="width: {{$deswidth}}px;height: {{$desheight}}px">
            @foreach($floormaps as $floormap)
            <div data-id="{{$floormap->id}}" class="draggable  @if(isset($floormap['errorcount']))
                                                                    @if($floormap['errorcount']['errorct']!=0)bck_red
                                                                    @elseif($floormap['errorcount']['correct']==0)bck_gray
                                                                    @else  bck_green
                                                                    @endif
                                                                @else  bck_gray
                                                                @endif
                                                    " style="rotate:{{$floormap->rotate}}deg;width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;">
                    <div class="text" style="rotate:-{{$floormap->rotate}}deg;">{{$floormap->bookcaseName}}</div>
                    <div class="text" style="rotate:-{{$floormap->rotate}}deg;"> (@if(isset($floormap['errorcount'])){{$floormap['errorcount']['errorct']}}@endif個錯誤碼)</div>

            </div>
            @endforeach
        </div>
    </div>

</div>
<div id="contentFrameWrapper">
    <div class="iframe_bar">
        <button id="closeiframe">X</button>
    </div>
    <iframe id="contentFrame" src="">
    </iframe>
</div>

<style>
    .iframe_bar{
        background-color: #f5ce84;
        position: relative;
        top:0;
        left: 0;
        height: 5%;
        width: 90%;
        opacity: 0;
        transition:opacity .5s;
    }
    #closeiframe{
        position: absolute;
        top: 0;
        right: 0;
        background-color: #f5ce84;
        color:black;
        padding: 1vh 20px;
        border: none;
        transition: background-color .1s,color .1s;
    }
    #closeiframe:hover{
        background-color: red;
        color:White;
    }
    .bck_red:after ,.bck_green:after ,.bck_gray:after{
        position: absolute;
        content: "";
        width: 100%;
        height: 100%;
        top:50%;
        left:50%;
        transform: translate(-50%,-50%);
        opacity: 0.35;
    }
    .bck_red:after{
        background-color: #f00 !important;
    }
    .bck_green:after{
        background-color: #0f0 !important;
    }
    .bck_gray:after{
        background-color: #555 !important;
    }

    .bookcaseblock{
        background-color: yellow;
    }
    #contentFrameWrapper {
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        flex-direction: column;
        display: none;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.5); /* 背景顏色，可以自行調整透明度 */
        z-index: 99999;
    }

    #contentFrame{
        width: 90%; /* iframe 寬度 */
        height: 90%; /* iframe 高度 */
        border: none;
        background-color: white;
        transition: all .1s ease-in-out; /* 動畫效果 */
        user-select: none;
        transform: translateX(-100%);
    }
</style>
<div class="mb-5">說明...</div>
<script>

    temp_url='{{ route('admin.inventory.floormap',['timesname'=>'times','floorid'=>$id]) }}'
    function refreshurl(){

        var selectedOptions = document.getElementById("field2").selectedOptions;
        var temp_data=""
        // 處理選擇的選項
        for (var i = 0; i < selectedOptions.length; i++) {
            if(temp_data!="") temp_data+=","
            temp_data+=selectedOptions[i].text
        }
        new_url=temp_url.replace('times',temp_data)
        window.location.href = new_url;
    }

    $(document).ready(function () {
        tempurl='{{ route('admin.inventory.bookcase',['timesname'=>$timesname,'floormapid'=>'skip']) }}'
        // 點擊 .draggable 元素時觸發的事件
        $('.draggable').on('click', function () {
            var getId = $(this).data('id'); // 假設你將 ID 存儲在 data-id 屬性中
            $('#contentFrameWrapper').css('display', 'flex');
            setTimeout(function (){
                $('#contentFrame').css('transform', 'translateX(0)');
            },10)
            $('.iframe_bar').css('opacity', '1');
            console.log(tempurl.replace('skip', getId));

            $('#contentFrame').attr('src', tempurl.replace('skip', getId));
        });

        // 點擊 #contentFrameWrapper 元素時觸發的事件
        $('#contentFrameWrapper').click(function (event) {
            // 檢查被點擊的目標是否是 #backtolastpage 元素
                setTimeout(function (){
                    $('#contentFrameWrapper').css('display', 'none');
                }, 100);
                $('#contentFrame').css('transform', 'translateX(-100%)');
            $('.iframe_bar').css('opacity', '0');
        });

    });
</script>
