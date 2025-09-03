<?php

namespace App\Http\Controllers\Api\Hk;

use DB;
use App\User;
use App\Transaction;
use App\AllowedTransaction;
use App\TemplateApprover;
use App\ApprovalStatus;
use App\HistoryLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller {

    public function store(Request $request)
    {
        $db = "PMC-WFS"; // LIVE database 
        $data = $request->transaction;
        $transaction_type = $data['type'] ?? null;
        $transid = $data['transid'] ?? null;

        if (!$transaction_type || !$transid) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required fields: type or transid.'
            ], 400);
        }

        $data_result = DB::table($db.'.dbo.allowed_transactions')->where('name', $transaction_type)->first();

        if (!$data_result) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction type not allowed.'
            ], 403);
        }
        
        if (!isset($data['token']) || $data_result->token !== $data['token']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token.'
            ], 401);
        }

        $existData = DB::table($db.'.dbo.transactions')->where('transid', $transid)->first();

        if ($existData) {
            DB::table($db.'.dbo.transactions')->where('transid', $transid)->update(['status' => 'PENDING']);
            DB::table($db.'.dbo.approval_status')->where('transaction_id', $existData->id)->update(['status' => 'PENDING']);

            return response()->json([
                'success' => true,
                'message' => 'Existing transaction updated to PENDING.',
                'transaction_id' => $existData->id
            ], 200);
        }

        $insertedID = DB::table($db.'.dbo.transactions')->insertGetId([
            'ref_req_no'       => $data['refno'],
            'source_app'       => $data['sourceapp'],
            'source_url'       => $data['sourceurl'],
            'details'          => $transaction_type,
            'requestor'        => $data['requestor'],
            'totalamount'      => 0,
            'converted_amount' => 0,
            'department'       => $data['department'],
            'transid'          => $data['transid'],
            'email'            => $data['email'],
            'status'           => 'PENDING',
            'created_at'       => now(),
            'currency'         => 'PHP',
            'purpose'          => $data['purpose'],
            'name'             => $data['name'],
            'approval_url'     => $data['approval_url'],
        ]);

        $approvers = DB::table($db.'.dbo.template_approvers')->where('template_id', $data_result->template_id)->get();

        foreach ($approvers as $approver) {
            if ($approver->designation === 'MANAGER') {
                $gdept = DB::table($db.'.dbo.transactions')->where('transid', $transid)->value('department');

                $gdivision = DB::table($db.'.dbo.users')->where('department', 'like', '%' . $gdept . '%')->first();

                if (!$gdivision) continue;

                $gmanager = DB::table($db.'.dbo.users')->where('division', 'like', '%' . $gdivision->division . '%')->where('department', 'like', '%' . $gdivision->department . '%')->where('is_alternate', 0)->first();

                $alt_gm = DB::table($db.'.dbo.users')->where('division', 'like', '%' . $gdivision->division . '%')->where('department', 'like', '%' . $gdivision->department . '%')->where('is_alternate', 1)->first();

                DB::table($db.'.dbo.approval_status')->insert([
                    'transaction_id'        => $insertedID,
                    'approver_id'           => $gmanager->id ?? 0,
                    'alternate_approver_id' => $alt_gm->id ?? 0,
                    'sequence_number'       => $approver->sequence_number,
                    'status'                => 'PENDING',
                    'created_at'            => now(),
                    'is_current'            => 1,
                ]);
            }
        }
        
        $this->createHistory($insertedID, $data['requestor'] ?? '', "SUBMITTED", $data['sourceapp'] ?? "");

        return response()->json([
            'success' => true,
            'message' => 'Submitted to WFS successfully.',
            'transaction_id' => $insertedID
        ], 201);
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

    public function history(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|integer|exists:transactions,id',
        ]);

        $transaction = Transaction::find($request->transaction_id);

        $history = HistoryLog::where('transaction_id', $transaction->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'history' => $history
        ], 200);
    }
}