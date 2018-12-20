<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smart Control</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/bootswatch/dist/lumen/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/alertify-js/build/css/alertify.min.css') }}" rel="stylesheet">
    <style>
    main.color {
        background: #F5F5F5 !important;
    }
    div.alertify-notifier{
        color: white;
    }
    </style>
</head>
<body>
<div id="app">
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary"> -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary flex-md-nowrap p-0 ">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                Smart Control
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    @if (Auth::guest())
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link">
                                Bienvenido, {{ Auth::user()->name }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Cerrar sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endif
                </ul>
            </div>

        </div>
    </nav>

    <div class="container-fluid">
        <div class="row" style="height: -webkit-fill-available;">

            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul id="menu" class="nav nav-pills flex-column" style="margin-top: 25px;">
                    @if(Auth::user()->es_admin == 1)
                    <!-- <li class="nav-item" id="dashboard">
                        <a class="nav-link" href="{{ url('/home') }}">
                        DASHBOARD
                        </a>
                    </li> -->
                    <li class="nav-item" id="users">
                        <a class="nav-link" href="{{ route('users.index') }}">
                        USUARIOS
                        </a>
                    </li>
                    <li class="nav-item" id="business">
                        <a class="nav-link" href="{{ route('business.index') }}">
                        EMPRESAS
                        </a>
                    </li>
                    @endif
                    <li class="nav-item" id="drivers">
                        <a class="nav-link" href="{{ route('drivers.index') }}">
                        CHOFERES
                        </a>
                    </li>
                    <li class="nav-item" id="receipts">
                        <a class="nav-link" href="{{ route('receipts.index') }}">
                        RECIBOS / VALES
                        </a>
                    </li>
                    <li class="nav-item" id="deposits">
                        <a class="nav-link" href="{{ route('deposits.index') }}">
                        DEPOSITOS
                        </a>
                    </li>
                    <br/>
                    <br/>
                    <li class="nav-item" id="report_receipt">
                        <a class="nav-link" href="{{ route('receipts.report') }}">
                        REPORTE RECIBOS
                        </a>
                    </li>
                    <li class="nav-item" id="report_deposit">
                        <a class="nav-link" href="{{ route('deposits.report') }}">
                        REPORTE DEPOSITOS
                        </a>
                    </li>
                    </ul>
                </div>
            </nav>

            @yield('content')

        </div>
    </div>

</div>

<!-- Scripts -->
<!-- <script src="{{ asset('js/app.js') }}"></script> -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="{{ asset('bower_components/alertify-js/build/alertify.min.js') }}"></script>

<script>
function updateMenu(option)
{
    $('#menu > li > a').removeClass('active');
    $('#menu > li#'+option+' > a').addClass('active');
}
alertify.set('notifier','position', 'top-right');
</script>
@stack('scripts')
</body>
</html>
