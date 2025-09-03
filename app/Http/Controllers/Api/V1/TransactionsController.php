<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use App\User;
use App\Transaction;
use App\AllowedTransaction;
use App\TemplateApprover;
use App\ApprovalStatus;
use App\HistoryLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{

    public function handshake()
    {
        // testing handshake via fetching data
        $transactions = Transaction::take(10)->orderBy('created_at','DESC')->get();
        
        return response()->json([
            'status' => 'success',
            'transactions' => $transactions
        ], 200);
    }

    public function transmit(Request $request)
    {
        // IMPORTANT NOTE: Change this in production use.
        // $db = "PMC-WFS"; // 20.28 test database
        $db = "PMC-WFS"; // LIVE database

        $allowed = DB::table($db.'.dbo.allowed_transactions')->where('token', $request->transaction['token'])->where('name', $request->transaction['type'])->first();

        if ($allowed || $allowed != NULL) {

            $transactions = DB::table($db.'.dbo.transactions')->where('ref_req_no', $request->transaction['refno'])->first();

            if ($transactions) {
                DB::table($db.'.dbo.transactions')
                    ->where('ref_req_no', $request->transaction['refno'])
                    ->update(['status' => 'PENDING']);
            }
            else {

                $transactions = DB::table($db.'.dbo.transactions')->insert([
                    'ref_req_no' => $request->transaction['refno'],
                    'source_app' => $request->transaction['sourceapp'],
                    'source_url' => $request->transaction['sourceurl'],
                    'details' => $request->transaction['type'],
                    'requestor' => $request->transaction['requestor'],
                    'status' => $request->transaction['status'],
                    'department' => $request->transaction['department'],
                    'transid' => $request->transaction['transid'],
                    'totalamount' =>$request->transaction['totalamount'],
                    'converted_amount' =>$request->transaction['converted_amount'],
                    'currency' =>$request->transaction['currency'],
                    'email' => $request->transaction['email'],
                    'purpose' => $request->transaction['purpose'],
                    'name' => $request->transaction['name'],
                    'locsite' =>$request->transaction['locsite'],
                    'approval_url' => $request->transaction['approval_url'],
                    'created_at' => $request->transaction['created_at']

                ]);

                $transactions = DB::table($db.'.dbo.transactions')->where('transid', $request->transaction['transid'])->first();

                $approvers = DB::table($db.'.dbo.template_approvers')->where('template_id', $allowed->template_id)->get();

                $is_current = 1;

                foreach($approvers as $approver) {

                    $approver_department = DB::table($db.'.dbo.transactions')->where('transid', $transactions->transid)->first();

                    $approver_division = DB::table($db.'.dbo.users')->where('department' , 'LIKE', "%{$approver_department->department}%")->first();

                    if ($approver->designation == 'DIVISION MANAGER' && $approver->is_dynamic == 'YES' && strpos($approver_department->transid, 'MRS') !== false) {
                        $approver_manager = DB::table($db.'.dbo.users')->where('designation' , 'LIKE', "%{$approver->designation}%")
                                                        ->where('is_alternate' , 0)->first();
                    }
                    else {
                        $approver_manager = DB::table($db.'.dbo.users')->where('division' , 'LIKE', "%{$approver_division->division}%")
                                                        ->where('department' , 'LIKE', "%{$approver_division->department}%")
                                                        ->where('designation' , 'LIKE', "%{$approver->designation}%")
                                                        ->where('is_alternate' , 0)->first();
                    }

                    $approver_alt_manager = DB::table($db.'.dbo.users')->where('division' , 'LIKE', "%{$approver_division->division}%")
                                                    ->where('department' , 'LIKE', "%{$approver_division->department}%")
                                                    ->where('is_alternate' , 1)->first();

                    if ($approver_alt_manager || $approver_alt_manager != NULL ) {
                        $approver_alt_approver = $approver_alt_manager->id;
                    }else {
                        $approver_alt_approver = 0;
                    }

                    $approval = DB::table($db.'.dbo.approval_status')->insert([
                        'transaction_id' => $transactions->id,
                        'approver_id' => $approver_manager->id,
                        'alternate_approver_id' => $approver_alt_approver,
                        'sequence_number' => $approver->sequence_number,
                        'status' => $transactions->status,
                        'created_at' => date("Y-m-d h:i:s"),
                        'is_current' => $is_current > 1 ? 0 : 1
                    ]);

                    $is_current++;
                }

            }

            $checkRun = DB::table($db.'.dbo.approval_status')->where('transaction_id', $transactions->id)->first();

            if ($checkRun) {
                return response()->json([
                    'status' => "success",
                    'message' => "Connection Confirmed!",
                    'Approval Status Sync' => $approval,
                    'Transactions Sync' => $transactions
                ], 200);
            } else {
                return response()->json([
                    'status' => "error",
                    'message' => "There is a problem upon saving Approvals.",
                ], 400);
            }

        } else {
            return response()->json([
                'status' => "error",
                'message' => "You are not Authorized!",
            ], 401);
        }

    }

    public function history(Request $request, $val) {

        // dd($reques);
        $transaction_history = Transaction::where('ref_req_no', $val)->first();
        $history = HistoryLog::where('transaction_id', $transaction_history->id)->get();

        // $history->updated_at = date('Y-m-d h:s:m', $history->updated_at);

        return response()->json([
            'status' => 'success',
            'history' => $history
        ], 200);
    }

}