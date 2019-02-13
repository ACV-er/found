<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lost extends Model
{
    //
    protected $fillable = ['title', 'description', 'stu_card', 'card_id', 'address', 'date', 'announcer', 'img'];
}
