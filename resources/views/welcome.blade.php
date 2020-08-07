<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Star Wars Character search</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="title m-b-md">
                Star Wars Character search
            </div>

            <div class="refresh">
                <div>Refresh the cache to load data from swapi.co</div>
                <button type="submit" onclick="window.location='{{ url("/characters") }}'" class="btn btn-block btn-primary">Refresh Cache</button>
            </div>

            <form name="searchByName" action="/" method="get">
                <div>Search by random string</div>
                <div>
                    <textarea name="randomString" placeholder="Type a name"></textarea>
                    <button type="submit" class="btn btn-block btn-primary">Search</button>
                </div>
            </form>

            @foreach($characters as $character)
                <li>{{$character->characterName}}</li>
            @endforeach

            <form name="searchBySkinHair" action="/" method="get">
                <div>Search by skin and hair colors</div>
                <div>
                    <select name="skin" id="skinId">
                        @foreach($skins as $skin)
                            <option value="{{$skin->skinId}}">{{$skin->skinName}}</option>
                        @endforeach
                    </select>
                    <select name="hair" id="hairId">
                        @foreach($hairs as $hair)
                            <option value="{{$hair->hairId}}">{{$hair->colorName}}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-block btn-primary">Search</button>
                </div>
            </form>
        </div>
    </body>
</html>
