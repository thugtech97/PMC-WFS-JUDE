<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.1
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
             <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>PMC | Workflow</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="vA7qVQuuPW5uVkXYLBzDxbftnt2GkJD0lMIPnetK">
        
        <!-- <link rel="icon" href="favicon.ico" type="image/x-icon" /> -->

        <link href="{{ url('assets/dist/css/font-face.css') }}" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/icon-kit/dist/css/iconkit.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/ionicons/dist/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/c3/c3.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/dist/css/theme.min.css') }}">
        <script src="{{ url('assets/src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
        <link rel="stylesheet" href="{{ url('assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.css') }}">

            <link rel="stylesheet" href="{{ url('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link href="{{ url('assets/plugins/bootstrap-toastr/toastr_notification.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #data_table_wrapper { width: 100%; }
    </style>

    </head>

<body style="background: #2f373e !important;">

    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <div style="width: 550px; display: block; margin: 100px auto 0; background: #636e77; padding: 30px;">
                  
                    <form method="POST" action="{{route('update.password')}}" role="form">
                    
                        @csrf
                        @method('PATCH')

                         @foreach ($errors->all() as $error)
                            <p class="text-warning">{{ $error }}</p>
                         @endforeach

                         @if(session()->has('error_message'))

                            <p class="text-warning">{{ session()->get('error_message') }}</p>

                         @endif

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"
                                style="color: #ffffff;">Current Password</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"
                                style="color: #ffffff;">New Password</label>

                            <div class="col-md-8">
                                <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"
                                style="color: #ffffff;">New Confirm Password</label>

                            <div class="col-md-8">
                                <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0 text-center">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Update Password
                                </button>
                            </div>
                        </div>

                    </form>

                    <a href="/transactions" class="btn btn-success"> << Back </a>

                </div>

            </div>

        </div>

    </div>


     <script src="{{ url('assets/js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ url('assets/src/js/vendor/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ url('assets/plugins/popper.js') }}/dist/umd/popper.min.js') }}"></script>
        <script src="{{ url('assets/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ url('assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ url('assets/plugins/screenfull/dist/screenfull.js') }}"></script>
        <!-- <script src="{{ url('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script> -->
<!--         <script src="{{ url('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ url('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ url('assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script> -->
        <script src="{{ url('assets/plugins/moment/moment.js') }}"></script>
        <script src="{{ url('assets/plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        <script src="{{ url('assets/plugins/d3/dist/d3.min.js') }}"></script>
        <script src="{{ url('assets/plugins/c3/c3.min.js') }}"></script>
        <script src="{{ url('assets/js/tables.js') }}"></script>
        <script src="{{ url('assets/js/charts.js') }}"></script>
        <script src="{{ url('assets/dist/js/theme.min.js') }}"></script>
        <script src="{{ url('assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>        
    
<!--         <script src="{{ url('assets/js/session-time-out.js') }}"></script>  comment temp --> 
            <script src="{{ url('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-toastr/toastr_notification.min.js') }}" type="text/javascript"></script>

</body>
