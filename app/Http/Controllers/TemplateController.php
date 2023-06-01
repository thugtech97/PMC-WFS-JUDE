<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\TemplateApprover;
use App\Template;
use App\User;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Template::orderBy('id','desc')->paginate(10);

        return view('template.index',compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $approvers = User::where('user_type','approver')->orderBy('name','asc')->get();        
        $alternate = User::where('user_type','alternate approver')->orderBy('name','asc')->get();


        return view('template.create',compact('approvers','alternate'));
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
            'name' => 'required'
        ]);

        $template = Template::create([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]); 

        if($template)
        {            
            $data = $request->all();                  
            $approver_id = $data['approver_id'];
            $altenate_id = $data['al_approver_id'];  
            $condition = $data['condition']; 
            $department = str_replace(',','|',$data['department']);
            $division = str_replace(',','|',$data['division']);
            $section = str_replace(',','|',$data['section']);
            $is_dynamic = $data['is_dynamic'];
            $designation = $data['designation'];            

            foreach($approver_id as $key => $id){
                TemplateApprover::create([
                    'template_id' => $template->id,
                    'approver_id' => $id,
                    'alternate_approver_id' => $altenate_id[$key],
                    'sequence_number' => $key, 
                    'condition' => $condition[$key],  
                    'department' => $department[$key],
                    'division' => $division[$key],                 
                    'section' => $section[$key],
                    'is_dynamic' => $is_dynamic[$key],
                    'designation' => $designation[$key]
                ]);
            }
        }

        return redirect(route('templates.index'))->with('successMsg', 'Template has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {   
        $approvers = User::where('user_type','approver')->orderBy('name','asc')->get();
        $template = Template::find($template->id);

        $alternate = User::where('user_type','alternate approver')->orderBy('name','asc')->get();

        return view('template.edit',compact('template','approvers','alternate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {

        $template = Template::find($template->id)->update([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]); 

        // if($template)
        // {
        //     $data = $request->all();
        //     $approver_id = $data['approver_id'];
        //     $altenate_id = $data['al_approver_id'];

        //     foreach($approver_id as $key => $id){
        //         TemplateApprover::where('template_id',$template->id)->where('approver_id',$id)->update([
        //             'template_id' => $template->id,
        //             'approver_id' => $id,
        //             'alternate_approver_id' => $altenate_id[$key],
        //             'sequence_number' => $key
        //         ]);
        //     }
        // }

        return redirect(route('templates.index'))->with('successMsg', 'Template has been updated successfully.');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        //
    }

    public function delete(Request $request)
    {
        Template::find($request->id)->delete();
        TemplateApprover::where('template_id',$request->id)->delete();

        return response()->json();
    }
}
