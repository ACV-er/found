<?php

    namespace App\Http\Controllers;

    use App\Post;
    use Illuminate\Http\Request;
    use App\User;

    use Symfony\Component\HttpFoundation\File\UploadedFile;

    class LAFController extends Controller
    {
        //
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
                3 => '错误访问',
                4 => '未知错误'
            );

            $result = array(
                'code' => $code,
                'status' => $status[$code],
                'data' => $msg
            );

            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        public function post(Request $request) // 获取单个帖子详情
        {
            $post = Post::query()->where('id', $request->route('id'))->first();
            $result = array_merge($post->toArray(), $post->userInfo());

            return $this->msg(0, $result);
        }

        public function postList() // 获取帖子列表
        {
            $result = Post::query()->where('updated_at', '>', date('Y-m-d H:i:s', time() - 86400 * 7))//86400秒一天
            ->where('solve', false)
                ->orderBy('updated_at', 'desc')
                ->get();

            return $this->msg(0, $result);
        }

        protected function saveImg(UploadedFile $file = null) //保存图片
        {
            $allow_ext = ['jpg', 'jpeg', 'png', 'gif'];

            $extension = $file->getClientOriginalExtension();
            if ($file->getSize() > 10240000) { //10M
                return false;
            }
            if (in_array($extension, $allow_ext)) {
                $savePath = public_path() . '/upload/laf';
                $filename = time() . rand(0, 100) . '.' . $extension;
                $file->move($savePath, $filename);

                return $filename;
            } else {
                return false;
            }
        }

        private function dataHandle(Request $request = null) //处理帖子信息
        {

            // 在发布的时候可以更新个人信息 诡异写法, 本人拒绝
            $mod = array(
                'nickname' => '/^[^\s]{2,30}$/',
                'phone' => '/(^1[0-9]{10}$)|(^$)/', //后面的 |(^$) 用来匹配 null
                'qq' => '/(^[0-9]{5,13}$)|(^$)/',
                'wx' => '/(^[a-zA-Z]{1}[-_a-zA-Z0-9]{5,19}$)|(^$)/',
                'class' => '/(^[^\s]{5,60}$)|(^$)/'
            );
            if (!$request->has(['nickname', 'qq', 'wx', 'phone', 'class'])) {
                return $this->msg(1, __LINE__);
            }

            $contact = $request->only(['qq', 'wx', 'phone']);
            if (!$contact['qq'] && !$contact['wx'] && !$contact['phone']) { //三者必填其一
                return $this->msg(3, '???' . __LINE__);
            }


            $data = $request->only(array_keys($mod));
            if (!$this->check($mod, $data)) {
                return $this->msg(3, '数据格式错误' . __LINE__);
            };

            $user = User::query()->where('id', $request->session()->get('id'))->update($data);

            if (!$user) {
                return $this->msg(4, '数据更新失败' . __LINE__);
            }

            //诡异写法结束


            $mod = array(
                'title' => '/^[\s\S]{0,300}$/',
                'description' => '/^[\s\S]{0,600}$/',
                'stu_card' => '/^1|0$/',
                'type' => '/^1|0$/',
                'card_id' => '/^20[\d]{8,10}$/',
                'address' => '/[\s\S]{0,90}/',
                'date' => '/^[\d]{4}-[\d]{2}-[\d]{2}$/',
            );

            $data = $request->only(array_keys($mod));
            if (!$this->check($mod, $data)) {
                return $this->msg(3, '数据格式错误' . __LINE__);
            };

            if (!$request->has(['title', 'description', 'stu_card', 'address', 'date', 'type'])) {
                return $this->msg(1, __LINE__);
            }
            if ($data['date'] > date('Y-m-d H:i:s', time())) {
                return $this->msg(3, '数据格式错误' . __LINE__);
            }
            if ($request->hasFile('img')) {
                $path = $this->saveImg($request->file('img'));
                if (!$path) {
                    return $this->msg(3, __LINE__);
                }
                $data['img'] = $path;
            }

            $data['user_id'] = session('id');

            return $data;
        }

        public function submitPost(Request $request) //发布帖子
        {

            $data = $this->dataHandle($request);
            if (!is_array($data)) {
                return $data;
            }
            $result = new Post($data);
            $result = $result->save();

            if($result){
                session(['time' => time()]); //防止重复提交

                return $this->msg(0, $result);
            } else {
                return $this->msg(3, __LINE__);
            }
        }

        public function updatePost(Request $request) //更新帖子
        {
            $data = $this->dataHandle($request);
            if (!is_array($data)) {
                return $data;
            }
            $result = Post::query()->where('id', $request->route('id'))->first();
            if (!$result->user_id == session('id')) {
                return $this->msg(3, __LINE__);
            }
            if ($request->hasFile('img') && file_exists(public_path() . '/upload/laf/' . $result->img)) { //更新图片时删除以前的图片
                unlink(public_path() . '/upload/laf/' . $result->img);
            }
            $result = $result->update($data);

            return $result ? $this->msg(0, null) : $this->msg(3, __LINE__);
        }

        public function finishPost(Request $request) //完成帖子  已找到
        {
            $result = Post::query()->where('id', $request->route('id'))->first();
            if (!$result->user_id == session('id')) {
                return $this->msg(3, __LINE__);
            }
            $result = $result->update(["solve" => true]);

            return $result ? $this->msg(0, null) : $this->msg(3, __LINE__);
        }

        public function markPost(Request $request) // 标记  之前有这个需求0.0
        {
            $result = Post::query()->where('id', $request->route('id'))->first();
            if (!$result->user_id == session('id')) {
                return $this->msg(3, __LINE__);
            }
            $result = $result->update(["mark" => true]);

            return $result ? $this->msg(0, null) : $this->msg(3, __LINE__);
        }

        public function search(Request $request) {
            if (!$request->has(['keyword'])) {
                return $this->msg(1, __LINE__);
            }

            $keyword = preg_replace("/^\xef\xbb\xbf/", '', $request->only('keyword')['keyword']);
            $keyword = json_decode($keyword, true);

            if (!is_array($keyword) || count($keyword) > 5) {
                return $this->msg(3, __LINE__);
            }
            for($i=0; $i<count($keyword); $i++) {
                $keyword[$i] = "%".$keyword[$i]."%";
            }
            for($i=count($keyword); $i<5; $i++) {
                $keyword[$i] = '%_%';
            }
            $result = Post::query()->whereRaw(
                "concat(`address`,`title`,`description`) like ? AND ".
                "concat(`address`,`title`,`description`) like ? AND ".
                "concat(`address`,`title`,`description`) like ? AND ".
                "concat(`address`,`title`,`description`) like ? AND ".
                "concat(`address`,`title`,`description`) like ?",
                $keyword)->get()->toArray();

            return $this->msg(0, $result);
        }
    }
