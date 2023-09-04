@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/css/dialog/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dialog/dialog.css') }}">
@endsection

@section('content')
<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="ik ik-file-text bg-blue"></i>
                <div class="d-inline">
                    <h5>Transaction Details</h5>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <nav class="breadcrumb-container" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Transactions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-7">
        <div class="card">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane show active" id="last-month" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="card-body">
                        <h4 class="mt-10">Type : {{ $data->details}}</h4>
                        <div class="form-group">
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
                            <div class="row mt-20">
                                <div class="col-md-3 col-6"> <strong>Transaction #</strong>
                                    <br>
                                    <p class="text-muted">{{$data->transid}}</p>
                                </div>
                                <div class="col-md-3 col-6"> <strong>Amount</strong>
                                    <br>
                                    <p class="text-muted">{{ number_format($data->totalamount,2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">To view the whole transaction details. Please click this <a href="{{$data->source_url}}" target="_blank" class="text-blue"><strong>link</strong></a>.</div>
                        <hr>
                        <h5 class="mt-30">Approvers</h5> {{-- 1= {{$approverstatus->status ?? 'NONE'}} - 2= {{$data->status ?? 'NONE'}} - 3= {{$previous->status ?? 'NONE'}} --}}
                        <div class="row clearfix">
                            @foreach($data->approvers as $a)
                            <div class="col-xl-3 col-md-6">
                                <div class="card ticket-card">
                                    <div class="card-body @if($data->status == 'CANCELLED' || $data->status == 'HOLD') @else(\App\ApprovalStatus::current_approver($data->id) == $a->approver_id) bg-default @endif">
                                        <p id="app{{$a->approver_id}}" class="mb-30 @if($a->status == 'APPROVED') bg-success @elseif($a->status == 'HOLD') bg-warning @elseif($a->status == 'CANCELLED') bg-danger @else bg-secondary @endif lbl-card"><i id="ticon{{$a->approver_id}}" class="ik @if($a->status == 'APPROVED') ik-check-circle @elseif($a->status == 'HOLD') ik-alert-triangle @elseif($a->status == 'CANCELLED') ik-bell-off @else ik-info @endif "></i> <span id="app{{$a->approver_id}}_status">{{ $a->status }}</span></p>

                                        <div class="text-center">
                                            <img src="{{ asset('assets/img/users/user.png') }}" style="height: 70px;" alt="">
                                            <br>
                                            @if($a->approver_id == env('VERIFIER_ID'))
                                            <p class="mb-0 d-inline-block">{{ $a->updated_last_by ? strtoupper($a->updated_last_by):''}}</p>
                                            <br>
                                            @else
                                            @if( $a->is_alternate )
                                            <p class="mb-0 d-inline-block">{{ strtoupper($a->updated_last_by) }} (ALT APPROVER)</p>
                                            <br>
                                            @endif
                                            @endif
                                            <p class="mb-0 d-inline-block">{{ strtoupper($a->user->name) }}</p>
                                            <br>
                                            <p class="mb-5 d-inline-block text-uppercase"><small>{{ $a->user->designation }}</small></p>
                                        </div>

                                        <p class="mt-10"><small>Date Responded : {{ \App\Transaction::date_format($a->updated_at) }}</small><br>
                                            <small>Response Aging : {{ \App\ApprovalStatus::no_of_days_of_responding($data->id,$a->approver_id) }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr>
                        @php $nxtapprover = \App\ApprovalStatus::next_approver($data->id,$approverstatus->sequence_number); @endphp

                        @if($previous)

                        @if($approverstatus->status == 'PENDING' && $data->status =='CANCELLED' && $previous->status=='APPROVED')

                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Old Remarks</label>
                                                <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}' , '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>

                                                @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                                @endif

                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($approverstatus->status == 'HOLD' && $data->status =='HOLD' && $previous->status=='APPROVED')
                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Old Remarks</label>
                                                <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                                 @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                                @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($approverstatus->status == 'HOLD' && $data->status =='HOLD' && $previous->status=='HOLD')
                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Old Remarks</label>
                                                <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                                 @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                                @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($approverstatus->status == 'PENDING' && $data->status =='IN-PROGRESS' && $previous->status=='APPROVED')
                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="exampleTextarea1">Old Remarks</label>
                                                    <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                                </div>
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                                 @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                                @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($approverstatus->status == 'HOLD' && $data->status =='HOLD' && $previous->status=='APPROVED')
                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="exampleTextarea1">Old Remarks</label>
                                                    <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                                </div>
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                             @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                            @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($approverstatus->status == 'PENDING' && $data->status =='APPROVED' && $previous->status=='APPROVED')
                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="exampleTextarea1">Old Remarks</label>
                                                    <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                                </div>
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                             @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                            @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($approverstatus->status == 'HOLD' && $data->status =='APPROVED' && $previous->status=='APPROVED')
                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Old Remarks</label>
                                                <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                                 @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                                @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($approverstatus->status == 'HOLD' && $data->status =='IN-PROGRESS' && $previous->status=='HOLD')
                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Old Remarks</label>
                                                <textarea class="form-control" id="oldremarks" rows="4">{{ $approverstatus->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                                 @if ($data->details!='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                                @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @else

                        @if($currentuser && ( $currentuser->status=='PENDING' || $currentuser->status=='HOLD') )

                        <div class="row" id="approverBtn">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">

                                            <div class="form-group" @if($currentuser->status=='PENDING') style="display: none" @endif>
                                                <label for="exampleTextarea1">Old Remarks</label>
                                                <textarea class="form-control" id="oldremarks" rows="4" readonly>{{ $approverstatus->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Remarks</label>
                                                <textarea class="form-control" id="remarks" rows="4"></textarea>
                                                <input type="hidden" id="position" value="{{ Auth::user()->position }}">
                                            </div>
                                            <div class="float-md-right">
                                                <button type="button" class="btn btn-primary mr-2" onclick="approve('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','APPROVED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Approve</button>
                                                 @if ($data->details=='OSTR')
                                                <button type="button" class="btn btn-warning mr-2" onclick="hold('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','HOLD','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Hold</button>
                                                @endif
                                                <button type="button" class="btn btn-danger mr-2" onclick="cancel('{{$data->ref_req_no}}','{{$data->id}}','{{auth()->user()->id}}','CANCELLED','{{$data->details}}','{{auth()->user()->username}}','{{auth()->user()->designation}}','{{$lastapprover}}','{{ $nxtapprover }}', '{{$last_app_seq->sequence_number}}', '{{$curr_seq->sequence_number}}');">Disapprove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                    <div class="alert alert-danger">View full history please click this <a data-toggle="modal" href="#approvalhistory{{ $data->id }}" class="text-blue"><strong>link</strong></a>.</div>
                    <div class="modal fade" id="approvalhistory{{ $data->id }}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #F3E0DC ">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h5 class="modal-title" style="color: #5C2018"><span>{{$data->transid }} </span> {{-- <i class="fa fa-times"></i> --}} </h5>

                                </div>
                                <div class="modal-body" style="height: 450px">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <td style="color: black; font-weight: bold; font-size: 15px; text-align: center;">Date and Time of Approval</td>
                                                <td style="color: black; font-weight: bold; font-size: 15px; text-align: center;">Update By</td>
                                                <td style="color: black; font-weight: bold; font-size: 15px; text-align: center;">Action</td>
                                                <td style="color: black; font-weight: bold; font-size: 15px; text-align: center;">Remarks</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($historicalremarks as $hr)
                                            <tr>
                                                <td style="color: black; font-size: 12px; text-align: center;">{{ $hr->updated_at }}</td>
                                                <td style="color: black; font-size: 12px; text-align: center;">{{ $hr->updated_last_by }}</td>
                                                <td style="color: black; font-size: 12px; text-align: center;">{{ $hr->status }}</td>
                                                <td style="color: black; font-size: 12px; text-align: center;">{{ $hr->remarks }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="14">
                                                    <center> No Record Found </center>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="{{ asset('assets/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/plugins/summernote/dist/summernote-bs4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });


    function view_request(id) {
        $.ajax({
            type: "GET",
            url: "/get-request-details/" + id,
            success: function(response) {
                $('#editLayoutItem').modal('show');
                $('#transaction_details').html(response);
            }
        });
    }

    // approve request
    function approve(rqid, tid, uid, status, trans_type, approver_name, designation, last_approver, nxtapprover, last_seq, curr_seq) {
        var remarks = $('#remarks').val() + ' by: ' + $('#position').val();
        var overall_status = '';
        var wfs_trans_stat = '';
        var bg = "";

        if (status == 'HOLD' || status == 'CANCELLED') {

            overall_status = status;
            wfs_trans_stat = status;

        } else {

            if (uid == last_approver && last_seq == curr_seq) {
                if (status = 'APPROVED') {
                    overall_status = 'FULLY APPROVED';
                    wfs_trans_stat = 'FULLY APPROVED';
                    nxtapprover = 'verifier';
                } else {
                    overall_status = status;
                    wfs_trans_stat = status;
                }

            } else {
                overall_status = 'PARTIALLY APPROVED';
                wfs_trans_stat = 'IN-PROGRESS';
            }
        }

        swal({
                title: "Are you sure?",
                text: "Do you really want to approve this transaction?",
                icon: "warning",
                buttons: ["Cancel", "Yes Approve!"],
                dangerMode: false,
            })
            .then((willApprove) => {
                if (willApprove) {

                    swal({
                        title: 'Please wait..!',
                        text: 'Updating approval status is in progress..',
                        button: false,
                        closeOnEsc: false,
                        closeOnClickOutside: false,
                        allowOutsideClick: false,
                        allowEnterKey: false,
                    })
                    if (trans_type == 'OREM REQUEST FOR PAYMENT') {
                        $.ajax({
                            url: "{{env('APP_API_OREM')}}/receiver.php",
                            // url: "http://localhost:8081/in-progress/03-30-2021/WFH-OREM/api/workflow/receiver.php",
                            type: "POST",
                            data: {
                                trans_id: rqid,
                                workflow_token: 'base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs=',
                                details: trans_type,
                                approver: approver_name,
                                current_approver: designation,
                                approver_remarks: remarks,
                                status: status,
                                overallstatus: overall_status,
                                nextapprover: nxtapprover
                            },

                            success: function(response) {
                                $.ajax({
                                    type: 'POST',
                                    url: '/updateStatus',
                                    data: {
                                        rid: tid,
                                        uid: uid,
                                        rstatus: status,
                                        remarks: remarks,
                                        ov_stat: wfs_trans_stat,
                                        curr_seq: curr_seq
                                    },

                                    success: function(response) {

                                        $('#app' + uid).removeClass('bg-secondary');
                                        $('#app' + uid).addClass('bg-success');
                                        $('#ticon' + uid).removeClass('ik-info');
                                        $('#ticon' + uid).addClass('ik-check-circle');

                                        $('#app' + uid + '_status').html('APPROVED');
                                        $('#approverBtn').hide();
                                        swal({
                                            title: "Success",
                                            text: "Transaction has been approved!",
                                            icon: "success",
                                            closeOnEsc: false,
                                            closeOnClickOutside: false,
                                            allowOutsideClick: false,
                                            allowEnterKey: false,
                                        }).then(function() {
                                            window.location.reload();
                                        });
                                    },
                                });
                            },
                            error: function(response) {
                                swal("Connection from other application was interrupted!");
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: "{{env('APP_URL')}}/updateStatus",
                            data: {
                                rid: tid,
                                uid: uid,
                                rstatus: status,
                                remarks: remarks,
                                ov_stat: wfs_trans_stat,
                                curr_seq: curr_seq
                            },

                            success: function(response) {

                                $('#app' + uid).removeClass('bg-secondary');
                                $('#app' + uid).addClass('bg-success');
                                $('#ticon' + uid).removeClass('ik-info');
                                $('#ticon' + uid).addClass('ik-check-circle');

                                $('#app' + uid + '_status').html('APPROVED');
                                $('#approverBtn').hide();
                                swal({
                                    title: "Success",
                                    text: "Transaction has been approved!",
                                    icon: "success",
                                    closeOnEsc: false,
                                    closeOnClickOutside: false,
                                    allowOutsideClick: false,
                                    allowEnterKey: false,
                                }).then(function() {
                                    window.location.reload();
                                });
                            },
                            error: function(response) {
                                swal("Failed to update status!");
                            }
                        });
                    }
                }
            });
    }
    //

    // hold request
    function hold(rqid, tid, uid, status, trans_type, approver_name, designation, last_approver, nxtapprover, last_seq, curr_seq) {
        var remarks = $('#remarks').val() + ' by: ' + $('#position').val();
        var overall_status = '';
        var wfs_trans_stat = '';
        var bg = "";

        if (status == 'HOLD' || status == 'CANCELLED') {

            overall_status = status;
            wfs_trans_stat = status;

        } else {

            if (uid == last_approver && last_seq == curr_seq) {
                if (status = 'APPROVED') {
                    overall_status = 'FULLY APPROVED';
                    wfs_trans_stat = 'FULLY APPROVED';
                    nxtapprover = 'verifier';
                } else {
                    overall_status = status;
                    wfs_trans_stat = status;
                }

            } else {
                overall_status = 'PARTIALLY APPROVED';
                wfs_trans_stat = 'IN-PROGRESS';
            }
        }

        swal({
                title: "Are you sure?",
                text: "Do you really want to hold this transaction?",
                icon: "warning",
                buttons: ["Cancel", "Yes Hold!"],
                dangerMode: true,
            })
            .then((willHold) => {
                if (willHold) {

                    swal({
                        title: 'Please wait..!',
                        text: 'Updating approval status is in progress..',
                        button: false,
                        closeOnEsc: false,
                        closeOnClickOutside: false,
                        allowOutsideClick: false,
                        allowEnterKey: false,
                    })
                    if (trans_type == 'OREM REQUEST FOR PAYMENT') {

                        $.ajax({
                            url: "{{env('APP_API_OREM')}}/receiver.php",
                            // url: "http://localhost:8081/in-progress/03-30-2021/WFH-OREM/api/workflow/receiver.php",
                            type: "POST",
                            data: {
                                trans_id: rqid,
                                workflow_token: 'base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs=',
                                details: trans_type,
                                approver: approver_name,
                                current_approver: designation,
                                approver_remarks: remarks,
                                status: status,
                                overallstatus: overall_status,
                                nextapprover: nxtapprover
                            },
                            success: function(response) {

                                $.ajax({
                                    type: 'POST',
                                    url: '/updateStatus',
                                    data: {
                                        rid: tid,
                                        uid: uid,
                                        rstatus: status,
                                        remarks: remarks,
                                        ov_stat: wfs_trans_stat,
                                        curr_seq: curr_seq
                                    },
                                    success: function(response) {
                                        $('#app' + uid).removeClass('bg-secondary');
                                        $('#app' + uid).addClass('bg-warning');
                                        $('#ticon' + uid).removeClass('ik-info');
                                        $('#ticon' + uid).addClass('ik-alert-triangle');

                                        $('#app' + uid + '_status').html('Hold');
                                        $('#approverBtn').hide();
                                        swal({
                                            title: "Success",
                                            text: "Transaction has been hold!",
                                            icon: "success",
                                            closeOnEsc: false,
                                            closeOnClickOutside: false,
                                            allowOutsideClick: false,
                                            allowEnterKey: false,
                                        }).then(function() {
                                            window.location.reload();
                                        });
                                    },
                                });
                            },
                            error: function(response) {
                                swal("Connection from other application was interrupted!");
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: "{{env('APP_URL')}}/updateStatus",
                            data: {
                                rid: tid,
                                uid: uid,
                                rstatus: status,
                                remarks: remarks,
                                ov_stat: wfs_trans_stat,
                                curr_seq: curr_seq
                            },

                            success: function(response) {
                                $('#app' + uid).removeClass('bg-secondary');
                                $('#app' + uid).addClass('bg-warning');
                                $('#ticon' + uid).removeClass('ik-info');
                                $('#ticon' + uid).addClass('ik-alert-triangle');

                                $('#app' + uid + '_status').html('Hold');
                                $('#approverBtn').hide();
                                swal({
                                    title: "Success",
                                    text: "Transaction has been hold!",
                                    icon: "success",
                                    closeOnEsc: false,
                                    closeOnClickOutside: false,
                                    allowOutsideClick: false,
                                    allowEnterKey: false,
                                }).then(function() {
                                    window.location.reload();
                                });
                            },
                            error: function(response) {
                                swal("Failed to update status!");
                            }
                        });
                    }
                }

            });
    }
    //

    // cancel request
    function cancel(rqid, tid, uid, status, trans_type, approver_name, designation, last_approver, nxtapprover, last_seq, curr_seq) {
        var remarks = $('#remarks').val() + ' by: ' + $('#position').val();
        var overall_status = '';
        var wfs_trans_stat = '';
        var bg = "";

        if (status == 'HOLD' || status == 'CANCELLED') {

            overall_status = status;
            wfs_trans_stat = status;

        } else {

            if (uid == last_approver && last_seq == curr_seq) {
                if (status = 'APPROVED') {
                    overall_status = 'FULLY APPROVED';
                    wfs_trans_stat = 'FULLY APPROVED';
                    nxtapprover = 'verifier';
                } else {
                    overall_status = status;
                    wfs_trans_stat = status;
                }

            } else {
                overall_status = 'PARTIALLY APPROVED';
                wfs_trans_stat = 'IN-PROGRESS';
            }
        }

        swal({
                // title: "Are you sure?",
                title: "Disapprove transaction?",
                // text: "Do you really want to cancel this transaction?",
                text: "Do you really want to disapprove this transaction?",
                icon: "warning",
                // buttons: ["Cancel", "Yes Cancel!"],
                buttons: ["No", "Yes"],
                dangerMode: true,
            })
            .then((willCancel) => {
                if (willCancel) {

                    swal({
                        title: 'Please wait..!',
                        text: 'Updating approval status is in progress..',
                        button: false,
                        closeOnEsc: false,
                        closeOnClickOutside: false,
                        allowOutsideClick: false,
                        allowEnterKey: false,
                    })
                    if (trans_type == 'OREM REQUEST FOR PAYMENT') {

                        $.ajax({
                            url: "{{env('APP_API_OREM')}}/receiver.php",
                            // url: "http://localhost:8081/in-progress/03-30-2021/WFH-OREM/api/workflow/receiver.php",
                            type: "POST",
                            data: {
                                trans_id: rqid,
                                workflow_token: 'base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs=',
                                details: trans_type,
                                approver: approver_name,
                                current_approver: designation,
                                approver_remarks: remarks,
                                status: status,
                                overallstatus: overall_status,
                                nextapprover: nxtapprover
                            },

                            success: function(response) {

                                $.ajax({
                                    type: 'POST',
                                    url: '/updateStatus',
                                    data: {
                                        rid: tid,
                                        uid: uid,
                                        rstatus: status,
                                        remarks: remarks,
                                        ov_stat: wfs_trans_stat,
                                        curr_seq: curr_seq
                                    },

                                    success: function(response) {

                                        $('#app' + uid).removeClass('bg-secondary');
                                        $('#app' + uid).addClass('bg-danger');
                                        $('#ticon' + uid).removeClass('ik-info');
                                        $('#ticon' + uid).addClass('ik-bell-off');

                                        $('#app' + uid + '_status').html('Cancelled');
                                        $('#approverBtn').hide();
                                        swal({
                                            title: "Success",
                                            text: "Transaction has been cancelled!",
                                            icon: "success",
                                            closeOnEsc: false,
                                            closeOnClickOutside: false,
                                            allowOutsideClick: false,
                                            allowEnterKey: false,
                                        }).then(function() {
                                            window.location.reload();
                                        });
                                    },
                                });
                            },
                            error: function(response) {
                                swal("Connection from other application was interrupted!");
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: "{{env('APP_URL')}}/updateStatus",
                            data: {
                                rid: tid,
                                uid: uid,
                                rstatus: status,
                                remarks: remarks,
                                ov_stat: wfs_trans_stat,
                                curr_seq: curr_seq
                            },

                            success: function(response) {

                                $('#app' + uid).removeClass('bg-secondary');
                                $('#app' + uid).addClass('bg-danger');
                                $('#ticon' + uid).removeClass('ik-info');
                                $('#ticon' + uid).addClass('ik-bell-off');

                                $('#app' + uid + '_status').html('Cancelled');
                                $('#approverBtn').hide();
                                swal({
                                    title: "Success",
                                    text: "Transaction has been cancelled!",
                                    icon: "success",
                                    closeOnEsc: false,
                                    closeOnClickOutside: false,
                                    allowOutsideClick: false,
                                    allowEnterKey: false,
                                }).then(function() {
                                    window.location.reload();
                                });
                            },
                            error: function(response) {
                                swal("Failed to update status!");
                            }
                        });
                    }
                }

            });
    }
    //
</script>
@endsection