<?php

    namespace App;

    use Illuminate\Notifications\Notifiable;
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
            'nickname', 'password', 'class', 'wx', 'qq', 'phone', 'stu_id', 'black',
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

        public function info()
        {
            $result = array(
                'user_id' => $this->id,
                'stu_id' => $this->stu_id,
                'nickname' => $this->nickname,
                'class' => $this->class,
                'qq' => $this->qq,
                'wx' => $this->wx,
                'phone' => $this->phone,
                'avatar' => $this->avatar
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

        public function post()
        {
            return $this->hasMany('App\Post', 'user_id', 'id');
        }

    }
