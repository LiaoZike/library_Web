<div class="container-fluid">
    <a href="{{route('admin.librarymap.floormap',$id)}}" class="editBtn"><i class="fa fa-window-close-o" aria-hidden="true"></i>
        點我關閉編輯模式</a>
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
        background-color: #febdbd;
        color:black;
        transition: background .2s;
    }
    .editBtn:hover{
        color:black;
        background-color: #f88e8e;
    }
</style>

<div class="container-fluid mt-2">
    <!-- 模態框背景 -->
    <div id="modalBg" class="modal-bg"></div>

    <!-- 確認框 -->
    <div id="customConfirm" class="confirmation">
        <button class="close-btn" onclick="cancelSubmit()">×</button>
        <div class="message">確定要送出嗎?</div>
        <div class="buttons">
            <button class="cancel_btn" onclick="cancelSubmit()">取消</button>
            <button onclick="confirmSubmit()">確定</button>
        </div>
    </div>
    <div class="diagram_info">
        <input type="checkbox" id="checkhide">
        <div class="diagram_title">
            <label for="checkhide" class="checkhide">
                <div class="close_info"></div>
            </label>
        </div>
        <div class="diagram_content container-fluid">
            <div class="input-group">
                <div class="">
                    <span>櫃碼:</span><input type="text" name="name" maxlength="20" placeholder="書櫃碼" value=""/>
                </div>
                <div class="">
                    <span>備註:</span><input type="text" name="note" maxlength="30" placeholder="備註" value=""/>
                </div>
                <div class="">
                    <span>上座標:</span><input type="number" name="top" min="0" placeholder="Top">
                </div>
                <div class="">
                    <span>左座標:</span><input type="number" name="left" min="0" placeholder="Left">
                </div>
                <div class="">
                    <span>高度:</span><input type="number" name="height" min="1" placeholder="Height">
                </div>
                <div class="">
                    <span>寬度:</span><input type="number" name="width" min="1" placeholder="Width">
                </div>
                <div class="">
                    <span>旋轉:</span><input type="number" name="rotate" step="5" placeholder="Rotate">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-4 col-md-3">
            <div class="row title">
                <div class="col-12 col-sm-2">順序</div>
                <div class="col-12 col-sm-4">樓層代碼</div>
                <div class="col-12 col-sm-4">備註</div>
                <div class="col-12 col-sm-2">櫃數</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.librarymap.floormapedit",$floor->id)}}" class="row floor"  @if($floor->id==$id) style="background-color: rgba(0,105,255,0.89)" @endif>
                    <div class="col-12 col-sm-2 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-4 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-4">{{$floor->note}}</div>
                    <div class="col-12 col-sm-2">{{$floor->sizeofobj}}</div>
                </a>
            @endforeach
        </div>
        <div class="col-12 col-sm-8 col-md-9">
            <!-- 功能選單位置 -->
            <form action="{{route("admin.librarymap.floormapedit",$id)}}" method="post" id="designinput">
                @csrf()
                <button id="addinputGP" type="button"><i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
                <button id="formcheck" onclick="return showCustomConfirm()" type="button"><i class="fa fa-upload" aria-hidden="true"></i> 儲存</button>
                <div class="row mt-1 mx-1" style="text-align: center;background-color: #555;color:white;">
                    <span>設定平面高寬度</span>
                </div>
                <div class="row  mx-1" id="designsize">
                    高:&nbsp;&nbsp;<input class="col-11" type="number" value="{{$desheight}}" name="designsize_H" min=0 max=2000 placeholder="長度(px)">
                   </div>
                <div class="row  mx-1" id="designsize">
                    寬:&nbsp;&nbsp;<input class="col-11" type="number" value="{{$deswidth}}" name="designsize_W" min=0 max=2000 placeholder="寬度(px)">
                </div>
                <div class="row title pt-1 mt-3">
{{--                    <div class="col-1">ID</div>--}}
                    <div class="col-3">書櫃碼</div>
                    <div class="col-2">書櫃備註</div>
                    <div class="col-1">上座標</div>
                    <div class="col-1">左座標</div>
                    <div class="col-1">高度</div>
                    <div class="col-1">寬度</div>
                    <div class="col-1">旋轉</div>
                    <div class="col-2">功能</div>
                </div>
                <div class="inputdivGP">
                    @foreach($floormaps as $floormap)
                        <div class="row inputGP">
                            <div class="col-1" style="display: none" ><span><input style="display: none" type="text" name="id[]" value="{{$floormap->id}}"></span></div>
                            <div class="col-12 col-sm-3"><input type="text" name="name[]" placeholder="書櫃碼" maxlength="20" value="{{$floormap->bookcaseName}}"> </div>
                            <div class="col-12 col-sm-2"><input type="text" name="note[]" placeholder="備註" maxlength="30" value="{{$floormap->bookcaseNote}}"> </div>
                            <div class="col-12 col-sm-1"><input type="number" min="0" name="top[]" placeholder="Top" value="{{$floormap->top}}"> </div>
                            <div class="col-12 col-sm-1"><input type="number" min="0" name="left[]" placeholder="Left" value="{{$floormap->left}}"> </div>
                            <div class="col-12 col-sm-1"><input type="number" min="1" name="height[]" placeholder="Height" value="{{$floormap->height}}"> </div>
                            <div class="col-12 col-sm-1"><input type="number" min="1" name="width[]" placeholder="Width" value="{{$floormap->width}}"> </div>
                            <div class="col-12 col-sm-1"><input type="number" step="5" min="-360" max="360" name="rotate[]" placeholder="Rotate" value="{{$floormap->rotate}}"> </div>
                            <div class="col-12 col-sm-2"><button type="button" class="destoryinputGP">刪除</button></div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
    <div class="designblockrow">
        <div class="designblock mt-3" style="width: {{$deswidth}}px;height: {{$desheight}}px">
            @foreach($floormaps as $floormap)
            <div class="draggable hidedraggable" style="rotate:{{$floormap->rotate}}deg; width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;">
                    <div class="routebtn"><i class="fa fa-repeat" aria-hidden="true"></i></div>
                    <div class="text">{{$floormap->bookcaseName}}</div>
                    <div class="resizable-handle"></div>
            </div>
            @endforeach
{{--            <div class="draggable" style="">888--}}
{{--                <div class="resizable-handle"></div>--}}
{{--            </div>--}}
        </div>
    </div>
    <!--自定義確認框-->
    <style>
        /* 自定義的確認框樣式 */
        /* 確認框外部背景 */
        .modal-bg {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* 透明度背景 */
            z-index: 10000;
        }

        /* 確認框樣式 */
        .confirmation {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 10002;
            width: 300px;
            max-width: 80%;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .confirmation .message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }

        .confirmation .buttons {
            text-align: center;
        }

        .confirmation button {
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .confirmation button:hover {
            background-color: #0056b3;
        }
        .confirmation .close-btn:hover,.cancel_btn:hover{
            background-color: #ff0000 !important;
        }
        /* 確認框中的取消按鈕（X） */
        .confirmation .close-btn {
            position: absolute;
            top: 0px;
            right: 0px;
            cursor: pointer;
            color: #888;
            font-size: 30px;
            background: none;
            border: none;
        }
    </style>
</div>
<div class="mb-5">說明...</div>
<link rel="stylesheet" href="{{asset("admin_css/librarymap/floormapsshow.css")}}">
<script>
    function showCustomConfirm() {
        document.getElementById("modalBg").style.display = "block";
        document.getElementById("customConfirm").style.display = "block";
        return false;
    }

    function cancelSubmit() {
        document.getElementById("modalBg").style.display = "none";
        document.getElementById("customConfirm").style.display = "none";
        return false;
    }
    function confirmSubmit() {
        // 確定提交表單
        document.getElementById("designinput").submit();
    }
    $('#modalBg').on('click',function(event) {
        if (event.target === this) {
            document.getElementById("modalBg").style.display = "none";
            document.getElementById("customConfirm").style.display = "none";
        }
    });
</script>
<script src="{{asset('admin_js/librarymap/floormapsshow.js')}}"></script>

