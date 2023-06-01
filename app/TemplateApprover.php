<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class TemplateApprover extends Model
{
    public $table='template_approvers';

    protected $fillable = ['template_id', 'approver_id', 'alternate_approver_id', 'sequence_number','condition','section', 'division', 'isdynamic', 'department', 'is_dynamic', 'designation'];

    public function user()
    {
        return $this->belongsTo('App\User','approver_id');
    }
}
