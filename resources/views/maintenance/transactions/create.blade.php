@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3>New Transaction</h3></div>
            <div class="card-body">
                <form autocomplete="off" method="post" action="{{ route('transaction.store') }}">
                @csrf
                    <div class="form-group">
                        <label for="exAppName">Transaction Name <i class="text-danger">*</i></label>
                        <input type="text" name="name" class="form-control" id="exAppName">
                    </div>
                    <div class="form-group">
                        <label>Token <i class="text-danger">*</i></label>
                        <textarea required name="token" class="form-control" id="" rows=3></textarea>
                    </div>
                    <div class="form-group">
                        <label>Template <i class="text-danger">*</i></label>
                        <select class="form-control" name="temp_id">
                            <option selected disabled>Select Template</option>
                            @foreach($templates as $t)
                            <option value="{{$t->id}}">{{$t->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="{{ route('allowed-transactions.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
