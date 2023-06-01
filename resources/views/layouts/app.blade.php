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

    <body>

        <div class="wrapper">
            <header class="header-top" header-theme="light">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <div class="top-menu d-flex align-items-center">
                            <button type="button" id="navbar-fullscreen" class="nav-link"><i class="ik ik-maximize"></i></button>
                        </div>
                        <div class="top-menu d-flex align-items-center">
                            {{-- <button type="button" class="nav-link ml-10 right-sidebar-toggle"><i class="ik ik-message-square"></i><span class="badge bg-success">3</span></button> --}}

                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="avatar" src="{{ asset('assets/img/user.jpg') }}" alt=""></a>                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ route('change.password') }}"><i class="ik ik-lock dropdown-icon"></i> Change Password</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"><i class="ik ik-power dropdown-icon"></i> Logout</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </header>

            <div class="page-wrap">
                @include('layouts.sidebar')

                <div class="main-content">
                    @yield('content')
                </div>

                @include('layouts.chat-list')

                @include('layouts.chat-panel')

                <footer class="footer">
                    <div class="w-100 clearfix">
                        <span class="text-center text-sm-left d-md-inline-block">Copyright © {{ date('Y') }} Philsaga Mining Corporation. All Rights Reserved.</span>
                        <span class="float-none float-sm-right mt-1 mt-sm-0 text-center">Crafted with <i class="fa fa-heart text-danger"></i> by <a href="javascript:;" class="text-dark">ICT</a></span>
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
