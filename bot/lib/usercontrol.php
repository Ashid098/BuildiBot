<?php 
    class usercontrol extends Database
    {
        public $session, $lang, $info;
        private $edited = false;
        function __construct()
        {
        }
        public function setSession($chatid, $bot){
            global $db;
            if($chatid){
                $this->session = $db->first("SELECT * FROM bot_users WHERE chatid = '$chatid' AND bot = '$bot'");
                if($this->session){
                    if(!$this->read_act()){
                        $this->session['act'] = [];
                    }
                    $this->lang = $this->session['lang'];
                }
            }
        }
        public function setSessionbyID($id){
            global $db;
            $this->session = $db->first("SELECT * FROM bot_users WHERE id = '$id'");
            if($this->session){
                if(!$this->read_act()){
                    $this->session['act'] = [];
                }
                $this->lang = $this->session['lang'];
            }
        }
        public function setinfo($k = false){
            global $db;
            $this->info = $db->first('SELECT * FROM user_info WHERE user = '.$this->session['chatid']);
            if($k && isset($this->info[$k])){
                return $this->info[$k];
            }
            return false;
        }
        public function getuser($id){
            global $db;
            return $db->first("SELECT * FROM bot_users WHERE id = '$id'");
        }
        public function getparent(){
            $ob = new usercontrol(0);
            $ob->setSessionbyID($this->session['sub']);
            return $ob;
        }
        public function updateuser($data){
            global $db;
            if($this->session && $this->session['id']){
                if(isset($data['act'])){
                    $this->session['act'] = $data['act'];
                    unset($data['act']);
                    $this->edited = true;
                }
                $db->update('bot_users', $data, 'id = '. $this->session['id']);
            }
        }
        public function updateinfo($data){
            global $db;
            if($this->session && $this->session['chatid']){
                $db->update('user_info', $data, 'user = '. $this->session['chatid']);
            }
        }
        public function insertuser()
        {
            global $db;
            $this->session['id'] = $db->insert('bot_users', $this->session);
            if(isset($this->session['chatid']) && $db->getValue('user', 'user_info', 'user = '.$this->session['chatid'])){
                $db->update('user_info', $this->info, 'user = '.$this->session['chatid']);
            }else{
                $db->insert('user_info', $this->info);
            }
            $file = BMPATH.'/cash/useract/u_'.$this->session['id'];
            $fh = fopen($file, 'w');
	        fwrite($fh, json_encode([],true));
        	fclose($fh);
        }
        public function totatsub()
        {
            global $db;
            return $db->countEntries('bot_users', 'sub = '.$this->session['id'])+0;
        }
        public function pushact($k){
            $this->session['act'][] = $k;
            $this->edited = true;
        }
        public function popact(){
            $this->edited = true;
            $last = array_pop($this->session['act']);
            return $last;
        }
        public function cleanact(){
            if($this->session){
                $this->edited = true;
                $this->session['act'] = array();
            }
        }
        public function act($k=false){
            if($k === false){
                return count($this->session['act'])-1;
            }else{
                if(isset($this->session['act'][$k])){
                    return $this->session['act'][$k];
                }else{
                    return false;
                }
            }
        }
        public function flusher()
        {
            $file = BMPATH.'/cash/useract/u_'.$this->session['id'];
            if($this->edited){
                $fh = fopen($file, 'w');
	            fwrite($fh, json_encode($this->session['act'],true));
        	    fclose($fh);
            }
        }
        public function flusherto($id, $act)
        {
            $file = BMPATH.'/cash/useract/u_'.$id;
            if($this->edited){
                $fh = fopen($file, 'w');
	            fwrite($fh, json_encode($act,true));
        	    fclose($fh);
            }
        }
        public function read_act()
        {
            $file = BMPATH.'/cash/useract/u_'.$this->session['id'];
            if(file_exists($file)){
                $this->session['act'] = (array) json_decode(file_get_contents($file),true);
                return true;
            }
            return false;
        }
    }
