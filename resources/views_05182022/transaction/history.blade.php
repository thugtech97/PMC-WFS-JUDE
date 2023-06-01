@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/plugins/daterange/daterangepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/plugins/select2/dist/css/select2.min.css') }}"/>
    <style type="text/css">
        #data_table_wrapper { width: 100%; }
    </style>
@endsection

@section('content')
    
    <div class="container-fluid">
    <form action="{{ route('transactions.history') }}" method="GET">
        @csrf
        <div class="page-header">
            
            <div class="row align-items-end">
                
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>Historical Transactions</h5>
                            <span>below are the list of previous transactions.</span>
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
             <div class="row align-items-end">
                
                <div class="col-lg-2">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>Filter</h5>
                            <span>Department</span>
                        </div>
                    </div>
                </div>              
                 <div class="col-lg-9">
                    &nbsp;<br>
                  <strong>Name:</strong> <select class="js-example-basic-single js-states form-control" data-live-search="true" id="id_label_single" name="rdepartment" style="height: 30px;"> 
                    @if($seldepartment)
                    <option value="{{ $seldepartment }}">{{ $seldepartment }}</option>
                    @endif
                    <option value="">SELECT</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->department}}">{{ $department->department}}</option>
                    @endforeach
                  </select>
                </div> 
            </div> 

             <div class="row align-items-end">
                
                <div class="col-lg-2">
                    <div class="page-header-title">
                        <i class="ik ik-calendar bg-blue"></i>
                        <div class="d-inline">
                            <h5>Filter</h5>
                            <span>Status</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    &nbsp;<br>
                  <strong>Status:</strong> <select class="bs-select form-control" data-live-search="true" id="transstatus" name="transstatus" style="height: 30px;"> 
                    @if($transstatus)
                    <option value="{{ $transstatus }}">{{ $transstatus }}</option>
                    @endif
                    <option value="">SELECT</option>
                    <option value="FULLY APPROVED">FULLY APPROVED</option>
                    <option value="CANCELLED">CANCELLED</option>
                  </select>
                </div> 
                <div class="col-lg-2">
                    <div class="page-header-title">
                        <i class="ik ik-calendar bg-blue"></i>
                        <div class="d-inline">
                            <h5>Search</h5>
                            <span>Date Range</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    &nbsp;<br>
                  <strong>Date:</strong> <input type="text" class="form-control" text-align="center" name="date_filter" id="date_filter"style="height: 30px;" />
                </div>       
                 <div class="col-lg-2">
                    &nbsp;<br>
                  <input type="submit" name="filter_submit" class="btn btn-sm btn-success" value="Filter"/>
                </div>           
            </div>        
        </div>
    </form>
        <div class="col-md-12">

    <!--         <div class="card-header row">
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
            </div> -->
            <div class="row">

                <table class="table table-stripped" id="data-table" style="width:100%">
                    
                    <thead>
                        <th>Transaction ID</th>
                        <th>Requestor</th>
                        <th>Date Requested</th>
                        <th>Source</th>
                         <th>Amount</th>
                        <th>Approval Summary</th>
                        <th>Overall Status</th>
                        <th>Purpose</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        @foreach($histories as $t)
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
                                <td>{{ $t->transid }}</td>
                                <td>{{ $t->requestor }}<br><small>{{ $t->department }}</small></td>
                                <td>{{ $t->created_at }}</td> 
                                
                                <td>{{ $t->details }}</td>
                                <td>{{ $t->currency }} {{ number_format($t->totalamount,2) }} <br> <small>@if($t->totalamount < $t->converted_amount) (PHP {{ number_format($t->converted_amount,2) }}) @endif</small> </td>
                                <td>
                                    @foreach($t->approvers as $a)
                                        <a href="#!"><i class="fa fa-user f-12 @if($a->status == 'CANCELLED') text-red @elseif($a->status == 'APPROVED') text-green @elseif($a->status == 'HOLD') text-orange @else text-default @endif"></i></a> 
                                    @endforeach
                                    &nbsp; {{-- {{ Carbon\Carbon::parse($t->created_at)->addDays(1)->diffForhumans(null,true) }}  @if (Carbon\Carbon::parse($t->created_at)->addDays(1)->diffForhumans(null,true))<i class="fa fa-exclamation text-red"></i> @endif --}}
                                    {{-- {{ \Carbon\Carbon::now()->diffInDays( \Carbon\Carbon::parse($t->created_at),false) }} --}}
                                    @if (\Carbon\Carbon::now()->diffInDays( \Carbon\Carbon::parse($t->created_at),false) <= 2 && $t->status=='PENDING')<i class="fa fa-exclamation text-red"></i> @endif 
                                </td>
                                <td><label class="badge badge-secondary">{{ $t->status }}</label></td>
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
<script type="text/javascript" src="{{ asset('/assets/plugins/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/plugins/daterange/daterangepicker.js') }}"></script>
<script type="text/javascript">
    $(function () {
        let dateInterval = getQueryParameter('date_filter');
        // let start = moment().startOf('isoWeek');
        // let end = moment().endOf('isoWeek');
        let start = moment().startOf('month');
        let end = moment().endOf('today');
        if (dateInterval) {
            dateInterval = dateInterval.split(' - ');
            start = dateInterval[0];
            end = dateInterval[1];
        }
        $('#date_filter').daterangepicker({
            "showDropdowns": true,
            "showWeekNumbers": true,
            "alwaysShowCalendars": true,
            startDate: start,
            endDate: end,
            locale: {
                format: 'YYYY-MM-DD',
                firstDay: 1,
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'All time': [moment().subtract(30, 'year').startOf('month'), moment().endOf('month')],
            }
        });
    });
    function getQueryParameter(name) {
        const url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
</script>    
<script type="text/javascript">
    $(document).ready(function() {
        $('#data-table').dataTable( {
             "order": [[ 2, 'desc' ]]
             });

    } );
</script>
<script>
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
});
</script>
@endsection