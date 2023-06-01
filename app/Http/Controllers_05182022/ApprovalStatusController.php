<?php

namespace App\Http\Controllers;

use App\ApprovalStatus;
use App\Transaction;
use App\Mail\NextApproverNotification;
use App\Mail\ApprovedNotification;
use App\Mail\CancelledNotification;
use App\Mail\OnholdNotification;

use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\User;
use DB;
use Illuminate\Support\Facades\Log;

class ApprovalStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ApprovalStatus  $approvalStatus
     * @return \Illuminate\Http\Response
     */
    public function show(ApprovalStatus $approvalStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ApprovalStatus  $approvalStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(ApprovalStatus $approvalStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ApprovalStatus  $approvalStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApprovalStatus $approvalStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ApprovalStatus  $approvalStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApprovalStatus $approvalStatus)
    {
        //
    }

    public function update_request_status(Request $request) {

        $transaction = Transaction::find($request->rid);
        $curr_seq    = ApprovalStatus::where('transaction_id', $transaction->id)
                                ->where('sequence_number', $request->curr_seq)                                
                                ->where('current_seq', null)->first();

        $next_seq    = ApprovalStatus::where('transaction_id', $transaction->id)
                                ->where('sequence_number', '>', $curr_seq->sequence_number)
                                ->first();

        $verifiers = User::where('designation', 'VERIFIER')->get();
        $template_verifier = User::where('designation', 'VERIFIED')->first();
        $message = '';

        if($request->rstatus != 'APPROVED') {

            $remaining_approval = ApprovalStatus::where('transaction_id', $transaction->id)
                                    ->where('sequence_number', '>=', $request->curr_seq)->get();

            foreach($remaining_approval as $approval ) {
                if($request->curr_seq == $approval->sequence_number) {
                    $approval->update([
                        'status'    => $request->rstatus ,
                        'remarks'   => $request->remarks , 
                        'updated_last_by'   => auth()->user()->username ,
                        'history'   => is_null($approval->history) ? auth()->user()->username ." ". \Carbon\Carbon::now()->format('Y-m-d H:i:s') . " " .$request->remarks . " ": $approval->history . " > " . auth()->user()->username ." ". \Carbon\Carbon::now()->format('Y-m-d H:i:s') . " " .$request->remarks ,
                        'is_alternate'      => auth()->user()->is_alternate ? 1:0                        
                    ]);
                } else {
                    $approval->update([
                        'status'    => $request->rstatus
                    ]);
                }
            }

            if($request->rstatus == 'HOLD') {
                $message = 'has been put onto on hold by';
            } else {
                $message = 'has been denied and cancelled by';
            }

            if( auth()->user()->designation == 'VERIFIER' ) {
                foreach($verifiers as $verifier) {
                    // para sa verifier
                    if(auth()->user()->id != $verifier->id ) {
                        if($request->rstatus == 'HOLD') {
                        } else {
                            //Mail::to($verifier->email)->send(new CancelledNotification($transaction,auth()->user(),$message));
                        }
                    }
                }
                // para ky requestor
                if($request->rstatus == 'HOLD') {
                    $m_to_user = User::where('email', $transaction->email)->first();
                    //Mail::to($transaction->email)->send(new OnholdNotification($transaction,$m_to_user,$message));
                } else {
                    $m_to_user = User::where('email', $transaction->email)->first();
                   // Mail::to($transaction->email)->send(new CancelledNotification($transaction,$m_to_user,$message));
                }
            } else { // approver ni dria
                // if($request->rstatus == 'HOLD') {
                //     Mail::to(auth()->user()->email)->send(new OnholdNotification($transaction,auth()->user(),$message));  
                // } else {
                //     Mail::to(auth()->user()->email)->send(new CancelledNotification($transaction,auth()->user(),$message)); 
                // }
                $m_to_user = User::where('email', $transaction->email)->first(); 
                if($request->rstatus == 'HOLD') {
                   // Mail::to($transaction->email)->send(new OnholdNotification($transaction,$m_to_user,$message));
                } else {
                   // Mail::to($transaction->email)->send(new CancelledNotification($transaction,$m_to_user,$message));
                }
            }

        } else {            
           
            $remaining_approval = ApprovalStatus::where('transaction_id', $transaction->id)
                                    ->where('sequence_number', '>=', $request->curr_seq)
                                    ->update(['status'  => 'PENDING']);

            $curr_seq->update([
                'status'            => $request->rstatus ,
                'remarks'           => $request->remarks , 
                'current_seq'       => $request->curr_seq , 
                'updated_last_by'   => auth()->user()->username ,
                'history'           => is_null($curr_seq->history) ? auth()->user()->username ." ". \Carbon\Carbon::now()->format('Y-m-d H:i:s') . " " .$request->remarks . " ": $curr_seq->history . " > " . auth()->user()->username ." ". \Carbon\Carbon::now()->format('Y-m-d H:i:s') . " " .$request->remarks ,
                'is_alternate'      => auth()->user()->is_alternate ? 1:0,
                'is_current' => 0
            ]);

            if( ($next_seq && $next_seq->approver_id == $template_verifier->id) || 
                    ($next_seq && in_array($next_seq->approver_id, $verifiers->pluck('id')->toArray()) ) ) {

                    $next_seq->update(['is_current' => 1]);

                foreach($verifiers as $verifier) {
                    if(!in_array($verifier->username, ['vjarizala','kcfelisilda','cnwasawas','jccadiao','admin'])) {
                        //Mail::to($verifier->email)->send(new NextApproverNotification($verifier, $transaction)); 
                    }
                }

            } else if($next_seq) {

                $next_approver = User::find($next_seq->approver_id);                

                if(!in_array($next_approver->username, ['vjarizala','kcfelisilda','cnwasawas','admin','vgquijada'])) {
                    $next_seq->update(['is_current' => 1]);
                    // Mail::to(auth()->user()->email)
                    //     ->send(new NextApproverNotification($next_approver, $transaction)); 
                    //Mail::to($next_approver->email)->send(new NextApproverNotification($next_approver, $transaction));
                }               
            }


        }

        $transaction->update(['status'  => $request->ov_stat]);

        return response()->json(); 

    }



}
