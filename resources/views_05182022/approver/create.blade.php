@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/dist/css/select2.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3>Create new User</h3></div>
            <div class="card-body">
                <form autocomplete="off" method="post" action="{{ route('approvers.store') }}">
                @csrf
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
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="division">Division</label>
                                <select id="division" class="form-control select2-multiple" name="division[]" multiple placeholder="SELECT DIVISION">
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
                                    <optgroup label="SELECT DEPARTMENT">
                                        <option value="">EMPTY</option>    
                                        @forelse($mergedDataDepartment as $dept)
                                        <option value="{{ $dept['DeptDesc'] }}">{{ $dept['DeptDesc'] }}</option>
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
                                <label for="section">Section</label>
                                  <select id="section" class="form-control select2-multiple" name="section[]" multiple placeholder="SELECT SECTION">
                                    <optgroup label="SELECT SECTION">
                                        <option value="">EMPTY</option>
                                        @forelse($mergedDataSection as $section)
                                        <option value="{{ $section['SectionDesc'] }}">{{ $section['SectionDesc'] }}</option>
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
                                <label for="designation">Designation</label>
                                <input type="text" name="designation" id="designation" class="form-control"  placeholder="Designation">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" class="form-control" id="type">
                                    <option value="approver">Approver</option>
                                    <option value="alternate approver">Alternate Approver</option>
                                </select>
                            </div>
                        </div>
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control" id="role">
                                    <option value="read only">Read Only</option>
                                    <option value="read write">Read Write</option>
                                </select>
                            </div>
                        </div>
                    </div>                   
                    <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="condition">Conditional Amount</label>
                                <input type="text" name="condition" id="condition" class="form-control" placeholder="Conditional Amount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_dynamic">Dynamic</label>
                                <select name="is_dynamic" class="form-control" id="is_dynamic">
                                    <option value="YES">YES</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                        </div>
                    </div>                    
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
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
