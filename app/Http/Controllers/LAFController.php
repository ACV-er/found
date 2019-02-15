<?php

namespace App\Http\Controllers;

use App\lost;
use App\found;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LAFController extends Controller
{
    //
    public function check($mod, $data_array) { //$mod为数据数组键名对应数据的正则, $data_array为数据数组
        foreach ($data_array as $key=>$value) { //$data_array的键名在$mod数组中必有对应  若无请检查调用时有无逻辑漏洞
            if(!preg_match($mod[$key], $value)) {
                die(''); //此处数据有误
            }
        }
    }

    public function msg($code, $msg) {
        $status = array(
            0 => '成功',
            1 => '缺失参数',
            2 => '错误访问'
        );

        $result = array(
            'code' => $code,
            'status' => $status[$code],
            'data' => $msg
        );

        return json_encode($result,  JSON_UNESCAPED_UNICODE);
    }

    public function lost($id){
        return DB::table('losts')->get()->where('id', 'id');
    }

    public function lostList(){
        $result = DB::table('losts')->get()
            ->where('updated_at', '>',date('Y-m-d H:i:s', time() - 86400 * 7)) //86400秒一天
            ->where('solve', false);

        return $result;
    }


    public function found($id){
        return DB::table('founds')->get()->where('id', 'id');
    }

    public function foundList(){
        $result = DB::table('founds')->get()
            ->where('updated_at', '>',date('Y-m-d H:i:s', time() - 86400 * 7)) //86400秒一天
            ->where('solve', false);

        return $result;
    }

    private function dataHandle($request) {
        $mod = array(
            'title' => '/^[\s\S]{0,100}$/',
            'description' => '/^[\s\S]{0,200}$/',
            'stu_card' => '/^1|0$/',
            'card_id' => '/^20[\d]{8,10}$/',
            'address' => '/[\s\S]{0,50}/',
            'date' => '/^[\d]{4}-[\d]{2}-[\d]{2}$/',
        );
        $data = $request->only(array_keys($mod));
        $this->check($mod, $data);
        if($request->has(['title', 'description', 'stu_card', 'address', 'date']) != true) {
            die($this->msg(1, null));
        }

        $data['img'] = '1232333';
        $data['user_id'] = session('id');
        $data['solve'] = 0;

        return $data;
    }

    public function submitLost(Request $request) {
        $result = new lost(
            $this->dataHandle($request)
        );
        $result = $result->save();

        return $result?$this->msg(0, null):$this->msg(2, __LINE__);
    }

    public function submitFound(Request $request) {
        $result = new found(
            $this->dataHandle($request)
        );
        $result = $result->save();

        return $result?$this->msg(0, null):$this->msg(2, __LINE__);
    }

    public function updateLost(Request $request) {
        $data = $this->dataHandle($request);
        $result = lost::query()->where('id', $request->route('id'))->first();
        //插入验证登陆者
        $result->update($data);

        return $result?$this->msg(0, null):$this->msg(2, __LINE__);
    }

    public function updateFound(Request $request) {
        $data = $this->dataHandle($request);
        $result = found::query()->where('id', $request->route('id'))->first();
        //插入验证登陆者
        $result->update($data);

        return $result?$this->msg(0, null):$this->msg(2, __LINE__);
    }

    public function finishLost(Request $request) {
        $result = lost::query()->where('id', $request->route('id'))->first();
        //插入验证登陆者
        $result->update(["solve"=>true]);

        return $result?$this->msg(0, null):$this->msg(2, __LINE__);
    }

    public function finishFound(Request $request) {
        $result = found::query()->where('id', $request->route('id'))->first();
        //插入验证登陆者
        $result->update(["solve"=>true]);

        return $result?$this->msg(0, null):$this->msg(2, __LINE__);
    }
}
