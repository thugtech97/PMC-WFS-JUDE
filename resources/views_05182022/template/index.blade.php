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
                    <i class="ik ik-list bg-blue"></i>
                    <div class="d-inline">
                        <h5>Template Approval Sequence</h5>
                        <span>Generic approver and tagged as dynamic will be overwritten on approval status with specific approver.</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Maintenance</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Templates</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row mb-20">
        <div class="col col-sm-12 text-right">
            <a href="{{ route('templates.create') }}" class="btn btn-primary">Create new Template</a>
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
                                <th>Approvers</th>
                                <th>Date Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $t)
                                <tr id="tr{{$t->id}}">
                                    <td>{{ $t->name }}</td>
                                    <td>
                                        @foreach($t->approvers as $a)
                                            <a class="badge badge-secondary mb-1 text-white text-uppercase"> [{{$a->sequence_number}}] {{$a->user->name}} @if($a->alternate_approver_id != NULL)|@endif  {{ \App\User::alternate_approver($a->alternate_approver_id) }}</a>
                                        @endforeach
                                    </td>
                                    <td>{{ \App\Transaction::date_format($t->created_at) }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <!-- <a href="{{ route('templates.edit',$t->id) }}"><i class="ik ik-edit-2"></i></a> -->
                                            <!-- <a href="javascript:;" onclick="deleteTemplate('{{$t->id}}')"><i class="ik ik-trash-2"></i></a> -->
                                        </div>    
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3"><center>No templates found!</center></td></tr>
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
        // function deleteTemplate(id){
        //     var remarks = $('#remarks').val();

        //     swal({
        //         title: "Are you sure?",
        //         text: "Do you really want to delete this template?",
        //         icon: "warning",
        //         buttons: ["Cancel", "Yes Delete!"],
        //         dangerMode: true,
        //     })
        //     .then((willDelete) =>{
        //         if(willDelete){
        //             $.ajax({
        //                 type: "GET",
        //                 url: "{{ route('template.delete') }}",
        //                 data: { id : id },
        //                 success: function( response ) {
        //                     $('#tr'+id).remove();
        //                     swal({
        //                         title: "Deleted",
        //                         text: "Template has been removed!",
        //                         icon: "success",
        //                     });
        //                 }
        //             });   
        //         }
                
        //     });
        // }
    </script>
@endsection
