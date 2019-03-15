<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @routes

    <script>
        window.Globals = <?php echo json_encode(\App\Globals::variables()); ?>;
    </script>
</head>
<body>
    <div id="app" class="body-container site-menubar-smart">
        <nav class="navbar navbar-expand site-navbar navbar-light bg-white navbar-laravel">
            <div class="navbar-header">
                <div class="ml-3 mt-1 d-md-none">
                    <menu-icon v-model="sidebarVisible"></menu-icon>
                </div>

                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="navbar-container container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @include('navs.user-right')
                    </ul>
                </div>
            </div>
        </nav>

        <div class="layout-wrapper">
            @auth
                <div class="site-menubar bg-dark-blue text-white" :class="{active: sidebarVisible}">
                    <scrollable class="site-menubar-body flex-fill">
                        <ul class="site-menu">
                            @include('navs.user-left')
                        </ul>
                    </scrollable>

                    <div class="site-menubar-footer d-flex align-items-center justify-content-center">
                        @include('navs.user-left-footer')
                    </div>
                </div>
            @endauth

            <scrollable class="site-page flex-fill">
                @yield('content')
            </scrollable>
        </div>
    </div>
</body>
</html>
