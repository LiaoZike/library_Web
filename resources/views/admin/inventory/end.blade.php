<link rel="stylesheet" href="{{asset('vendor\laravel-admin\font-awesome\css\font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_css/inventory/end.css')}}">

<div class="ifcontent container-fluid">
    <style>
        .block_row{
            display: flex;
            align-items: end;
        }
        .mybooks_row{
            display: flex;
            align-items: start;
        }
        /* DBBooks 和 mybooks 的共用樣式 */
        .DBBooks, .mybooks {
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .mybooks{
            cursor: pointer !important;
        }
        /* DBBooks 的樣式，設定圖片寬度和高度，並使用 object-fit 來控制圖片在容器內的放置方式 */
        .DBBooks img,.mybooks img{
            object-fit: cover; /* 調整這個值以滿足你的需求，可能的值包括 cover、contain、fill、scale-down */
            outline: 3px solid #ddd; /* 可以根據需要添加邊框樣式 */
        }
        .DB_orange{
            outline: 3px solid rgba(182, 115, 3, 1) !important; /* 可以根據需要添加邊框樣式 */
            background-color: rgba(255, 159, 0, 0.4);
        }
        .my_orange{
            outline: 3px solid rgba(182, 115, 3, 1) !important; /* 可以根據需要添加邊框樣式 */
            background-color: rgba(20, 20, 20, 0.2);
        
        }
    </style>
    @if(session('success'))
        <div id="success-message" class="success-message">
            <i class="fa fa-check" aria-hidden="true"></i>
            {{ session('success') }}
        </div>
    @endif
    <div id="bookFrameWrapper">
        <input type="checkbox" id="checkhide">
        <div class="diagram_info">
            <div class="diagram_title">
                <label for="checkhide" class="checkhide">
                    <div class="close_info"></div>
                </label>
            </div>
            <iframe id="contentFrame" src=""></iframe>
        </div>

    </div>

    <div class="row">
        <a href="{{ route('admin.inventory.bookcase',['timesname'=>$timesname,'floormapid'=>$gotopid]) }}">上一頁</a>
        <input type="number" name="link_id" value="{{5}}" style="display: none"/>
        <div class="times">{{$timesname}}</div>
        <div class="floorName">樓層：{{$floor}}</div>
        <div class="bookcaseName">書櫃名：{{$floormap}}</div>
        <div class="bookcaseNo">書櫃格(書架)編號：{{$caseno}}</div>
    </div>

    <div id="FormBlock">
        <!-- 資料庫書本 -->
        <div class="block_row">
            @for($i=0;$i<sizeof($results);$i++)
{{--                    <h1>DB計數:{{$DBcount}} /書本計數: {{$i}} / 目前ord:{{$results[$i]->ord}}</h1>--}}
               <div class="books_block">
                   @if(!is_null($results[$i][1])&&($results[$i][1]!='DB_black')&&($results[$i][1]!='DB_orange'))
                      <div class="DBBooks">
                          <img src="{{$results[$i][1]['url']}}" style="user-select:none;">
                      </div>
                   @elseif(!is_null($results[$i][1])&&($results[$i][1]=='DB_orange'))
                        <div class="DBBooks DB_orange">
                            <img style="user-select:none;width: 0;">
                       </div>
                   @else
                       <div class="DBBooks @if(isset($results[$i][1])&&$results[$i][1]=='DB_black') DB_black @else DB_gray @endif">
                           <img style="user-select:none;width: 0;">
                       </div>
                   @endif

                   @if(!is_null($results[$i][0])&&$results[$i][0]!='my_orange'&&($results[$i][1]!='DB_black')&&($results[$i][1]!='DB_orange'))
                   <div class="block_hidden">
{{--                           <img src="{{asset($results[$i][0]['url'])}}" style="user-select:none;">--}}
                        <span class="x1" style="display: none">{{$results[$i][0]['x1']}}</span>
                        <span class="x2" style="display: none">{{$results[$i][0]['x2']}}</span>
                        <span class="y1" style="display: none">{{$results[$i][0]['y1']}}</span>
                        <span class="y2" style="display: none">{{$results[$i][0]['y2']}}</span>
                        <span class="imageord" style="display: none">{{$results[$i][0]['imageord']}}</span>
                   </div>
                   @endif
               </div>
            @endfor

        </div>

        <!-- 辨識書本 -->
        <div class="mybooks_row">
            @for($i=0;$i<sizeof($results);$i++)
                <div class="books_block"> {{--block_hidden--}}
                    @if(!is_null($results[$i][0])&&$results[$i][0]!='my_orange')
                        <a href="{{route('admin.inventory.small',['timesname'=>$timesname,'results_id'=>$results[$i][0]['id'],'DBbooksID'=>$results[$i][0]['matchid']])}}"  class="mybooks
                            @if($results[$i][0]['ishere']==1) bck_green
                            @elseif($results[$i][0]['ishere']==0) bck_red
                            @elseif($results[$i][0]['ishere']==2) bck_orange
                            @elseif($results[$i][0]['ishere']==-1) my_gray
                        @endif" style="display: block;">

                            <img src="{{$results[$i][0]['url']}}" style="user-select:none;">
                        </a>
                    @elseif($results[$i][0]=='my_orange')
                        <div class="mybooks my_orange">
                            <img style="user-select:none;width: 0;">
                        </div>
                    @elseif(!is_null($results[$i][0]))
                        <a href="{{route('admin.inventory.small',['timesname'=>$timesname,'results_id'=>"null",'DBbooksID'=>$results[$i][0]['matchid']])}}"  class="mybooks my_gray"  style="display: inline-block">
                            <img src="" style="user-select:none;width: 0;">
                        </a>
                    @else
                        <a class="mybooks my_black" style="display: inline-block">

                            <img style="user-select:none;width: 0;">
                        </a>
                    @endif

                    @if(!is_null($results[$i][0])&&$results[$i][0]!='my_orange')
                        <div class="block_hidden">
                            <img src="{{asset($results[$i][0]['url'])}}" style="user-select:none;">
                            <span class="x1" style="display: none">{{$results[$i][0]['x1']}}</span>
                            <span class="x2" style="display: none">{{$results[$i][0]['x2']}}</span>
                            <span class="y1" style="display: none">{{$results[$i][0]['y1']}}</span>
                            <span class="y2" style="display: none">{{$results[$i][0]['y2']}}</span>
                            <span class="imageord" style="display: none">{{$results[$i][0]['imageord']}}</span>
                        </div>
                    @endif
                </div>
            @endfor

        </div>

    </div>
        @php
            $temp_ct=0;
        @endphp
        <!-- 導航輪播 -->
        <div class="row pt-3" style="text-align: center;"><h1>拍攝圖片</h1></div>
        <div class="row imagerow">
            <section class="wow fadeIn example no-padding no-transition slider-top">
                <div>
                    <!-- Jssor Slider Begin -->
                    <div id="slider1_container" style="visibility: hidden; position: relative; margin: 0 auto;
        top: 0px; left: 0px;">
                        <!-- Slides Container -->
                        <div class="slides" data-u="slides" style="position: absolute; left: 0px; top: 0px;">
                            @foreach($bookcaseimg as $img)
                                <div class="image{{$temp_ct}}">
                                    <img src="{{asset($img->url)}}" >
                                    <div class="border"></div>
                                </div>
                                {{$temp_ct++}}
                            @endforeach
                        </div>
                        <div data-u="navigator" class="jssorb031" style="position:absolute;bottom:12px;right:12px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
                            <div data-u="prototype" class="i" style="width:16px;height:16px;">
                                <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                                    <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                                </svg>
                            </div>
                        </div>
                        <div data-u="arrowleft" class="jssora051" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
                            <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                                <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
                            </svg>
                        </div>
                        <div data-u="arrowright" class="jssora051" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
                            <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                                <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
                            </svg>
                        </div>
                        <!--#endregion Arrow Navigator Skin End -->
                    </div>
                </div>
            </section>
        </div>

        <style>
            #FormBlock {
                background: url({{asset('image/wood.jpeg')}});
            }
        </style>


        <script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
        <script src="{{asset('admin_js/inventory/end.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jssor-slider/27.1.0/jssor.slider.min.js"></script>

        <script>
            var DBread_scale=0.5
            $(document).ready(function (){
                setTimeout(function() {
                    // 創建一個空陣列來存儲寬度值
                    var mybookswidthsArray = [];
                    var mybooksheight=0;
                    var DBbookswidthsArray = [];
                    var DBbooksheight=0;

                    $('.DBbooks img').map(function() {
                        // 使用 .css() 方法獲取每個元素的寬度並將其轉換為數字
                        var widthValue = parseFloat($(this).css('width'))*DBread_scale;
                        DBbooksheight=Math.max(DBbooksheight,parseFloat($(this).css('height'))*DBread_scale);
                        DBbookswidthsArray.push(widthValue);
                    });
                    $('.mybooks img').each(function(index) {
                        // 將先前存儲的寬度值應用到當前元素
                        mybooksheight=Math.max(mybooksheight,parseFloat($(this).css('height')));
                        if($(this).css('width')==='0px'){
                            $(this).css('width', DBbookswidthsArray[index] + 'px');
                        }
                        var widthValue = parseFloat($(this).css('width'));
                        mybookswidthsArray.push(widthValue);
                    });
                    // 使用 .each() 方法遍歷所有符合選擇器的 .DBbooks img 元素
                    $('.DBbooks img').each(function(index) {
                        // 將先前存儲的寬度值應用到當前元素
                        $(this).css('width', mybookswidthsArray[index] + 'px');
                    });
                    $('.my_gray').css('height',mybooksheight*0.8+'px')
                    $('.my_orange').css('height',mybooksheight*0.8+'px')
                    $('.DB_gray').css('height',DBbooksheight*0.8+'px')
                    $('.DB_black').css('height',DBbooksheight*0.8+'px')
                    $('.DB_orange').css('height',DBbooksheight+'px')
                }, 500); // 3000 毫秒等於 3 秒
            });

            window.addEventListener('message', function (event) {
                if(event.data==="iframe_successful"){
                    location.reload();
                }
            });
        </script>
</div>
