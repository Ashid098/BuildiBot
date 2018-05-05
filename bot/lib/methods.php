<?php
    class methods{
        public
            $token,
            $input,
            $method = 'SendMessage',
            $response,
            $result;
        function __construct($t, $r=1){
            $this->token = $t;
            if($r)$this->recive();
        }
        protected function recive()
        {
            $res = file_get_contents('php://input');
            $this->input = $this->decode($res);
            if(!is_array($this->input)){
                insertlog('Reciver', 'no data recived', 'methodlog');
                $this->input = false;
            }
            if(Debugmode){
                insertlog('Resived', $res, 'methodlog');
            }
        }
        public function response(){
            if($this->response == false)
                return 0;
            $res = $this->send($this->method, $this->response);
            $this->result = $this->decode($res);
            if(!is_array($this->result)){
                insertlog('Response', 'no data recived for result', 'methodlog');
                $this->input = false;
            }
            if(Debugmode){
                insertlog('Response Result', $res, 'methodlog');
            }
        }
        public function send($m, $d, $token = false)
        {
            if(!$token){
                $token = $this->token;
            }
            if(Debugmode){
                insertlog('Response Data', json_encode($d), 'methodlog');
            }
            $ch = curl_init();
            $url = 'https://api.telegram.org/bot' . $token . '/'.$m;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($d));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;            
        }
        public function gsend($m){
            return $this->osend($this->token, $m);
        }
        public function osend($t, $m){
            $url = 'https://api.telegram.org/bot' . $t . '/'.$m;
            return file_get_contents($url);
        }
        protected function decode($js){
            return (array) json_decode($js, true);
        }
        public function checkresult($res){
            $arr = $this->decode($res);
            if(!is_array($arr)){
                insertlog('Result', 'no data recived', 'methodlog');
            }
            return $arr;
        }
    }