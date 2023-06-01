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

    <body>

        <div class="row">
            <div class="col-lg-12 col-md-7">
                <div class="card">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane show active" id="last-month" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="card-body">
                                <h4 class="mt-10">Type : {{ $data->details}}</h4>
                                <div class="row mt-20">
                                    <div class="col-md-3 col-6"> <strong>Requestor</strong>
                                        <br>
                                        <p class="text-muted">{{$data->requestor}}</p>
                                    </div>
                                    <div class="col-md-3 col-6"> <strong>Email</strong>
                                        <br>
                                        <p class="text-muted">{{ $data->email }}</p>
                                    </div>
                                    <div class="col-md-3 col-6"> <strong>Date Submitted</strong>
                                        <br>
                                        <p class="text-muted">{{ $data->created_at }}</p>
                                    </div>
                                    <div class="col-md-3 col-6"> <strong>Status</strong>
                                        <br>
                                        <p class="text-muted">{{$data->status}}</p>
                                    </div>
                                </div>
                                
                                <hr>
                                <h5 class="mt-30">Approvers</h5>
                                <div class="row clearfix">
                                    @foreach($data->approvers as $a)
                                        <div class="col-xl-3 col-md-6">
                                            <div class="card ticket-card">
                                                <div class="card-body @if(\App\ApprovalStatus::current_approver($data->id) == $a->approver_id) bg-default @endif">
                                                    <p id="app{{$a->approver_id}}" class="mb-30 @if($a->status == 'APPROVED') bg-success @elseif($a->status == 'HOLD') bg-warning @elseif($a->status == 'CANCELLED') bg-danger @else bg-secondary @endif lbl-card"><i id="ticon{{$a->approver_id}}" class="ik @if($a->status == 'APPROVED') ik-check-circle @elseif($a->status == 'HOLD') ik-alert-triangle @elseif($a->status == 'CANCELLED') ik-bell-off @else ik-info @endif "></i> <span id="app{{$a->approver_id}}_status">{{ $a->status }}</span></p>

                                                    @php $approver_d = \App\User::find($a->approver_id); @endphp

                                                    <div class="text-center">
                                                        <img src="{{ asset('assets/img/users/user.png') }}" style="height: 70px;" alt="">
                                                        <br>
                                                        @if($a->approver_id == env('VERIFIER_ID'))
                                                        <p class="mb-0 d-inline-block">{{ $a->updated_last_by ? strtoupper($a->updated_last_by):''}}</p>
                                                        <br>
                                                        @endif                                                        
                                                        <p class="mb-0 d-inline-block">[{{ $a->sequence_number }}] {{ $a->user->name }}</p>
                                                        <br>
                                                        <p class="mb-5 d-inline-block text-uppercase"><small>{{ $a->user->designation }}</small></p>
                                                    </div> 
                                                    
                                                    <p class="mt-10"><small>Date Responded : {{ \App\Transaction::date_format($a->updated_at) }}</small><br>
                                                    <small>Response Aging : {{ \App\ApprovalStatus::no_of_days_of_responding($data->id,$a->approver_id) }}</small></p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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



