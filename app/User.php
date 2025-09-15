<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'department', 'dept_id', 'designation', 'role', 'isActive', 'remember_token', 'username', 'email', 'user_type', 'condition', 'section', 'division', 'isdynamic', 'is_dynamic', 'position', 'trans_types'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function alternate_approver($id)
    {
        $user = User::find($id);

        if($id <> NULL){
            return $user->name;
        }
        
    }

    public function setSectionAttribute($value)
    {
        // $this->attributes['section'] = json_encode($value);
        $this->attributes['section'] = implode('|', $value);
    }

    // public function getSectionAttribute($value)
    // {
    //     // return $this->attributes['section'] = json_decode($value);
    //     // return $this->attributes['section'] = explode('|', $value);
    // }

    public function setDepartmentAttribute($value)
    {
        // $this->attributes['department'] = json_encode($value);
        $this->attributes['department'] =  implode('|', $value);
    }

    // public function getDepartmentAttribute($value)
    // {        
    //     // return $this->attributes['department'] = json_decode($value);
    //     // return $this->attributes['department'] = explode('|', $value);
    // }  

    public function setDivisionAttribute($value)
    {
        // $this->attributes['division'] = json_encode($value);
        $this->attributes['division'] =  implode('|', $value);
    }

    // public function getDivisionAttribute($value)
    // {
    //     // return $this->attributes['division'] = json_decode($value);
    //     // return $this->attributes['division'] = explode('|', $value);
    // }


}
