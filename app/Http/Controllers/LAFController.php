<?php

namespace App\Http\Controllers;

use App\lost;
use App\found;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LAFController extends Controller
{
    //
    public function msg($code, $msg) {
        $status = array(
            '0' => '成功',
            '1' => '缺失参数',
            '2' => '错误访问'
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
            ->where('updated_at', '>', time() - 86400 * 7) //86400秒一天
            ->where('solve', false);

        return $result;
    }


    public function found($id){
        return DB::table('founds')->get()->where('id', 'id');
    }

    public function foundList(){
        $result = DB::table('founds')->get()
            ->where('updated_at', '>', time() - 86400 * 7) //86400秒一天
            ->where('solve', false);

        return $result;
    }

    private function dataHandle($request) {
        $data = $request->only(['title', 'description', 'stu_card', 'card_id', 'address', 'date']);
        if($request->has(['title', 'description', 'stu_card', 'address', 'date']) != true) {
            die($this->msg('1', null));
        }

        $data['img'] = '1232333';
        $data['announcer'] = 'dhd';
        $data['solve'] = 0;

        return $data;
    }

    public function submitLost(Request $request) {
        $result = new lost(
            $this->dataHandle($request)
        );
        $result = $result->save();

        return $result?$this->msg('0', null):$this->msg('2', null);
    }

    public function submitFound(Request $request) {
        $result = new found(
            $this->dataHandle($request)
        );
        $result = $result->save();

        return $result?$this->msg('0', null):$this->msg('2', null);
    }

    public function updateLost(Request $request) {
        $data = $this->dataHandle($request);
        $result = lost::query()->where('id', $request->route('id'))->update($data);

        return $result?$this->msg('0', null):$this->msg('2', null);
    }

    public function updateFound(Request $request) {
        $data = $this->dataHandle($request);
        $result = lost::query()->where('id', $request->route('id'))->update($data);

        return $result?$this->msg('0', null):$this->msg('2', null);
    }

    public function finishLost(Request $request) {
        $result = lost::query()->where('id', $request->route('id'))->update(["solve"=>true]);

        return $result?$this->msg('0', null):$this->msg('2', null);
    }

    public function finishFound(Request $request) {
        $result = found::query()->where('id', $request->route('id'))->update(["solve"=>true]);

        return $result?$this->msg('0', null):$this->msg('2', null);
    }
}
