<div class="container-fluid">
    <a href="{{route('admin.librarymap.flooredit')}}" class="editBtn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        點我開啟編輯模式</a>
</div>
<style>
    .editBtn{
        width: 100%;
        display: block;
        cursor: pointer;
        text-align: center;
        padding: 1px 0px;
        font-size: 20px;
        border: 2px solid black;
        border-radius: 10px;
        background-color: #afdbf2;
        color:black;
        transition: background .2s;
    }
    .editBtn:hover{
        color:black;
        background-color: #8ed3f8;
    }
</style>
<div class="container-fluid mt-2">
    <div class="row">
        <div class="container-fluid">
            <div class="row title">
                <div class="col-12 col-sm-1">順序</div>
                <div class="col-12 col-sm-5">樓層代碼</div>
                <div class="col-12 col-sm-4">備註</div>
                <div class="col-12 col-sm-2">櫃數</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.librarymap.floormap",$floor->id)}}" class="row floor">
                    <div class="col-12 col-sm-1 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-5 floor_name">{{$floor->name}}</div>
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

        .floor:hover{
            color:black;
            background-color: rgba(0, 95, 247, 0.65);
            transition:  background-color .3s;

        }
        .floor_name{
            background-color: rgba(255, 140, 0, 0.78);
            border-radius: 5px;
        }
        .floor_ord{
            background-color: rgba(170, 170, 170, 0.25);
            border-radius: 500px;
            text-align: center;
        }
    </style>
</div>
<script>
</script>
