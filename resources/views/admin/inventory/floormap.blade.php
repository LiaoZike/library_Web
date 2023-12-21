
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-12 col-sm-4 col-md-3">
            <div class="row title">
                <div class="col-12 col-sm-2">順序</div>
                <div class="col-12 col-sm-4">樓層代碼</div>
                <div class="col-12 col-sm-4">備註</div>
                <div class="col-12 col-sm-2">櫃數</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.inventory.floormap",$floor->id)}}" class="row floor @if($floor->id%4==3) bck_red @endif"  @if($floor->id==$id) style="background-color: rgba(0,105,255,0.89)" @endif>
                    <div class="col-12 col-sm-2 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-4 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-4">{{$floor->note}}</div>
                    <div class="col-12 col-sm-2">{{$floor->sizeofobj}}</div>
                </a>
            @endforeach
        </div>

    </div>
    <div class="designblockrow">
        <div class="designblock mt-3" style="width: {{$deswidth}}px;height: {{$desheight}}px">
            @foreach($floormaps as $floormap)
            <a href="#" class="draggable @if($floormap->id%5==0) bck_red @endif" style="width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;">
                    <div class="text">{{$floormap->bookcaseName}}</div>
            </a>
            @endforeach
        </div>
    </div>

    <style>
        a{
            color:black;
        }
        .bck_red{
            background-color: #ff4000 !important;
        }
        .container-fluid{
            overflow-x:auto;
        }
        .designblockrow{
            width: 100%;
            overflow-x:auto;
        }

        #checkhide:checked ~ .diagram_content{
            max-height: 0;
            max-width: 40px;
        }
        #checkhide:checked ~ .diagram_title{
            max-width: 40px;
        }


        .destoryinputGP{
            width: 80%;
            background-color: red;
        }
        input{
            width: 100%;
        }
        .inputGP{
            padding: 5px 0px;
        }
        .inputGP:hover{
            background-color: rgba(255, 165, 0, 0.41);
        }
        .title{
            background-color: #555;
            color:white;
        }
        .designblock{
            outline: black 1px solid;
            overflow: auto;
            position: relative;
            background-color: #f4ecdc;
        }
        .draggable{
            width: 50px;
            height: 50px;
            top:10px;
            left:10px;
            opacity: 0.9;
            background-color: #e0e0e0;
            position: absolute;
            cursor: pointer;
            user-select: none;
            outline: 2px solid #000000;
            border-radius: 5px;
        }


        .title{
            text-align: center;
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
