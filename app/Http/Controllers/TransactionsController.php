<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Caheader;
use App\Toheader;

use App\Liqheader;
use App\Rfpheader;
use Carbon\Carbon;

use App\HistoryLog;
use App\Transaction;
use App\ApprovalStatus;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\NextApproverNotification;

class TransactionsController extends Controller
{
    // all requests

    public function index()
    {
        // $transactions = Transaction::whereIn('id',
        //     function($query){
        //         $query->select('transaction_id')->from('approval_status')
        //         ->where('approver_id',auth()->user()->id)
        //         ->orWhere('alternate_approver_id',auth()->user()->id);
        //     })
        // ->get();  
        
        $transactions = Transaction::whereIn('id' , // $transactions = Transaction::whereIn('id' ,
            function($query){
                $query->select('transaction_id')->from('approval_status')
                // ->where('current_seq',null)
                // ->where('status','PENDING')
                ->where(function ($query) { 

                    if(Auth::user()->designation == 'VERIFIER'){
                        $verifiers = User::where('designation', 'VERIFIER')->get();
                        $verifier_main = User::where('designation', 'VERIFIED')->first();

                        $verifiers_array =  $verifiers->pluck('id')->toArray();

                        $verifiers_array[] = $verifier_main->id;
                        
                        $query->whereIn('approver_id', $verifiers_array)
                            ->where('current_seq', null)
                            ->where('is_current',1);
                            
                    } else {                        
                        $query->where('approver_id', auth()->user()->id)
                            ->where('current_seq', null)
                            ->where('is_current',1);

                        // dd($query->orderBy('transaction_id', 'DESC')->get());
                    }

                })->orWhere(function($query) {
                    if(Auth::user()->designation == 'VERIFIER'){
                        $verifiers = User::where('designation', 'VERIFIER')->get();
                        $verifier_main = User::where('designation', 'VERIFIED')->first();

                        $verifiers_array =  $verifiers->pluck('id')->toArray();

                        $verifiers_array[] = $verifier_main->id;
                        
                        $query->whereIn('alternate_approver_id', $verifiers_array)
                            ->where('current_seq', null)
                            ->where('is_current',1);                            
                    } else {
                        $query->where('alternate_approver_id', auth()->user()->id)
                            ->where('current_seq', null)
                            ->where('is_current',1);
                    }
                });
                // ->where('approver_id',auth()->user()->id)                
                // ->orWhere('alternate_approver_id',auth()->user()->id);
            });

        $pending = DB::table('approval_status')
                    ->join('users', 'users.id', '=', 'approval_status.approver_id')
                    ->join('transactions', 'approval_status.transaction_id', '=', 'transactions.id')
                    ->where('users.id',auth()->user()->id)
                    ->where('transactions.status', '=', 'PENDING')
                    ->get()->count();

        $upcoming = DB::table('approval_status')
                    ->join('users', 'users.id', '=', 'approval_status.approver_id')
                    ->join('transactions', 'approval_status.transaction_id', '=', 'transactions.id')
                    ->where('users.id',auth()->user()->id)
                    ->whereIn('transactions.status', ['PENDING','IN-PROGRESS','RESUBMITTED'])
                    ->get()->count();

        $inprogress = DB::table('approval_status')
                    ->join('users', 'users.id', '=', 'approval_status.approver_id')
                    ->join('transactions', 'approval_status.transaction_id', '=', 'transactions.id')
                    ->where('users.id',auth()->user()->id)
                    ->where('transactions.status', '=', 'IN-PROGRESS')
                    ->get()->count();

        // if(auth::user()->designation == 'MANAGER') {
        //     if( auth()->user()->username == 'jccadiao' ) {
        //         $transactions = $transactions->where(function($query){
        //             // dria n2 tirahon ang connection sa transaction og sa template
        //             // kung accounting + is_not_dynamic meaning si maam J
        //         })->whereIn('status', ['PENDING','IN-PROGRESS','HOLD','RESUBMITTED'])->get();
        //     } else {
        //         // dd($transactions->whereIn('status', ['PENDING', 'HOLD','RESUBMITTED'])->take(44)->get());
        //         $transactions = $transactions->whereIn('status', ['PENDING', 'HOLD','RESUBMITTED'])->get();
                
        //         // $test_qry = $transactions->whereIn('status', ['PENDING', 'HOLD','RESUBMITTED'])->count();
        //         // $rows_to_get = max(0,$test_qry - 5);
        //         // $transactions = $transactions_query->whereIn('status', ['PENDING', 'HOLD','RESUBMITTED'])->take($rows_to_get)->get();

        //         // $transactions = $transactions->where('transid', 'RFP-00000s4556')->get();
        //         // dd($rows_to_get, $transactions);
        //     }
        // } else {
        //     $transactions = $transactions->whereIn('status', ['IN-PROGRESS', 'HOLD','RESUBMITTED'])->get();
        // }
        
        // $transactions = $transactions->whereIn('status', ['IN-PROGRESS', 'HOLD','RESUBMITTED'])->get();

        if(auth::user()->designation == 'MANAGER') {
            
            if( auth()->user()->username == 'jccadiao' ) {
                $transactions = $transactions->where(function($query){
                    // dria n2 tirahon ang connection sa transaction og sa template
                    // kung accounting + is_not_dynamic meaning si maam J
                })->whereIn('status', ['PENDING','IN-PROGRESS','HOLD','RESUBMITTED'])->get();
            } else {
                $transactions = $transactions->whereIn('status', ['PENDING','IN-PROGRESS', 'HOLD','RESUBMITTED'])->get();
            }

            // if( auth()->user()->username == 'jccadiao' ) {
            //     $transactions = $transactions->where(function($query){
            //         // dria n2 tirahon ang connection sa transaction og sa template
            //         // kung accounting + is_not_dynamic meaning si maam J
            //     })->whereIn('status', ['PENDING','IN-PROGRESS','HOLD','RESUBMITTED'])->get();
            // } else {
                // $transactions = $transactions->whereIn('status', ['PENDING', 'HOLD','RESUBMITTED'])->get();
                // $transactions = $transactions->whereIn('status', ['IN-PROGRESS', 'HOLD','RESUBMITTED'])->get();
            // }

        } else {
            $transactions = $transactions->whereIn('status', ['IN-PROGRESS', 'HOLD','RESUBMITTED'])->get();
        } 

        return view('transaction.index',compact('transactions','upcoming','inprogress','pending'));
    }

    public function index_new(Request $request)
    {
        $type = $request->get('details');
        $transactions = Transaction::where('details', 'like', '%' . $type . '%')
            ->whereIn('id' ,
            function($query){
                $query->select('transaction_id')->from('approval_status')
                ->where(function ($query) { 

                    if(Auth::user()->designation == 'VERIFIER'){
                        $verifiers = User::where('designation', 'VERIFIER')->get();
                        $verifier_main = User::where('designation', 'VERIFIED')->first();

                        $verifiers_array =  $verifiers->pluck('id')->toArray();

                        $verifiers_array[] = $verifier_main->id;
                        
                        $query->whereIn('approver_id', $verifiers_array)
                            ->where('current_seq', null)
                            ->where('is_current',1);
                            
                    } else {                        
                        $query->where('approver_id', auth()->user()->id)
                            ->where('current_seq', null)
                            ->where('is_current',1);
                    }

                })->orWhere(function($query) {
                    if(Auth::user()->designation == 'VERIFIER'){
                        $verifiers = User::where('designation', 'VERIFIER')->get();
                        $verifier_main = User::where('designation', 'VERIFIED')->first();

                        $verifiers_array =  $verifiers->pluck('id')->toArray();

                        $verifiers_array[] = $verifier_main->id;
                        
                        $query->whereIn('alternate_approver_id', $verifiers_array)
                            ->where('current_seq', null)
                            ->where('is_current',1);                            
                    } else {
                        $query->where('alternate_approver_id', auth()->user()->id)
                            ->where('current_seq', null)
                            ->where('is_current',1);
                    }
                });
            });
        
        $transTypes = ['OREM', 'IMP', 'OSTR', 'GATEPASS', 'HK', 'VBS'];
        $pendingAll = [];

        foreach ($transTypes as $ttype) {
            $pendingAll[$ttype] = DB::table('approval_status')
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'approval_status.approver_id')
                        ->orOn('users.id', '=', 'approval_status.alternate_approver_id');
                })
                ->join('transactions', 'transactions.id', '=', 'approval_status.transaction_id')
                ->where('users.id', auth()->id())
                ->where('transactions.status', 'PENDING')
                ->where('transactions.details', 'like', "%{$ttype}%")
                ->count();
        }

        $pending = DB::table('approval_status')
                    ->join('users', 'users.id', '=', 'approval_status.approver_id')
                    ->join('transactions', 'approval_status.transaction_id', '=', 'transactions.id')
                    ->where('users.id',auth()->user()->id)
                    ->where('transactions.status', '=', 'PENDING')
                    ->get()->count();

        $upcoming = DB::table('approval_status')
                    ->join('users', 'users.id', '=', 'approval_status.approver_id')
                    ->join('transactions', 'approval_status.transaction_id', '=', 'transactions.id')
                    ->where('users.id',auth()->user()->id)
                    ->whereIn('transactions.status', ['PENDING','IN-PROGRESS','RESUBMITTED'])
                    ->get()->count();

        $inprogress = DB::table('approval_status')
                    ->join('users', 'users.id', '=', 'approval_status.approver_id')
                    ->join('transactions', 'approval_status.transaction_id', '=', 'transactions.id')
                    ->where('users.id',auth()->user()->id)
                    ->where('transactions.status', '=', 'IN-PROGRESS')
                    ->get()->count();

        if(auth::user()->designation == 'MANAGER') {
            
            if( auth()->user()->username == 'jccadiao' ) {
                $transactions = $transactions->where(function($query){
                })->whereIn('status', ['PENDING','IN-PROGRESS','HOLD','RESUBMITTED'])->get();
            } else {
                $transactions = $transactions->whereIn('status', ['PENDING','IN-PROGRESS', 'HOLD','RESUBMITTED'])->get();
            }

        } else {
            $transactions = $transactions->whereIn('status', ['IN-PROGRESS', 'HOLD','RESUBMITTED'])->get();
        } 

        return view('transaction.index_new',compact('transactions','upcoming','inprogress','pending','pendingAll'));
    }

    public function history(Request $request)
    {                
        $departments = Transaction::select('department')->distinct()->orderBy('department','ASC')->get();

         if (isset($request->date_filter)) {
            
          $parts = explode(' - ' , $request->date_filter);
          $date_from = $parts[0];
          $date_to = $parts[1];

          if(!isset($request->transstatus) && !isset($request->rdepartment)) {      
            $histories = Transaction::whereIn('id',
            function($query){
                $query = $query->select('transaction_id')->from('approval_status');
                
                if(auth()->user()->designation == 'VERIFIER' ) {                    
                    
                    $query = $query->where('approver_id', '18');                     

                }elseif(auth()->user()->is_alternate){
                    $query = $query->where('alternate_approver_id',auth()->user()->id);
                }else{
                    $query = $query->where('approver_id',auth()->user()->id);
                }                               
            })
            ->where('created_at', '>=', $date_from . ' 00:00:00.001')
            ->where('created_at', '<=', $date_to . ' 23:59:59.999')
            // ->whereIn('status',['FULLY APPROVED','CANCELLED'])
            ->whereIn('status',['FULLY APPROVED','CANCELLED','IN-PROGRESS','HOLD'])
            ->get();

          } elseif(!isset($request->transstatus) && isset($request->rdepartment)) {              

            $histories = Transaction::whereIn('id',
            function($query){
                $query = $query->select('transaction_id')->from('approval_status');
                
                if(auth()->user()->designation == 'VERIFIER' ) {                    
                    
                    $query = $query->where('approver_id', '18');                     

                }elseif(auth()->user()->is_alternate){
                    $query = $query->where('alternate_approver_id',auth()->user()->id);
                }else{
                    $query = $query->where('approver_id',auth()->user()->id);
                }                               
            })
            ->where('created_at', '>=', $date_from . ' 00:00:00.001')
            ->where('created_at', '<=', $date_to . ' 23:59:59.999')
            ->where('department','=',$request->rdepartment) 
            // ->whereIn('status',['FULLY APPROVED','CANCELLED'])
            ->whereIn('status',['FULLY APPROVED','CANCELLED','IN-PROGRESS','HOLD'])
            ->get();

          } elseif(isset($request->transstatus) && !isset($request->rdepartment)) {   

            $histories = Transaction::whereIn('id',
            function($query){
                $query = $query->select('transaction_id')->from('approval_status');
                
                if(auth()->user()->designation == 'VERIFIER' ) {                    
                    
                    $query = $query->where('approver_id', '18');                     

                }elseif(auth()->user()->is_alternate){
                    $query = $query->where('alternate_approver_id',auth()->user()->id);
                }else{
                    $query = $query->where('approver_id',auth()->user()->id);
                }                               
            })
            ->where('created_at', '>=', $date_from . ' 00:00:00.001')
            ->where('created_at', '<=', $date_to . ' 23:59:59.999')            
            ->where('status','=',$request->transstatus)            
            ->get();

          } else {       


            $histories = Transaction::whereIn('id',
            function($query){
                $query = $query->select('transaction_id')->from('approval_status');
                
                if(auth()->user()->designation == 'VERIFIER' ) {                    
                    
                    $query = $query->where('approver_id', '18');                     

                }elseif(auth()->user()->is_alternate){
                    $query = $query->where('alternate_approver_id',auth()->user()->id);
                }else{
                    $query = $query->where('approver_id',auth()->user()->id);
                }                               
            })
            ->where('created_at', '>=', $date_from . ' 00:00:00.001')
            ->where('created_at', '<=', $date_to . ' 23:59:59.999')   
            ->where('status','=',$request->transstatus)   
            ->where('department','=',$request->rdepartment)
            ->get();
          }

          $date_filter=$request->date_filter;
          $transstatus = $request->transstatus;
          $seldepartment = $request->rdepartment;   

      } else {
        
        $carbon_date_from = new Carbon('first day of this month');
        $date_from = $carbon_date_from->toDateString();
        $carbon_date_to = new Carbon('today');
        $date_to = $carbon_date_to->toDateString();

        $date_filter = $date_from .' - '. $date_to;
        $transstatus = '';
        $seldepartment = '';

        $histories = Transaction::whereIn('id',
            function($query){
                $query = $query->select('transaction_id')->from('approval_status');
                
                if(auth()->user()->designation == 'VERIFIER' ) {                    
                    
                    $query = $query->where('approver_id', '18');                     

                }elseif(auth()->user()->is_alternate){
                    $query = $query->where('alternate_approver_id',auth()->user()->id);
                }else{
                    $query = $query->where('approver_id',auth()->user()->id);
                }                               
            })
        ->where('created_at', '>=', $date_from . ' 00:00:00.001')
        ->where('created_at', '<=', $date_to . ' 23:59:59.999')
        ->whereIn('status',['FULLY APPROVED','CANCELLED','IN-PROGRESS','HOLD'])
        ->get();  

        // $histories = Transaction::whereIn('status',['FULLY APPROVED','CANCELLED'])
        // ->get();      
    }

        return view('transaction.history',compact('histories','date_filter','transstatus','departments','seldepartment'));
    }

    public function get_request_details($id)
    {
        $data = Transaction::find($id);
        $query = ApprovalStatus::where('transaction_id',$id);

        // $lastapprover = $query->latest('id')->first();
        $lastapprover = $query->latest('sequence_number')->first();
        $approverstatus = $query->where('approver_id',auth()->user()->id)->first();
        return view('transaction.ajax-details',compact('data','lastapprover','approverstatus'));
    }

    public function details($id)
    {        
        
        $data  = Transaction::find($id);
        
        $query = ApprovalStatus::where('transaction_id',$id);
        // $historicalremarks =  $query->get();

        $historicalremarks = HistoryLog::where('transaction_id',$id)->get();

        // $rs   = $query->latest('id')->first();
        $rs   = $query->latest('sequence_number')->first();   

        // $rs2   = $query->where('current_seq','!=',null)->first();                 

        $lastapprover = $rs->approver_id; 
        $last_app_seq = $rs;
        
        // previous code 
        //$approverstatus = $query->where('approver_id',auth()->user()->id)->first();
        //$currentuser = $query->where('approver_id',Auth::user()->id)->first(); 

        if(auth::user()->designation == 'VERIFIER') {

            $verifiers = User::where('designation', 'VERIFIER')->get();
            $verifier_main = User::where('designation', 'VERIFIED')->first();

            $verifiers_array =  $verifiers->pluck('id')->toArray();

            $verifiers_array[] = $verifier_main->id;

            $currentuser = ApprovalStatus::where('transaction_id',$id)->whereIn('approver_id', $verifiers_array)->first();
            $approverstatus = ApprovalStatus::where('transaction_id',$id)->whereIn('approver_id', $verifiers_array)->first();
            $curr_seq = ApprovalStatus::where('transaction_id',$id)->where('current_seq','=',null)->first(); 

        } else {
            $currentuser = ApprovalStatus::where('transaction_id',$id);
            $approverstatus = ApprovalStatus::where('transaction_id',$id);
                if(auth()->user()->is_alternate){
                    $currentuser = $currentuser->where('alternate_approver_id', Auth::user()->id)->first(); 
                    $approverstatus = $approverstatus->where('alternate_approver_id', Auth::user()->id)->first();
                }else{
                    $currentuser = $currentuser->where('approver_id',Auth::user()->id)->first();
                    $approverstatus = $approverstatus->where('approver_id',auth()->user()->id)->first();
                }
                                
            $curr_seq = ApprovalStatus::where('transaction_id',$id)->where('current_seq','=',null)->first(); 
        }

        // $previous = ApprovalStatus::where('transaction_id',$id)->where('sequence_number', '<', $query2->sequence_number)->orderBy('id', 'DESC')->first();       

        $recurring = ApprovalStatus::where('transaction_id',$id)->where('approver_id',Auth::user()->id)->where('status','PENDING')->get();

        if(count($recurring)>1)  {
            
            $firstinstance =  ApprovalStatus::where('transaction_id',$id)->where('approver_id',Auth::user()->id)->where('status','PENDING')->orderBy('sequence_number','asc')->first();            
            
            $previous = ApprovalStatus::where('transaction_id',$id)->where('sequence_number', '<', $firstinstance->sequence_number)->orderBy('id', 'DESC')->first(); 

            $next = ApprovalStatus::where('transaction_id',$id)->where('sequence_number', '>', $firstinstance->sequence_number)->first(); 

        } else {

            $previous = ApprovalStatus::where('transaction_id',$id)->where('sequence_number', '<', $currentuser->sequence_number)->orderBy('id', 'DESC')->first(); 
            $next = ApprovalStatus::where('transaction_id',$id)->where('sequence_number', '>', $currentuser->sequence_number)->first();

        }               

        if( $curr_seq && auth()->user()->username == 'jccadiao') {
            $currentuser = ApprovalStatus::where('transaction_id',$id)
                ->where('sequence_number', $curr_seq->sequence_number)
                ->where('approver_id',Auth::user()->id)->first(); 
        }

        try {
            $url = env('HK_API_URL') . 'transaction-details/' . $data->ref_req_no;

            $response = Http::get($url, [
                'whic_token' => 'epwKerea6oFJKEAy7zyOkifnv1Re9r'
            ]);

            if ($response->successful()) {
                $transactionDetails = $response->json();
            } else {
                Log::warning('HK API request failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $transactionDetails = null;
            }
        } catch (\Throwable $e) {
            Log::error('HK API request error: ' . $e->getMessage(), [
                'url' => $url,
                'trace' => $e->getTraceAsString(),
            ]);
            $transactionDetails = null;
        }

        return view('transaction.details',compact('transactionDetails', 'data','lastapprover','approverstatus','currentuser','previous','next','curr_seq','last_app_seq','historicalremarks'));
    }

    // approve request
    public function approve($id)
    {
        $transaction = Transaction::find($id);
        $transaction->update(['status' => 'APPROVED']);        

        return response()->json();
    }

    // deny request
    public function deny($id)
    {
        $transaction = Transaction::find($id);
        $transaction->update(['status' => 'DENIED']);

        return response()->json();
    }

    // cancel request
    public function cancel($id)
    {
        $transaction = Transaction::find($id);
        $transaction->update(['status' => 'CANCELLED']);

        return response()->json();
    }

    public function overview($id,$type)
    {
        $data  = Transaction::where('ref_req_no',$id)->where('details',$type)->first();

        return view('transaction.overview',compact('data'));

    }

    // public function batchsubmit(Request $request)
    // {
        
    //     $value = rtrim($request->selected_data,"|");      
    //     $cbid = explode('|', $value); 

    //      foreach ($cbid as $key => $i) {

    //      if($i!=="" || $i!=null) {

    //         $approved = Transaction::find($i);                          

    //         $approvers= ApprovalStatus::where('transaction_id',$approved->id)->where('status','PENDING')->orderBy('sequence_number','ASC')->get();            

    //         $last_sequence = ApprovalStatus::where('transaction_id',$approved->id)->orderBy('sequence_number','DESC')->first();            


    //      // ApprovalStatus::where('sequence_number',$approver->sequence_number)->where('approver_id',Auth::user()->id)->where('transaction_id', $approved->id)->update(['status' => 'APPROVED', 'remarks' => 'by: '.Auth::user()->position, 'current_seq' => $approver->sequence_number]);

    //         if($last_sequence->sequence_number < $approver->sequence_number) {   

    //               Transaction::where('id',$approved->id)->update(['status' => 'FULLY APPROVED']); 

    //                $current_approver_sequence =  ApprovalStatus::where('sequence_number',$approver->sequence_number)->where('approver_id',Auth::user()->id)->where('transaction_id', $approved->id)->first();            

    //                     $next_approver_sequence = ApprovalStatus::where('transaction_id', $approved->id)->where('current_seq',null)->first();

    //                     if (strpos($approved->transid,'CA-') !== false) {

    //                     $requested_trans = Caheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'FULLY APPROVED', 'stages' => '']);

    //                    } elseif (strpos($approved->transid,'LIQ-') !== false) {

    //                         $requested_trans = Liqheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'FULLY APPROVED', 'stages' => '']);

    //                    } elseif(strpos($approved->transid,'RFP-') !== false) {

    //                         $requested_trans = Rfpheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'FULLY APPROVED', 'stages' => '']);

    //                    } elseif(strpos($approved->transid,'TO-') !== false) {

    //                         $requested_trans = Toheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'FULLY APPROVED', 'stages' => '']);

    //                    }

    //         } else {

    //              Transaction::where('id',$approved->id)->update(['status' => 'IN-PROGRESS']);

    //               $current_approver_sequence =  ApprovalStatus::where('sequence_number',$approver->sequence_number)->where('approver_id',Auth::user()->id)->where('transaction_id', $approved->id)->first();            

    //                 $next_approver_sequence = ApprovalStatus::where('transaction_id', $approved->id)->where('current_seq',null)->first();

    //                 $next_approver_username = User::where('id', $next_approver_sequence->approver_id)->first();


    //                 if (strpos($approved->transid,'CA-') !== false) {

    //                     $requested_trans = Caheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'PARTIALLY APPROVED', 'stages' => '']);

    //                } elseif (strpos($approved->transid,'LIQ-') !== false) {

    //                     $requested_trans = Liqheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'PARTIALLY APPROVED', 'stages' => '']);

    //                } elseif(strpos($approved->transid,'RFP-') !== false) {

    //                     $requested_trans = Rfpheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'PARTIALLY APPROVED', 'stages' => '']);

    //                } elseif(strpos($approved->transid,'TO-') !== false) {

    //                     $requested_trans = Toheader::where('transid',$approved->transid)->update(['current_approver' => Auth::user()->username, 'approver_remarks' => 'by: '.Auth::user()->position, 'next_approver' => $next_approver_username->username, 'overallstatus' =>'PARTIALLY APPROVED', 'stages' => '']);

    //                }
    //         }
    //        ApprovalStatus::where('sequence_number',$approver->sequence_number)->where('approver_id',Auth::user()->id)->where('transaction_id', $approved->id)->update(['status' => 'APPROVED', 'remarks' => 'by: '.Auth::user()->position, 'current_seq' => $approver->sequence_number]); break;

    //       } //end of 2nd foreach


    //      } //end of 1st if

    //  }// end of 1st foreach

    //  $notification = array(
    //         'message' => 'Request(s) has been successfully approved.',
    //         'alert-type' => 'info'
    //     );


    //     return back()->with('notification', $notification);

    // }


    public function batchsubmit(Request $request)
    {
        // status = FULLY || PARTIAL
        // overallstats = APPROVED

        $value = trim($request->selected_data,"|");      
        $cbid = explode('|', $value); 

        $isPartial = true;
        $isLast = false;

        $verifiers = User::where('designation', 'VERIFIER')->get();
        $verifier_acc = User::where('designation', 'VERIFIED')->first();

        $verifiers_id = $verifiers->pluck('id')->toArray();
        array_push($verifiers_id, $verifier_acc->id);

        if($request->cancel_reason !== "" || $request->cancel_reason!=null) {
            $request->cancel_reason = $request->selected_action;
        } 

        foreach ($cbid as $key => $i) {

            if($i!=="" || $i!=null) {

                $transaction = Transaction::find($i);                          
                $requested_trans = "";

                if(!$transaction) continue;

                $last_sequence      = ApprovalStatus::where('transaction_id',$transaction->id)->orderBy('sequence_number','DESC')->first();            
                $next_sequence      = ApprovalStatus::where('transaction_id', $transaction->id)
                                            ->where('current_seq', null)
                                            ->first();

                if( auth()->user()->designation == 'VERIFIER' ) {
                    $approver_sequence = ApprovalStatus::where('transaction_id', $transaction->id)->whereIn('approver_id',$verifiers_id)->first();
                } else {
                    $approver_sequence = ApprovalStatus::where('transaction_id', $transaction->id);
                    if( auth()->user()->is_alternate) {
                        $approver_sequence = $approver_sequence->where('alternate_approver_id', auth()->user()->id)->first();                        
                    }else{
                        $approver_sequence = $approver_sequence->where('approver_id', auth()->user()->id)->first();
                    }
                    if( auth()->user()->username == 'jccadiao' && $approver_sequence->status == 'APPROVED') {
                        $approver_sequence = ApprovalStatus::where('transaction_id', $transaction->id)
                            ->where('approver_id', auth()->user()->id)
                            ->latest()->first();
                    }
                }

                // \Log::info($transaction->id);
                // \Log::info('--------------');
                // \Log::info(json_encode($approver_sequence));
                // \Log::info('--------------');
                // \Log::info(json_encode($next_sequence));
                // \Log::info('--------------');
                // \Log::info(json_encode($last_sequence));
                // \Log::info('--------------');

                if( !$next_sequence || $next_sequence->sequence_number != $approver_sequence->sequence_number ) continue;
                          
                if( $last_sequence->sequence_number > $approver_sequence->sequence_number ) {
                    if($request->selected_action == 'APPROVED') {
                        Transaction::where('id',$i)->update(['status' => 'IN-PROGRESS']);
                    } else {
                        Transaction::where('id', $i)->update(['status' => $request->selected_action]);
                    }                    
                } else {
                    if($request->selected_action == 'APPROVED') {
                        Transaction::where('id',$i)->update(['status' => 'FULLY APPROVED']);
                    } else {
                        Transaction::where('id', $i)->update(['status' => $request->selected_action]);
                    }
                    $isPartial = false;
                    $isLast = true;
                }
                
                // \Log::info("is partial ? {$isPartial} : is Last ? {$isLast}");
                // \Log::info('--------------');

                if (strpos($transaction->transid,'CA-') !== false) {
                    $requested_trans = Caheader::where('transid',$transaction->transid);                        
                } elseif (strpos($transaction->transid,'LIQ-') !== false) {
                    $requested_trans = Liqheader::where('transid',$transaction->transid);                        
                } elseif(strpos($transaction->transid,'RFP-') !== false) {
                    $requested_trans = Rfpheader::where('transid',$transaction->transid);                        
                } elseif(strpos($transaction->transid,'TO-') !== false) {
                    $requested_trans = Toheader::where('transid',$transaction->transid);
                }

                // run IMP-MRS Api..
                // try {

                //     $items = array(
                //         "item_id" => $transaction->transid,
                //         "item_status" => $transaction->status
                //     );

                //     $response = Http::post('http://172.16.20.28/pmc-imp_jeff2/public/api/items/details', [
                //                     'items' => $items
                //                 ]);

                // }catch(\GuzzleHttp\Exception\RequestException $e) {
                //     \Log::info($e->getMessage());
                //     \Log::info($e->getCode());
                //     \Log::info($e->getResponse()->getBody()->getContents());
                // }

                // \Log::info("action taken {$request->selected_action}");
                // \Log::info('--------------');

                if( $request->selected_action != 'APPROVED' ) {

                    $approval_steps = ApprovalStatus::where('transaction_id', $i)
                                            ->where('current_seq', null)
                                            ->get();

                    foreach( $approval_steps as $step ) {

                        if( auth()->user()->designation == 'VERIFIER' ) {

                            if(in_array($step->approver_id, $verifiers_id) ) {
                                $step->remarks      = 'by: ' . auth()->user()->position;
                                $step->history      = is_null($step->history) ? 
                                                      'by: ' . auth()->user()->position : $step->history . ' > ' . 'by: ' . auth()->user()->position;
                                $step->updated_last_by = auth()->user()->name;                                
                            } 

                        } else if (  $step->approver_id == auth()->user()->id || $step->alternate_approver_id == auth()->user()->id ) {
                            $step->remarks      = 'by: ' . auth()->user()->position;
                            $step->history      = is_null($step->history) ? 
                                                  'by: ' . auth()->user()->position : $step->history . ' > ' . 'by: ' . auth()->user()->position;
                            $step->updated_last_by = auth()->user()->name;
                            $step->is_alternate = auth()->user()->is_alternate ? 1:0;                            
                        }

                        $step->status       = $request->selected_action;                                               
                        $step->save(); 
                    
                    }

                    $requested_trans = $requested_trans->update([
                        'current_approver'  =>  Auth::user()->username ,
                        'approver_remarks'  =>  $request->cancel_reason . ' by: ' . Auth::user()->position ,
                        'next_approver'     =>  Auth::user()->username ,
                        'overallstatus'     =>  $request->selected_action ,
                        'status'            =>  $request->selected_action ,
                        'overallstatus'     =>  $request->selected_action 
                    ]);

                    // Save a history log of every action made..
                    $this->createHistory($i,auth()->user()->name,$request->selected_action,$request->selected_action != 'CANCELLED' ? 'HOLD by' . auth()->user()->name : $request->cancel_reason);

                } else {

                    ApprovalStatus::where('transaction_id', $i)->where('current_seq', null)->update(['status' => 'PENDING']);

                    $approver_sequence->current_seq = $approver_sequence->sequence_number;
                    $approver_sequence->status      = $request->selected_action;
                    $approver_sequence->history     = is_null($approver_sequence->history) ? 
                                                        'by: ' . auth()->user()->position : 
                                                        $approver_sequence->history . ' > ' . 'by: ' . auth()->user()->position;
                    $approver_sequence->updated_last_by = auth()->user()->name;
                    $approver_sequence->is_alternate = auth()->user()->is_alternate ? 1:0;
                    $approver_sequence->is_current = 0;
                    $approver_sequence->save(); 

                    // \Log::info(json_encode($approver_sequence));
                    // \Log::info('--------------');

                    if(!$isLast) {
                        $new_next_approver = ApprovalStatus::where('transaction_id', $i)
                            ->where('current_seq', null)
                            ->first();                             
                            $new_next_approver->update(['is_current' => 1]);


                        $ddata = [
                            't_id'  => $i ,
                            'new_next_approver' => $new_next_approver
                        ];

                        $new_next_approver_user = User::find($new_next_approver->approver_id);
                        

                        $requested_trans = $requested_trans->update([
                                'current_approver'  =>  Auth::user()->username , 
                                'approver_remarks'  =>  'by: ' . Auth::user()->position , 
                                'next_approver'     =>  $new_next_approver_user->username ,
                                'overallstatus'     =>  $request->selected_action ,
                                'status'            =>  $isPartial ? 'PARTIALLY APPROVED' : 'FULLY APPROVED'
                            ]);
                    } else {     
                        $new_next_approver = ApprovalStatus::where('transaction_id', $i)
                            ->where('current_seq', null)
                            ->first();    
                        if($new_next_approver){
                            $new_next_approver->update(['is_current' => 1]);
                        }
                        $requested_trans = $requested_trans->update([
                                'current_approver'  =>  Auth::user()->username , 
                                'approver_remarks'  =>  'by: ' . Auth::user()->position , 
                                'next_approver'     =>  $verifier_acc->username ,
                                'overallstatus'     =>  $request->selected_action ,
                                'status'            =>  $isPartial ? 'PARTIALLY APPROVED' : 'FULLY APPROVED'
                            ]);
                    }

                    // Save a history log of every action made..
                    $this->createHistory($i,auth()->user()->name,$request->selected_action,'Approved by ' .$request->cancel_reason);
                }


            }

        }

        $notification = array(
            'message' => "Request(s) has been successfully {$request->selected_action}.",
            'alert-type' => 'info'
        );


        return back()->with('notification', $notification);

    }

    public function createHistory($tid,$username,$status,$remarks)
    {
        $history = new HistoryLog;
        $history->transaction_id = $tid;
        $history->approver = $username;
        $history->status = $status;
        $history->remarks = $remarks;
        $history->created_at = date('Y-m-d h:m:s');
        $history->save();
    }

    
}