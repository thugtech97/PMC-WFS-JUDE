<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
	public $table='history_logs';
	
    protected $fillable = ['transaction_id', 'approver_id', 'status', 'remarks', 'updated_at'];

    public function history(){

        return $this->belongsTo('App\Transaction', 'transaction_id');
    }

    public function approver()
    {
        return $this->hasMany('App\User', 'approver_id');
    }
}