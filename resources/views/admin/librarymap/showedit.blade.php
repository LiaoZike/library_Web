
<div class="container-fluid">
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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-4 col-md-3">
            <div class="row title">
                <div class="col-12 col-sm-2">順序</div>
                <div class="col-12 col-sm-5">樓層代碼</div>
                <div class="col-12 col-sm-5">備註</div>
            </div>
            @foreach($floors as $floor)
                <a href="{{route("admin.librarymap.editmap",$floor->id)}}" class="row floor"  @if($floor->id==$id) style="background-color: rgba(0,105,255,0.89)" @endif>
                    <div class="col-12 col-sm-2 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-5 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-5">{{$floor->note}}</div>
                </a>
            @endforeach
        </div>
        <div class="col-12 col-sm-8 col-md-9">
            <!-- 功能選單位置 -->
            <form action="{{route("admin.librarymap.editmap",$id)}}" method="post" id="designinput">
                @csrf()
                <button id="addinputGP" type="button"><i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
                <button id="submit" type="submit"><i class="fa fa-upload" aria-hidden="true"></i> 儲存</button>
                <div class="row mt-1 mx-1">
                    設定平面室內長寬度
                </div>
                <div class="row  mx-1" id="designsize">
                    長:<input type="number" value="{{$desheight}}" name="designsize_H" min=0 max=3000 placeholder="長度(px)">
                    寬:<input type="number" value="{{$deswidth}}" name="designsize_W" min=0 max=3000 placeholder="寬度(px)">
                </div>
                <div class="row title pt-1 mt-3 mx-1">
{{--                    <div class="col-1">ID</div>--}}
                    <div class="col-3">書櫃碼</div>
                    <div class="col-3">書櫃備註</div>
                    <div class="col-1">上座標</div>
                    <div class="col-1">左座標</div>
                    <div class="col-1">高度</div>
                    <div class="col-1">寬度</div>
                    <div class="col-2">功能</div>
                </div>
                <div class="inputdivGP">
                    @foreach($floormaps as $floormap)
                        <div class="row inputGP">
                            <div class="col-1" style="display: none" ><span><input style="display: none" type="text" name="id[]" value="{{$floormap->id}}"></span></div>
                            <div class="col-3"><input type="text" name="name[]" placeholder="書櫃碼" maxlength="20" value="{{$floormap->bookcaseName}}"> </div>
                            <div class="col-3"><input type="text" name="note[]" placeholder="備註" maxlength="30" value="{{$floormap->bookcaseNote}}"> </div>
                            <div class="col-1"><input type="number" min="0" name="top[]" placeholder="Top" value="{{$floormap->top}}"> </div>
                            <div class="col-1"><input type="number" min="0" name="left[]" placeholder="Left" value="{{$floormap->left}}"> </div>
                            <div class="col-1"><input type="number" min="1" name="height[]" placeholder="Height" value="{{$floormap->height}}"> </div>
                            <div class="col-1"><input type="number" min="1" name="width[]" placeholder="Width" value="{{$floormap->width}}"> </div>
                            <div class="col-2"><button type="button" class="destoryinputGP">刪除</button></div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
        <div class="designblock mt-3" style="width: {{$deswidth}}px;height: {{$desheight}}px">
            @foreach($floormaps as $floormap)
            <div class="draggable" style="width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;">
                    <div class="text">{{$floormap->bookcaseName}}</div>
                    <div class="resizable-handle"></div>
            </div>
            @endforeach
{{--            <div class="draggable" style="">888--}}
{{--                <div class="resizable-handle"></div>--}}
{{--            </div>--}}
        </div>
    </div>

    <style>
        #checkhide:checked ~ .diagram_content{
            max-height: 0;
            max-width: 40px;
        }
        #checkhide:checked ~ .diagram_title{
            max-width: 40px;
        }

        .diagram_info{
            position: fixed;
            right: 0px;
            top: 50px;
            background-color: #f8f8f8;
            z-index: 9999;
            box-shadow: 0px 0px 3px 1px #909090;
            border-radius: 5px;
        }
        .diagram_content{
            max-height: 400px; /* 调整展开的高度 */
            overflow: hidden;
            max-width: 100px;
            transition: max-height .2s,max-width.2s;
        }
        .diagram_title{
            height: 30px;
            background-color: #fbe7e7;
            position: relative;
            overflow: hidden;
            max-width: 100px;
            transition:  .2s,max-width.2s;
        }
        .checkhide{ /* label */
            background-color: transparent;
            position: absolute;
            top: 0px;
            right: 0px;
            width: 40px;
            height: 30px;
            cursor: pointer;
        }
        #checkhide{
            display: none;
        }
        /* 關閉按鈕 */
        .close_info{
            width: 50%;
            height: 1px;
            background-color: #000;
            border-radius: 5px;
            transition: all .2s ease-in-out;

            box-shadow: 0 0 0px rgb(0, 0, 0);
            visibility: hidden;
            transform: translate(-25px,16px);
        }
        .checkhide:hover{
            background-color: red;
            transition: background-color .3s;
        }
        #checkhide:checked ~ .diagram_title .checkhide{
            background-color: grey;
        }
        .close_info::before, .close_info::after {
            content: '';
            position: absolute;
            right: 50%;
            width: 100%;
            height: 1px;
            background-color: #000;
            border-radius: 5px;
            transition: all .5s ease-in-out;
        }
        .close_info::before{
            visibility: visible; /* 顯示偽元素 */
            opacity: 1;
            transform: rotate(45deg)
            translateX(32px)
            translateY(-32px);
        }
        .close_info::after{
            visibility: visible; /* 顯示偽元素 */
            transform: rotate(-45deg)
            translateX(32px)
            translateY(32px);
            visibility: visible; /* 顯示偽元素 */
        }
        /* 漢堡選單to打叉動畫 */
        #checkhide:checked ~ .diagram_title .close_info{
            transform: translate(50%,16px);
            visibility: visible; /* 顯示偽元素 */

        }
        #checkhide:checked ~ .diagram_title .close_info::before{ /*右上斜左下線*/
            transform: translate(50%,-6px);
            visibility: visible; /* 顯示偽元素 */
            transition: all .5s ease-in-out;

        }
        #checkhide:checked ~ .diagram_title .close_info::after{ /*左上斜右下線*/
            transform: translate(50%,6px);
            visibility: visible; /* 顯示偽元素 */
            transition: all .5s ease-in-out;
        }
    </style>
    <style>
        .choose_css{
            background-color: #3c3c3c !important;
        }
        .color_FFF{
            color:white !important;
        }

        .bck_orange{
            background-color: #ffd180;
        }
        .inputdivGP{
            height: 200px;
            overflow-y: auto;
            overflow-x:hidden;
        }
        #designinput button{
            height: 100%;
            margin: 0 5px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        #addinputGP{
            padding: 5px 30px;
            background-color: #28a745;
        }
        #submit{
            padding: 5px 30px;
            background-color: #007bff;
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
            outline: black 3px solid;
            overflow: auto;
            position: relative;
            background-color: #f4ecdc;
            resize: both;
        }
        .draggable{
            width: 50px;
            height: 50px;
            top:10px;
            left:10px;
            opacity: 0.9;
            background-color: #e0e0e0;
            position: absolute;
            cursor: grab;
            user-select: none;
            outline: 2px solid #000000;
        }


        /* resize */
        .resizable-handle {
            width: 10px;
            height: 10px;
            background-color: #000;
            position: absolute;
            bottom: 0 !important;
            right: 0 !important;
            cursor: nwse-resize;
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
<script>
    can_ctrlV=false;
    copy_element= {};

    $(document).ready(function() {
        $('form input').off();
        $(document).off();

        $('form input').on('keypress', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault(); // 防止Enter鍵
            }
        });
        /**************************************/
        /*-------     Document事件     -------*/
        /**************************************/
        /* 點擊事件 */
        $(document).on('click', function(e) {

            //Focus其他inputGP和拖動區塊取消focus和初始選擇元素
            if ($(e.target).closest('.designblock').length > 0 ||
                ($(e.target).closest('.inputdivGP').length > 0 &&
                !($(e.target).closest('.inputdivGP input').length > 0))) {
                //點設計區塊 或是 表單空白處 ->可以貼上
                can_ctrlV=true;
                console.log("點設計區塊 或是 表單空白處");
            } else if (($(e.target).closest('.diagram_info').length > 0) ||
                ($(e.target).closest('.inputGP').length > 0) ||
                ($(e.target).closest('#addinputGP').length > 0)) {
                //點輔助框input 或表單的input 或新增不要被刷掉
                can_ctrlV=false;
                console.log("點輔助框input");
            }else{
                can_ctrlV=false;
                focus_element(null,select_element);
                //改變輔助框inpu
                setdiagram_info("","","","","","");

                console.log("未定義空白處");
            }
        });
        /* 鍵盤事件 */
        $(document).on('keydown', function(event) {
            // 刪除元素
            var keyPressed = event.key || event.which;
            if (keyPressed === 'Delete' || keyPressed === 'del' || keyPressed === 46) {
                if(select_element!=null){
                    var tindex=select_element.index()
                    $('#designinput .inputGP')[tindex].remove();
                    $('.designblock .draggable')[tindex].remove();
                    focus_element(null,select_element);
                    select_element=null;
                    //改變輔助框inpu
                    setdiagram_info("","","","","","");
                }
            }

            // Ctrl事件  處理CtrlC複製和CtrlV貼上
            if (event.ctrlKey) {
                // 按下 Ctrl+C (Copy) 複製指定格式
                if ((event.key === 'c' || event.keyCode === 67) && select_element!=null) {
                    copy_element['top']=parseInt($(select_element).css('top'),10);
                    copy_element['left']=parseInt($(select_element).css('left'),10);
                    copy_element['height']=parseInt($(select_element).css('height'),10);
                    copy_element['width']=parseInt($(select_element).css('width'),10);
                }
                // 按下 Ctrl+V (Paste)
                else if (event.key === 'v' || event.keyCode === 86) {
                    // 按下 Ctrl+V (Paste) 貼上(新增)指定格式資料
                    if(Object.keys(copy_element).length !== 0 && can_ctrlV){
                        addelement("lib_id?","",copy_element['top']+10,copy_element['left']+10,
                            copy_element['height'],copy_element['width']);
                        copy_element['top']+=10;
                        copy_element['left']+=10;
                    }
                }
            }
        });
        $(document).on('mouseup', function() {
            ismove = false; //移動元素
            move_currentElement = null;

            isResizing = false; //改變元素大小
        });

        $(document).on('mousemove', function(e) {
            /* link:move1 */
            if(!isResizing && ismove){
                throttle(() => drag(e), 10)();
            }
            /* link:resize1 */
            if (isResizing){
                const width = $(resize_box).width() - (move_prevX - e.pageX);
                const height = $(resize_box).height() - (move_prevY - e.pageY);

                $(resize_box).css('width', width+'px');
                $(resize_box).css('height', height+'px');

                const index = resize_box.index();
                $($('#designinput input[name="width[]"]')[index]).val(width);  //改變input
                $($('#designinput input[name="height[]"]')[index]).val(height); //改變input
                $('.diagram_info input[name="width"]').val(width);  //改變輔助框input
                $('.diagram_info input[name="height"]').val(height);  //改變輔助框input

                move_prevX = e.pageX;
                move_prevY = e.pageY;
            }
        });
        /**************************************/
        /*-------       移動程式碼       -------*/
        /**************************************/
        let ismove = false; //元素是否有移動
        let mouse_init_X; //滑鼠初始值X
        let mouse_init_Y; //滑鼠初始值Y
        let element_initX; //元素起始值X
        let element_initY; //元素起始值Y
        let select_element=null; //上一個改變z-index的元素
        // link: move1
        /* 刷新移動所需 */
        function flash_move(){
            $('.draggable').off();
            $('.resizable-handle').off();
            $('.destoryinputGP').off();

            $('.destoryinputGP').on('click', function() { //手點刪除按鈕
                var tindex = $('#designinput .destoryinputGP').index(this);
                $('#designinput .inputGP')[tindex].remove();
                $('.designblock .draggable')[tindex].remove();

                //改變輔助框inpu
                setdiagram_info("","","","","","");

            });
            //點書櫃區塊
            $('.draggable').on('mousedown',function (e){
                mouse_init_X = e.pageX; //整個網頁x座標
                mouse_init_Y = e.pageY; //整個網頁y座標

                element_initX=parseInt($(this).css('left'), 10);
                element_initY=parseInt($(this).css('top'), 10);
                ismove = true;

                move_currentElement = $(this); //抓取目前目標
                var t_index=$(this).index();
                var input_name=$($('#designinput input[name="name[]"]')[t_index]).val();
                var input_note=$($('#designinput input[name="note[]"]')[t_index]).val();
                var input_top=parseInt($(this).css('top'), 10);
                var input_left=parseInt($(this).css('left'), 10);
                var input_height=parseInt($(this).css('height'), 10);
                var input_width=parseInt($(this).css('width'), 10);

                //改變輔助框inpu
                setdiagram_info(input_name,input_note,input_top,input_left,input_height,input_width);

                focus_element(this,select_element); //
                select_element= $(this);
            });
            $('.resizable-handle').on('mousedown',function (e){
                isResizing = true;
                move_prevX = e.pageX;
                move_prevY = e.pageY;
                resize_box = $(this).closest('.draggable');
            });
        }

        /* 移動 */
        function drag(e) {
            if (ismove && move_currentElement) {
                e.preventDefault();
                currentX=element_initX+ (e.pageX - mouse_init_X); //left值=原始元素值X+滑鼠移動差X
                currentY=element_initY+ (e.pageY - mouse_init_Y); //left值=原始元素值Y+滑鼠移動差Y
                element_width=parseInt($(move_currentElement).css('width'), 10); //元素的寬度width
                element_height=parseInt($(move_currentElement).css('height'), 10); //元素的寬度width
                // 確認不會超出左右邊界
                 if (currentX < 0) {
                     currentX = 0;
                 } else if (currentX+element_width > parseInt($('.designblock').css('width'), 10)) {
                     currentX = parseInt($('.designblock').css('width'), 10)- element_width;
                 }
                 // 確認不會超出上下邊界
                 if (currentY < 0) {
                     currentY = 0;
                 } else if (currentY+element_height > parseInt($('.designblock').css('height'), 10)) {
                     currentY = parseInt($('.designblock').css('height'), 10)- element_height;
                 }
                setTranslate(currentX, currentY, move_currentElement);
            }
        }

        /* 改變+更新移動座標 */
        function setTranslate(xPos, yPos, el) {
             $(el).css('left',xPos+'px')
             $(el).css('top',yPos+'px')
             const index = move_currentElement.index();
             $($('#designinput input[name="left[]"]')[index]).val(xPos); //改變input
             $($('#designinput input[name="top[]"]')[index]).val(yPos); //改變input
             $('.diagram_info input[name="left"]').val(xPos);  //改變輔助框input
             $('.diagram_info input[name="top"]').val(yPos);  //改變輔助框input
         }

        /* 節流工作 */
        function throttle(func, limit) {
            let inThrottle;
            return function () {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => (inThrottle = false), limit);
                }
            };
        }
        /**************** END. ****************/

        /**************************************/
        /*-------     改變size程式碼     -------*/
        /**************************************/
        let isResizing = false;  //元素是否要改變大小resize
        let move_prevX; //上一個改變大小滑鼠X值
        let move_prevY; //上一個改變大小滑鼠Y值
        let resize_box; //要改變大小的元素
        // link: resize1
        /**************** END. ****************/


        /**************************************/
        /*-------        功能函式        -------*/
        /**************************************/
        /* 新增可拉動和表單input Group元素 */
        //link:addele1
        function addelement(name,note,top,left,height,width){
            var newField = $(
                '<div class="draggable" style="width:'+width+'px;height: '+height+'px;top:'+top+'px;left:'+left+'px;">'+
                '<div class="text">'+name+'<\/div>'+
                '   <div class="resizable-handle"><\/div>'+
                '<\/div>'
            );
            // $('.designblock').append(newField); // 加入到表單中
            $('.designblock').prepend(newField); // 加入到表單中

            newField = $(
                '<div class="row inputGP bck_orange">'+
                '    <div class="col-1" style="display: none"><span><input style="display: none" type="text" name="id[]" value="-1"><\/span><\/div>'+
                '    <div class="col-3"><input type="text" name="name[]" maxlength="20"  placeholder="書櫃碼" value="'+name+'"> <\/div>'+
                '    <div class="col-3"><input type="text" name="note[]" maxlength="30" placeholder="備註"> <\/div>'+
                '    <div class="col-1"><input type="number" name="top[]" placeholder="Top" value="'+top+'"> <\/div>'+
                '    <div class="col-1"><input type="number" name="left[]" placeholder="Left" value="'+left+'"> <\/div>'+
                '    <div class="col-1"><input type="number" name="height[]" placeholder="Height" value="'+height+'"> <\/div>'+
                '    <div class="col-1"><input type="number" name="width[]" placeholder="width" value="'+width+'"> <\/div>'+
                '    <div class="col-2"><button type="button" class="destoryinputGP">刪除<\/button><\/div>'+
                '<\/div>'
            );
            // $('.inputdivGP').append(newField);
            $('.inputdivGP').prepend(newField);

            //改變輔助框inpu
            setdiagram_info(name,note,top,left,height,width);


            focus_element($('.designblock .draggable').first(),select_element)
            select_element= $('.designblock .draggable').first();
            flash_move();
            flash_inputHanld();
        }

        /* 改變焦點的背景顏色等 */
        function focus_element(fuc_add_ele,fuc_remove_ele){ //焦點元素(書櫃區塊),取消焦點元素(書櫃區塊)
            if(fuc_remove_ele!=null){ //上焦點
                var temp_i = $(fuc_remove_ele).index();
                $(fuc_remove_ele).css('z-index','auto');
                $(fuc_remove_ele).removeClass("choose_css");
                $(fuc_remove_ele).removeClass("color_FFF");
                $($('#designinput .inputGP')[temp_i]).removeClass("choose_css");
            }
            if(fuc_add_ele!=null) { //取消焦點
                var temp_i = $(fuc_add_ele).index();
                $(fuc_add_ele).css('z-index','999');
                $(fuc_add_ele).addClass("choose_css");
                $(fuc_add_ele).addClass("color_FFF");
                $($('#designinput .inputGP')[temp_i]).addClass("choose_css");
            }
        }


        /* 更新input事件 */
        function flash_inputHanld(){
            $('input[name="name[]"]').off();
            $('input[name="top[]"]').off();
            $('input[name="left[]"]').off();
            $('input[name="height[]"]').off();
            $('input[name="width[]"]').off();

            $('input[name="name[]"]').on('input', function() {
                const userInput = $(this).val();
                var index=$('#designinput .inputdivGP input[name="name[]').index(this);
                $($('.designblock .draggable')[index]).find('.text').text(userInput)
                $('.diagram_info input[name="name"]').val(userInput);  //改變輔助框input
            });
            $('input[name="note[]"]').on('input', function() {
                const userInput = $(this).val();
                $('.diagram_info input[name="note"]').val(userInput);  //改變輔助框input
            });
            $('input[name="top[]"]').on('input', function() {
                const userInput = $(this).val();
                var index=$('#designinput .inputdivGP input[name="top[]').index(this);
                $($('.designblock .draggable')[index]).css('top',userInput+'px');
                $('.diagram_info input[name="top"]').val(userInput);  //改變輔助框input
            });
            $('input[name="left[]"]').on('input', function() {
                const userInput = $(this).val();
                var index=$('#designinput .inputdivGP input[name="left[]').index(this);
                $($('.designblock .draggable')[index]).css('left',userInput+'px');
                $('.diagram_info input[name="left"]').val(userInput);  //改變輔助框input
            });
            $('input[name="height[]"]').on('input', function() {
                const userInput = $(this).val();
                var index=$('#designinput .inputdivGP input[name="height[]').index(this);
                $($('.designblock .draggable')[index]).css('height',userInput+'px');
                $('.diagram_info input[name="height"]').val(userInput);  //改變輔助框input
            });
            $('input[name="width[]"]').on('input', function() {
                const userInput = $(this).val();
                var index=$('#designinput .inputdivGP input[name="width[]').index(this);
                $($('.designblock .draggable')[index]).css('width',userInput+'px');
                $('.diagram_info input[name="width"]').val(userInput);  //改變輔助框input
            });
            //畫布長寬設定
            $('#designsize input[name="designsize_H"]').on('input',function (){
                const userInput = $(this).val();
                if(userInput>=3000){
                    this.value = 3000;
                }
                if(userInput<=1){
                    this.value = 1;
                }
                $('.designblock').css('height',userInput)
            });

            $('#designsize input[name="designsize_W"]').on('input',function (){
                const userInput = $(this).val();
                if(userInput>=3000){
                    this.value = 3000;
                }
                if(userInput<=1){
                    this.value = 1;
                }
                $('.designblock').css('width',userInput)
            });
            //可以透過表單input Focus到設計圖
            $('.inputdivGP .inputGP').click(function (e){
                if($(e.target).closest('.destoryinputGP').length > 0){
                    return;
                }
                var t_index=$(this).index();
                var input_name=$($('#designinput input[name="name[]"]')[t_index]).val();
                var input_note=$($('#designinput input[name="note[]"]')[t_index]).val();
                var input_top=$($(this).find('input[name="top[]"]')).val();
                var input_left=$($(this).find('input[name="left[]"]')).val();
                var input_height=$($(this).find('input[name="height[]"]')).val();
                var input_width=$($(this).find('input[name="width[]"]')).val();
                //改變輔助框input
                setdiagram_info(input_name,input_note,input_top,input_left,input_height,input_width);

                temp_element=$('.designblock .draggable')[$(this).index()];
                focus_element(temp_element,select_element); //

                select_element= $(temp_element);

            });
        }
        //改變輔助區塊input內容
        function setdiagram_info(name,note,top,left,height,width){
            $('.diagram_info input[name="name"]').val(name);  //改變輔助框input
            $('.diagram_info input[name="note"]').val(note);  //改變輔助框input
            $('.diagram_info input[name="top"]').val(top);  //改變輔助框input
            $('.diagram_info input[name="left"]').val(left);  //改變輔助框input
            $('.diagram_info input[name="height"]').val(height);  //改變輔助框input
            $('.diagram_info input[name="width"]').val(width);  //改變輔助框input

        }
        //輔助區塊input編輯連動
        function diagram_block(){
            $('.diagram_info input[name="name"]').on('input', function() {
                const userInput = $(this).val();
                if(select_element!=null){
                    var t_index=$(select_element).index();
                    $(select_element).find('.text').text(userInput);
                    $($('.inputdivGP input[name="name[]"]')[t_index]).val(userInput);
                }
            });
            $('.diagram_info input[name="note"]').on('input', function() {
                const userInput = $(this).val();
                if(select_element!=null){
                    var t_index=$(select_element).index();
                    $($('.inputdivGP input[name="note[]"]')[t_index]).val(userInput);
                }
            });
            $('.diagram_info input[name="top"]').on('input', function() {
                const userInput = $(this).val();
                if(select_element!=null){
                    var t_index=$(select_element).index();
                    $($('.inputdivGP input[name="top[]"]')[t_index]).val(userInput);
                    $(select_element).css('top',userInput+'px');
                }
            });
            $('.diagram_info input[name="left"]').on('input', function() {
                const userInput = $(this).val();
                if(select_element!=null){
                    var t_index=$(select_element).index();
                    $($('.inputdivGP input[name="left[]"]')[t_index]).val(userInput);
                    $(select_element).css('left',userInput+'px');
                }
            });
            $('.diagram_info input[name="height"]').on('input', function() {
                const userInput = $(this).val();
                if(select_element!=null){
                    var t_index=$(select_element).index();
                    $($('.inputdivGP input[name="height[]"]')[t_index]).val(userInput);
                    $(select_element).css('height',userInput+'px');
                }
            });
            $('.diagram_info input[name="width"]').on('input', function() {
                const userInput = $(this).val();
                if(select_element!=null) {
                    var t_index = $(select_element).index();
                    $($('.inputdivGP input[name="width[]"]')[t_index]).val(userInput);
                    $(select_element).css('width', userInput + 'px');
                }
            });
        }
        /* 改變焦點的背景顏色等 */
        $('#addinputGP').click(function (){
            addelement("lib_id?","",10,10,50,50); //link:addele1
        });
        flash_move();
        flash_inputHanld();
        diagram_block();
});



</script>
