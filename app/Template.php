<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function approvers()
    {
        return $this->hasMany('App\TemplateApprover','template_id');
    }

}
