<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>PMC | Workflow</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- <link rel="icon" href="favicon.ico" type="image/x-icon" /> -->

        <link href="{{ asset('assets/dist/css/font-face.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/icon-kit/dist/css/iconkit.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/ionicons/dist/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/c3/c3.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/dist/css/theme.min.css') }}">
        <script src="{{ asset('assets/src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.css') }}">

        @yield('pagecss')
    </head>

    <style type="text/css">
        body .wrapper .header-top,
        body .wrapper .page-wrap .main-content {
            padding-left: 0px;
        }

        /*Custom CSS for revamp UI 2025 j.p.*/
        .main-nav-btn {
            height: 44px;
            min-width: 100px;
        }

        .nav-card:hover .nav-card-icon {
            /*background: linear-gradient(25deg, #001e47, blue);*/
            background: var(--primary-color);
            cursor: pointer;
            top: -10px;
            transition: all .2s;
        }

        .nav-card:hover b {
            color: var(--primary-color) !important;
        }

        .nav-card:hover button {
            box-shadow: 0 1rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .nav-card-icon {
            padding: 12px 14px;
            background-color: gray;
            position: absolute;
            top: 1px;
            left: -25px;
            /*background: linear-gradient(45deg, blue, #001e47);*/
            background: var(--primary-color);
        }

        .nav-card.active .nav-card-icon {
            top: -10px;
        }

        .nav-card.active b {
            color: var(--primary-color) !important;
        }

        .menu-container {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        .menu-item {
            position: relative;
        }

        .menu-link {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: 600;
            color: #333;
            transition: color 0.2s;
        }

        .menu-link:hover {
            color: #5E17EB;
        }

        .sub-menu {
            list-style: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px 0;
            min-width: 180px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
        }

        .sub-menu .menu-link {
            padding: 8px 15px;
            font-weight: 500;
            color: #333;
            white-space: nowrap;
        }

        .sub-menu .menu-link:hover {
            background: #f7f7f7;
            color: #5E17EB;
        }
        
        .menu-item:hover > .sub-menu {
            display: block;
        }

        .menu-item .menu-link {
            border-radius: 4px !important;
        }
        .page-header.mt-4 .card.shadow-sm.flex-grow-1,
        .page-header.mt-4 .card.shadow-sm.flex-grow-1.mr-2 {
            margin-bottom: 0px;
        }

        .r-menu:hover {
            background-color: var(--primary-color) !important;
            color: var(--text-default-hover-color) !important;
            box-shadow: var(--box-shadow) !important;
        }

        /*End 2025 j.p.*/
    </style>

    <body id="wfs-wrapper" data-theme="sky-theme">

        <div class="wrapper">
            
            @include('layouts.header')

            <div class="page-wrap">

                @include('layouts.sidebar')

                <div class="main-content">
                    @yield('content')
                </div>

                @include('layouts.chat-list')

                @include('layouts.chat-panel')

                <footer class="footer wfs-bg" style="padding-left: 32px">
                    <div class="w-100 clearfix">
                        <span class="text-center text-sm-left d-md-inline-block wfs-text-default">Copyright © {{ date('Y') }} Philsaga Mining Corporation. All Rights Reserved.</span>
                        <span class="float-none float-sm-right mt-1 mt-sm-0 text-center wfs-text-default">Crafted with <i class="fa fa-heart text-danger"></i> by <a href="javascript:;" class="wfs-text-default">ICT</a></span>
                    </div>
                </footer>
                
            </div>
        </div>
        
        <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('assets/src/js/vendor/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/popper.js/dist/umd/popper.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/screenfull/dist/screenfull.js') }}"></script>
        <!-- <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script> -->
<!--         <script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script> -->
        <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
        <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/d3/dist/d3.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/c3/c3.min.js') }}"></script>
        <script src="{{ asset('assets/js/tables.js') }}"></script>
        <script src="{{ asset('assets/js/charts.js') }}"></script>
        <script src="{{ asset('assets/dist/js/theme.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>        
    
<!--         <script src="{{ asset('assets/js/session-time-out.js') }}"></script>  comment temp --> 
        @yield('pagejs')

        @if($msg = Session::get('successMsg'))
            <script>
                $(document).ready(function(){
                    $.toast({
                      heading: 'Success',
                      text: "{{ Session::get('successMsg') }}",
                      showHideTransition: 'slide',
                      icon: 'success',
                      loaderBg: '#f96868',
                      position: 'top-right'
                    })
                });
            </script>
        @endif
    </body>
</html>
