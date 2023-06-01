@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select/bootstrap-select.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h3>Create Template</h3></div>
            <div class="card-body">
                <form autocomplete="off" method="post" action="{{ route('templates.store') }}">
                @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail3">Name</label>
                                <input type="text" name="name" class="form-control" id="exampleInputEmail3" placeholder="Template name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Approvers</label>
                                <select class="form-control text-uppercase" id="selected_approver">
                                    <option selected disabled>Select Approver</option>
                                    @foreach($approvers as $a)
                                    <option value="{{$a->id}}|{{$a->name}}|{{$a->designation}}|{{$a->condition}}|{{str_replace('|',',',$a->department)}}|{{str_replace('|',',',$a->section)}}|{{$a->is_dynamic}}|{{str_replace('|',',',$a->division)}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button data-repeater-create type="button" onclick="add_approver()" class="btn btn-success btn-icon mt-30 mb-2"><i class="ik ik-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="approvers"></div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="{{ route('templates.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterLabel">Search Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="">
                    @csrf
                    <div class="form-group">
                        <label>Approvers</label>
                        <select class="form-control" id="al_approver">
                            <option selected disabled>Select Approver</option>
                            @foreach($alternate as $a)
                            <option value="{{$a->id}}|{{$a->name}}|{{$a->designation}}">{{$a->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" id="uid">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btnAdd">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/js/bootstrap-select/bootstrap-select.js') }}"></script>

    <script>
        
        var i = 0;  

        function add_approver()
        {
            var selected_approver = $('#selected_approver').val();
            var approver = selected_approver.split('|');
            i++;

            $('#approvers').append(
            '<div class="col-xl-3 col-md-6" id="row'+i+'">'+
                '<div class="card ticket-card">'+
                    '<div class="card-body">'+
                        '<button class="btn mb-15 btn-secondary btn_remove" id="'+i+'">Remove</button>'+
                        '<button type="button" onclick="addAlternateApprover('+approver[0]+')" class="btn btn-success btn-icon float-md-right"><i class="ik ik-plus"></i></button>'+
                        '<input type="hidden" name="approver_id[]" value="'+approver[0]+'"/>'+
                        '<input type="hidden" name="al_approver_id[]" id="al_id'+approver[0]+'"/>'+
                        '<input type="hidden" name="designation[]" value="'+approver[2]+'"/>'+
                        '<input type="hidden" name="condition[]" value="'+approver[3]+'"/>'+
                        '<input type="hidden" name="department[]" value="'+approver[4]+'"/>'+
                        '<input type="hidden" name="section[]" value="'+approver[5]+'"/>'+
                        '<input type="hidden" name="is_dynamic[]" value="'+approver[6]+'"/>'+
                        '<input type="hidden" name="division[]" value="'+approver[7]+'"/>'+
                        '<div class="text-center">'+
                            '<img src="{{ asset("assets/img/users/user.png") }}" style="height: 70px;" alt="">'+
                            '<br>'+
                            '<p class="mb-0 d-inline-block">'+approver[1]+'</p>'+
                            '<br>'+
                            '<p class="mb-5 d-inline-block text-uppercase"><small>'+approver[2]+'</small></p>'+
                            '<br>'+
                            'Alternate Approver : <p class="mb-5 d-inline-block" id="alternate_approver'+approver[0]+'"><small>N/A</small></p>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>');
        }

        function addAlternateApprover(id){
            $('#exampleModalCenter').modal('show');
            $('#uid').val(id);
        }

        $(document).on('click','.btnAdd', function(){
            var al_approver = $('#al_approver').val();
            var x = al_approver.split('|');

            var id = $('#uid').val();

            $('#alternate_approver'+id).html(x[1]);
            $('#al_id'+id).val(x[0]);
            
            $('#exampleModalCenter').modal('hide');
            $('#al_approver').val('');
        });

        $(document).on('click', '.btn_remove', function(){  
           var id = $(this).attr("id");   
           $('#row'+id+'').remove();  
        });
    </script>
@endsection
