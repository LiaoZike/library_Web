<div class="container">
    <div class="row">
        <!-- 模態框背景 -->
        <div id="modalBg" class="modal-bg"></div>

        <!-- 確認框 -->
        <div id="customConfirm" class="confirmation">
            <button class="close-btn" onclick="cancelSubmit()">×</button>
            <div class="message">確定要送出嗎?</div>
            <div class="buttons">
                <button onclick="cancelSubmit()">取消</button>
                <button onclick="confirmSubmit()">確定</button>
            </div>
        </div>


        <form  id="floorForm" method="POST" action="{{route("admin.librarymap.floorSave")}}">
            @csrf

            <button type="button" id="addFloor">+ 新增欄位</button>
            <button type="button" id="checkFloor" onclick="return showCustomConfirm()">v 儲存送出</button>
                    <div class="container-fluid mt-3" id="floorFields">
                        <div class="row floortitle">
                            <div class="col-12 col-sm-2">排序</div>
                            <div class="col-12 col-sm-3">樓層代碼</div>
                            <div class="col-12 col-sm-3">備註</div>
                            <div class="col-12 col-sm-4">功能鍵</div>
                        </div>
                        <div class="row flooritems">
                        <!-- 初始的一組輸入欄位 -->
                        @foreach($floors as $floor)

                            <div class="row flooritem">
                                <div style="display:none"><input type="text" name="id[]" value="{{$floor->id}}" min="1" readonly tabindex="-1"></div>
                                <div class="col-12 col-sm-2 readonly"><input type="text" name="ord[]" value="{{$floor->ord}}" min="1" readonly tabindex="-1"></div>
                                <div class="col-12 col-sm-3"><input type="text" name="name[]"  placeholder="樓層" value="{{$floor->name}}"></div>
                                <div class="col-12 col-sm-3"><input type="text" name="note[]" placeholder="備註" value="{{$floor->note}}"></div>
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
    <style>

        .bck_orange{
            background-color: orange;
        }
        #floorFields{
            text-align: center;
        }
        .floortitle{
            background-color: #555;
            color:white;
        }
        .flooritem{
            padding-top: 2px;
            padding-bottom: 2px;
            border-bottom: 1px solid black;
        }
        .flooritem button{
            width: 100%;
        }
        .flooritem input{
            width: 100%;
        }
        .flooritem input[type="text"] {
            margin-right: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .flooritem input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            transition: box-shadow .4s ,border-color .4s;
        }
        .remove-floor {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .flooritem:hover{
            background-color: #ffd180 !important;
            transition: background-color .2s;
        }
        .remove-floor:hover {
            background-color: #c82333;
        }
        .readonly input{
            background-color: rgba(128, 128, 128, 0.35);
            border:1px #ccc solid !important;
            box-shadow: none !important;
            cursor: not-allowed;
        }
        #addFloor {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
            cursor: pointer;
        }

        #addFloor:hover {
            background-color: #218838;
        }

        #checkFloor {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
            cursor: pointer;
        }

        #checkFloor:hover {
            background-color: #0056b3;
        }
        td{
            min-width: 180px;
        }

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
            z-index: 1000;
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
            z-index: 1001;
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

        .move-up:hover, .move-down:hover{
            background-color: greenyellow;
        }
    </style>
</div>
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
        document.getElementById("floorForm").submit();
    }

    $(document).ready(function() {
        flash_move();
        // 更新所有欄位的順序值
        function updateOrder() {
            var totalItems = $('#floorFields .flooritem').length;
            $('#floorFields .flooritem').each(function(index) {
                var newIndex = totalItems - index;
                $(this).find('input[name="ord[]"]').val(newIndex);
            });
        }

        // 新增欄位按鈕點擊事件
        $('#addFloor').click(function() {
            // 新增一組輸入欄位
            var newField = $(
                '<div class="row flooritem bck_orange">'+
                '<div style="display:none"><input type="text" name="id[]" value="-1" min="1" readonly tabindex="-1"><\/div>'+
                '<div class="col-12 col-sm-2 readonly"><input type="text" name="ord[]" value="0" min="1" readonly tabindex="-1"><\/div>'+
                    '<div class="col-12 col-sm-3"><input type="text" name="name[]"  placeholder="樓層"><\/div>'+
                    '<div class="col-12 col-sm-3"><input type="text" name="note[]" placeholder="備註"><\/div>'+
                    '<div class="col-12 col-sm-2"><button type="button" class="remove-floor">刪除</button><\/div>'+
                    '<div class="col-12 col-sm-1">'+
                        '<button type="button" class="move-up">↑<\/button>'+
                    '<\/div>'+
                    '<div class="col-12 col-sm-1">'+
                    '<button type="button" class="move-down">↓<\/button>'+
                    '<\/div>'+
                '<\/div>'
            );

            $('.flooritems').prepend(newField); // 加入到表單中
            flash_move();
            updateOrder(); // 移除後更新所有欄位的順序值
        });

        // 移除欄位按鈕點擊事件
        $('#floorFields').on('click', '.remove-floor', function() {
            $(this).closest('.flooritem').remove(); // 移除被點擊的輸入欄位組
            updateOrder(); // 移除後更新所有欄位的順序值
        });

        function flash_move(){
            $('.move-up').off();
            $('.move-down').off();

            // 上移按鈕點擊事件
            $('.move-up').click(function() {
                var row = $(this).closest('.flooritem');
                row.insertBefore(row.prev());
                updateOrder(); // 移除後更新所有欄位的順序值
            });

            // 下移按鈕點擊事件
            $('.move-down').click(function() {
                var row = $(this).closest('.flooritem');
                row.insertAfter(row.next());
                updateOrder(); // 移除後更新所有欄位的順序值
            });
        }
        $('#modalBg').click(function(event) {
            if (event.target === this) {
                document.getElementById("modalBg").style.display = "none";
                document.getElementById("customConfirm").style.display = "none";
            }
        });
    });

</script>
