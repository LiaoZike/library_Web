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
    sortableList=document.getElementById("sortable-list");
    new Sortable(sortableList, {
        animation: 200, // 動畫時間，以毫秒為單位
        ghostClass: 'bck_ffd180',
        onEnd: function (/**Event*/ evt) {
            updateOrder(); // 移除後更新所有欄位的順序值
        }
    });
    //
    // $('#sortable-list').sortable({
    //
    //     handle: '.handle',
    //     invertSwap: true,
    //     // axis: 'y',
    //     // cursor: 'grabbing',
    //     // update: function (event, ui) {
    //     //     updateOrder(); // 移除後更新所有欄位的順序值
    //     //
    //     // }
    // });
    // $('#sortable-list').disableSelection();

    flash_move();
    $('#modalBg').off();
    $('#floorFields').off();
    $('#addFloor').off();
    // 更新所有欄位的順序值
    function updateOrder() {
        var totalItems = $('#floorFields .flooritem').length;
        $('#floorFields .flooritem').each(function(index) {
            var newIndex = totalItems - index;
            $(this).find('input[name="ord[]"]').val(newIndex);
            $(this).css('transform', 'translateY(0px)');
        });
    }

    // 新增欄位按鈕點擊事件
    $('#addFloor').on('click',function() {
        // 新增一組輸入欄位
        var newField = $(

            '<div class="row flooritem bck_orange">'+
            '    <div style="display:none"><input type="text" name="id[]" value="-1" min="1" readonly tabindex="-1"><\/div>'+
            '    <div class="col-12 col-sm-1 readonly"><input type="text" name="ord[]" min="1" readonly tabindex="-1"><\/div>'+
            '    <div class="col-12 col-sm-3"><input type="text" name="name[]"  placeholder="樓層"><\/div>'+
            '    <div class="col-12 col-sm-3"><input type="text" name="note[]" placeholder="備註"><\/div>'+
            '    <div class="col-12 col-sm-1">0<\/div>'+
            '    <div class="col-12 col-sm-2"><button type="button" class="remove-floor">刪除<\/button><\/div>'+
            '    <!-- 上移按鈕  -->'+
            '    <div class="col-12 col-sm-1">'+
            '        <button type="button" class="move-up">↑<\/button>'+
            '    <\/div>'+
            '    <!-- 下移按鈕  -->'+
            '    <div class="col-12 col-sm-1">'+
            '        <button type="button" class="move-down">↓<\/button>'+
            '    <\/div>'+
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
        $('.move-up').on('click',function() {
            var row = $(this).closest('.flooritem');
            var position = row.index(); // 獲取此元素在 sortable-list 中的索引位置
            var prevrow=row.prev('.flooritem');
            if(position>0) {
                var moveheight = parseInt($(row).css('height'), 10)
                $(row).css('transform','translateY('+-1*moveheight+'px)');

                if (prevrow.length) {
                    var moveheight2 = parseInt($(prevrow).css('height'), 10)
                    $(prevrow).css('transform','translateY('+moveheight2+'px)');
                }
                setTimeout(function () {
                    $('.flooritem').css('transition', 'none'); // 取消過渡效果
                    $(row).css('transform', 'translateY(0px)');
                    if (prevrow.length) {
                        $(prevrow).css('transform', 'translateY(0px)');
                    }
                    row.insertBefore(row.prev());
                    updateOrder(); // 移除後更新所有欄位的順序值
                    $('.flooritem').css('transition', 'transform .5s'); // 取消過渡效果

                }, 500)
            }
        });

        // 下移按鈕點擊事件
        $('.move-down').on('click',function() {
            var floorItems = document.querySelectorAll('.flooritems .flooritem');
            var floorItemsSize = floorItems.length;

            var row = $(this).closest('.flooritem');
            var position = row.index();
            var nextrow=row.next('.flooritem');
            if(position<floorItemsSize-1){
                var moveheight=parseInt($(row).css('height'),10)
                $(row).css('transform','translateY('+moveheight+'px)');


                if (nextrow.length) {
                    var moveheight2 = parseInt($(nextrow).css('height'), 10)
                    $(nextrow).css('transform','translateY('+-1*moveheight2+'px)');
                }
                setTimeout(function () {
                    $('.flooritem').css('transition', 'none'); // 取消過渡效果
                    $(row).css('transform', 'translateY(0px)');
                    if (nextrow.length) {
                        $(nextrow).css('transform', 'translateY(0px)');
                    }
                    row.insertAfter(row.next());
                    updateOrder(); // 移除後更新所有欄位的順序值
                    $('.flooritem').css('transition', 'transform .5s'); // 取消過渡效果

                }, 500)


            }

        });
    }
    $('#modalBg').on('click',function(event) {
        if (event.target === this) {
            document.getElementById("modalBg").style.display = "none";
            document.getElementById("customConfirm").style.display = "none";
        }
    });
});
