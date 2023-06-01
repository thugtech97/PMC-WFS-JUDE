<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllowedTransaction extends Model
{
    use SoftDeletes;

    public $table='allowed_transactions';

    protected $fillable = ['name', 'token', 'template_id', 'created_at', 'updated_at'];

    public function template()
    {
        return $this->belongsTo('App\Template','template_id');
    }
}
