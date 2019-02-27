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
                return false; //此处数据有误
            }
        }

        return true;
    }

    public function msg($code, $msg) {
        $status = array(
            0 => '成功',
            1 => '缺失参数',
            3 => '错误访问'
        );

        $result = array(
            'code' => $code,
            'status' => $status[$code],
            'data' => $msg
        );

        return json_encode($result,  JSON_UNESCAPED_UNICODE);
    }

    public function lost($id){
        $lost = lost::query()->where('id', $id)->first();
        $result = array_merge($lost->toArray(), $lost->userInfo());

        return $this->msg(0, $result);
    }

    public function lostList(){
        $result = DB::table('losts')->where('updated_at', '>',date('Y-m-d H:i:s', time() - 86400 * 7)) //86400秒一天
            ->where('solve', false)
            ->orderBy('updated_at', 'desc')
            ->get();

        return $this->msg(0, $result);
    }


    public function found($id){
        $found = found::query()->where('id', $id)->first();
        $result = array_merge($found->toArray(), $found->userInfo());

        return $this->msg(0, $result);
    }

    public function foundList(){
        $result = DB::table('founds')->where('updated_at', '>',date('Y-m-d H:i:s', time() - 86400 * 7)) //86400秒一天
            ->where('solve', false)
            ->orderBy('updated_at', 'desc')
            ->get();

        return $this->msg(0, $result);
    }

    public function laf() {
        $result = array(
            'lost' => DB::table('losts')->where('updated_at', '>',date('Y-m-d H:i:s', time() - 86400 * 7)) //86400秒一天
                        ->where('solve', false)
                        ->orderBy('updated_at', 'desc')
                        ->get(),
            'found' => DB::table('founds')->where('updated_at', '>',date('Y-m-d H:i:s', time() - 86400 * 7)) //86400秒一天
                        ->where('solve', false)
                        ->orderBy('updated_at', 'desc')
                        ->get()
        );

        return $this->msg(0, $result);
    }

    protected function saveImg($file){
        $allow_ext = ['jpg', 'jpeg', 'png', 'gif'];

        $extension = $file->getClientOriginalExtension();
        if($file->getClientSize() > 10240000) { //10M
            return false;
        }
        if(in_array($extension, $allow_ext)) {
            $savePath = public_path().'/upload/laf';
            $filename = time().rand(0,100).'.'.$extension;
            $file->move($savePath, $filename);

            return $filename;
        } else {
            return false;
        }
    }

    private function dataHandle($request) {
        $mod = array(
            'title' => '/^[\s\S]{0,300}$/',
            'description' => '/^[\s\S]{0,600}$/',
            'stu_card' => '/^1|0$/',
            'card_id' => '/^20[\d]{8,10}$/',
            'address' => '/[\s\S]{0,90}/',
            'date' => '/^[\d]{4}-[\d]{2}-[\d]{2}$/',
        );

        $data = $request->only(array_keys($mod));
        if(!$this->check($mod, $data)) {
            return $this->msg(3, '数据格式错误'.__LINE__);
        };
        if($data['date'] > date('Y-m-d H:i:s', time())) {
            return $this->msg(3, '数据格式错误'.__LINE__);
        }
        if(!$request->has(['title', 'description', 'stu_card', 'address', 'date'])) {
            return $this->msg(1, __LINE__);
        }
        if($request->hasFile(['img']) && $request->) {
            $path = $this->saveImg($request->file('img'));
            if(!$path) {
                return $this->smg(3, __LINE__);
            }
            $data['img'] = $path;
        }

        $data['user_id'] = session('id');
//        $data['user_id'] = 4;

        return $data;
    }

    public function submitLost(Request $request) {
        $result = new lost(
            $this->dataHandle($request)
        );
        $result = $result->save();

        return $result?$this->msg(0, $result):$this->smg(3, __LINE__);
    }

    public function submitFound(Request $request) {
        $result = new found(
            $this->dataHandle($request)
        );
        $result = $result->save();

        return $result?$this->msg(0, null):$this->smg(3, __LINE__);
    }

    public function updateLost(Request $request) {
        $data = $this->dataHandle($request);
        $result = lost::query()->where('id', $request->route('id'))->first();
        if(!$result->user_id == session('id')) {
            return $this->smg(3, __LINE__);
        }
        if($result->img != null && file_exists(public_path().'/upload/laf/'.$result->img)) { //更新图片时删除以前的图片
            unlink(public_path().'/upload/laf/'.$result->img);
        }
        $result->update($data);

        return $result?$this->msg(0, null):$this->smg(3, __LINE__);
    }

    public function updateFound(Request $request) {
        $data = $this->dataHandle($request);
        $result = found::query()->where('id', $request->route('id'))->first();
        if(!$result->user_id == session('id')) {
            return $this->smg(3, __LINE__);
        }
        if($result->img != null && file_exists(public_path().'/upload/laf/'.$result->img)) { //更新图片时删除以前的图片
            unlink(public_path().'/upload/laf/'.$result->img);
        }
        $result->update($data);

        return $result?$this->msg(0, null):$this->smg(3, __LINE__);
    }

    public function finishLost(Request $request) {
        $result = lost::query()->where('id', $request->route('id'))->first();
        if(!$result->user_id == session('id')) {
            return $this->smg(3, __LINE__);
        }
        $result->update(["solve"=>true]);

        return $result?$this->msg(0, null):$this->smg(3, __LINE__);
    }

    public function finishFound(Request $request) {
        $result = found::query()->where('id', $request->route('id'))->first();
        if(!$result->user_id == session('id')) {
            return $this->smg(3, __LINE__);
        }
        $result->update(["solve"=>true]);

        return $result?$this->msg(0, null):$this->smg(3, __LINE__);
    }

    public function markLost(Request $request) {
        $result = lost::query()->where('id', $request->route('id'))->first();
        if(!$result->user_id == session('id')) {
            return $this->smg(3, __LINE__);
        }
        $result->update(["mark"=>true]);

        return $result?$this->msg(0, null):$this->smg(3, __LINE__);
    }

    public function markFound(Request $request) {
        $result = found::query()->where('id', $request->route('id'))->first();
        if(!$result->user_id == session('id')) {
            return $this->smg(3, __LINE__);
        }
        $result->update(["mark"=>true]);

        return $result?$this->msg(0, null):$this->smg(3, __LINE__);
    }
}
