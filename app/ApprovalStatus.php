<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Transaction;
use App\User;

use DB;

class ApprovalStatus extends Model
{
    public $table='approval_status';

    protected $fillable = ['transaction_id', 'approver_id', 'alternate_approver_id', 'sequence_number', 'status','remarks','current_seq','history','updated_last_by','is_alternate','is_current'];

    public function user()
    {
        return $this->belongsTo('App\User','approver_id');
    }


    public static function validation($rid)
    {
    	// if first approver, allow buttons (Approve,Hold,Cancel)
    	$rs = ApprovalStatus::where('transaction_id',$rid)->where('approver_id',auth()->user()->id)->first();
        $next_data = ApprovalStatus::where('transaction_id',$rid)->where('sequence_number','>',$rs->sequence_number)->where('status','APPROVED')->count();
        $prev_data = ApprovalStatus::where('transaction_id',$rid)->where('sequence_number','<',$rs->sequence_number)->whereIN('status',['APPROVED','PENDING'])->count();

    	if($rs->sequence_number == 0)
    	{
    		if($next_data > 0){
                return 0;
            } else {
                return 1;
            }

    	} else {

            if($prev_data > 0){
                return 1;
            } else {
                
            }
    		$qry = ApprovalStatus::where('transaction_id',$rid)->where('approver_id',auth()->user()->id)->first();

    		if($qry->status == 'PENDING'){
                $qry1 = ApprovalStatus::where('transaction_id',$rid)->where('id','<',$qry->id)->latest('id')->first();

                if($qry1->status == 'APPROVED'){
                    return 1;
                } else {
                    return 0;
                }
    		} else {
                return 0;
            }
    	}
    }

    public static function current_approver($tid)
    {

        // $rs = ApprovalStatus::where('transaction_id',$tid)->where('status','PENDING')->oldest('id')->first();

        // return $rs->approver_id;

        $rs = ApprovalStatus::where('transaction_id',$tid)->where('status','PENDING')->get();

        if (count($rs)>0) {

            // $rs->where('status','PENDING')->oldest('id')->first();

            $rs =  DB::table('approval_status')->where('transaction_id',$tid)
                ->where('status','PENDING')
                ->orderBy('id','asc')->first();

            return $rs->approver_id;
        }        

    }

    public static function next_approver($id,$sqno)
    {

        if(auth()->user()->designation == 'VERIFIER') {
            $verifiers = User::where('designation', 'VERIFIER')->get();
            $verifier_main = User::where('designation', 'VERIFIED')->first();

            $verifiers_array =  $verifiers->pluck('id')->toArray();

            $verifiers_array[] = $verifier_main->id;
            $rs = ApprovalStatus::where('transaction_id',$id)->whereIn('approver_id',$verifiers_array)->first();   
        } else {    
            $rs = ApprovalStatus::where('transaction_id',$id)->where('approver_id',auth()->user()->id)  
                    ->orWhere('alternate_approver_id', auth()->user()->id)->first();            
        }

        $qry = ApprovalStatus::where('id','>',$rs->id);
        $nx  = $qry->first();

        if($qry->count() < 1 ){
            return '';
        } else {
            $us = User::find($nx->approver_id);

            return $us->username;
        }

    }

    public static function no_of_days_of_responding($tid,$uid)
    {

        $query = ApprovalStatus::where('transaction_id',$tid)->where('approver_id',$uid);

        $rs = $query->first();

        if($rs->status == 'PENDING'){
            
            return 'N/A';

        } 

        else {

            if($rs->sequence_number == 0){

                $data = Transaction::find($tid);

                $days = Carbon::parse($data->created_at)->diffInDays($rs->updated_at);

                return $days.' days';

            } else {

                $qry = ApprovalStatus::where('id','<',$rs->id)->latest('id')->first();

                $days = Carbon::parse($qry->updated_at)->diffInDays($rs->updated_at);

                return $days.' days';

            }

        } 
    }
}
