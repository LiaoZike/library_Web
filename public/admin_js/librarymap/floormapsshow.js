
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
        } else if (($(e.target).closest('.diagram_info').length > 0) ||
            ($(e.target).closest('.inputGP').length > 0) ||
            ($(e.target).closest('#addinputGP').length > 0)) {
            //點輔助框input 或表單的input 或新增不要被刷掉
            can_ctrlV=false;
        }else{
            can_ctrlV=false;
            focus_element(null,select_element);
            //改變輔助框inpu
            setdiagram_info("","","","","","");
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
                setdiagram_info("","","","","","","");
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
                copy_element['rotate']=parseInt($(select_element).css('rotate'),10);
            }
            // 按下 Ctrl+V (Paste)
            else if (event.key === 'v' || event.keyCode === 86) {
                // 按下 Ctrl+V (Paste) 貼上(新增)指定格式資料
                if(Object.keys(copy_element).length !== 0 && can_ctrlV){
                    addelement("lib_id?","",copy_element['top']+10,copy_element['left']+10,
                        copy_element['height'],copy_element['width'],copy_element['rotate']);
                    copy_element['top']+=10;
                    copy_element['left']+=10;
                }
            }
        }
    });
    $(document).on('click', function(event) {
        console.log('mouse_Y',event.pageY);
    });
    $(document).on('mouseup', function() {
        ismove = false; //移動元素
        move_currentElement = null;
        isdesignresize=false;
        isResizing = false; //改變元素大小
        isRoating=false;
    });

    $(document).on('mousemove', function(e) {
        /* link:move1 */
        if(!isResizing && !isRoating && ismove){
            throttle(() => drag(e), 10)();
        }
        if(isRoating){
            // var mouseX = e.pageX;
            // var mouseY  = e.pageY;
            const mouseX = e.pageX;
            const mouseY = e.pageY;
            //Roat_init_X=$(Roatingbtnele).offset().left;
            //Roat_init_Y=$(Roatingbtnele).offset().top;


            const deltaX = mouseX - Roate_lemCenterX;
            const deltaY = mouseY - Roat_elemCenterY;

            const radians = Math.atan2(deltaY, deltaX);
            var angle = radians * (180 / Math.PI)+90;
            angle=angle-angle%5

            const index = $(move_currentElement).index();
            $(move_currentElement).css('rotate',angle+'deg')
            $($(move_currentElement).find('.text')).css('rotate',-1*angle+'deg')
            $($('#designinput input[name="rotate[]"]')[index]).val(angle); //改變input
            $('.diagram_info input[name="rotate"]').val(angle);
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
        if(isdesignresize){
            $('#designsize input[name="designsize_H"]').val(parseInt($('.designblock').css('height'),10));
            $('#designsize input[name="designsize_W"]').val(parseInt($('.designblock').css('width'),10))
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
        $('.routebtn').off();

        $('.destoryinputGP').on('click', function() { //手點刪除按鈕
            var tindex = $('#designinput .destoryinputGP').index(this);
            $('#designinput .inputGP')[tindex].remove();
            $('.designblock .draggable')[tindex].remove();
            //改變輔助框inpu
            setdiagram_info("","","","","","","");

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
            var input_rotate=parseInt($(this).css('rotate'), 10);

            //改變輔助框inpu
            setdiagram_info(input_name,input_note,input_top,input_left,input_height,input_width,input_rotate);

            focus_element(this,select_element); //
            select_element= $(this);
            if(isRoating){
                var elewidth=parseInt($(move_currentElement).css('width'));
                var eleheight=parseInt($(move_currentElement).css('height'));
                var eleleft=parseInt($(move_currentElement).css('left'));
                var eletop=parseInt($(move_currentElement).css('top'))
                //設計區塊的網頁位置
                var eleoffsettop=parseInt($('.designblock').offset().top)
                var eleoffsetleft=parseInt($('.designblock').offset().left)
                Roate_lemCenterX = eleoffsetleft + eleleft+elewidth/2;
                Roat_elemCenterY =eleoffsettop + eletop+eleheight/2;

                /*
                var elewidth=parseInt($(move_currentElement).css('width'));
                var eleheight=parseInt($(move_currentElement).css('height'));
                var elseoffleft=parseInt($(move_currentElement).offset().left);
                var elseofftop=parseInt($(move_currentElement).offset().top);
                var elerotate=(parseInt($(move_currentElement).css('rotate'))); // /180*Math.PI

                elerotate=(elerotate+720)%360
                if(elerotate>=0 && elerotate<=180){ //0~180
                    elerotate=elerotate/180*Math.PI;
                    console.log("中心點X：",elseoffleft +  (Math.abs(elewidth*Math.cos(elerotate))+eleheight*Math.sin(elerotate))/2);
                    Roate_lemCenterX = elseoffleft +  (Math.abs(elewidth*Math.cos(elerotate))+eleheight*Math.sin(elerotate))/2;
                    Roat_elemCenterY = elseofftop + (Math.abs(elewidth*Math.sin(elerotate))+eleheight*Math.cos(elerotate))/2;
                }else if(elerotate>180 && elerotate<=360){ //-
                    elerotate=Math.abs(360-elerotate)
                    elerotate=elerotate/180*Math.PI;
                    Roate_lemCenterX = elseoffleft +  (Math.abs(elewidth*Math.sin(elerotate))+eleheight*Math.cos(elerotate))/2;
                    Roat_elemCenterY =elseofftop + (Math.abs(elewidth*Math.cos(elerotate))+eleheight*Math.sin(elerotate))/2;
                }*/

            }});
        $('.resizable-handle').on('mousedown',function (e){
            isResizing = true;
            move_prevX = e.pageX;
            move_prevY = e.pageY;
            resize_box = $(this).closest('.draggable');
        });
        $('.routebtn').on('mousedown',function (e){
            Roatingbtnele=this;
            isRoating=true;
            Roat_init_X = e.pageX;
            Roat_init_Y = e.pageY;

        })
    }

    /* 移動 */
    function drag(e) {
        if (ismove && move_currentElement) {
            e.preventDefault();
            currentX=element_initX+ (e.pageX - mouse_init_X); //left值=原始元素值X+滑鼠移動差X
            currentY=element_initY+ (e.pageY - mouse_init_Y); //left值=原始元素值Y+滑鼠移動差Y
            element_width=parseInt($(move_currentElement).css('width'), 10); //元素的寬度width
            element_height=parseInt($(move_currentElement).css('height'), 10); //元素的寬度width

            setTranslate(currentX, currentY, move_currentElement);
        }
    }

    /* 改變+更新移動座標 */
    function setTranslate(xPos, yPos, el) {
        $(el).css('left',xPos+'px')
        $(el).css('top',yPos+'px')
        sideleft=$('.designblock').offset().left;
        sidetop=$('.designblock').offset().top;
        sideright = $('.designblock').offset().left;
        sidebottom=$('.designblock').offset().top+parseInt($('.designblock').css('height'));

        if($(el).offset().left-sideleft<0){ //超出左邊界
            xPos=xPos+Math.ceil(Math.abs($(el).offset().left-sideleft));
        }
        //
        // if($(el).offset().left+parseInt($(el).css('width'),10)>sideright){ //超出右邊界
        //     console.log("超出")
        //     xPos=xPos-Math.floor(Math.abs(sideright-($(el).offset().left+parseInt($(el).css('width'),10))));
        // }
        if($(el).offset().top-sidetop<0){ //超出上邊界
            yPos=yPos+Math.ceil(Math.abs($(el).offset().top-sidetop));
        }
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
    /*-------       旋轉程式碼       -------*/
    /**************************************/
    let isRoating = false;
    let Roatingbtnele=null;
    let Roat_init_X=0
    let Roat_init_Y=0
    let Roate_lemCenterX = null;
    let Roat_elemCenterY = null;

    /**************** END. ****************/

    let isdesignresize = false;
    /**************************************/
    /*-------        功能函式        -------*/
    /**************************************/
    /* 新增可拉動和表單input Group元素 */
    //link:addele1
    function addelement(name,note,top,left,height,width,rotate){
        var newField = $(
            '<div class="draggable hidedraggable" style="rotate:'+rotate+'deg;width:'+width+'px;height: '+height+'px;top:'+top+'px;left:'+left+'px;">'+
            '   <div class="routebtn"><i class="fa fa-repeat" aria-hidden="true"><\/i><\/div>'+
            '   <div class="text">'+name+'<\/div>'+
            '   <div class="resizable-handle"><\/div>'+
            '<\/div>'
        );
        // $('.designblock').append(newField); // 加入到表單中
        $('.designblock').prepend(newField); // 加入到表單中

        newField = $(
            '<div class="row inputGP bck_orange">'+
            '    <div class="col-1" style="display: none" ><span><input style="display: none" type="text" name="id[]" value="-1"></span></div>'+
            '    <div class="col-12 col-sm-3"><input type="text" name="name[]" placeholder="書櫃碼" maxlength="20"  value="'+name+'"> <\/div>'+
            '    <div class="col-12 col-sm-2"><input type="text" name="note[]" placeholder="備註" maxlength="30"> </div>'+
            '    <div class="col-12 col-sm-1"><input type="number" name="top[]"  placeholder="Top" value="'+top+'"> <\/div>'+
            '    <div class="col-12 col-sm-1"><input type="number" name="left[]" placeholder="Left" value="'+left+'"> <\/div>'+
            '    <div class="col-12 col-sm-1"><input type="number" name="height[]" placeholder="Height" value="'+height+'"> <\/div>'+
            '    <div class="col-12 col-sm-1"><input type="number" name="width[]" placeholder="Width" value="'+width+'"> <\/div>'+
            '    <div class="col-12 col-sm-1"><input type="number" step="5" min="-360" max="360" name="rotate[]" placeholder="Rotate" value="0"> </div>'+
            '    <div class="col-12 col-sm-2"><button type="button" class="destoryinputGP">刪除</button></div>'+
            '</div>'
        );
        // $('.inputdivGP').append(newField);
        $('.inputdivGP').prepend(newField);

        //改變輔助框inpu
        setdiagram_info(name,note,top,left,height,width,rotate);


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
            $($(fuc_remove_ele).find('.routebtn')).css('display','none');
            $(fuc_remove_ele).addClass('hidedraggable');
        }
        if(fuc_add_ele!=null) { //取消焦點
            var temp_i = $(fuc_add_ele).index();
            $(fuc_add_ele).css('z-index','999');
            $(fuc_add_ele).addClass("choose_css");
            $(fuc_add_ele).addClass("color_FFF");
            $($('#designinput .inputGP')[temp_i]).addClass("choose_css");
            $($(fuc_add_ele).find('.routebtn')).css('display','block');
            $(fuc_add_ele).removeClass('hidedraggable');
        }
    }


    /* 更新input事件 */
    function flash_inputHanld(){
        $('input[name="name[]"]').off();
        $('input[name="top[]"]').off();
        $('input[name="left[]"]').off();
        $('input[name="height[]"]').off();
        $('input[name="width[]"]').off();
        $('input[name="rotate[]"]').off()

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
        $('input[name="rotate[]"]').on('input', function() {
            var userInput = $(this).val();
            if(userInput<=-360) userInput=-360;
            else if(userInput>=360) userInput=0;
            this.value = userInput;
            var index=$('#designinput .inputdivGP input[name="rotate[]').index(this);
            $($('.designblock .draggable')[index]).css('rotate',(userInput)+'deg');
            $($($('.designblock .draggable')[index]).find('.text')).css('rotate',-1*userInput+'deg')

            $('.diagram_info input[name="rotate"]').val(userInput);  //改變輔助框input
        });
        $('input[name="rotate[]"]').on('change', function() {
            var currentValue = parseInt($(this).val());
            // 增加 5
            if(currentValue<=-360) currentValue=-360;
            else if(currentValue>=360) currentValue=0;
            $(this).val(currentValue);
        });

        //畫布長寬設定
        $('#designsize input[name="designsize_H"]').on('input',function (){
            var userInput = $(this).val();
            if(userInput>=2000){
                this.value = 2000;
                userInput=2000;
            }
            if(userInput<=1){
                this.value = 1;
                userInput=1;
            }
            $('.designblock').css('height',userInput+'px')
        });

        $('#designsize input[name="designsize_W"]').on('input',function (){
            var userInput = $(this).val();
            if(userInput>=2000){
                this.value = 2000;
                userInput=2000;
            }
            if(userInput<=1){
                this.value = 1;
                userInput=1;
            }
            $('.designblock').css('width',userInput+'px')
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
            var input_rotate=$($(this).find('input[name="rotate[]"]')).val();
            //改變輔助框input
            setdiagram_info(input_name,input_note,input_top,input_left,input_height,input_width,input_rotate);

            temp_element=$('.designblock .draggable')[$(this).index()];
            focus_element(temp_element,select_element); //

            select_element= $(temp_element);

        });
    }
    //改變輔助區塊input內容
    function setdiagram_info(name,note,top,left,height,width,rotate){
        $('.diagram_info input[name="name"]').val(name);  //改變輔助框input
        $('.diagram_info input[name="note"]').val(note);  //改變輔助框input
        $('.diagram_info input[name="top"]').val(top);  //改變輔助框input
        $('.diagram_info input[name="left"]').val(left);  //改變輔助框input
        $('.diagram_info input[name="height"]').val(height);  //改變輔助框input
        $('.diagram_info input[name="width"]').val(width);  //改變輔助框input
        $('.diagram_info input[name="rotate"]').val(rotate);  //改變輔助框input

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
        $('.diagram_info input[name="rotate"]').on('input', function() {
            var userInput = $(this).val();
            if(select_element!=null) {
                var t_index = $(select_element).index();
                if(userInput<=-360) userInput=-360;
                else if(userInput>=360) userInput=0;
                this.value = userInput;

                $($('.inputdivGP input[name="rotate[]"]')[t_index]).val(userInput);
                $(select_element).css('rotate', userInput+'deg');
                $($(select_element).find('.text')).css('rotate',-1*userInput+'deg')



            }
        });
    }
    /* 改變焦點的背景顏色等 */
    $('#addinputGP').click(function (){
        addelement("lib_id?","",10,10,50,50,0); //link:addele1
    });
    $('.designblock').on('mousedown',function (e){
        isdesignresize = true;
    });
    flash_move();
    flash_inputHanld();
    diagram_block();
});


