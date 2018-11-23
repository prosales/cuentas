<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Smart Party</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="confetti/site/site.css" />
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
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
            right: 20px;
            top: 28px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
            font-weight: 900;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            padding: 10px;
            border-radius: 6px;
            border: 3px solid #565656;
            color: #565656;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .settings-div {
            white-space: nowrap;
            display: inline-block;
            overflow: hidden;
            position: absolute;
            background: rgba(255,255,255,0.1);
            padding: 20px;
            margin: 0 auto;
            top: 0;
            left: 0;
            right: 0;
            height: 100vh;
            z-index: 9;
        }

        .txt-party {
            color: #52D7B8;
        }

    </style>
</head>
<body>
    <!-- <canvas id="confetti-holder"></canvas> -->
    <div class="settings-div flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                    @else
                    <a href="{{ route('login') }}">Iniciar Sesión</a>
                    <!-- <a href="{{ route('register') }}">Register</a> -->
                    @endauth
            </div>
        @endif

        <div class="content">
            <div class="title m-b-md" style="margin-top: 23%;">
                Smart <span class="txt-party">Control</span>
            </div>

            <!-- <div class="links">
                <a href="https://laravel.com/docs">Documentation</a>
                <a href="https://laracasts.com">Laracasts</a>
                <a href="https://laravel-news.com">News</a>
                <a href="https://forge.laravel.com">Forge</a>
                <a href="https://github.com/laravel/laravel">GitHub</a>
            </div> -->
        </div>
    </div>
    <script type="text/javascript" src="confetti/dist/index.min.js"></script>
    <script type="text/javascript" src="confetti/site/site.js"></script>
</body>
</html>
