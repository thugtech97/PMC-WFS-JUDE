<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\User;
use App\HRISDavaoDivision;
use App\HRISAgusanDivision;
use App\HRISDavaoDepartment;
use App\HRISAgusanDepartment;
use App\HRISDavaoSection;
use App\HRISAgusanSection;

use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $approvers = User::where('user_type','<>','ict')->orderBy('id','desc')->paginate(10);            

        $approvers = User::where('user_type','<>','ict')->orderBy('id','desc')->get(); 

        return view('approver.index',compact('approvers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataAgusanDivision = HRISAgusanDivision::orderby('DivisionName')->get();
        $dataDavaoDivision  = HRISDavaoDivision::orderby('DivisionName')->get();

        $mergedDataDivision = array_unique(array_merge($dataAgusanDivision->toArray(), $dataDavaoDivision->toArray()),SORT_REGULAR);

        $dataAgusanDepartment = HRISAgusanDepartment::orderby('DeptDesc')->get();
        $dataDavaoDepartment = HRISDavaoDepartment::orderby('DeptDesc')->get();

        $mergedDataDepartment = array_unique(array_merge($dataAgusanDepartment->toArray(), $dataDavaoDepartment->toArray()),SORT_REGULAR);

        $dataAgusanSection = HRISAgusanSection::orderby('SectionDesc')->get();
        $dataDavaoSection = HRISDavaoSection::orderby('SectionDesc')->get();

        $mergedDataSection = array_unique(array_merge($dataAgusanSection->toArray(), $dataDavaoSection->toArray()),SORT_REGULAR);


        return view('approver.create')->with('mergedDataDivision',$mergedDataDivision)->with('mergedDataDepartment',$mergedDataDepartment)->with('mergedDataSection',$mergedDataSection);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'department' => 'required',
            'designation' => 'required',
            'username' => 'required',
            'type' => 'required',
            'role' => 'required',
        ]);        

        $approver = new User([
            'name' => $request->name,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'department' => $request->department,
            'designation' => $request->designation,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'remember_token' => Str::random(12),
            'dept_id' => 10,
            'isActive' => 1,
            'isApprover' => 1,
            'user_type' => $request->type,
            'condition' => $request->condition,
            'section' => $request->section,
            'division' => $request->division,
            'is_dynamic' => $request->is_dynamic
        ]); 

        $approver->save();

        return redirect(route('approvers.index'))->with('successMsg', 'Approver has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);

        $dataAgusanDivision = HRISAgusanDivision::orderby('DivisionName')->get();
        $dataDavaoDivision  = HRISDavaoDivision::orderby('DivisionName')->get();

        $mergedDataDivision = array_unique(array_merge($dataAgusanDivision->toArray(), $dataDavaoDivision->toArray()),SORT_REGULAR);

        $dataAgusanDepartment = HRISAgusanDepartment::orderby('DeptDesc')->get();
        $dataDavaoDepartment = HRISDavaoDepartment::orderby('DeptDesc')->get();

        $mergedDataDepartment = array_unique(array_merge($dataAgusanDepartment->toArray(), $dataDavaoDepartment->toArray()),SORT_REGULAR);

        $dataAgusanSection = HRISAgusanSection::orderby('SectionDesc')->get();
        $dataDavaoSection = HRISDavaoSection::orderby('SectionDesc')->get();

        $mergedDataSection = array_unique(array_merge($dataAgusanSection->toArray(), $dataDavaoSection->toArray()),SORT_REGULAR);

        return view('approver.edit',compact('data'))->with('mergedDataDivision',$mergedDataDivision)->with('mergedDataDepartment',$mergedDataDepartment)->with('mergedDataSection',$mergedDataSection);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'department' => 'required',
            'designation' => 'required',
            'username' => 'required',
            'role' => 'required',
            'user_type' => 'required'
        ]);

        $approver = User::find($id);
        $approver->update($request->all());

        return redirect(route('approvers.index'))->with('successMsg',' Approver details has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $approver = User::find($request->id);
        $approver->delete();

        return response()->json();
    }

    public function updatePassword(Request $request) {

        $user = \Auth::user();
        $hasher = app('hash');

        $validate = $request->validate([
            'current_password'      => 'required',
            'new_password'          => ['required', 'string'],
            'new_confirm_password'  => ['required', 'string','same:new_password'],
        ]);

        if ($hasher->check($request->current_password, $user->password)) {

            $user->update([
                'password'  => Hash::make($request->new_password)
            ]);

            \Auth::logout();
            return redirect('/login');

        }

        \Session::flash('error_message', 'Something is wrong while trying to change the password');

        return back();

    }
}
