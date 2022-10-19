<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>@yield('title', 'Laravel')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <!-- Ladda style -->
    <link href="{{ asset('css/plugins/ladda/dist/ladda-themeless.min.css') }}" rel="stylesheet">

    <!-- Toastr style -->
{{--    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">--}}

    <!-- Gritter -->
    {{-- <link href="{{ asset('js/plugins/gritter/js/jquery.gritter.css') }}" rel="stylesheet"> --}}

    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    @section('style')@show
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> --}}
</head>
<body>
    <div id="wrapper">
        @include('layouts.element.sidebar')

        <div id="page-wrapper" class="gray-bg dashbard-1">
            @include('layouts.element.header')
            {{-- @include('layouts.element.breadcrumb') --}}

            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
{{--                        @include('layouts.element.__flash_message')--}}
                        {{-- @include('flash::message') --}}

                        @yield('content')
                    </div>
                </div>
            </div>

            <footer>
                @include('layouts.element.footer')
            </footer>

            @include('layouts.element.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('select2:open', () => {
            if ($(document).find('input.select2-search__field').length) {
                setTimeout(function () {
                    document.querySelector('input.select2-search__field').focus();
                }, 0)
            }
        });

    </script>

    @section('script')@show
    @yield('toastr')
</body>
</html>
