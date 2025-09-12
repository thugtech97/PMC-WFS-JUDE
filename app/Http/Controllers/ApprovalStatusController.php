<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Exception;
use App\HistoryLog;
use App\Transaction;
use GuzzleHttp\Client;
use App\ApprovalStatus;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;

use App\Mail\OnholdNotification;
use App\Mail\ApprovedNotification;
use App\Mail\CancelledNotification;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\NextApproverNotification;

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
        // dd($request->trans_type);
        $transaction = Transaction::find($request->rid);

        if ($transaction && $transaction->details === "HK" && $transaction->approval_url) {
            $response = $this->submit_status_to_app($transaction->approval_url, $transaction->id, $request->ov_stat, $request->remarks);

            if (!$response) {
                return response()->json([
                    'status' => "success",
                    'message' => 'Failed to submit status to source application.',
                ], 500);
            }
        }

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
                        'updated_last_by_name'   => auth()->user()->name ,
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
                'updated_last_by_name'   => auth()->user()->name ,
                'history'           => is_null($curr_seq->history) ? auth()->user()->username ." ". \Carbon\Carbon::now()->format('Y-m-d H:i:s') . " " .$request->remarks . " ": $curr_seq->history . " > " . auth()->user()->username ." ". \Carbon\Carbon::now()->format('Y-m-d H:i:s') . " " .$request->remarks ,
                'is_alternate'      => auth()->user()->is_alternate ? 1:0,
                'is_current' => 0
            ]);

            if( ($next_seq && $next_seq->approver_id == $template_verifier->id) || 
                    ($next_seq && in_array($next_seq->approver_id, $verifiers->pluck('id')->toArray()) ) ) {

                    $next_seq->update(['is_current' => 1]);

                foreach($verifiers as $verifier) {
                    if(!in_array($verifier->username, ['vjarizala','kcfelisilda','cnwasawas','jccadiao','admin','dslapasanda'])) {
                        //Mail::to($verifier->email)->send(new NextApproverNotification($verifier, $transaction)); 
                    }
                }

            } else if($next_seq) {

                $next_approver = User::find($next_seq->approver_id);                

                if(!in_array($next_approver->username, ['vjarizala','kcfelisilda','cnwasawas','admin','dslapasanda'])) {
                    $next_seq->update(['is_current' => 1]);
                    // Mail::to(auth()->user()->email)
                    //     ->send(new NextApproverNotification($next_approver, $transaction)); 
                    // Mail::to($next_approver->email)->send(new NextApproverNotification($next_approver, $transaction));
                }               
            }
        }

        $transaction->update(['status'  => $request->ov_stat]);

        // run OSTR Api..
        $response = true;
        if ($request->trans_type == 'OSTR') {
            try {

                $stock = array(
                    "stock_id" => $transaction->ref_req_no,
                    "stock_status" => $transaction->status
                );

                // IMPORTANT NOTE: Change this in production use.
                // $response = Http::get('http://172.16.20.28/PMC-OSTR/public/api/transactions'); // handshake
                // $response = Http::post('http://172.16.20.28/PMC-OSTR/public/api/ostr-sync', [ // 20.28 url
                $response = Http::post('http://mlsvrostdr/PMC-OSTR/public/api/ostr-sync', [ // LIVE url
                                'stock' => $stock
                            ]);

            } catch(\GuzzleHttp\Exception\RequestException $e) {
                \Log::info($e->getMessage());
                \Log::info($e->getCode());
                \Log::info($e->getResponse()->getBody()->getContents());
            }

        }

        // run IMP-MRS Api..
        // if ($request->trans_type == 'IMP') {
        //     try {

        //         $items = array(
        //             "id" => $transaction->transid,
        //             "status" => $transaction->status
        //         );

        //         dd($items);

        //         $response = Http::post('http://172.16.20.28/pmc-imp_jeff2/public/api/items/updateItemDetails', [
        //                         'items' => $items
        //                     ]);

        //     } catch(\GuzzleHttp\Exception\RequestException $e) {
        //         \Log::info($e->getMessage());
        //         \Log::info($e->getCode());
        //         \Log::info($e->getResponse()->getBody()->getContents());
        //     }

        // }

        // Save a history log of every action made..
        $this->createHistory($transaction->id,auth()->user()->name,$transaction->status,$request->remarks);

        return response()->json([
                'status' => "success",
                'message' => "Data transfer is completed!",
                'reply' => $response
            ], 200);
        
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

    public function submit_status_to_app($approval_url, $id, $status, $remarks): bool
    {
        try {
            $payload = [
                'transaction_id' => $id,
                'status' => $status,
                'acted_by' => Auth::user()->name,
                'remarks' => $remarks
            ];

            $response = Http::post($approval_url, $payload);

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }

}
