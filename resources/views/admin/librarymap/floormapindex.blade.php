<link rel="stylesheet" href="{{asset('admin_css/shared.css')}}">
<link rel="stylesheet" href="{{asset('admin_css/librarymap/floormapindex.css')}}">
<div class="container-fluid">
    <a href="{{route('admin.librarymap.floormapedit',$id)}}"  class="editBtn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        點我開啟編輯模式</a>
</div>
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
                <a href="{{route("admin.librarymap.floormap",$floor->id)}}" class="row floor"  @if($floor->id==$id) style="background-color: #a47878" @endif>
                    <div class="col-12 col-sm-2 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-4 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-4">{{$floor->note}}</div>
                    <div class="col-12 col-sm-2">{{$floor->maps_count}}</div>
                </a>
            @endforeach
        </div>

    </div>
    <div class="designblockrow">
        <div class="designblock mt-3" style="width: {{$deswidth}}px;height: {{$desheight}}px">
            @foreach($floormaps as $floormap)
            <div data-id="{{$floormap->id}}" class="draggable" style="rotate:{{$floormap->rotate}}deg;width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;">
                    <div class="text" style="rotate:-{{$floormap->rotate}}deg;">{{$floormap->bookcaseName}}</div>
            </div>
            @endforeach
        </div>
    </div>

</div>
<div id="contentFrameWrapper">
    <iframe id="contentFrame" src=""></iframe>
</div>

<style>
    .bookcaseblock{
        background-color: yellow;
    }
    #contentFrameWrapper {
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;

        display: none;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.5); /* 背景顏色，可以自行調整透明度 */
        z-index: 99999;
    }

    #contentFrame{
        width: 80%; /* iframe 寬度 */
        height: 80%; /* iframe 高度 */
        border: none;
        background-color: white;
        transition: all .1s ease-in-out; /* 動畫效果 */
        user-select: none;
        transform: translateX(-100%);
    }
</style>
<div class="mb-5">說明...</div>
<script>

    $(document).ready(function () {
        // 點擊 .draggable 元素時觸發的事件
        $('.draggable').on('click', function () {
            var getId = $(this).data('id'); // 假設你將 ID 存儲在 data-id 屬性中
            console.log('{{ route('admin.librarymap.bookcaseedit', '') }}' + '/' + getId);
            $('#contentFrameWrapper').css('display', 'flex');
            setTimeout(function (){
                $('#contentFrame').css('transform', 'translateX(0)');
            },10)
            $('#contentFrame').attr('src', '{{ route('admin.librarymap.bookcaseedit', '') }}' + '/' + getId);
        });

        // 點擊 #contentFrameWrapper 元素時觸發的事件
        $('#contentFrameWrapper').click(function () {
            setTimeout(function (){
                $('#contentFrameWrapper').css('display', 'none');
            },100);
            $('#contentFrame').css('transform', 'translateX(-100%)');
        });
    });
</script>
