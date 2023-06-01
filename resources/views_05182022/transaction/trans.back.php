@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    
    <div class="page-header">
        
        <div class="row align-items-end">
            
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-file-text bg-blue"></i>
                    <div class="d-inline">
                        <h5>Transactions</h5>
                        <span>below are the list of pending, in-progress, approved, hold and cancelled transactions.</span>
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

            <table class="table table-stripped" id="data_table" style="width:100%">
                
                <thead>
                    <th>Transaction ID</th>
                    <th>Requestor</th>
                    <th>Date Requested</th>
                    <th>Details</th>
                    <th>Approval Summary</th>
                    <th>Overall Status</th>
                    <th>Action</th>
                </thead>

                <tbody>
                    @foreach($transactions as $t)
                        <tr>
                            <td>
                                @if( $t->details == 'Cash Advance' )
                                    CA-{{ sprintf('%06d',$t->ref_req_no) }}
                                @elseif( $t->details == 'Request for Payment' )
                                    RFP-{{ sprintf('%06d',$t->ref_req_no) }}
                                @elseif( $t->details == 'Liquidation' )
                                    L-{{ sprintf('%06d',$t->ref_req_no) }}
                                @elseif( $t->details == 'Travel Order' )
                                    TO-{{ sprintf('%06d',$t->ref_req_no) }}
                                @endif 
                            </td>
                            <td>{{ $t->requestor }}<br><small>ICT\Communications</small></td>
                            <td>{{ $t->created_at }}</td> 
                            
                            <td>{{ $t->details }}</td>
                            <td>
                                @foreach($t->approvers as $a)
                                    <a href="#!"><i class="fa fa-user f-12 @if($a->status == 'CANCELLED') text-red @elseif($a->status == 'APPROVED') text-green @elseif($a->status == 'HOLD') text-orange @else text-default @endif"></i></a>
                                @endforeach
                            </td>
                            <td><label class="badge badge-secondary">{{ $t->status }}</label></td>
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

@endsection
 
@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection








@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="ik ik-file-text bg-blue"></i>
                <div class="d-inline">
                    <h5>Transactions</h5>
                    <span>below are the list of pending, in-progress, approved, hold and cancelled transactions.</span>
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

<div class="col-md-12 list-item">
    <div class="card table-card">
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
        <div class="card-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Requestor</th>
                            <th>Date Requested</th>
                            <th>Details</th>
                            <th>Approval Summary</th>
                            <th>Overall Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @forelse($transactions as $t)
                                <tr>
                                    <td>{{ $t->requestor }}<br><small>ICT\Communications</small></td>
                                    <td>{{ $t->created_at }}</td>
                                    
                                    <td>{{ $t->details }}</td>
                                    <td>
                                        @foreach($t->approvers as $a)
                                            <a href="#!"><i class="fa fa-user f-12 @if($a->status == 'CANCELLED') text-red @elseif($a->status == 'APPROVED') text-green @elseif($a->status == 'HOLD') text-orange @else text-default @endif"></i></a>
                                        @endforeach
                                    </td>
                                    <td><label class="badge badge-secondary">{{ $t->status }}</label></td>
                                    <td>
                                        <div class="list-actions">
                                            <a href="{{ route('transaction.details',$t->id) }}"><i class="ik ik-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7"><center>No transactions found!</center></td></tr>
                            @endforelse
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row card-footer clearfix">
                <div class="col-md-6">
                    <p>Showing {{$transactions->firstItem()}} to {{$transactions->lastItem()}} of {{$transactions->total()}} items</p>
                </div>
                <div class="col-md-6">
                    <nav class="float-md-right">
                        <ul class="pagination mb-0">
                            {{ $transactions->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
