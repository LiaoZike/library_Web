<link rel="stylesheet" href="{{asset('admin_css/shared.css')}}">

<script src="{{asset('admin_js/inventory/multiselect-dropdown.js')}}"></script>

<div class="container-fluid mt-2">



    <div class="row">
        <select name="field2" id="field2" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3" onchange="refreshurl()">
            @foreach($DBtimes as $DBtime)
                <option @if(in_array($DBtime->inventory_time,$filter_times)) selected @endif value="{{$DBtime->inventory_time}}">{{$DBtime->inventory_time}}</option>
            @endforeach
        </select>
    </div>
    <script>
        var temp_url='{{route('admin.inventory.floor',"times")}}'
        function refreshurl(){

            var selectedOptions = document.getElementById("field2").selectedOptions;
            var temp_data=""
            // 處理選擇的選項
            for (var i = 0; i < selectedOptions.length; i++) {
                if(temp_data!="") temp_data+=","
                temp_data+=selectedOptions[i].text
            }
            temp_url=temp_url.replace('times',temp_data)
            window.location.href = temp_url;

        }
    </script>
    <br>
    <div class="row">
        <div class="container-fluid">
            <div class="row title">
                <div class="col-12 col-sm-1">順序</div>
                <div class="col-12 col-sm-1"></div>
                <div class="col-12 col-sm-3">樓層代碼</div>
                <div class="col-12 col-sm-3">備註</div>
                <div class="col-12 col-sm-2">有問題櫃數</div>
                <div class="col-12 col-sm-2">總設計櫃數</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.inventory.floormap",['timesname' => $timesname, 'floorid' => $floor->id])}}" class="row floor @if($floor->errorcount!=0) book_notice_red @elseif($floor->currectct!=0) book_notice_green @else book_notice_gray @endif">
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
    <div class="row mt-5">
        <div class="container-fluid mt-2 error_block">
            <div class="row error_title">
                <span>其他盤點資訊</span>
            </div>
            @foreach($ErrorInfos as $ErrorInfo)
            <div class="row error_item">
                <span class="text-red">{{$ErrorInfo}}</span>
            </div>
            @endforeach
        </div>
    </div>
    <style>
        .text-red{
            color:red;
        }
        .title{
            text-align: center;
            background-color: #555;
            color:white;
        }
        .floor:nth-child(2){
            border-top: 1px solid black;
        }
        .floor{
            /*border: 1px solid black;*/
            border-bottom: 1px solid black;
            padding: 10px 0px;
            text-align: center;
            background-color: #ddd;
            color:black;
        }

        .error_title{
            background: #e9b34e;
            text-align: center;
        }
        .error_block{
            border:1px solid black;
        }
        .error_item{

        }

    </style>
</div>
