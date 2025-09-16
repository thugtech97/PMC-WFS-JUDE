<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
    <!-- Template by Jeff @2025 -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="author" content="SemiColonWeb">
    <meta name="description" content="A template made by Jeff for WFS upgraded landing page">

    <!-- Font Imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap"
        rel="stylesheet">

    <!-- Core Style -->
    <link rel="stylesheet" href="{{ asset('assets_new/style.css') }}">

    <!-- Font Icons -->
    <link rel="stylesheet" href="{{ asset('assets_new/css/font-icons.css') }}">

    <!-- Plugins/Components CSS -->
    <link rel="stylesheet" href="{{ asset('assets_new/css/swiper.css') }}">

    <!-- Niche Demos -->
    <link rel="stylesheet" href="{{ asset('assets_new/demos/landing-2/landing-2.css') }}">

    <link href="{{ asset('assets/dist/css/font-face.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icon-kit/dist/css/iconkit.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/ionicons/dist/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/weather-icons/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/c3/c3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/theme.min.css') }}">
    <script src="{{ asset('assets/src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets_new/css/custom.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Document Title
 ============================================= -->
    <title>WorkFlow System</title>

    <!-- Custom css part is crucial, please copy this to correct path file -->
    <!-- Change custom color effects based on user template selections -->
    <style>
        .main-nav-btn {
            height: 54px;
            min-width: 100px;
        }

        .nav-card:hover .nav-card-icon {
            background: linear-gradient(25deg, #001e47, blue);
            cursor: pointer;
            top: -10px;
            transition: all .2s;
        }

        .nav-card:hover b {
            color: blue !important;
        }

        .nav-card:hover button {
            box-shadow: 0 1rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .nav-card-icon {
            padding: 14px 18px;
            background-color: gray;
            position: absolute;
            top: 1px;
            left: -25px;
            background: linear-gradient(45deg, blue, #001e47);
        }

        .nav-card.active .nav-card-icon {
            top: -10px;
        }

        .nav-card.active b {
            color: blue !important;
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
    </style>
    @yield('pagecss')
</head>


    <body class="stretched">

        <!-- Document Wrapper
 ============================================= -->
        <div id="wrapper" style="min-height: auto; background-color: #efefef; padding-bottom: 80px;">

            <!-- Header
  ============================================= -->
            <header id="header" class="border-bottom-0 no-sticky transparent-header header-custom-size">
                <div id="header-wrap">
                    <div class="container-fluid py-2">
                        <div class="d-flex justify-content-between w-100">

                            <!-- Logo
      ============================================= -->
                            <div class="d-flex justify-content-start align-items-center">
                                <img class="mr-2" height="40" src="{{ asset('assets/img/pmc-logo-solo.png') }}">
                                <h3 class="main-heading-text wfs-text-default">WORK<span class="primary-color">FLOW</span> SYSTEM</h3>
                            </div>

                            <!-- Primary Navigation
      ============================================= -->
                            <nav class="primary-menu">

                                <ul class="menu-container">
                                    <!-- Transactions with dropdown -->
                                    <li class="menu-item">
                                        <a class="menu-link text-uppercase" href="javascript:void(0)">
                                            <div>Transactions</div>
                                        </a>
                                        @php
                                            $subMenuItems = [
                                                'OREM'     => 'OREM',
                                                'IMP'      => 'IMP',
                                                'OSTR'     => 'OSTR',
                                                'GATEPASS' => 'Gatepass',
                                                'HK'       => 'Housekeeping',
                                                'VBS'      => 'VBS',
                                            ];

                                            $userTransTypes = [];
                                            if (!empty(Auth::user()->trans_types)) {
                                                $userTransTypes = explode('|', Auth::user()->trans_types);
                                            }
                                        @endphp

                                        <ul class="sub-menu">
                                            @foreach($subMenuItems as $code => $label)
                                                @if(in_array($code, $userTransTypes))
                                                    <li>
                                                        <a class="menu-link" href="{{ route('transactions.index_new', ['details' => $code]) }}">
                                                            {{ $label }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>

                                    </li>

                                    <!-- History -->
                                    <li class="menu-item">
                                        <a class="menu-link text-uppercase" href="{{ route('transactions.history') }}">
                                            <div>History</div>
                                        </a>
                                    </li>

                                    <!-- Documentation -->
                                    <li class="menu-item">
                                        <a class="menu-link text-uppercase" href="javascript:void(0)">
                                            <div>Documentation</div>
                                        </a>
                                    </li>
                                </ul>


                                <div class="dropdown">
                                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <!-- <img class="avatar" src="{{ asset('assets/img/user.jpg') }}" alt=""> -->
                                        <img class="avatar" src="{{ asset('assets/img/users/user.png') }}" style="height:35px; width: 35px; border-radius: 100%; background-color: #c7c7c7;" alt="">
                                    </a>                            
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown shadow" id="profileDropdown" aria-labelledby="userDropdown" style="padding: 14px; border-top: 1px solid #e1e1e1;">
                                        <span>
                                            <b>{{ auth()->user()->name }}</b>
                                        </span>
                                        <hr style="margin: 8px 0px;" />
                                        <a class="dropdown-item" style="cursor: pointer;" id="navbar-fullscreen">
                                            <i class="ik ik-maximize mr-2"></i>
                                            Switch Screen Mode
                                        </a>
                                        <a class="dropdown-item right-sidebar-toggle" id="theme_customizer" style="cursor: pointer;">
                                            <i class="ik ik-star dropdown-icon"></i>
                                            Theme Customizer
                                        </a>
                                        <a class="dropdown-item" href="{{ route('change.password') }}">
                                            <i class="ik ik-lock dropdown-icon"></i>
                                            Change Password
                                        </a>
                                        <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="ik ik-power dropdown-icon"></i>
                                            Logout
                                        </a>
                                    </div>
                                </div>

                            </nav><!-- #primary-menu end -->

                        </div>
                    </div>
                </div>
                <div class="header-wrap-clone" style="height: 85px;"></div>
            </header><!-- #header end -->

            <!-- Content (Sample navigation only for testing purposes, modify as per required)
  ============================================= -->
            <section id="content" class="bg-transparent d-flex flex-column">

                <!-- This is an example only based the current landing page of WFS modify as per required -->
                <div class="content-body px-2 w-100" id="orem-panel">
                    @yield('content')
                </div>

            </section><!-- #content end -->

        </div><!-- #wrapper end -->

        <!-- Go To Top
 ============================================= -->
        <!-- <div id="gotoTop" class="uil uil-angle-up"></div> -->

        <!-- JavaScripts
 ============================================= -->
        <script src="{{ asset('assets_new/js/plugins.min.js') }}"></script>
        <script src="{{ asset('assets_new/js/functions.bundle.js') }}"></script>

        <!-- Parallax Script
 ============================================= 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/skrollr/0.6.30/skrollr.min.js"></script>
        <script>
            var s = !SEMICOLON.Mobile.any() && skrollr.init({
                forceHeight: false
            });
        </script>
-->
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
        <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}">
        </script>
        <script src="{{ asset('assets/plugins/d3/dist/d3.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/c3/c3.min.js') }}"></script>
        <script src="{{ asset('assets/js/tables.js') }}"></script>
        <script src="{{ asset('assets/js/charts.js') }}"></script>
        <script src="{{ asset('assets/dist/js/theme.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>

        <!--         <script src="{{ asset('assets/js/session-time-out.js') }}"></script>  comment temp -->
        @yield('pagejs')

        @if ($msg = Session::get('successMsg'))
            <script>
                $(document).ready(function() {
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
