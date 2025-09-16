@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link href="{{ asset('assets/plugins/bootstrap-toastr/toastr_notification.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #data_table_wrapper {
            width: 100%;
        }

        .dataTables_length label {
            text-transform: capitalize;
            font-weight: bolder !important;
        }

        .table-container-class .dataTables_length {
            position: absolute;
        }

        .table-container-class #data-table_wrapper .dt-buttons a.dt-button.buttons-collection.buttons-page-length {
            display: none;
        }

        .table-container-class #data-table_wrapper .dt-buttons {
            position: absolute;
            left: 12%;
            top: 28px;
        }

        .table-container-class #data-table_wrapper .dt-buttons a.dt-button.buttons-excel {
            border-radius: 6px;
            padding: 8px 12px 8px 5px;
            color: white;
            font-weight: 600;
        }

        body[data-theme="sky-theme"] .table-container-class #data-table_wrapper .dt-buttons a.dt-button.buttons-excel {
            background-color: #0862f6 !important;
        }

        body[data-theme="earth-theme"] .table-container-class #data-table_wrapper .dt-buttons a.dt-button.buttons-excel {
            background-color: #f38500 !important;
        }

        .dt-buttons a.dt-button.buttons-excel.buttons-html5.btn.btn-primary.text-white {
            display: flex;
            align-items: center;
        }

        .nav-card .badge {
            padding: 6px 8px;
            font-size: 10px;
            top: -8px !important;
        }
    </style>
@endsection

@section('content')
    <div class="top-nav my-2 px-2 mb-5">
        <div class="w-100 d-flex justify-content-start gap-5" style="padding-left: 10px;">

            <!-- Modify each navigation to ROUTING -->
            @php
                $navItems = [
                    'OREM'     => 'OREM',
                    'IMP'      => 'MRS-IMP',
                    'OSTR'     => 'OSTR',
                    'GATEPASS' => 'GATE PASS',
                    'HK'       => 'HOUSEKEEPING',
                    'VBS'      => 'VBS',
                ];

                // Get current user’s allowed types
                $userTransTypes = [];
                if (!empty(Auth::user()->trans_types)) {
                    $userTransTypes = explode('|', Auth::user()->trans_types);
                }
            @endphp

            @foreach($navItems as $code => $label)
                @if(in_array($code, $userTransTypes))
                    <div class="nav-card mx-4 position-relative {{ request('details') === $code ? 'active' : '' }}">
                        <span class="nav-card-icon rounded text-light shadow-sm">
                            <a class="text-white" href="{{ route('transactions.index_new', ['details' => $code]) }}">
                                <i class="bi-briefcase-fill"></i>
                                @if($label == 'OREM')
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 0 0-2 2v4m5-6h8M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m0 0h3a2 2 0 0 1 2 2v4m0 0v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6m18 0s-4 2-9 2-9-2-9-2m9-2h.01"/>
                                    </svg>
                                @elseif($label == 'MRS-IMP')
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                    </svg>
                                @elseif($label == 'OSTR')
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                                    </svg>
                                @else
                                    {{ $pendingAll[$code] ?? 0 }}
                                @endif
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $pendingAll[$code] ?? 0 }}
                                </span>
                            </a>
                        </span>
                        <a href="{{ route('transactions.index_new', ['details' => $code]) }}"
                        class="nav-link px-3 py-2 shadow-sm bg-white rounded main-nav-btn d-flex" style="border: 1px solid #e7e7e7">
                            <b style="color: #434343; display: flex; align-items: center;">&nbsp;&nbsp; {{ $label }}</b>
                        </a>
                    </div>
                @endif
            @endforeach


        </div>
    </div>
    <div class="container-fluid wfs-transactions">

        <div class="page-header mt-4">
            <div class="row align-items-center">
                <div class="col-lg-9 d-flex align-items-center">
                    <!-- Page Header Card -->
                    <div class="card shadow-sm flex-grow-1">
                        <div class="card-body d-flex align-items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="#5E17EB" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z" />
                                </svg>
                            </span>
                            <div>
                                <h5 class="card-title mb-1"><b>{{ request('details') }} Transactions</b></h5>
                                <p class="card-text text-muted mb-0" style="font-size:14px;">
                                    Below are the list of pending and on hold {{ request('details') }} transactions.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-stretch ml-4 w-100">
                        <!-- Pending -->
                        <div class="card shadow-sm flex-grow-1 mr-2">
                            <div class="card-body d-flex align-items-center">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#5E17EB"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" />
                                    </svg>
                                </span>
                                <div>
                                    <h5 class="mb-0"><b style="font-weight:800">{{ $pending }}</b></h5>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming -->
                        <div class="card shadow-sm flex-grow-1 mr-2">
                            <div class="card-body d-flex align-items-center">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#5E17EB"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5 4a2 2 0 0 0-2 2v1h10.968l-1.9-2.28A2 2 0 0 0 10.532 4H5ZM3 19V9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm11.707-7.707a1 1 0 0 0-1.414 1.414l.293.293H8a1 1 0 1 0 0 2h5.586l-.293.293a1 1 0 0 0 1.414 1.414l2-2a1 1 0 0 0 0-1.414l-2-2Z" />
                                    </svg>
                                </span>
                                <div>
                                    <h5 class="mb-0"><b style="font-weight:800">{{ $upcoming }}</b></h5>
                                    <small class="text-muted">Upcoming</small>
                                </div>
                            </div>
                        </div>

                        <!-- In Progress -->
                        <div class="card shadow-sm flex-grow-1">
                            <div class="card-body d-flex align-items-center">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#5E17EB"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 5a2 2 0 0 1 2-2h4.157a2 2 0 0 1 1.656.879L15.249 6H19a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2v-5a3 3 0 0 0-3-3h-3.22l-1.14-1.682A3 3 0 0 0 9.157 6H6V5Z" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3 9a2 2 0 0 1 2-2h4.157a2 2 0 0 1 1.656.879L12.249 10H3V9Zm0 3v7a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-7H3Z" />
                                    </svg>
                                </span>
                                <div>
                                    <h5 class="mb-0"><b style="font-weight:800">{{ $inprogress }}</b></h5>
                                    <small class="text-muted">In Progress</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 d-flex align-items-center justify-content-end">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item d-inline align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                    viewBox="0 0 24 24" style="transform:translate(0,-2px);">
                                    <path stroke="#5E17EB" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 3v4a1 1 0 0 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                                </svg>
                                <a href="#">{{ request('details') }} Transactions</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Lists</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="title-card-class bg-white ml-4" id="btnbatchsubmit" style="display:none; min-height: 70px; position: relative;">
            <small class="action-pill-label">Action Selection</small>
            <button type="submit" class= "d-flex mr-2 btn btn-default btn-primary btn-batch-submit box-shadow" data-action="APPROVED" style="font-size: 12px; font-weight: 600;">
                <svg class="mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 0 0-1 1H6a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2a1 1 0 0 0-1-1H9Zm1 2h4v2h1a1 1 0 1 1 0 2H9a1 1 0 0 1 0-2h1V4Zm5.707 8.707a1 1 0 0 0-1.414-1.414L11 14.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                </svg>
                Batch Approval
            </button> 
            <button type="submit" class= "d-flex mr-2 btn btn-dark btn-batch-submit box-shadow" data-action="HOLD" style="font-size: 12px; font-weight: 600;">
                <svg class="mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9-3a1 1 0 1 0-2 0v6a1 1 0 1 0 2 0V9Zm4 0a1 1 0 1 0-2 0v6a1 1 0 1 0 2 0V9Z" clip-rule="evenodd"/>
                </svg>
                Batch Hold
            </button> 
            <button onclick="openBatchReasonModal()" class="d-flex btn btn-default btn-danger box-shadow" data-action="CANCELLED" style="font-size: 12px; font-weight: 600;"> 
                <svg class="mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clip-rule="evenodd"/>
                </svg>
                Batch Cancel
            </button> 
        </div>

        <div class="col-md-12 table-container-class">

            <form action="{{ route('transaction.batchsubmit') }}" method="POST" id="batchdata">
                {{ csrf_field() }}
                <input type="hidden" name="selected_data" id="selected_data" class="" readonly>
                <input type="hidden" name="selected_action" id="selected_action" readonly>
                <input type="hidden" name="cancel_reason" id="cancel_reason" readonly>
            </form>

            <br>

            <div class="status-filter d-flex align-items-center justify-content-start">
                <span for="statusFilter"><b>Status:</b></span>
                <select id="statusFilter" class="form-control">
                    <option value="">All</option>
                    <option value="PENDING">Pending</option>
                    <option value="FULLY APPROVED">Fully Approved</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <div class="type-filter d-flex align-items-center justify-content-start">
                <span for="typeFilter"><b>Transaction Type:</b></span>
                <select id="typeFilter" class="form-control">
                    <option value="">All</option>
                    <option value="CA">Cash Advance</option>
                    <option value="LIQ">Liquidation</option>
                    <option value="RFP">Request for Payment</option>
                    <option value="TO">Travel Order</option>
                    <option value="IMP-MRS">IMP-MRS</option>
                    <option value="IMP-IMF">IMP-IMF</option>
                </select>
            </div>

            <div class="row">

                <table class="table table-stripped" id="data-table" style="width:100%">

                    <thead>
                        <th><input id="checkall" class='table_cb' type="checkbox"></th>
                        <th>Transaction ID</th>
                        <th>Requestor</th>
                        <th>Date Requested</th>
                        @if(request('details') == "OREM")
                            <th>Payee</th>
                            <th>Amount</th>
                        @endif
                        <th>Approval Summary</th>
                        <th>Overall Status</th>
                        <th>Purpose</th>
                        <th hidden>Action</th>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $t)
                            <tr>
                                @php
                                    $next_approver = \App\ApprovalStatus::where('transaction_id', $t->id)
                                        ->where('current_seq', null)
                                        ->first();

                                    $verifiers = \App\User::where('designation', 'VERIFIER')->get();
                                    $verifier_acc = \App\User::where('designation', 'VERIFIED')->first();

                                    $verifiers_id = $verifiers->pluck('id')->toArray();
                                    array_push($verifiers_id, $verifier_acc->id);
                                @endphp
                                <td>
                                    @if (auth()->user()->designation == 'VERIFIER')
                                        <input class='checkboxes table_details_cb table_cb' type="checkbox"
                                            name="checkboxes[]" value="{{ $t->id }}"
                                            @if (!in_array($next_approver->approver_id, $verifiers_id)) disabled @endif>
                                    @else
                                        <input class='checkboxes table_details_cb table_cb' type="checkbox"
                                            name="checkboxes[]" value="{{ $t->id }}"
                                            @if (
                                                $next_approver &&
                                                    ($next_approver->approver_id != auth()->user()->id &&
                                                        $next_approver->alternate_approver_id != auth()->user()->id)) disabled @endif>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('transaction.details', $t->id) }}" class="scale-1"
                                        title="View Details">
                                        {{ $t->transid }}
                                        <br>
                                        <span style="font-weight: bold; font-size: 11px">
                                            {{ $t->details }} @if (empty($t->locsite))
                                                (Agusan)
                                            @elseif(!empty($t->locsite))
                                                (Davao)
                                            @endif
                                        </span>
                                    </a>
                                </td>
                                <td><b>{{ $t->requestor }}</b><br><small>{{ $t->department }}</small></td>
                                <td>{{ $t->created_at != null ? $t->created_at->translatedFormat('F j, Y @g:ia') : '' }}
                                </td>
                                @if(request('details') == "OREM")
                                    <td>{{ $t->name }}</td> <!-- MISSING FOR VBS REQUESTS -->
                                    <td class="amount-td"><b>{{ $t->currency }} {{ number_format($t->totalamount, 2) }}</b>
                                        <br> <small>
                                            @if ($t->totalamount < $t->converted_amount)
                                                (PHP {{ number_format($t->converted_amount, 2) }})
                                            @endif
                                        </small>
                                    </td>
                                @endif
                                <!-- Approval Summary Count -->
                                <?php
                                // dd($t);
                                ?>
                                <td>
                                    @foreach ($t->approvers as $a)
                                        <a href="#!"><i
                                                class="fa fa-user f-12 @if ($a->status == 'CANCELLED') text-red @elseif($a->status == 'APPROVED') text-green @elseif($a->status == 'HOLD') text-orange @else text-default @endif"></i></a>
                                    @endforeach
                                    &nbsp; {{-- {{ Carbon\Carbon::parse($t->created_at)->addDays(1)->diffForhumans(null,true) }}  @if (Carbon\Carbon::parse($t->created_at)->addDays(1)->diffForhumans(null, true))<i class="fa fa-exclamation text-red"></i> @endif --}}
                                    {{-- {{ \Carbon\Carbon::now()->diffInDays( \Carbon\Carbon::parse($t->created_at),false) }} --}}
                                    @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($t->created_at), false) <= 2 && $t->status == 'PENDING')
                                        <i class="fa fa-exclamation text-red"></i>
                                    @endif
                                </td>
                                <td><label
                                        class="badge @if ($t->status == 'FULLY APPROVED') badge-success @elseif($t->status == 'CANCELLED') badge-danger @else badge-secondary @endif">{{ $t->status }}</label>
                                </td>
                                <td style="max-width: 260px !important; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $t->purpose }}</td>
                                <td hidden>
                                    <div class="list-actions">
                                        <a href="{{ route('transaction.details', $t->id) }}"><i
                                                class="ik ik-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Bulk Cancel Modal -->
    <div class="modal fade" id="batchCancelModal" tabindex="-1" role="dialog" aria-labelledby="batchCancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="batchCancelModalLabel">
                        <b>
                            <span>
                                <svg style="transform: translate(0px, -3px);" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                        d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Zm-4 1h.01v.01H15V5Zm-2 2h.01v.01H13V7Zm2 2h.01v.01H15V9Zm-2 2h.01v.01H13V11Zm2 2h.01v.01H15V13Zm-2 2h.01v.01H13V15Zm2 2h.01v.01H15V17Zm-2 2h.01v.01H13V19Z" />
                                </svg>
                            </span>
                            Batch Cancel Reason
                        </b>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('transaction.batchsubmit') }}" method="POST" id="canceldata">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" name="cancel_reason" id="cancel_reason" rows="6"
                                placeholder="Type cancel reason here.."></textarea>
                        </div>
                        <input type="hidden" class="selected_data_cancel" name="selected_data" id="selected_data"
                            class="" readonly>
                        <input type="hidden" class="selected_action_cancel" name="selected_action" id="selected_action"
                            readonly>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-toastr/toastr_notification.min.js') }}" type="text/javascript">
    </script>

    <script type="text/javascript" src="{{ asset('assets/plugins/datatables.net/js/dataTables.buttons.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datatables.net/js/jszip.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datatables.net/js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#data-table").dataTable({
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
                    "search": "<b>Search:<b/>",
                    "zeroRecords": "No matching records found",
                    "searching": true
                },
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excel',
                    className: 'btn btn-primary text-white', // Bootstrap style, or use your own class
                    text: `
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                            width="20" height="20" fill="white" viewBox="0 0 24 24" 
                            style="margin-right:6px;vertical-align:middle;">
                        <path fill-rule="evenodd" d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v9.293l-2-2a1 1 0 0 0-1.414 1.414l.293.293h-6.586a1 1 0 1 0 0 2h6.586l-.293.293A1 1 0 0 0 18 16.707l2-2V20a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/>
                        </svg>
                        <span style="vertical-align:middle;">Export to Excel</span>
                    `
                }, 'pageLength'],

                responsive: true,

                "order": [
                    [2, 'desc']
                ],

                "lengthMenu": [
                    [5, 10, 15, 25, -1],
                    [5, 10, 15, 25, "All"]
                ],
                "pageLength": 25,
            });
            let data_table = $('#data-table').DataTable();

            $("#filterTable_filter.dataTables_filter").append($("#statusFilter"));
            $("#filterTable_filter.dataTables_filter").append($("#typeFilter"));

            var statusIndex = 0;
            var typeIndex = 0;

            $("#data-table th").each(function(i) {
                if ($($(this)).html() == "Overall Status") {
                    statusIndex = i;
                    return false;
                }
            });

            $("#data-table th").each(function(i) {
                if ($($(this)).html() == "Transaction ID") {
                    typeIndex = i;
                    return false;
                }
            });

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {

                    var selectedItem = $('#statusFilter').val()
                    var status = data[statusIndex];

                    if (selectedItem === "" || status.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {

                    var selectedType = $('#typeFilter').val()
                    var type = data[typeIndex];

                    if (selectedType === "" || type.includes(selectedType)) {
                        return true;
                    }
                    return false;
                }
            );

            $("#statusFilter").change(function(e) {
                data_table.draw();
            });

            $("#typeFilter").change(function(e) {
                data_table.draw();
            });

        });
    </script>
    @if(request('details') == "OREM")
        <script type="text/javascript">
            $(".table_cb").on("change", function() {
                if ($(".table_details_cb:checked").length > 0) {
                    $('#btnbatchsubmit').show();
                } else {
                    $('#btnbatchsubmit').hide();
                }
            });
        </script>
    @endif

    <script type="text/javascript">
        $(document).on('click', "#checkall", function() {

            if ($("#checkall").is(':checked')) {
                $(".checkboxes").each(function() {
                    //                $(this).prop("checked", true);
                    $(this).click();
                });
            } else {
                $(".checkboxes").each(function() {
                    //                $(this).prop("checked", false);
                    $(this).click();
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).on('click', '.checkboxes', function() {
            var i = '';
            // console.log('aw');
            $(".checkboxes").each(function() {
                $('#selected_data').val('');

                if ($(this).is(":checked")) {
                    i = i + "|" + $(this).val();
                }
            });
            $('#selected_data').val(i);
            $('.selected_data_cancel').val(i);
        });
    </script>
    <script type="text/javascript">
        $('.btn-batch-submit').click(function() {
            // console.log('clicked');
            $('#selected_action').val($(this).data('action'));
            document.getElementById("batchdata").submit();
        });
    </script>
    <script>
        @if (Session::has('notification'))
            var type = "{{ Session::get('notification.alert-type', 'info') }}";
            switch (type) {
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
        function openBatchReasonModal() {
            $('#batchCancelModal').modal('show');
            $('.selected_action_cancel').val('CANCELLED');
        }
    </script>
@endsection
