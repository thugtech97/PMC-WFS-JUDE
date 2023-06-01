<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Str;
use App\User;

class ApproverController extends Controller
{
    // all approvers
    public function index()
    {
        $data = User::where('user_type','approver')->orderBy('id','desc')->paginate(10);

        return response()->json($data);
    }

    // add approver
    public function add(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'role' => 'required',
        ]);

        $approver = new User([
            'name' => $request->name,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'department' => $request->department,
            'designation' => $request->designation,
            'role' => $request->role,
            'remember_token' => Str::random(12),
            'dept_id' => 10,
            'isActive' => 1,
            'isApprover' => 1
        ]); 


        $approver->save();

        return response()->json();
    }

    // edit approver
    public function edit($id)
    {
        $approver = User::find($id);
        return response()->json($approver);
    }

    // update approver
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'role' => 'required',
        ]);

        $approver = User::find($id);
        $approver->update($request->all());

        return response()->json();
    }

    // delete approver
    public function delete($id)
    {
        $approver = User::find($id);
        $approver->delete();

        return response()->json();
    }
}