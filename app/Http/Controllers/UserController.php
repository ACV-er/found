<?php

    namespace App\Http\Controllers;

    use App\found;
    use App\lost;
    use Illuminate\Http\Request;
    use App\User;

    class UserController extends Controller
    {
        //
        public function check($mod, $data_array)
        { //$mod为数据数组键名对应数据的正则, $data_array为数据数组
            foreach ($data_array as $key => $value) { //$data_array的键名在$mod数组中必有对应  若无请检查调用时有无逻辑漏洞
                if (!preg_match($mod[$key], $value)) {
                    die($this->msg(3, $mod[$key].' => '.$value)); //此处数据有误
                }
            }
        }

        public function msg($code, $msg)
        {
            $status = array(
                0 => '成功',
                1 => '缺失参数',
                2 => '账号密码错误',
                3 => '错误访问',
                4 => '未知错误',
                5 => '其他错误'
            );

            $result = array(
                'code' => $code,
                'status' => $status[$code],
                'data' => $msg
            );

            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        private function chechUser($sid, $password)
        {
            $api_url = "https://api.sky31.com/edu-new/student_info.php";
            $api_url = $api_url . "?role=" . env('ROLE') . '&hash=' . env('HASH') . '&sid=' . $sid . '&password=' . $password;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            return json_decode($output, true);
        }

        public function login(Request $request)
        {
            $mod = array(
                'stu_id' => '/^20[\d]{8,10}$/',
                'password' => '/^[\w]{8,20}$/',
            );
            if (!$request->has(array_keys($mod))) {
                die($this->msg(1, __LINE__));
            }
            $data = $request->only(array_keys($mod));
            $this->check($mod, $data);
            $user = User::query()->where('stu_id', $data['stu_id'])->first();
            if (!$user) {
                // 该用户未在数据库中 用户名错误 或 用户从未登录
                //利用三翼api确定用户账号密码是否正确
                $output = $this->chechUser(urlencode($data['stu_id']), $data['password']);

                if ($output['code'] == 0) {
                    $info = array(
                        'nickname' => '热心的路人甲',
                        'stu_id' => $data['stu_id'],
                        'password' => md5($data['password']),
                    );
                    $user = new User($info);
                    $result = $user->save();
                    if ($result) {
                        session(['login' => true, 'id' => $user->id]);
                        return $this->msg(0, $user->info());
                    } else {
                        return $this->msg(4, __LINE__);
                    }

                } else {
                    //失败
                    return $this->msg(2, __LINE__);
                }
            } else {
                if ($user->password === md5($data['password'])) {
                    session(['login' => true, 'id' => $user->id]);
                    return $this->msg(0, $user->info());
                } else {
                    $output = $this->chechUser(urlencode($data['stu_id']), $data['password']);
                    if ($output['code'] == 0) {
                        $user->password = md5($data['password']);
                        $user->save();
                        session(['login' => true, 'id' => $user->id]);
                        return $this->msg(0, $user->info());
                    } else {
                        return $this->msg(2, __LINE__);
                    }
                }

            }

        }

        public function updateUserInfo(Request $request)
        {
            $mod = array(
                'nickname' => '/^[^\s]{2,16}$/',
                'phone' => '/^1[0-9]{10}$/',
                'qq' => '/^[0-9]{5,13}$/',
                'wx' => '/^[a-zA-Z]{1}[-_a-zA-Z0-9]{5,19}+$/',
                'class' => '/^[^\s]{5,30}$/'
            );
            if (!$request->has(['nickname'])) {
                die($this->msg(1, __LINE__));
            }
            if( empty($request->only(['qq', 'wx', 'phone'])) ) {
                die($this->msg(3, '???'));
            }
            $data = $request->only(array_keys($mod));
            $this->check($mod, $data);

//            $user = User::query()->where('id', $request->session()->get('id'))->update($data);
            $user = User::query()->where('id', 4)->update($data);
            if($user) {
                return $this->msg(0, null);
            } else {
                return $this->msg(4, '数据更新失败'.__LINE__);
            }
        }

        public function getUserLost(Request $request)
        {
//            $List = lost::query()->where('user_id', $request->session()->get('id'))->get();
            $List = lost::query()->where('user_id', 4)->get();
            return $List;
        }

        public function getUserFound(Request $request)
        {
//            $List = found::query()->where('user_id', $request->session()->get('id'))->get();
            $List = found::query()->where('user_id', 4)->get();
            return $List;
        }
    }
