<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    //
    protected $fillable = ['stu_id', 'power', 'password', 'name'];
    public function info()
    {
        $result = array(
            'user_id' => $this->id,
            'stu_id' => $this->stu_id,
            'name' => $this->name,
            'power' => $this->power,
        );

        return $result;
    }
}
