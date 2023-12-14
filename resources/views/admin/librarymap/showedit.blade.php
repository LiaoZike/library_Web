<div class="container-fluid">
    <div class="diagram_info">
        <input type="checkbox" id="checkhide">
        <div class="diagram_title">
            <button class="hide_info"><label for="checkhide" class="checkhide"></label></button>
            <button class="close_info"></button>
        </div>
        <div class="diagram_content container-fluid">
            <div class="input-group">
                <div class="">
                    <span>Name:</span><input type="text" maxlength="20" placeholder="書櫃碼" value="null"/>
                </div>
                <div class="">
                    <span>Note:</span><input type="text" maxlength="30" placeholder="備註" value="null"/>
                </div>
                <div class="">
                    <span>Top:</span><input type="number" min="0" placeholder="Top" value=0>
                </div>
                <div class="">
                    <span>Left:</span><input type="number" min="0" placeholder="Left" value=0>
                </div>
                <div class="">
                    <span>Height:</span><input type="number" min="1" placeholder="Height" value=1>
                </div>
                <div class="">
                    <span>Width:</span><input type="number" min="1" placeholder="Width" value=1>
                </div>
            </div>
        </div>
    </div>
    <style>

        #checkhide:checked ~ .diagram_content{
            max-height: 0;
        }

        .diagram_info{
            position: fixed;
            right: 0px;
            top: 50px;
            background-color: #f8f8f8;
            z-index: 9999;
            box-shadow: 0px 0px 3px 1px #909090;
            width: 100px;
            border-radius: 5px;
        }
        .diagram_content{
            max-height: 400px; /* 调整展开的高度 */
            overflow: hidden;
            transition: max-height .2s;
        }
        .diagram_title{
            height: 30px;
            background-color: #fbe7e7;
            position: relative;
        }
        .hide_info{
            background-color: transparent;
            border: none;
            position: absolute;
            top:0px;
            right: 40px;
            width: 40px;
            height: 30px;
        }
        .hide_info:hover{
            background-color: #cacaca;
            transition: background-color .3s;
        }
        .hide_info:before{
            content: "";
            position: absolute;
            top:50%;
            left:50%;
            transform: translate(-50%,-50%) ;
            width: 50%;
            height: 2px;
            background-color: #000000;
        }
        .checkhide{ /* label */
            width: 100%;
            height: 100%;
            display: block;
            cursor: pointer;
        }
        #checkhide{
            display: none;
        }
        /* 關閉按鈕 */
        .close_info{
            background-color: transparent;
            border: none;
            position: absolute;
            top:0px;
            right: 0px;
            width: 40px;
            height: 30px;
        }
        .close_info:hover{
            background-color: red;
            transition: background-color .3s;
        }
        .close_info::before{
            content: "";
            position: absolute;
            top:50%;
            right:0%;
            transform: translate(-50%,-50%) rotate(45deg);
            width: 20px;
            height: 1px;
            background-color: black;
        }
        .close_info::after{
            position: absolute;
            top:50%;
            right:0%;
            content: "";
            width: 20px;
            height: 1px;
            transform: translate(-50%,-50%) rotate(-45deg);
            background-color: black;
        }
        .close_info:hover::before{
            background-color: white;
            transition: background-color .3s;
        }
        .close_info:hover::after{
            background-color: white;
            transition: background-color .3s;
        }
    </style>
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
                <button id="addinputGP" type="button">+ 新增</button>
                <button id="submit" type="submit">v 儲存</button>
                <div class="row mt-1 mx-1">
                    設定平面室內長寬度
                </div>
                <div class="row  mx-1">
                    <input type="number" value="800" placeholder="長度(px)">
                    <input type="number" value="600" placeholder="寬度(px)">
                </div>
                <div class="row title pt-1 mt-3 mx-1">
{{--                    <div class="col-1">ID</div>--}}
                    <div class="col-3">書櫃碼</div>
                    <div class="col-3">書櫃備註</div>
                    <div class="col-1">Top</div>
                    <div class="col-1">Left</div>
                    <div class="col-1">Height</div>
                    <div class="col-1">Width</div>
                    <div class="col-2">刪除</div>
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
{{--                    <div class="row inputGP">--}}
{{--                        <div class="col-1" ><span><input style="display: none" type="text" name="id[]" value="{{$floormap->id}}"></span></div>--}}
{{--                        <div class="col-2"><input type="text" name="name[]" placeholder="書櫃碼" maxlength="20" value="{{$floormap->bookcaseName}}"> </div>--}}
{{--                        <div class="col-2"><input type="text" name="note[]" placeholder="備註" maxlength="30" value="{{$floormap->bookcaseNote}}"> </div>--}}
{{--                        <div class="col-1"><input type="number" name="top[]" placeholder="Top" value="{{$floormap->top}}"> </div>--}}
{{--                        <div class="col-1"><input type="number" name="left[]" placeholder="Left" value="{{$floormap->left}}"> </div>--}}
{{--                        <div class="col-1"><input type="number" name="height[]" placeholder="Height" value="{{$floormap->height}}"> </div>--}}
{{--                        <div class="col-1"><input type="number" name="width[]" placeholder="Width" value="{{$floormap->width}}"> </div>--}}
{{--                        <div class="col-2"><button type="button" class="destoryinputGP">刪除</button></div>--}}
{{--                    </div>--}}
                </div>
            </form>
        </div>
        <div class="designblock mt-3" style="width: 1600px;height: 600px">
            @foreach($floormaps as $floormap)
                <div class="draggable" style=
                        "width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;"
                >
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
                !$(e.target).closest('input').length > 0)) {
                can_ctrlV=true; //指定區域可以貼上
            } else if (!($(e.target).closest('#addinputGP').length > 0)){ //點擊其他位置
                focus_element(null,select_element);
                select_element=null;
                can_ctrlV=false;

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
                    select_element=null;
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

            // $('.destoryinputGP').click(function (){
            $(document).on('click', '.destoryinputGP', function() {

                var tindex = $('#designinput .destoryinputGP').index(this);
                $('#designinput .inputGP')[tindex].remove();
                $('.designblock .draggable')[tindex].remove();
            });
            $(document).on('mousedown', '.draggable', function(e) {
                mouse_init_X = e.pageX; //整個網頁x座標
                mouse_init_Y = e.pageY; //整個網頁y座標

                element_initX=parseInt($(this).css('left'), 10);
                element_initY=parseInt($(this).css('top'), 10);
                ismove = true;

                move_currentElement = $(this); //抓取目前目標

                focus_element(this,select_element); //

                select_element= $(this);
            });
            $(document).on('mousedown', '.resizable-handle', function(e) {
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
            });
            $('input[name="top[]"]').on('input', function() {
                const userInput = $(this).val();
                console.log(userInput);
                var index=$('#designinput .inputdivGP input[name="top[]').index(this);
                $($('.designblock .draggable')[index]).css('top',userInput+'px');
            });
            $('input[name="left[]"]').on('input', function() {
                const userInput = $(this).val();
                console.log(userInput);
                var index=$('#designinput .inputdivGP input[name="left[]').index(this);
                $($('.designblock .draggable')[index]).css('left',userInput+'px');
            });
            $('input[name="height[]"]').on('input', function() {
                const userInput = $(this).val();
                console.log(userInput);
                var index=$('#designinput .inputdivGP input[name="height[]').index(this);
                $($('.designblock .draggable')[index]).css('height',userInput+'px');
            });
            $('input[name="width[]"]').on('input', function() {
                const userInput = $(this).val();
                console.log(userInput);
                var index=$('#designinput .inputdivGP input[name="width[]').index(this);
                $($('.designblock .draggable')[index]).css('width',userInput+'px');
            });


            $('.inputdivGP .inputGP').click(function (){
                temp_element=$('.designblock .draggable')[$(this).index()];
                focus_element(temp_element,select_element); //

                select_element= temp_element;

            });
        }
        /* 改變焦點的背景顏色等 */
        $('#addinputGP').click(function (){
            addelement("lib_id?","",10,10,50,50); //link:addele1
        });
        flash_move();
        flash_inputHanld();
});



</script>
