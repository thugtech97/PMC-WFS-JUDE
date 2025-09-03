<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>PMC | Workflow</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="icon" href="../favicon.ico" type="image/x-icon" />

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/ionicons/dist/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/icon-kit/dist/css/iconkit.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/dist/css/theme.min.css') }}">
        <script src="{{ asset('assets/src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    </head>
    <style type="text/css">
        .auth-wrapper .lavalite-bg .lavalite-overlay { background: none; }
    </style>
    <body>

        <div class="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100 bg-white">
                    <div class="col-md-6">
                        <div class="authentication-form mx-auto" style="padding: 0;">
                            <div class="w-100 text-center">
                                <img src="{{ asset('assets/img/logo2.png') }}" class="w-50" alt="">
                            </div>
                            <div class="alert alert-danger mb-4 shadow-sm" style="border: 1px solid #721c2442; margin-top: -15px;">
                                <h5 class="block d-flex align-items-center">
                                    <svg aria-hidden="true" class="mr-1 mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <span style="font-size: 16px; font-weight: 800;">Announcement</span>
                                </h5>
                                <p style="text-align: justify;text-justify: inter-word;font-size: 12px;"> If you are an OSTDR user, please take note that all requests from OSTDR requiring your approval will now be displayed in WFS. You can conveniently approve or disapprove them in WFS, and the changes will automatically sync back to OSTDR.</p>
                            </div>
                            <p style="display: none;">Happy to see you again!</p>
                            <form autocomplete="off" method="post" action="{{ route('login-attempt') }}">
                            @csrf
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control shadow-sm border-fade" placeholder="Username" required="">
                                    <i class="ik ik-user"></i>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control shadow-sm border-fade" placeholder="*********" required="">
                                    <i class="ik ik-lock"></i>
                                </div>
                                <div class="sign-btn text-center" style="margin-top:15px;">
                                    <button type="submit" class="btn btn-theme w-100 text-center shadow-sm">
                                        <svg style="transform: rotate(45deg) translate(-2px, -3px);" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 18-7 3 7-18 7 18-7-3Zm0 0v-5"/>
                                        </svg>
                                        Sign In
                                    </button>
                                    <p>
                                        <br />
                                        <br />
                                        <img class="" height="20" src="{{ asset('assets/img/pmc-logo-solo.png') }}">
                                        <b>Philsaga Mining Corporation</b>
                                    </p>
                                </div>
                                <div class="register">
                                    
                                </div>
                            </form>
                            
                        </div>
                    </div>
                    <div class="col-md-6 p-0 d-md-block d-lg-block d-sm-none d-none">
                        <div class="lavalite-bg" style="background-image: url('{{asset('assets/img/working.svg')}}'); background-repeat: no-repeat; background-size: auto 94%;">
                            <div class="lavalite-overlay"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/dist/js/theme.js') }}"></script>
    </body>
</html>