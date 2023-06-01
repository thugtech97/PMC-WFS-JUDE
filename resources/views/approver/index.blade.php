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
                    <i class="ik ik-users bg-blue"></i>
                    <div class="d-inline">
                        <h5>Approvers Table</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Maintenance</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Approvers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row mb-20">
        <div class="col col-sm-12 text-right">
            <a href="{{ route('approvers.create') }}" class="btn btn-primary">Create new Approver</a>
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
                                <th>Username</th>
                                <th>Department</th>
                                <th>Groupings</th>
                                <th>Role</th>
                                <th>Type</th>
                                <th>Date Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($approvers as $p)
                                <tr id="tr{{$p->id}}">
                                    <td>{{ strtoupper($p->name) }}<br>{{ $p->email }}</td>
                                    <td>{{ $p->username }}</td>
                                    <td>
                                    @if ($p->department != "")
                                      @foreach(explode('|', $p->department) as $info) 
                                        <span><strong>*</strong> {{$info}}</span><br>
                                      @endforeach
                                    @endif
                                    </td>
                                    <td>{{ $p->designation }}</td>
                                    <td>{{ ucwords($p->role) }}</td>
                                    <td>{{ ucwords($p->user_type) }}</td>
                                    <td>{{ \App\Transaction::date_format($p->created_at) }}</td>
                                    <td>    
                                        <div class="table-actions">
                                            <a href="{{ route('approvers.edit',$p->id) }}"><i class="ik ik-edit-2"></i></a>
                                            <a href="javascript:;" onclick="deleteApprover('{{$p->id}}')"><i class="ik ik-trash-2"></i></a>
                                        </div>   
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8"><center>No approver found!</center></td></tr>
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
        function deleteApprover(id){
            var remarks = $('#remarks').val();

            swal({
                title: "Are you sure?",
                text: "Do you really want to delete this user?",
                icon: "warning",
                buttons: ["Cancel", "Yes Delete!"],
                dangerMode: true,
            })
            .then((willDelete) =>{
                if(willDelete){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('approver.delete') }}",
                        data: { id : id },
                        success: function( response ) {
                            $('#tr'+id).remove();
                            swal({
                                title: "Deleted",
                                text: "User has been removed!",
                                icon: "success",
                            });
                        }
                    });   
                }
                
            });
        }
    </script>
@endsection