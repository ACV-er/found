<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lost extends Model
{
    //
    protected $fillable = ['title', 'description', 'stu_card', 'card_id', 'address', 'date', 'announcer', 'img', 'solve'];

    public function user()
    {
        return $this->belongsTo('App\User', 'announcer', 'stu_id');
    }
}
