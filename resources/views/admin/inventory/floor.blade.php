<link rel="stylesheet" href="{{asset('admin_css/shared.css')}}">
<div class="container-fluid mt-2">
    <div class="row">
        <div class="container-fluid">
            <div class="row title">
                <div class="col-12 col-sm-1">順序</div>
                <div class="col-12 col-sm-1"></div>
                <div class="col-12 col-sm-4">樓層代碼</div>
                <div class="col-12 col-sm-4">備註</div>
                <div class="col-12 col-sm-2">已設計櫃數</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.inventory.floormap",$floor->id)}}" class="row floor @if($floor->id%4==0) book_notice_red @endif">
                    <div class="col-12 col-sm-1 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-1"></div>
                    <div class="col-12 col-sm-4 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-4">{{$floor->note}}</div>
                    <div class="col-12 col-sm-2">{{$floor->count}}</div>
                </a>
            @endforeach

        </div>
    </div>
    <style>
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

    </style>
</div>
