<link rel="stylesheet" href="{{asset('vendor\laravel-admin\font-awesome\css\font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
<div class="ifcontent container-fluid">
    @if(session('success'))
        <div id="success-message" class="success-message">
            <i class="fa fa-check" aria-hidden="true"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{route('admin.librarymap.bookcaseeditsave',$floormapid)}}" method="POST" id="numbersetform">
        @csrf()
        <div class="row">
            <input type="number" name="link_id" value="{{$floormapid}}" style="display: none"/>
            <div class="bookcaseName">書櫃名：{{$bookcaseName}}</div>
            <div class="bookcaseNote">備註：{{$bookcaseNote}}</div>
        </div>
        <div class="row mt-2" style="align-items: center;">
            <div class="col-12 col-md-5">
                <div class="col-12">
                    層數：<input name="severalrows" id="severalrows" style="width:calc(100% - 70px)" type="number" min="0" value="{{$severalrows}}"/>
                </div>
            </div>
            <div class="col-12 col-md-5">
                <div class="col-12">
                    行數：<input name="severalcols" id="severalcols"  style="width:calc(100% - 70px)"  type="number" min="0" value="{{$severalcols}}"/>
                </div>
            </div>


            <div class="col-12 col-md-2">
                <button class="submit" type="submit">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    確認並送出
                </button>
            </div>
        </div>

        <div id="FormBlock">
            @for($i=0;$i<$severalrows;$i++)
            <div class="BlockRow">
                @for($j=0;$j<$severalcols;$j++)
                    <div class="BlockCol">
                        <div class="NumberBlock">
                            <input type="number" name="id[]" value="{{ isset($BookCaseNos[$j * $severalrows + $i + 1]) ? $BookCaseNos[$j * $severalrows + $i + 1]->id : -1}}"  style="display: none"/>
                            <input style="width: 30%;display: none;" type="text" name="ord[]" value="{{$j*$severalrows+$i+1}}">
                            <div  class="showord" style="text-align: center">{{$j*$severalrows+$i+1}}</div>
                            <div class="StartNumber">
                                <label>起始編碼</label>
                                <input style="width: 95%;" value="{{isset($BookCaseNos[$j * $severalrows + $i + 1]) ? $BookCaseNos[$j * $severalrows + $i + 1]->startnum : ""}}" type="text" name="startnum[]" placeholder="xxx xxxx xxxx"/>
                            </div>
                            <div class="StartNumber">
                                <label>結束編碼</label>
                                <input style="width: 95%;" value="{{isset($BookCaseNos[$j * $severalrows + $i + 1]) ? $BookCaseNos[$j * $severalrows + $i + 1]->endnum : ""}}" type="text" name="endnum[]" placeholder="xxx xxxx xxxx"/>
                            </div>
                        </div>
                    </div>
                @endfor

            </div>
            @endfor

        </div>
    </form>

    <style>
        body{
            font-family: arial,"Microsoft JhengHei","微軟正黑體",sans-serif !important;
            margin: 0;
        }

        .bookcaseName{
            background-color: wheat;
            text-align: center;
        }
        .bookcaseNote{
            background-color: wheat;
            text-align: center;
        }
        .NumberBlock{
            padding: 5px;
            border:1px solid black;
            border-radius: 5px;
            background-color: #ebebeb;
            margin: 5px 0;
        }
        .BlockCol{
            width: 170px;
            display: inline-block;
            white-space:normal ; /* 防止換行 */

        }
        #FormBlock{
            background: url({{asset('image/wood.jpeg')}});
            background-repeat:repeat;
            padding: 5px 5px 5px 10px;
            width: 100%;
            margin-top: 5px;
            overflow-x: auto; /* 啟用橫向捲動條 */
            overflow-y: hidden; /* 禁用垂直捲動條 */
            white-space: nowrap; /* 防止換行 */
        }
        .submit{
            margin-left: auto;
            width: 130px;
            padding: 8px 10px;
            background-color: #007bff;
            color:white;
            cursor: pointer;
            border: none;
            border-radius: 3px;
        }
        .submit:hover{
            background-color: #0b5ed7;
            transition: all .35s;
        }

        /* 儲存成功通知 */
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px 30px;
            position: fixed;
            border-radius: 5px;
            top: 5px;
            right: 0;
            transform: translateX(-50%);
            z-index: 9999;
            animation: hideMessage 6s forwards;
        }
        /* Optional: Add animation for hiding */
        @keyframes hideMessage {
            0% { opacity: 0; }
            5% {opacity: 1; }
            100% { opacity: 0; display: none; }
        }
    </style>
    <script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>

    <script>
        function updateord() {
            // 遍歷每個 .BlockRow
            $('.BlockRow').each(function(rowIndex) {
                // 在每個 .BlockRow 中遍歷 .BlockCol
                $(this).find('.BlockCol').each(function(colIndex) {
                    var newValue = colIndex*$('#FormBlock .BlockRow').length+rowIndex+1
                    $(this).find('input[name^="ord"]').val(newValue);
                    $(this).find('.showord').text(newValue);
                });
            });
        }
        $(document).ready(function(){
            // 取得 input 元素
            const ELE_severalrows = $('#severalrows');
            const ELE_severalcols = $('#severalcols');
            var nowrowsLength=$('#FormBlock .BlockRow').length
            var nowcolsLength=$('#FormBlock .BlockRow .BlockCol').length/$('#FormBlock .BlockRow').length

            ELE_severalrows.on('input', function() {
                $(this).val(Math.abs(parseInt($(this).val(),10)))
                input_rows=parseInt(ELE_severalrows.val(),10)
                // 刪除層數動作
                if (input_rows < nowrowsLength) {
                    $('#FormBlock .BlockRow').slice(input_rows-nowrowsLength).remove();
                    nowrowsLength=input_rows

                }else if (input_rows > nowrowsLength) {
                    // 新增層數動作
                    var rowsToAdd = input_rows - nowrowsLength;
                    for (var i = 0; i < rowsToAdd; i++) {
                        // 創建新的 .BlockRow 元素
                        var newElement='<div class="BlockRow">'
                        for (var j = 0; j < nowcolsLength; j++) {
                            var newCol =
                                '<div class="BlockCol"> '+
                                '    <div class="NumberBlock"> '+
                                '   <input type="number" name="id[]" value="-1"  style="display: none"\/> '+
                                '        <input style="width: 30%;display:none" type="text" name="ord[]" value="-1"\/> '+
                                '        <div class="showord" style="text-align: center">-1<\/div>'+
                                '        <div class="StartNumber"> '+
                                '            <label>起始編碼<\/label> '+
                                '            <input style="width: 95%;" type="text" name="startnum[]" placeholder="xxx xxxx xxxx"\/> '+
                                '        <\/div> '+
                                '        <div class="StartNumber"> '+
                                '            <label>結束編碼<\/label> '+
                                '            <input style="width: 95%;" type="text" name="endnum[]" placeholder="xxx xxxx xxxx"\/> '+
                                '        <\/div> '+
                                '    <\/div> '+
                                '<\/div> '

                            newElement+=newCol;
                        }
                        newElement+='<\/div>'
                        // 將新的 .BlockRow 添加到 #FormBlock 中
                        $('#FormBlock').append($(newElement));
                    }
                    nowrowsLength=input_rows
                }
                updateord();
            });

            ELE_severalcols.on('input', function() {
                $(this).val(Math.abs(parseInt($(this).val(),10)))
                input_cols=parseInt(ELE_severalcols.val(),10)
                // 刪除橫格數動作
                if (input_cols < nowcolsLength) {
                    var rows = $('.BlockRow'); // 取得所有 .BlockRow 元素
                    rows.each(function() {
                        var cols = $(this).find('.BlockCol');
                        var colsToRemove = cols.length - input_cols;
                        cols.slice(-colsToRemove).remove();
                    });
                    nowcolsLength=input_cols
                }else if(input_cols > nowcolsLength) {
                    // 新增橫格數動作
                    var rows = $('.BlockRow'); // 取得所有 .BlockRow 元素
                    rows.each(function() {
                        var cols = $(this).find('.BlockCol');
                        var colsToAdd = input_cols - nowcolsLength;
                        newCol=""
                        for (var i = 0; i < colsToAdd; i++) {
                            newCol+='<div class="BlockCol"> '+
                                    '    <div class="NumberBlock"> '+
                                    '   <input type="number" name="id[]" value="-1"  style="display: none"\/> '+
                                    '        <input style="width: 30%;display:none" type="text" name="ord[]" value="-1"\/> '+
                                    '        <div class="showord" style="text-align: center">-1<\/div>'+
                                    '        <div class="StartNumber"> '+
                                    '            <label>起始編碼<\/label> '+
                                    '            <input style="width: 95%;" type="text" name="startnum[]" placeholder="xxx xxxx xxxx"\/> '+
                                    '        <\/div> '+
                                    '        <div class="StartNumber"> '+
                                    '            <label>結束編碼<\/label> '+
                                    '            <input style="width: 95%;" type="text" name="endnum[]" placeholder="xxx xxxx xxxx"\/> '+
                                    '        <\/div> '+
                                    '    <\/div> '+
                                    '<\/div> '
                        }
                        $(this).append(newCol);
                    });
                    nowcolsLength = input_cols;
                }
                updateord();
            });
        });


    </script>

</div>
