<div class="container-fluid">
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
                    <div class="col-1">ID</div>
                    <div class="col-2">書櫃碼</div>
                    <div class="col-2">書櫃備註</div>
                    <div class="col-1">Top</div>
                    <div class="col-1">Left</div>
                    <div class="col-1">Height</div>
                    <div class="col-1">Width</div>
                    <div class="col-2">刪除</div>
                </div>
                <div class="inputdivGP">
                    @foreach($floormaps as $floormap)
                        <div class="row inputGP">
                            <div class="col-1" ><span><input style="display: none" type="text" name="id[]" value="{{$floormap->id}}"></span></div>
                            <div class="col-2"><input type="text" name="name[]" placeholder="書櫃碼" value="{{$floormap->bookcaseName}}"> </div>
                            <div class="col-2"><input type="text" name="note[]" placeholder="備註" value="{{$floormap->bookcaseNote}}"> </div>
                            <div class="col-1"><input type="text" name="top[]" placeholder="Top" value="{{$floormap->top}}"> </div>
                            <div class="col-1"><input type="text" name="left[]" placeholder="Left" value="{{$floormap->left}}"> </div>
                            <div class="col-1"><input type="text" name="height[]" placeholder="Height" value="{{$floormap->height}}"> </div>
                            <div class="col-1"><input type="text" name="width[]" placeholder="Width" value="{{$floormap->width}}"> </div>
                            <div class="col-2"><button type="button" class="destoryinputGP">刪除</button></div>
                        </div>
                    @endforeach
    {{--                <div class="row inputGP">--}}
    {{--                    <div class="col-1 mx-1"><span><input style="display: none" type="text" name="id[]" value="-1"></span></div>--}}
    {{--                    <div class="col-2 mx-1"><input type="text" name="name[]" placeholder="書櫃碼1"> </div>--}}
    {{--                    <div class="col-2 mx-1"><input type="text" name="note[]" placeholder="備註"> </div>--}}
    {{--                    <div class="col-1 mx-1"><input type="text" name="top[]" placeholder="Top"> </div>--}}
    {{--                    <div class="col-1 mx-1"><input type="text" name="left[]" placeholder="Left"> </div>--}}
    {{--                    <div class="col-1 mx-1"><input type="text" name="height[]" placeholder="Height"> </div>--}}
    {{--                    <div class="col-1 mx-1"><input type="text" name="width[]" placeholder="Width"> </div>--}}
    {{--                </div>--}}
                </div>
            </form>
        </div>
        <div class="designblock mt-3" style="width: 1600px;height: 600px">
            @foreach($floormaps as $floormap)
                <div class="draggable" style=
                        "width:{{$floormap->width}}px;height: {{$floormap->height}}px;top:{{$floormap->top}}px;left:{{$floormap->left}}px;"
                >{{$floormap->bookcaseName}}
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
        .draggable {
            width: 50px;
            height: 50px;
            top:10px;
            left:10px;
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
    $(document).ready(function() {
        $(document).click(function (e){
            if ($(e.target).closest('.designblock').length > 0) {
                can_ctrlV=true;
            } else {
                can_ctrlV=false;
            }
        });
        $(document).on('keydown', function(event) { //文件鍵盤事件
            // 刪除元素
            var keyPressed = event.key || event.which;
            if (keyPressed === 'Delete' || keyPressed === 'del' || keyPressed === 46) {
                if(last_element!=null){
                    var tindex=last_element.index()
                    $('#designinput .inputGP')[tindex].remove();
                    $('.designblock .draggable')[tindex].remove();

                }
            }
            if (event.ctrlKey) {
                // 按下 Ctrl+C (Copy)
                if (event.key === 'c' || event.keyCode === 67) {
                    console.log('Ctrl+C 被按下');
                    // 在這裡處理 Ctrl+C 事件
                }
                // 按下 Ctrl+V (Paste)
                else if (event.key === 'v' || event.keyCode === 86) {
                    // 在這裡處理 Ctrl+V 事件
                    if(last_element!=null && can_ctrlV){
                        addelement("lib_id?","",parseInt($(last_element).css("top"),10)+10,parseInt($(last_element).css("left"),10)+10,
                            parseInt($(last_element).css("height"),10),parseInt($(last_element).css("width"),10));
                        console.log('Ctrl+C 被按下');
                    }
                }
            }
        });
        $(document).mouseup(function (){
            ismove = false; //移動元素
            move_currentElement = null;

            isResizing = false; //改變元素大小
        });

        $(document).mousemove(function (e){
            //移動元素
            if(!isResizing && ismove){
                throttle(() => drag(e), 10)();
            }

            //改變元素大小
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

        let ismove = false; //元素是否有移動
        let mouse_init_X; //滑鼠初始值X
        let mouse_init_Y; //滑鼠初始值Y
        let element_initX; //元素起始值X
        let element_initY; //元素起始值Y
        let last_element=null; //上一個改變z-index的元素
        function flash_move(){
            $('.draggable').off();
            $('.resizable-handle').off();
            $('.destoryinputGP').off();
            $('.destoryinputGP').click(function (){
                var tindex = $('#designinput .destoryinputGP').index(this);
                console.log(tindex);
                $('#designinput .inputGP')[tindex].remove();
                $('.designblock .draggable')[tindex].remove();
            });
            $('.draggable').mousedown(function (e){ //移動
                mouse_init_X = e.pageX; //整個網頁x座標
                mouse_init_Y = e.pageY; //整個網頁y座標
                // console.log("滑鼠起始值X(page)",mouse_init_X);
                // console.log("滑鼠起始值Y(page)",mouse_init_Y);
                element_initX=parseInt($(this).css('left'), 10);
                element_initY=parseInt($(this).css('top'), 10);
                // console.log("元素起始值X(left)",element_initX);
                // console.log("元素起始值Y(top)",element_initY);
                ismove = true;

                let index;
                if(last_element!=null){
                    index = last_element.index();
                    //$($('#designinput .inputGP')[index]).css('background-color','transparent'); //改變input
                    $(last_element).css('z-index','auto');
                    $($('#designinput .inputGP')[index]).removeClass("choose_css");

                    $(last_element).removeClass("choose_css"); //background-color','#4c4c4c')
                    $(last_element).removeClass("color_FFF"); //'color','#FFF')
                }
                move_currentElement = $(this); //抓取目前目標
                index = move_currentElement.index();

                $($('#designinput .inputGP')[index]).addClass("choose_css");
                //$($('#designinput .inputGP')[index]).css('background-color','#4c4c4c'); //改變input
                $(this).css('z-index','99');
                $(this).addClass("choose_css"); //background-color','#4c4c4c')
                $(this).addClass("color_FFF"); //'color','#FFF')


                last_element= $(this);
            });
            $('.resizable-handle').mousedown(function(e) {
                isResizing = true;
                move_prevX = e.pageX;
                move_prevY = e.pageY;
                resize_box = $(this).closest('.draggable');
            });
        }

        /*由document取代掉
        $('.draggable').mouseup(function (){
            ismove = false;
            move_currentElement = null;
        });*/
        /*由document取代掉
        $('.draggable').mousemove(function (e){
            throttle(() => drag(e), 10)();
        });*/

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

             function setTranslate(xPos, yPos, el) {
             $(el).css('left',xPos+'px')
             $(el).css('top',yPos+'px')

             const index = move_currentElement.index();
             $($('#designinput input[name="left[]"]')[index]).val(xPos); //改變input
             $($('#designinput input[name="top[]"]')[index]).val(yPos); //改變input

         }

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

        let isResizing = false;  //元素是否要改變大小resize
        let move_prevX; //上一個改變大小滑鼠X值
        let move_prevY; //上一個改變大小滑鼠Y值
        let resize_box; //要改變大小的元素

        /*由document取代掉
        $(document).mouseup(function() {
            isResizing = false;
        });*/
        function addelement(name,note,top,left,height,width){
            var newField = $(
                '<div class="draggable" style="width:'+width+'px;height: '+height+'px;top:'+top+'px;left:'+left+'px;">'+name+
                '   <div class="resizable-handle"><\/div>'+
                '<\/div>'
            );

            // $('.designblock').append(newField); // 加入到表單中
            $('.designblock').prepend(newField); // 加入到表單中

            newField = $(
                '<div class="row inputGP bck_orange">'+
                '    <div class="col-1"><span><input style="display: none" type="text" name="id[]" value="-1"><\/span><\/div>'+
                '    <div class="col-2"><input type="text" name="name[]" placeholder="書櫃碼1" value="'+name+'"> <\/div>'+
                '    <div class="col-2"><input type="text" name="note[]" placeholder="備註"> <\/div>'+
                '    <div class="col-1"><input type="text" name="top[]" placeholder="Top" value="'+top+'"> <\/div>'+
                '    <div class="col-1"><input type="text" name="left[]" placeholder="Left" value="'+left+'"> <\/div>'+
                '    <div class="col-1"><input type="text" name="height[]" placeholder="Height" value="'+height+'"> <\/div>'+
                '    <div class="col-1"><input type="text" name="width[]" placeholder="width" value="'+width+'"> <\/div>'+
                '    <div class="col-2"><button type="button" class="destoryinputGP">刪除<\/button><\/div>'+
                '<\/div>'
            );
            // $('.inputdivGP').append(newField);
            $('.inputdivGP').prepend(newField);

            if(last_element!=null){
                var tindex = last_element.index();
                console.log(tindex);
                $(last_element).css('z-index','auto');
                $($('#designinput .inputGP')[tindex]).removeClass("choose_css");

                $(last_element).removeClass("choose_css"); //background-color','#4c4c4c')
                $(last_element).removeClass("color_FFF"); //'color','#FFF')
            }

            // $('#designinput .inputGP').last().addClass("choose_css");
            $('#designinput .inputGP').first().addClass("choose_css");
            //$($('#designinput .inputGP')[index]).css('background-color','#4c4c4c'); //改變input
            // last_element= $('.designblock .draggable').last();
            last_element= $('.designblock .draggable').first();
            $(last_element).css('z-index','99');
            $(last_element).addClass("choose_css"); //background-color','#4c4c4c')
            $(last_element).addClass("color_FFF"); //'color','#FFF')

            flash_move();
        }
        //新增欄位按鈕
        $('#addinputGP').click(function (){
            addelement("lib_id?","",10,10,50,50);
        });
        flash_move();
});




</script>
