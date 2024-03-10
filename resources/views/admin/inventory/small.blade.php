@csrf
<style>
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
@if(isset($mybooks[0]))
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
<style>
    *{
        color:white;
    }
</style>
<script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
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
