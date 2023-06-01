<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class HRISAgusanDivision extends Model
{
	protected $connection = 'sqlsrv_agn_hris';
    public $table = 'HRDivision';
}
