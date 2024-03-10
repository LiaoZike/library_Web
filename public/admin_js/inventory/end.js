jQuery(document).ready(function ($) {

    var options = {
        $FillMode: 2,
        $AutoPlay: 0, // Set to 0 to disable auto-play
        $Idle: 4000,
        $PauseOnHover: 1,
        $ArrowKeyNavigation: 1,
        $SlideEasing: $Jease$.$OutQuint,
        $SlideDuration: 800,
        $MinDragOffsetToSlide: 20,
        $SlideSpacing: 0,
        $UISearchMode: 1,
        $PlayOrientation: 1,
        $DragOrientation: 1,

        $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$,
            $ChanceToShow: 2,
            $SpacingX: 8,
            $Orientation: 1
        },

        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$,
            $ChanceToShow: 2
        }
    };

    var jssor_slider1 = new $JssorSlider$("slider1_container", options);

    // Responsive code begin
    // You can remove responsive code if you don't want the slider to scale while window resizing
    function ScaleSlider() {
        var bodyWidth = document.body.clientWidth;
        if (bodyWidth)
            jssor_slider1.$ScaleWidth(Math.min(bodyWidth, 1920));
        else
            window.setTimeout(ScaleSlider, 30);
    }

    ScaleSlider();

    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    // Responsive code end





    $('.books_block').click(function (){
        var imgord=($($(this).find('.imageord')).text());
        if(imgord===""){
            $('.border').css('top','0');
            $('.border').css('left','0');
            $('.border').css('height','0');
            $('.border').css('width','0');
            return false;
        }
        var x1 =($($(this).find('.x1')).text());
        var x2 =($($(this).find('.x2')).text());
        var y1 =($($(this).find('.y1')).text());
        var y2 =($($(this).find('.y2')).text());
        temp=".image"+String(imgord)
        border_ele=$($(temp).find('.border'));

        $(border_ele).css('top',y1+'px');
        $(border_ele).css('left',parseInt(x1,10)+'px');
        $(border_ele).css('height',y2-y1+'px');
        $(border_ele).css('width',x2-x1+'px');
        jssor_slider1.$GoTo(imgord);
        // console.log(x1);
    })
    $('.jssora051').click(function (){
        $('.border').css('top','0');
        $('.border').css('left','0');
        $('.border').css('height','0');
        $('.border').css('width','0');
    })

});



<!-- 某一書本顯示/狀態處理JS -->
$(document).ready(function (){
    $(document).click(function (event){
        var clickedElement = event.target;
        if ($(clickedElement).closest('.books_block a').length > 0) {
            console.log("pass");
        }else if ($(clickedElement).closest('.diagram_info').length <= 0 &&
                    $(clickedElement).closest('.checkhide').length > 0) {
            $('#checkhide').prop('checked', false);
        }
    });
    $('.books_block a').click(function(event){
        event.preventDefault();
        // 取得連結位置
        var linkUrl = this.href;
        console.log("連結位置：" + linkUrl);
        if(linkUrl!==""){
            $('#contentFrame').attr('src', linkUrl);
            setTimeout(function (){$('#checkhide').prop('checked', true);},50)

        }
    })
});



