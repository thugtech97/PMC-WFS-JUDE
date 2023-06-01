@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-inbox bg-blue"></i>
                    <div class="d-inline">
                        <h5>Transactions Table</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Maintenance</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="row mb-20">
        <div class="col col-sm-12 text-right">
            <a href="{{ route('transaction.create') }}" class="btn btn-primary">Create new Transaction</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Token</th>
                                <th>Template Used</th>
                                <th>Last Update</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apps as $app)
                                <tr id="tr{{$app->id}}">
                                    <td>{{ $app->name }}</td>
                                    <td>{{ $app->token }}</td>
                                    <td>{{ $app->template->name }}</td>
                                    <td>{{ \App\Transaction::date_format($app->updated_at) }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('transaction.edit',$app->id) }}"><i class="ik ik-edit-2"></i></a>
                                            <a href="javascript:;" onclick="deleteApp('{{$app->id}}')"><i class="ik ik-trash-2"></i></a>
                                        </div>   
                                    </td>
                                </tr>
                            @empty
                                <tr><td><center>No approver found!</center></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>    
    <script src="{{ asset('assets/plugins/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script>

        function deleteApp(id){

            swal({
                title: "Are you sure?",
                text: "Do you really want to remove this application?",
                icon: "warning",
                buttons: ["Cancel", "Yes Remove!"],
                dangerMode: true,
            })
            .then((willRemove) =>{
                if(willRemove){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('app.remove') }}",
                        data: { id : id },
                        success: function( response ) {
                            $('#tr'+id).remove();
                            swal({
                                title: "Removed",
                                text: "Application has been removed!",
                                icon: "success",
                            });
                        }
                    });   
                }
                
            });
        }
    </script>
@endsection