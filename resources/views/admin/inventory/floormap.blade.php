<link rel="stylesheet" href="{{asset('admin_css/shared.css')}}">
<link rel="stylesheet" href="{{asset('admin_css/inventory/floormap.css')}}">
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
                <a href="{{route("admin.inventory.floormap",$floor->id)}}" class="row floor @if($floor->id%4==0  ) book_notice_red @endif"  @if($floor->id==$id) style="background-color: #a47878 !important;" @endif>
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
            <a href="#" onclick="openPopup(); return false;" class="draggable @if($floormap->id%5==2) book_notice_red @endif" style="rotate:{{$floormap->rotate}}deg;width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;">
                    <div class="text">{{$floormap->bookcaseName}}</div>
            </a>
            @endforeach
        </div>
    </div>

</div>
