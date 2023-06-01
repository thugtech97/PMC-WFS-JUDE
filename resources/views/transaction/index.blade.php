@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link href="{{ asset('assets/plugins/bootstrap-toastr/toastr_notification.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #data_table_wrapper { width: 100%; }
    </style>
@endsection

@section('content')
    
    <div class="container-fluid">

        <div class="page-header">
            
            <div class="row align-items-end">
                
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>For Approval</h5>
                            <span>below are the list of pending and on hold transactions.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('transactions.index') }}">Transactions</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Lists</li>
                        </ol>
                    </nav>
                </div>

            </div>

        </div>

        <div class="col-md-12">

        <div class="text-right" id="btnbatchsubmit"  style="display:none;">                                                      
            <button type="submit" class= "btn btn-default btn-primary btn-batch-submit" data-action="APPROVED"> 
                <i class="fa fa-check"> Batch Approval</i></button> 
            <button type="submit" class= "btn btn-default btn-default btn-batch-submit" data-action="HOLD"> 
                <i class="fa fa-hand-paper"> Batch Hold</i></button> 
            <button type="submit" class= "btn btn-default btn-danger btn-batch-submit" data-action="CANCELLED"> 
                <i class="fa fa-trash"> Batch Cancel</i></button> 
        </div>

         <form action="{{ route('transaction.batchsubmit') }}" method="POST" id="batchdata">                      
              {{ csrf_field() }}
            <input type="hidden" name="selected_data" id="selected_data" class="" readonly>        
            <input type="hidden" name="selected_action" id="selected_action" readonly>    
        </form> 
        <br>
        {{-- <div class="card-header row">
                <div class="col col-sm-9">
                    <div class="dropdown d-inline-block">
                        <a class="nav-link dropdown-toggle" href="#" id="moreDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-more-horizontal"></i></a>
                        <div class="dropdown-menu" aria-labelledby="moreDropdown">
                            <a class="dropdown-item" id="checkbox_select_all" href="javascript:void(0);">Filter Options here...</a>
                        </div>
                    </div>
                </div>
                <div class="col col-sm-3">
                    <div class="card-search with-adv-search dropdown">
                        <form action="">
                            <input type="text" class="form-control" placeholder="Search.." required>
                            <button type="submit" class="btn btn-icon"><i class="ik ik-search"></i></button>
                        </form>
                    </div>
                </div>
            </div> --}}           

            <div class="row">

                <table class="table table-stripped" id="data-table" style="width:100%">
                    
                    <thead>
                        <th>Transaction ID</th>
                        <th><input id="checkall" class='table_cb' type="checkbox" ></th>
                        <th>Requestor</th>
                        <th>Date Requested</th>
                        <th>Payee</th>
                        <th>Amount</th>
                        <th>Approval Summary</th>
                        <th>Overall Status</th>
                        <th>Purpose</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        @foreach($transactions as $t)
                            <tr>
                                {{-- <td>
                                    @if( $t->details == 'OREM CASH ADVANCE' )
                                        CA-{{ sprintf('%06d',$t->ref_req_no) }}
                                    @elseif( $t->details == 'OREM REQUEST FOR PAYMENT' )
                                        RFP-{{ sprintf('%06d',$t->ref_req_no) }}
                                    @elseif( $t->details == 'OREM LIQUIDATION' )
                                        LIQ-{{ sprintf('%06d',$t->ref_req_no) }}
                                    @elseif( $t->details == 'OREM TRAVEL ORDER' )
                                        TO-{{ sprintf('%06d',$t->ref_req_no) }}
                                    @endif 
                                </td> --}}
                                <td>{{ $t->transid }} <br> <span style="font-weight: bold; font-size: 11px"> @if(empty($t->locsite)) (Agusan) @elseif(!empty($t->locsite)) (Davao) @endif </span> </td>

                                @php
                                    $next_approver = \App\ApprovalStatus::where('transaction_id', $t->id)
                                        ->where('current_seq', null)->first();

                                    $verifiers = \App\User::where('designation', 'VERIFIER')->get();
                                    $verifier_acc = \App\User::where('designation', 'VERIFIED')->first();

                                    $verifiers_id = $verifiers->pluck('id')->toArray();
                                    array_push($verifiers_id, $verifier_acc->id); 
                                @endphp
                                <td>
                                    @if(auth()->user()->designation == 'VERIFIER')
                                        <input class='checkboxes table_details_cb table_cb' type="checkbox" name="checkboxes[]" value="{{$t->id}}" 
                                            @if(!in_array($next_approver->approver_id, $verifiers_id)) disabled @endif>
                                    @else
                                        <input class='checkboxes table_details_cb table_cb' type="checkbox" name="checkboxes[]" value="{{$t->id}}" 
                                            @if($next_approver && 
                                                ($next_approver->approver_id != auth()->user()->id && $next_approver->alternate_approver_id != auth()->user()->id)) disabled @endif>
                                    @endif
                                </td>
                                <td>{{ $t->requestor }}<br><small>{{ $t->department }}</small></td>
                                <td>{{ $t->created_at }}</td> 
                                
                                <td>{{ $t->name }}</td>
                                <td>{{ $t->currency }} {{ number_format($t->totalamount,2) }} <br> <small>@if($t->totalamount < $t->converted_amount) (PHP {{ number_format($t->converted_amount,2) }}) @endif</small> </td>
                                <td>
                                    @foreach($t->approvers as $a)
                                        <a href="#!"><i class="fa fa-user f-12 @if($a->status == 'CANCELLED') text-red @elseif($a->status == 'APPROVED') text-green @elseif($a->status == 'HOLD') text-orange @else text-default @endif"></i></a> 
                                    @endforeach
                                    &nbsp; {{-- {{ Carbon\Carbon::parse($t->created_at)->addDays(1)->diffForhumans(null,true) }}  @if (Carbon\Carbon::parse($t->created_at)->addDays(1)->diffForhumans(null,true))<i class="fa fa-exclamation text-red"></i> @endif --}}
                                    {{-- {{ \Carbon\Carbon::now()->diffInDays( \Carbon\Carbon::parse($t->created_at),false) }} --}}
                                    @if (\Carbon\Carbon::now()->diffInDays( \Carbon\Carbon::parse($t->created_at),false) <= 2 && $t->status=='PENDING')<i class="fa fa-exclamation text-red"></i> @endif 
                                </td>
                                <td><label class="badge badge-secondary" >{{ $t->status }}</label></td>
                                <td>{{ $t->purpose }}</td>
                                <td>
                                    <div class="list-actions">
                                        <a href="{{ route('transaction.details',$t->id) }}"><i class="ik ik-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        
        </div>

    </div>

@endsection
 
@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-toastr/toastr_notification.min.js') }}" type="text/javascript"></script>
   <!--  <script type="text/javascript">
        $(document).ready(function() {
            $('#data-table').dataTable( {
                 "order": [[ 2, 'desc' ]]
                 });

        } );
    </script> -->
    <script type="text/javascript">
    $(document).ready(function() {
    $("#data-table").dataTable({
          // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},


            buttons: [
                { extend: 'print', className: 'btn dark btn-outline' },
                { extend: 'copy', className: 'btn red btn-outline' },
                { extend: 'pdf', className: 'btn green btn-outline' },
                { extend: 'excel', className: 'btn yellow btn-outline ' },
                { extend: 'csv', className: 'btn purple btn-outline ' },
                { extend: 'colvis', className: 'btn dark btn-outline', text: 'Columns'}
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: true,

            //"ordering": false, disable column ordering 
            //"paging": false, disable pagination

            "order": [
                [2, 'desc']
            ],
            
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
       
});
</script>
<script type="text/javascript"> 
     $(".table_cb").on("change",function (){
        if($(".table_details_cb:checked").length > 0){
            $('#btnbatchsubmit').show();
        }
        else{
            $('#btnbatchsubmit').hide();
        }
    });
    $(document).on('click',"#checkall", function (){

        if ($("#checkall").is(':checked')){
            $(".checkboxes").each(function (){
//                $(this).prop("checked", true);
                $(this).click();
            });
        }else{
            $(".checkboxes").each(function (){
//                $(this).prop("checked", false);
                  $(this).click();
            });
        }
    });
</script>
<script type="text/javascript">

        $(document).on('click','.checkboxes', function(){
            var i = '';
            console.log('aw');
            $(".checkboxes").each(function (){
                $('#selected_data').val('');

                if($(this).is(":checked")){
                    i = i + "|"+ $(this).val();
                }
            });
            $('#selected_data').val(i);
        });

</script>
<script type="text/javascript">
$('.btn-batch-submit').click(function() {
    console.log('clicked');
    $('#selected_action').val($(this).data('action'));
    document.getElementById("batchdata").submit();
});
</script>
<script>
    @if(Session::has('notification'))
        var type = "{{ Session::get('notification.alert-type', 'info') }}";
        switch(type){
            case 'info':
                toastr.info("{{ Session::get('notification.message') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('notification.message') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('notification.message') }}");
                break;
            case 'error':
                toastr.options.closeButton = true;
                toastr.error("{{ Session::get('notification.message') }}");
                break;
        }
    @endif
</script>
@endsection