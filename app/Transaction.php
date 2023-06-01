<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	public $table='transactions';
	
    protected $fillable = ['ref_req_no', 'source_app', 'source_url', 'details', 'requestor', 'status', 'totalamount', 'converted_amount','department', 'transid','email','currency,','purpose','locsite'];

    public function user_details(){

        return $this->belongsTo('App\User','requestor_id');
    }

    public function approvers()
    {
        return $this->hasMany('App\ApprovalStatus','transaction_id','id');
    }

    public static function date_format($date) {
        if ($date == null || trim($date) == '') {
            return "N/A";
        }
        else if ($date != null && strtotime($date) < strtotime('-1 day')) {
            return Carbon::parse($date)->isoFormat('lll');
        }

        return Carbon::parse($date)->diffForHumans();
	}

}
