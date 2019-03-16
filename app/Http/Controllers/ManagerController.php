<?php

namespace App\Http\Controllers;

use App\Manager;
use App\Post;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function check($mod, $data_array)
    { //$mod为数据数组键名对应数据的正则, $data_array为数据数组
        foreach ($data_array as $key => $value) { //$data_array的键名在$mod数组中必有对应  若无请检查调用时有无逻辑漏洞
            if (!preg_match($mod[$key], $value)) {
                return false; //此处数据有误
            }
        }

        return true;
    }

    public function msg($code, $msg)
    {
        $status = array(
            0 => '成功',
            1 => '缺失参数',
            2 => '账号密码错误',
            3 => '错误访问',
            4 => '未知错误',
            5 => '权限不足'
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
        $api_url = $api_url . "?role=" . env('ROLE') . '&hash=' . env('HASH') . '&sid=' . urlencode($sid) . '&password=' . urlencode($password);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    public function login(Request $request)
    {
        session(['manager_login' => false]);
        $mod = array(
            'stu_id' => '/(^20[\d]{8,10}$)|(^[a-z]{2,10}$)/',
            'password' => '/^[^\s]{8,20}$/',
        );
        if (!$request->has(array_keys($mod))) {
            return $this->msg(1, __LINE__);
        }
        $data = $request->only(array_keys($mod));
        if (!$this->check($mod, $data)) {
            return $this->msg(3, '数据格式错误' . __LINE__);
        };
        $user = Manager::query()->where('stu_id', $data['stu_id'])->first();
        if(!$user) {
            return $this->msg(2, __LINE__);
        }
        if ($user->password === md5($data['password'])) {
            session(['manager_login' => true, 'manager_id' => $user->id, 'power' => $user->power]);
            return $this->msg(0, $user->info());
        } else {
            $output = $this->chechUser(urlencode($data['stu_id']), $data['password']);
            if ($output['code'] == 0) {
                $user->password = md5($data['password']);
                $user->name = $output['data']['name'];
                $user->save();
                session(['manager_login' => true, 'manager_id' => $user->id, 'power' => $user->power]);
                return $this->msg(0, $user->info());
            } else {
                return $this->msg(2, __LINE__);
            }
        }
    }

    public function updatePost(Request $request) {
        $post = Post::query()->where('id', $request->route('id'))->first();
        $data = $request->only(['title', 'description']);

        $post->update($data);
        if($post) {
            return $this->msg(0, null);
        } else {
            return $this->msg(4, __LINE__);
        }
    }

    public function deletePost(Request $request) {
        $post = Post::query()->where('id', $request->route('id'))->first();
        try {
            $result = $post->delete();
        } catch (\Exception $e) {
            return $this->msg(4, $e.__LINE__);
        }

        if($result) {
            return $this->msg(0, null);
        } else {
            return $this->msg(4, __LINE__);
        }
    }

    public function managerList() {
        $managers = Manager::query()->get()->toArray();

        return $this->msg(0, $managers);
    }
    
    public function managerDelete(Request $request) {
        $manager = Manager::query()->where('id', $request->route('id'))->first();
        if(session('power') < $manager['power']) {
            try {
                $result = $manager->delete();
            } catch (\Exception $e) {
                return $this->msg(4, $e.__LINE__);
            }
            if($result) {
                return $this->msg(0, null);
            } else {
                return $this->msg(4, __LINE__);
            }
        } else {
            return $this->msg(5, __LINE__);
        }

    }

    public function managerAdd(Request $request) {
        $data = $request->only(['stu_id', 'power']);
        $data['password'] = '0';
        $data['name'] = "从未登陆";
        $test = Manager::query()->where('stu_id', $data['stu_id'])->first();
        if($test) {
            return $this->msg(3, '用户已存在');
        }
        if($data['power'] > 2 || session('power') >= $data['power']) {
            return $this->msg(5, __LINE__);
        }
//        return $this->msg(1, $data);
        $manager = new Manager($data);
        $result = $manager->save();

        if($result){
            return $this->msg(0, null);
        } else {
            return $this->msg(3, __LINE__);
        }
    }

    public function managerUpdate(Request $request) {
        $data = $request->only(['stu_id', 'power']);
        $maneger = Manager::query()->where('id', $request->route('id'))->first();
        if($data['power'] > 2 || session('power') >= $data['power'] || $maneger->power <= session('power')) {
            return $this->msg(5, __LINE__);
        }
        $result = $maneger->update($data);

        if($result){
            return $this->msg(0, null);
        } else {
            return $this->msg(3, __LINE__);
        }
    }

}
