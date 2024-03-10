<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>單一書本狀態處理</title>
    <link rel="stylesheet" href="{{asset('admin_css/inventory/small.css')}}">
</head>
<body>
    @csrf
    <style>
        *{
            color:white;
        }
        .blur-background{
            position: fixed;
            top:0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(80, 80, 80, 0.5);
            backdrop-filter: blur(12px);
            z-index: -999;
        }
    </style>
    <div class="blur-background"></div>
    <div class="row">
        @if(isset($mybooks[0]))
        <select id="ishereSelect"  class="form-control info-select">
            <option value="1" @if($mybooks[0]['ishere']==1) selected @endif>正確書本</option>
            <option value="0" @if($mybooks[0]['ishere']==0) selected @endif>錯誤書本</option>
            <option value="2" @if($mybooks[0]['ishere']==2) selected @endif>此書本在正確架上，但錯位</option>
            <option value="-1" @if($mybooks[0]['ishere']==-1) selected @endif>虛擬書本(不存在書本)</option>
        </select>
            <button id="sendpatchbtn">確認</button>
        @endif
    </div>
    @if(isset($DBbook))
    <div class="DBdatarow">
        <div class="row DBrow">
            <div class="col">書名：</div>
            <div class="col">{{$DBbook['title']}}</div>
            <div class="col">{{$DBbook['number']}} </div>
        </div>
        <div class="row DBrow">@if($DBbook['lend']==0)目前未被借出 @else 目前被借出 @endif</div>

        <div class="row DBrow">
            <div class="col">作者：</div>
            <div class="col">{{$DBbook['author']}}</div>
        </div>
        <div class="row DBrow">
            <div class="col">放置位置：</div>
            <div class="col">{{$DBbook['local']}} - {{$DBbook['local_suff']}}</div>
        </div>
    </div>
    @endif

    <div class="row DBrow">
        狀態:
        @if($mybooks[0]['ord']==-1)
            此書本為虛擬書本
        @elseif($mybooks[0]['ishere']==0)
            此書本不屬於該架上
        @elseif($mybooks[0]['ishere']==2)
            此書本在架上，但位置錯誤
        @else
            此書本目前正確
        @endif
    </div>

    <div class="book_row">
        <div class="col">
            <div class="mybooks_title">
                盤點書本
            </div>
            <div class="mybooks_content">
                @foreach($mybooks as $mybook)
                    <img src="{{$mybook['url']}}" style="user-select:none;">
                @endforeach
            </div>
        </div>
        <div class="col" style="padding-left: 10px">
            <div class="DBbooks_title">
                資料庫書本
            </div>

            <div class="DBbooks_content">
                @if(!is_null($DBbook))
                    <img src="{{$DBbook['url']}}">
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row">
            EZ查詢書本位置工具
        </div>
        <form id="searchform">
            <div class="row">
                <label for="booknumber" style="display: none">輸入書本位置編號:</label>
                <input type="text" id="booknumber" name="booknumber" placeholder="輸入書本位置編號"/>
                <button type="submit" id="searchbooklocal">查詢</button>
            </div>
        </form>
        <table id="searchresult">
            <caption class="searchresult_info">_</caption>
            <thead class="searchresult_title">
                <tr>
                    <th class="ID">ID</th>
                    <th class="floor">Floor</th>
                    <th class="floormap">Floormap</th>
                    <th class="bookcaseno">Bookcaseno</th>
                    <th class="startnum">startnum</th>
                    <th class="endnum">endnum</th>
                </tr>
            </thead>
            <tbody class="searchresult_content">
            </tbody>
        </table>
        <style>
            *{
                font-family: arial,"Microsoft JhengHei","微軟正黑體",sans-serif !important;
            }
            .searchresult_title{
                background-color: #292929;
            }
            #searchresult{
                text-align: center;
            }
            .searchresult_content td{
               border-bottom: 2px solid black !important;
            }
            body{
                padding-bottom: 50px;
            }
        </style>
    </div>
    <script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
    <script>
        $(document).ready(function (){
            var url="{{route('admin.inventory.searchlocal',['number'=>'number'])}}"
            $('#searchform').submit(function (event){
                event.preventDefault();
                var csrfToken = $('input[name="_token"]').val();
                var text=$('#booknumber').val()
                var newurl = url.replace('number',text)
                $('#searchresult .searchresult_info').text(text+"搜尋結果:");
                $('#searchresult .searchresult_content').text("");
                fetch(newurl, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json', // 設定資料類型為 JSON
                        'X-CSRF-TOKEN': csrfToken, // 包含 CSRF 令牌
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json(); // 解析 JSON 格式的回應
                    // return response;
                })
                .then(data => {
                    if(data.length === 0){
                        $('#searchresult .searchresult_content').append(
                            '<tr><td colspan=6 style="background-color: red">查無資料</td></tr>'
                        );
                        console.log("NO")
                    }else{
                        console.log(data)
                        data.forEach(item => {
                            $('#searchresult .searchresult_content').append(
                                '<tr>'+
                                '    <td class="ID">' + item.id + '</td>'+
                                '    <td class="floor">' + item.Floor + '</td>'+
                                '    <td class="floormap">' + item.Floormap + '</td>'+
                                '    <td class="bookcaseno">' + item.ord + '</td>'+
                                '    <td class="startnum">' + item.startnum + '</td>'+
                                '    <td class="endnum">' + item.endnum + '</td>'+
                                '</tr>'
                            );
                        });
                    }
                })
                .catch(error => {
                    console.log("錯誤",error)
                });

            })
        })
    </script>
    <script>
        // 取得按鈕元素
        const sendPatchBtn = document.querySelector('#sendpatchbtn');

        // 監聽按鈕點擊事件
        sendPatchBtn.addEventListener('click', () => {
            // 要發送 PATCH 請求的網址
            var csrfToken = document.querySelector('input[name="_token"]').getAttribute('value');
            var url = "{{route('admin.inventory.smallPatch',['results_id'=>"results_id",'DBbooksID'=>"DBbooksID","ishere"=>"ishere"])}}";
            var ishereSelect = document.getElementById('ishereSelect');
            var selectedValue = ishereSelect.value;

            url = url.replace("ishere",selectedValue)
            @if(isset($mybooks[0]))
                url = url.replace("results_id",{{$mybooks[0]['id']}})
            @else
                url = url.replace("results_id","null")
            @endif

            @if(!is_null($DBbook))
                url = url.replace("DBbooksID",{{$DBbook['id']}})
            @else
                url = url.replace("DBbooksID","null")
            @endif
            console.log(url);

            // 發送 PATCH 請求
            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json', // 設定資料類型為 JSON
                    'X-CSRF-TOKEN': csrfToken, // 包含 CSRF 令牌

                    // 其他可能需要的標頭
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json(); // 解析 JSON 格式的回應
                })
                .then(data => {
                    // 處理成功回應的資料
                    alert("成功");
                    parent.postMessage("iframe_successful");
                    location.reload();
                    // console.log("成功",data)
                })
                .catch(error => {
                    // 處理錯誤
                    alert("錯誤")
                    parent.postMessage("iframe_error");
                    location.reload();
                    // console.log("錯誤",error)
                });
        });

    </script>
    <script>
        $(document).ready(function (){
            var myBooksContent=$('.mybooks_content img')

            var maxHeight = 0;  //初始化最大高度
            myBooksContent.each(function () {
                var imgHeight = $(this).height();
                maxHeight=Math.max(maxHeight,imgHeight);
            });
            if(maxHeight != 0){
                var myBooksContent=$('.DBbooks_content img')
                myBooksContent.each(function () {
                    $(this).css('height',maxHeight);
                });
            }else{
                var myBooksContent=$('.DBbooks_content img')
                myBooksContent.each(function () {
                    $(this).css('height',parseInt($(this).css('height'))/2+'px');
                });
            }
        })

    </script>

</body>
</html>
