@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/dist/css/select2.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3>Update User</h3></div>
            <div class="card-body">
                <form autocomplete="off" method="post" action="{{ route('approvers.update',$data->id) }}">
                    @csrf
                    @method('PUT')
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="search">SEARCH EMPLOYEE NAME</label>
                                <input type="text" name="search" id="search" class="form-control text-uppercase" placeholder="Search Employee Name">
                            <span><img style="display: none;" id="emp_spinner" class="wd-15p mg-t-1" src="{{ asset('assets/apps/img/spinner/spinner5.gif') }}" height="50" width="150" alt=""></span>
                            <div id="employee_list"></div> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{$data->name}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{$data->email}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{$data->username}}">
                            </div>
                        </div>
                    </div>                   
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="division">Division</label>
                                <select id="division" class="form-control select2-multiple" name="division[]" multiple placeholder="SELECT DIVISION">
                                    @if ($data->division != "")
                                        @foreach(explode('|', $data->division) as $division) 
                                            <option value="{{$division}}" selected>{{$division}}</option>
                                        @endforeach
                                    @endif
                                    <optgroup label="SELECT DIVISION">
                                        <option value="">EMPTY</option>      
                                        @forelse($mergedDataDivision as $division)
                                            <option value="{{ $division['DivisionName'] }}">{{ $division['DivisionName'] }}</option>
                                        @empty
                                            No division record found!
                                        @endforelse                               
                                    </optgroup>
                                </select>                               
                            </div>                            
                        </div>                      
                    </div>                  
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select id="department" class="form-control select2-multiple" name="department[]" multiple placeholder="SELECT DEPARTMENT">
                                    @if ($data->department != "")
                                        @foreach(explode('|', $data->department) as $department) 
                                            <option value="{{$department}}" selected>{{$department}}</option>
                                        @endforeach
                                    @endif
                                    <optgroup label="SELECT DEPARTMENT">
                                        <option value="">EMPTY</option>
                                        @forelse($mergedDataDepartment as $department)
                                            <option value="{{ $department['DeptDesc'] }}">{{ $department['DeptDesc'] }}</option>
                                        @empty
                                            No department record found!
                                        @endforelse                               
                                    </optgroup>
                                </select>                               
                            </div>                            
                        </div>                      
                    </div>
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="section">Section</label>
                                <select id="section" class="form-control select2-multiple" name="section[]" multiple placeholder="SELECT SECTION">
                                    @if ($data->section != "")
                                        @foreach(explode('|', $data->section) as $section) 
                                            <option value="{{$section}}" selected>{{$section}}</option>
                                        @endforeach
                                    @endif
                                    <optgroup label="SELECT SECTION">
                                        <option value="">EMPTY</option>
                                        @forelse($mergedDataSection as $section)
                                            <option value="{{ $section['SectionDesc'] }}">{{ $section['SectionDesc'] }}</option>
                                        @empty
                                            No section record found!
                                        @endforelse                               
                                    </optgroup>
                                </select>                               
                            </div>                            
                        </div>                      
                    </div>
                    <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <input type="text" name="designation" class="form-control" id="designation" placeholder="Designation" value="{{$data->designation}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_type">Type</label>
                                <select name="user_type" class="form-control" id="user_type">
                                    <option @if($data->user_type == 'approver') selected @endif value="approver">Approver</option>
                                    <option @if($data->user_type == 'alternate approver') selected @endif value="alternate approver">Alternate Approver</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control" id="role">
                                    <option @if($data->role == 'read only') selected @endif value="read only">Read Only</option>
                                    <option @if($data->role == 'read write') selected @endif value="read write">Read Write</option>
                                </select>
                            </div>
                        </div>
                    </div>                 
                    <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="condition">Conditional Amount</label>
                                <input type="text" name="condition" class="form-control" id="condition" placeholder="Conditional Amount" value="{{$data->condition}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_dynamic">Dynamic</label>
                                <select name="is_dynamic" class="form-control" id="is_dynamic">
                                    <option @if($data->is_dynamic == 'YES')  selected @endif value="YES">YES</option>
                                    <option @if($data->is_dynamic == 'NO')  selected @endif value="NO">NO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
<script>

    $(document).ready(function() {
        $('#division').select2();
        $('#department').select2();
        $('#section').select2();
    });

    $(document).ready(function(){

    var typingTimer;    

    $('#search').keyup(function(){
        var _search = $('#search').val().trim();
        if( _search == "" ){ 
            $('#emp_spinner').hide();
            $('#employee_list').html('');
                $('#search').val('');                
                $('#name').val('');
                $('#department').val('');
                $('#designation').val('');               
            return false; 
        }
        $('#emp_spinner').show();
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTypingEmployee, 2000);
    });

    function doneTypingEmployee(){
        var query = $('#search').val();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('search.hris.employee') }}",
            method: "POST",
            data: { query :query, _token:_token },
            success: function(data)
            {
                $('#emp_spinner').hide();
                $('#employee_list').fadeIn();
                $('#employee_list').html(data);
            }
        })
    }

    });

    $(document).on('click','.emp_list',function(){
    var emp = $(this).text();
    var i = emp.split("=");    

    $('#search').val(i[0]);
    $('#name').val(i[2]);
    // $('#department').val(i[4]);
    $('#designation').val(i[5]); 
    $('#employee_list').fadeOut();    
    });    

    </script>
    @endsection

