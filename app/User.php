<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'password', 'class', 'wx', 'qq', 'phone', 'stu_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function info() {
        $result = array(
            'user_id' => $this->id,
            'stu_id' => $this->stu_id,
            'nickname' => $this->nickname,
            'class' => $this->class,
            'qq' => $this->qq,
            'wx' => $this->wx,
            'phone' => $this->phone,
        );

        return $result;
    }

    public function lost()
    {
        return $this->hasMany('App\lost', 'user_id', 'id');
    }

    public function found()
    {
        return $this->hasMany('App\found', 'user_id', 'id');
    }

}
