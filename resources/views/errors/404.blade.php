<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .cable1 {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .cable2 {
            justify-content: center!important;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
            
        }
    </style>
</head>
<body>
    <div style="display: block;">
        <div style="position: relative;">
            {{-- <img src="{{asset('font_404.png')}}" alt="" style="width: 100%; position: absolute;"> --}}
            <div style="text-align: center;">
                <img src="{{asset('img/404.png')}}" alt="404" style="    height: 100%; width: 100%; max-width: 610px;">
            </div>
            <div class="">
                <div class="">
                    <img src="{{asset('img/cable_404.png')}}" alt="cable" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
</body>
</html>