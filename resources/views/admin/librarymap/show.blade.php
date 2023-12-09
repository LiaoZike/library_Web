<div class="container">
    <div class="row">
        <div class="container-fluid">
            @foreach($floors as $floor)
                <a href="{{route("admin.librarymap.editmap","3F")}}" class="row floor">
                    <div class="col-12 col-sm-4 floor_ord">{{$floor->ord}}</div>
                    <div class="col-12 col-sm-4 floor_name">{{$floor->name}}</div>
                    <div class="col-12 col-sm-4">{{$floor->note}}</div>
                </a>
            @endforeach

        </div>
    </div>
    <style>
        .floor{
            border: 1px solid black;
            padding: 10px 0px;
            text-align: center;
            background-color: #ddd;
            color:black;
        }

        .floor:hover{
            color:black;
        }
        .floor_name{
            background-color: darkorange;
        }
        .floor_ord{
            background-color: #AAA;
            text-align: center;
        }
    </style>
</div>
<script>
</script>
