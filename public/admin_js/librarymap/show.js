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
    $('#sortable-list').sortable({
        axis: 'y',
        cursor: 'grabbing',
        update: function (event, ui) {
            updateOrder(); // 移除後更新所有欄位的順序值

        }
    });
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
            row.insertBefore(row.prev());
            updateOrder(); // 移除後更新所有欄位的順序值
        });

        // 下移按鈕點擊事件
        $('.move-down').on('click',function() {
            var row = $(this).closest('.flooritem');
            row.insertAfter(row.next());
            updateOrder(); // 移除後更新所有欄位的順序值
        });
    }
    $('#modalBg').on('click',function(event) {
        if (event.target === this) {
            document.getElementById("modalBg").style.display = "none";
            document.getElementById("customConfirm").style.display = "none";
        }
    });
});
