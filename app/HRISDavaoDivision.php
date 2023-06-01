<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class HRISDavaoDivision extends Model
{
	protected $connection = 'sqlsrv_dvo_hris';
    public $table = 'HRDivision';
}
