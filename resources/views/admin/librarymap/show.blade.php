<link rel="stylesheet" href="{{asset('admin_css/shared.css')}}">
<link rel="stylesheet" href="{{asset('admin_css/librarymap/show.css')}}">
<div class="container-fluid">
    <a href="{{route('admin.librarymap.floor')}}" class="editBtn"><i class="fa fa-times" aria-hidden="true"></i>
        點我關閉編輯模式</a>
</div>
<div class="container-fluid mt-2">
    <div class="row">
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


        <form  id="floorForm" method="POST" action="{{route("admin.librarymap.flooreditsave")}}">
            @csrf
            <button type="button" id="addFloor"><i class="fa fa-plus" aria-hidden="true"></i> 新增欄位</button>
            <button type="button" id="checkFloor" onclick="return showCustomConfirm()"><i class="fa fa-upload" aria-hidden="true"></i> 儲存送出</button>
                    <div class="mt-1" id="floorFields">
                        <div class="row floortitle">
                            <div class="col-12 col-sm-1">順序</div>
                            <div class="col-12 col-sm-3">樓層代碼</div>
                            <div class="col-12 col-sm-3">備註</div>
                            <div class="col-12 col-sm-1">櫃數</div>
                            <div class="col-12 col-sm-2">刪除</div>
                            <div class="col-12 col-sm-2">功能鍵</div>
                        </div>
                        <div class="flooritems" id="sortable-list">
                        <!-- 初始的一組輸入欄位 -->
                        @foreach($floors as $floor)
                            <div class="row flooritem" draggable="true">
                                <div style="display:none"><label for="floorId">Floor ID</label><input type="text" name="id[]" value="{{$floor->id}}" min="1" readonly tabindex="-1"></div>
                                <div class="col-12 col-sm-1 readonly">
                                    <label for="floorId" style="display:none">樓層順序</label>
                                    <input type="text" name="ord[]" value="{{$floor->ord}}" min="1" readonly tabindex="-1">
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="floorId" style="display:none">樓層名稱</label>
                                    <input type="text" name="name[]"  placeholder="樓層" value="{{$floor->name}}">
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="floorId" style="display:none">樓層備註</label>
                                    <input type="text" name="note[]" placeholder="備註" value="{{$floor->note}}">
                                </div>
                                <div class="col-12 col-sm-1">{{$floor->maps_count}}</div>
                                <div class="col-12 col-sm-2"><button type="button" class="remove-floor">刪除</button></div>

                                <!-- 上移按鈕  -->
                                <div class="col-12 col-sm-1">
                                    <button type="button" class="move-up">↑</button>
                                </div>
                                <!-- 下移按鈕  -->
                                <div class="col-12 col-sm-1">
                                    <button type="button" class="move-down">↓</button>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
        </form>

    </div>
</div>
 ※支援樓層拖曳功能：可使用滑鼠拖曳樓層改變順序。<br>
 ※離開編輯介面時記得儲存送出。
<!-- 引入 jQuery UI -->
{{--<script src="{{asset('js/jquery-ui.min.js')}}"></script>--}}
{{--<link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">--}}
<script src="{{asset('js/sortable.js')}}"></script>
<script src="{{asset('admin_js/librarymap/show.js')}}"></script>
