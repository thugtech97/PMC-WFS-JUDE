<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Toheader extends Model
{

    protected $connection = 'sqlsrv_orem';
    public $table = 'toheaders';
   
    protected $fillable = [     
      'id'
      ,'name'
      ,'empid'
      ,'position'
      ,'department'
      ,'purpose'
      ,'status'
      ,'is_locked'
      ,'requested_by'
      ,'created_at'
      ,'created_by'
      ,'updated_at'
      ,'updated_by'
      ,'departure_date'
      ,'return_date'
      ,'email'
      ,'departure_time'
      ,'return_time'
      ,'ecost'
      ,'is_budget'
      ,'bamount'
      ,'is_unbudget'
      ,'unbudget_reason'
      ,'location'
      ,'act_date_from'
      ,'act_date_to'
      ,'birthdate'
      ,'gender'
      ,'transid'
      ,'acost'
      ,'urgency_reason'
      ,'unbudgeted_amount'
      ,'is_immediate'
      ,'date_needed'
      ,'verify'
      ,'doctype'
      ,'current_approver'
      ,'approver_remarks'
      ,'datetime_needed'
      ,'currency'
      ,'saccounting'
      ,'overallstatus'
      ,'is_wait_docs'
      ,'is_doc_received'
      ,'is_process_request'
      ,'is_ready_release'
      ,'is_payment_release'
      ,'is_liquidated'
      ,'stages'
      ,'is_pending'
      ,'converted_amount'
      ,'is_posted'
      ,'docrecieve'
      ,'is_processing_payment'
      ,'puch_work_order'
    ];   
}
