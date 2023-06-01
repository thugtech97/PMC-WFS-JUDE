@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="ik ik-file-text bg-blue"></i>
                <div class="d-inline">
                    <h5>Pending Transactions</h5>
                    <span>transactions that is subject for approval.</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <nav class="breadcrumb-container" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Approval Requests</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Pendings</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="separator mb-10"></div>

        <div class="row layout-wrap" id="layout-wrap">
            @forelse($transactions as $t)
                <div class="col-12 list-item">
                    <div class="card d-flex flex-row mb-3">
                        <div class="d-flex flex-grow-1 min-width-zero card-content">
                            <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                                
                                <p class="mb-1 text-muted text-small category w-40 w-xs-100">{{ $t->requestor }}<br>{{$t->department}}</p>
                                
                                <p class="mb-1 text-muted text-small category w-15 w-xs-100">{{ $t->ref_req_no }}</p>
                                <p class="mb-1 text-muted text-small category w-15 w-xs-100">{{ $t->details }}<br><small>{{ $t->source_app }}</small></p>
                                <p class="mb-1 text-muted text-small date w-15 w-xs-100">{{ \App\Transaction::date_format($t->created_at) }}</p>
                                <div class="w-15 w-xs-100">
                                    <span class="badge badge-pill badge-secondary">{{ $t->status }}</span>
                                </div>
                            </div>
                            <div class="list-actions">
                                <a href="javascript:;" onclick="view_request('{{$t->id}}');"><i class="ik ik-eye"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty

            @endforelse
        </div>
    </div>
</div>

<div class="modal fade edit-layout-modal" id="editLayoutItem" tabindex="-1" role="dialog" aria-labelledby="editLayoutItemLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" id="transaction_details">
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>    
    <script src="{{ asset('assets/plugins/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        

        function view_request(id){
            $.ajax({
                type: "GET",
                url: "/get-request-details/"+id,
                success: function( response ) {
                    $('#editLayoutItem').modal('show');
                    $('#transaction_details').html(response);
                }
            });
        }

    // approve request
        function approve(tid,uid,status,trans_type,approver_name,designation,last_approver){
            var remarks = $('#remarks').val();
            var overall_status = '';
            var bg = "";

            if(status == 'HOLD' || status == 'CANCELLED'){

                overall_status = status;

            } else {

                if(uid == last_approver){
                    overall_status = status;
                } else {
                    overall_status = 'IN-PROGRESS';
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
                if(willApprove){

                    $.ajax({
                        type: 'POST',
                        url: '/updateStatus',
                        data: { 
                                rid : tid,
                                uid : uid,
                                rstatus : status,
                                remarks : remarks 
                            },
                        success: function( response ) {

                            $.ajax({
                                url: "http://172.16.20.28/WFH-OREM/api/workflow/receiver.php",
                                type: "POST",
                                data: { 
                                        trans_id : tid,
                                        workflow_token : 'base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs=',
                                        details : trans_type,
                                        approver : approver_name,
                                        current_approver : designation,
                                        approver_remarks : remarks,
                                        status : status,
                                        overallstatus : overall_status 
                                    },
                                success: function( response ) {
                                    $('#app'+uid).removeClass('bg-secondary');
                                    $('#app'+uid).addClass('bg-success'); 
                                    $('#ticon'+uid).removeClass('ik-info');
                                    $('#ticon'+uid).addClass('ik-check-circle'); 

                                    $('#app'+uid+'_status').html('APPROVED');
                                    $('#approverBtn').hide();
                                    swal({
                                        title: "Success",
                                        text: "Transaction has been approved!",
                                        icon: "success",
                                    });
                                },
                            }); 

                        },
                    });    
                }
                
            });
        }
    //

    // hold request
        function hold(tid,uid,status,trans_type,approver_name,designation,last_approver){
            var remarks = $('#remarks').val();
            var overall_status = '';
            var bg = "";

            if(status == 'HOLD' || status == 'CANCELLED'){

                overall_status = status;

            } else {

                if(uid == last_approver){
                    overall_status = status;
                } else {
                    overall_status = 'IN-PROGRESS';
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
                if(willHold){

                    $.ajax({
                        type: 'POST',
                        url: '/updateStatus',
                        data: { 
                                rid : tid,
                                uid : uid,
                                rstatus : status,
                                remarks : remarks 
                            },
                        success: function( response ) {

                            $.ajax({
                                url: "http://172.16.20.28/WFH-OREM/api/workflow/receiver.php",
                                type: "POST",
                                data: { 
                                        trans_id : tid,
                                        workflow_token : 'base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs=',
                                        details : trans_type,
                                        approver : approver_name,
                                        current_approver : designation,
                                        approver_remarks : remarks,
                                        status : status,
                                        overallstatus : overall_status 
                                    },
                                success: function( response ) {
                                    $('#app'+uid).removeClass('bg-secondary');
                                    $('#app'+uid).addClass('bg-warning'); 
                                    $('#ticon'+uid).removeClass('ik-info');
                                    $('#ticon'+uid).addClass('ik-alert-triangle'); 

                                    $('#app'+uid+'_status').html('Hold');
                                    $('#approverBtn').hide();
                                    swal({
                                        title: "Success",
                                        text: "Transaction has been hold!",
                                        icon: "success",
                                    });
                                },
                            }); 

                        },
                    });    
                }
                
            });
        }
    //

    // cancel request
        function cancel(tid,uid,status,trans_type,approver_name,designation,last_approver){
            var remarks = $('#remarks').val();
            var overall_status = '';
            var bg = "";

            if(status == 'HOLD' || status == 'CANCELLED'){

                overall_status = status;

            } else {

                if(uid == last_approver){
                    overall_status = status;
                } else {
                    overall_status = 'IN-PROGRESS';
                }

            }

            swal({
                title: "Are you sure?",
                text: "Do you really want to cancel this transaction?",
                icon: "warning",
                buttons: ["Cancel", "Yes Cancel!"],
                dangerMode: true,
            })
            .then((willHold) => {
                if(willHold){

                    $.ajax({
                        type: 'POST',
                        url: '/updateStatus',
                        data: { 
                                rid : tid,
                                uid : uid,
                                rstatus : status,
                                remarks : remarks 
                            },
                        success: function( response ) {

                            $.ajax({
                                url: "http://172.16.20.28/WFH-OREM/api/workflow/receiver.php",
                                type: "POST",
                                data: { 
                                        trans_id : tid,
                                        workflow_token : 'base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs=',
                                        details : trans_type,
                                        approver : approver_name,
                                        current_approver : designation,
                                        approver_remarks : remarks,
                                        status : status,
                                        overallstatus : overall_status 
                                    },
                                success: function( response ) {
                                    $('#app'+uid).removeClass('bg-secondary');
                                    $('#app'+uid).addClass('bg-danger'); 
                                    $('#ticon'+uid).removeClass('ik-info');
                                    $('#ticon'+uid).addClass('ik-bell-off'); 

                                    $('#app'+uid+'_status').html('Cancelled');
                                    $('#approverBtn').hide();
                                    swal({
                                        title: "Success",
                                        text: "Transaction has been cancelled!",
                                        icon: "success",
                                    });
                                },
                            }); 

                        },
                    });    
                }
                
            });
        }
    //
    </script>
@endsection
