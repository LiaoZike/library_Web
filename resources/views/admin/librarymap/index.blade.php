<link rel="stylesheet" href="{{asset('admin_css/shared.css')}}">
<link rel="stylesheet" href="{{asset('admin_css/librarymap/index.css')}}">
<div class="container-fluid">
    <a href="{{route('admin.librarymap.flooredit')}}" class="editBtn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        點我開啟編輯模式</a>
</div>
<div class="container-fluid mt-2">
    <div class="row">
        <div class="container-fluid">
            <div class="row title">
                <div class="col-12 col-sm-1">順序</div>
                <div class="col-12 col-sm-1"> </div>
                <div class="col-12 col-sm-4">樓層代碼</div>
                <div class="col-12 col-sm-4">備註</div>
                <div class="col-12 col-sm-2">已設計櫃數</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.librarymap.floormap",$floor->id)}}" class="row floor">
                    <div class="col-12 col-sm-1 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-1"> </div>
                    <div class="col-12 col-sm-4 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-4">{{$floor->note}}</div>
                    <div class="col-12 col-sm-2">{{$floor->maps_count}}</div>
                </a>
            @endforeach

        </div>
    </div>
</div>
