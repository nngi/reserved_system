<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <!-- bootstrapを利用する -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- ElementUIのcss -->
  <link href="{{ asset('css/index.css') }}" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-default navbar-static-top">
    <div style="width:100%">
      <div>
        <div style="float:left;margin-left:30px;">
          <a class="navbar-brand" href="{{ url('/') }}">予約管理システム</a>
        </div>
        <div style="float:right;margin-right:30px;margin-top:15px;">
          @if (Auth::guest())
            <span style="padding: 20px;">
              <span class="glyphicon glyphicon-log-in" style="margin-right: 5px"></span>
              <a href="{{ route('login') }}">Login</a>
            </span>
            <span>
              <span class="glyphicon glyphicon-plus" style="margin-right: 5px"></span>
              <a href="{{ route('register') }}">Register</a>
            </span>
          @else
            <span style="padding: 20px;">
              <span class="glyphicon glyphicon-user" style="margin-right: 5px"></span>{{ Auth::user()->name }}
            </span>
            <span>
              <span class="glyphicon glyphicon-log-out" style="margin-right: 5px"></span>
              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          @endif
        </div>
      </div>
      <!--
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}">
          {{ config('app.name', 'Laravel') }}
        </a>
      </div>

      <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <span>
          Guest:{{ Auth::guest() }}
        </span>
        <ul class="nav navbar-nav">
          &nbsp;
        </ul>

        <ul class="nav navbar-nav navbar-right">
          @if (Auth::guest())
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
          @else
            <li style="padding: 10px;">
              {{ Auth::user()->name }}
            </li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          @endif
        </ul>
      </div>
      -->
    </div>
  </nav>
  @yield('content')
  <!-- Scripts -->
  <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
