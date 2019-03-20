<?php
    class DFA { //DFA算法
        protected $wordTree;

        //用json形式保存wordTree构造
        public function __construct()
        {
            $wordTreeJson = file_get_contents(public_path()."/mgcTree.json");
            $this->wordTree = json_decode($wordTreeJson,true);
        }

        public function init() {
            $this->wordTree = array();
            $wordJson = file_get_contents(public_path()."/mgc.json");
            $word = json_decode($wordJson);
            for($i=0; $i<count($word); $i++) {
                $this->addWord($word[$i]);
            }
        }

        private function textSplit($text = "") {
            return preg_split('/(?<!^)(?!$)/u', $text );
        }

        public function addWord($keyword) {
            $keyword = $this->textSplit($keyword);
            $st = &$this->wordTree;

            foreach($keyword as $word ) {
                if(is_array($st) && array_key_exists($word, $st)) {
                    $st = &$st[$word];
                } else {
                    if(is_array($st)) {
                        $st[$word] = array('isEnd'=>0);
                    } else {
                        $st = array();
                        $st[$word] = array('isEnd'=>0);
                    }
                    $st = &$st[$word];
                }
            }
            $st['isEnd'] = 1;

            $f = fopen(public_path()."/mgcTree.json", "w");
            fwrite($f, json_encode($this->wordTree));
            fclose($f);
        }

        public function check($text = "") {
            $text = $this->textSplit($text);
            $bad = array();

            $begin = 0;
            $end = 0;
            $st = $this->wordTree;

            for($pos=0; $pos<count($text); $pos++) {
                $item = $text[$pos];
                if(array_key_exists($item, $st)) {
                    if($st == $this->wordTree) {
                        $begin = $pos;
                    } else if($st[$item]['isEnd'] == 1){
                        $end = $pos;
                    }
                    $st = $st[$item];
                } else {
                    if($begin < $end) {
                        array_push($bad, array($begin, $end));
                        $begin = $end;
                    }
                    if($st != $this->wordTree) {
                        $pos--;
                        $st = $this->wordTree;
                    }
                }
            }
            //结尾敏感词
            if($begin < $end) {
                array_push($bad, array($begin, $end));
            }

            if(empty($bad)) {
                return true;
            }

            foreach ($bad as $item) {
                for($i=$item[0]; $i<=$item[1]; $i++) {
                    $text[$i] = '*';
                }
            }
            $result = join('', $text);

            return $result;
        }
    }
