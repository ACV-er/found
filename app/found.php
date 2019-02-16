<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class found extends Model
{
    //
    protected $fillable = ['title', 'description', 'stu_card', 'card_id', 'address', 'date', 'user_id', 'img', 'solve'];

    public function userInfo() {

        return $this->user->info();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
