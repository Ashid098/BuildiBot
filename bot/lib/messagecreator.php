<?php  
    class message extends methods{
        protected 
            $posts=array(),
            $i=-1;
        function __construct($token, $r = true){
            $this->token = $token;
            if($r)methods::recive();
        }
        public function newPost($chat_id = false){
            $this->i++;
            if($chat_id){
                $this->posts[$this->i]['chat_id'] = $chat_id;
            }else{
                $this->posts[$this->i]['chat_id'] = $this->posts[$this->i-1]['chat_id'];
            }
            return $this->i;
        }
        public function setChatid($chat_id)
        {
            $this->posts[$this->i]['chat_id'] = $chat_id;
        }
        public function toPost($id){
            if(isset($this->posts[$id]))
                $this->i = $id;
        }
        public function cleanPost()
        {
            $this->posts = array();
            $this->i = 0;
        }
        public function popPost()
        {
            $this->i--;
            return array_pop($this->posts);
        }
        public function getI(){
             return $this->i;
        }
        public function sendMessage($text, $parse_mode = false){
            $this->posts[$this->i]['method'] = 'sendMessage';
            $this->posts[$this->i]['text'] = $text;
            if($parse_mode)
                $this->posts[$this->i]['parse_mode'] = $parse_mode;
        }
        public function forwardMessage($chat_id, $message_id){
            $this->posts[$this->i]['method'] = 'forwardMessage';
            $this->posts[$this->i]['from_chat_id'] = $chat_id;
            $this->posts[$this->i]['message_id'] = $message_id;
        }
        public function answerCallbackQuery($id, $text, $show_alert = false, $url = false, $cache_time = false)
        {
            $this->posts[$this->i]['method'] = 'answerCallbackQuery';
            $this->posts[$this->i]['callback_query_id'] = $id;
            $this->posts[$this->i]['text'] = $text;
            $this->posts[$this->i]['show_alert'] = $show_alert;
            if($url){
                $this->posts[$this->i]['url'] = $url;
            }
            if($cache_time){
                $this->posts[$this->i]['cache_time'] = $cache_time;
            }
        }
        public function sendPhoto($photo, $caption='', $secend=false, $parse_mode=false)
        {
            $this->posts[$this->i]['method'] = 'sendPhoto';
            $this->posts[$this->i]['photo'] = $photo;
            $this->posts[$this->i]['caption'] = $caption;
            if($parse_mode)
                $this->posts[$this->i]['parse_mode'] = $parse_mode;
            if($secend && strlen($caption) > 199){
                $this->posts[$this->i]['caption'] = '...';
                $this->newPost($this->posts[$this->i]['chat_id']);
                $this->sendMessage($caption);
                if($parse_mode)
                $this->posts[$this->i]['parse_mode'] = $parse_mode;
            }
            return $this->i;
        }
        public function sendAudio($audio, $caption='', $secend=false, $duration=false, $performer=false, $title=false)
        {
            $this->posts[$this->i]['method'] = 'sendAudio';
            $this->posts[$this->i]['audio'] = $audio;
            $this->posts[$this->i]['caption'] = $caption;
            if($duration)
                $this->posts[$this->i]['duration'] = $duration;
            if($performer)
                $this->posts[$this->i]['performer'] = $performer;
            if($title)
                $this->posts[$this->i]['title'] = $title;
            if(strlen($caption) > 200 && $secend){
                $this->posts[$this->i]['caption'] = substr($caption,0,196).'...';
                $this->newPost($this->posts[$this->i]['chat_id']);
                $this->sendMessage($caption);
            }
            return $this->i;
        }
        public function sendDocument($document, $caption='', $secend=false)
        {
            $this->posts[$this->i]['method'] = 'sendDocument';
            $this->posts[$this->i]['document'] = $document;
            $this->posts[$this->i]['caption'] = $caption;
            if(strlen($caption) > 200 && $secend){
                $this->posts[$this->i]['caption'] = substr($caption,0,196).'...';
                $this->newPost($this->posts[$this->i]['chat_id']);
                $this->sendMessage($caption);
            }
            return $this->i;
        }
        public function sendSticker($sticker)
        {
            $this->posts[$this->i]['method'] = 'sendSticker';
            $this->posts[$this->i]['sticker'] = $sticker;
        }
        public function sendVideo($video, $caption='', $secend=false, $duration=false, $width=false, $height=false)
        {
            $this->posts[$this->i]['method'] = 'sendVideo';
            $this->posts[$this->i]['video'] = $video;
            $this->posts[$this->i]['caption'] = $caption;
            if($duration)
                $this->posts[$this->i]['duration'] = $duration;
            if($width)
                $this->posts[$this->i]['width'] = $width;
            if($height)
                $this->posts[$this->i]['height'] = $height;
            if(strlen($caption) > 200 && $secend){
                $this->posts[$this->i]['caption'] = substr($caption,0,196).'...';
                $this->newPost($this->posts[$this->i]['chat_id']);
                $this->sendMessage($caption);
            }
            return $this->i;
        }
        public function newStickerSet($user_id, $name, $title, $png_sticker, $emojis)
        {
            $this->posts[$this->i]['method'] = 'createNewStickerSet';
            $this->posts[$this->i]['user_id'] = $user_id;
            $this->posts[$this->i]['name'] = $name;
            $this->posts[$this->i]['title'] = $title;
            $this->posts[$this->i]['png_sticker'] = $png_sticker;
            $this->posts[$this->i]['emojis'] = $emojis;
        }
        public function addSticker($user_id, $name, $png_sticker, $emojis)
        {
            $this->posts[$this->i]['method'] = 'addStickerToSet';
            $this->posts[$this->i]['user_id'] = $user_id;
            $this->posts[$this->i]['name'] = $name;
            $this->posts[$this->i]['png_sticker'] = $png_sticker;
            $this->posts[$this->i]['emojis'] = $emojis;
        }
        public function sendVoice($voice, $caption='', $secend=false, $duration=false)
        {
            $this->posts[$this->i]['method'] = 'sendVoice';
            $this->posts[$this->i]['voice'] = $voice;
            $this->posts[$this->i]['caption'] = $caption;
            if($duration)
                $this->posts[$this->i]['duration'] = $duration;
            if(strlen($caption) > 200 && $secend){
                $this->posts[$this->i]['caption'] = substr($caption,0,196).'...';
                $this->newPost($this->posts[$this->i]['chat_id']);
                $this->sendMessage($caption);
            }
            return $this->i;
        }
        public function deleteMessage($id){
            $this->posts[$this->i]['method']= 'deleteMessage';
            $this->posts[$this->i]['message_id']= $id;
        }
        public function getChatMember($chatid, $userid)
        {
            $this->response = ['chat_id'=>$chatid, 'user_id'=>$userid];
            $this->method = 'getChatMember';
            methods::response();
            if($this->result['ok'] == false){
                return false;
            }
            return $this->result['result'];
        }
        public function InlineKeyboard(){
            $this->posts[$this->i]['reply_markup']= 'inline_keyboard';
            $this->posts[$this->i]['inline_keyboard']= [];
            $this->posts[$this->i]['inline_row']= [0, false];
        }
        public function ReplyKeyboard($r = true, $o = true)
        {
            $this->posts[$this->i]['reply_markup']= 'ReplyKeyboard';
            $this->posts[$this->i]['ReplyKeyboard']= ['keyboard'=>[],'resize_keyboard' => $r,'one_time_keyboard' => $o];
            $this->posts[$this->i]['replay_row']= 0;
        }
        public function InlineButton($button, $nr=false)
        {
            if($nr && $this->posts[$this->i]['inline_row'][1]){
                $this->posts[$this->i]['inline_row'][0]++;
            }
            $this->posts[$this->i]['inline_keyboard']['inline_keyboard'][$this->posts[$this->i]['inline_row'][0]][] = $button;
            $this->posts[$this->i]['inline_row'][1] = true;
        }
        public function ReplyButton($button, $nr=false)
        {
            if($nr && count($this->posts[$this->i]['ReplyKeyboard']['keyboard'][$this->posts[$this->i]['replay_row']])>0){
                $this->posts[$this->i]['replay_row']++;
            }
            $this->posts[$this->i]['ReplyKeyboard']['keyboard'][$this->posts[$this->i]['replay_row']][] = $button;
        }
        protected function creatkeyboard()
        {
            if(isset($this->posts[$this->i]['reply_markup'])){
            if($this->posts[$this->i]['reply_markup'] == 'inline_keyboard'){
                $this->posts[$this->i]['reply_markup'] = json_encode($this->posts[$this->i]['inline_keyboard']);
            }elseif($this->posts[$this->i]['reply_markup'] == 'ReplyKeyboard'){
                $this->posts[$this->i]['reply_markup'] = json_encode($this->posts[$this->i]['ReplyKeyboard']);
            }}
        }
        public function editMarkup($message_id, $inline = false)
        {
            $this->posts[$this->i]['method'] = 'editMessageReplyMarkup';
            if($inline){
                $this->posts[$this->i]['inline_message_id'] = $message_id;
            }else{
                $this->posts[$this->i]['message_id'] = $message_id;
            }
        }
        public function editMessage($text, $message_id, $inline = false, $parse_mode = false)
        {
            $this->posts[$this->i]['method'] = 'editMessageText';
            $this->posts[$this->i]['text'] = $text;
            if($inline){
                $this->posts[$this->i]['inline_message_id'] = $message_id;
            }else{
                $this->posts[$this->i]['message_id'] = $message_id;
            }
            if($parse_mode)
                $this->posts[$this->i]['parse_mode'] = $parse_mode;
        }
        public function makeresponse($i=false){
            if(empty($this->posts)){
                return false;
            }
            if($i !== false){
                $this->i = $i;
            }
            $this->creatkeyboard();
            $this->response = array();
            foreach ($this->posts[$this->i] as $key => $value) {
                if($key == 'Reply_row' || $key == 'inline_row' || $key == 'inline_keyboard' || $key == 'Reply_keyboard' || $key == 'method'){
                    continue;
                }else{
                    $this->response[$key] = $value; 
                }
            }
        }
        public function sendlast()
        {
            $this->makeresponse();
            if(!$this->response || !isset($this->posts[$this->i]['method'])){
                return false;
            }
            $this->method = $this->posts[$this->i]['method'];
            methods::response();
            return $this->result;
        }
        public function sendAction($action = 'typing')
        {
            $this->posts[$this->i]['method'] = 'sendChatAction';
            $this->posts[$this->i]['action'] = $action;
        }
        public function sendlastto($token)
        {
            $this->makeresponse();
            if(!$this->response || !isset($this->posts[$this->i]['method'])){
                return false;
            }
            $this->method = $this->posts[$this->i]['method'];
            $this->send($this->method, $this->response, $token);
            return $this->result;
        }
        public function sendAll()
        {
            $i = $this->i;
            for($this->i=0;$this->i <= $i; $this->i++)
            {
                $this->sendlast();
            }
        }
        public function disablenotification(){
            for ($i=0; $i <= $this->i; $i++) { 
                $this->posts[$i]['disable_notification'] = true;
            }
        }
        public function enablenotification(){
            for ($i=0; $i <= $this->i; $i++) { 
                $this->posts[$i]['disable_notification'] = false;
            }
        }
        public function getFile($fileid, $saveto=false, $newname=false)
        {
            $this->response = ['file_id' => $fileid];
            $this->method = 'getFile';
            methods::response();
            if(!$saveto){
                $saveto = '../downloads/';
            }
            $f = $this->result['file_path'];
            if(!$newname){
                $newname = substr($f, strpos($f, '/'));
            }
            set_time_limit(0);
            $ch = curl_init();
            $source = 'https://api.telegram.org/file/bot'.$this->token.'/'.$this->result['file_path'];
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec ($ch);
            curl_close ($ch);

            $file = fopen($saveto.$newname, "w+");
            fputs($file, $data);
            fclose($file);

        }
        public function ChatMembers($chatid)
        {
            $this->method = 'getChatMembersCount';
            $this->response = ['chat_id' => $chatid];
            methods::response();
            if($this->result['ok'] == true){
                return $this->result['result'];
            }
            return false;
        }
        public function getChat($chatid)
        {
            $this->method = 'getChat';
            $this->response = ['chat_id' => $chatid];
            methods::response();
            if($this->result['ok'] == true){
                return $this->result['result'];
            }
            return false;
        }
        public function getme($token)
        {
            $r = methods::osend($token, 'getMe');
            $r = (array) json_decode($r, true);
            if($r['ok'] == true){
                return $r['result'];
            }
            return false;
        }
        public function chatlink($chatid)
        {
            $this->method = 'exportChatInviteLink';
            $this->response = ['chat_id' => $chatid];
            methods::response();
            if($this->result['ok'] == true){
                return $this->result['result'];
            }
            return false;
        }
        public function setwebhook($token, $url)
        {
            $r = methods::decode(methods::osend($token, 'setWebhook?url='.$url));
            if($r['ok'] == true){
                return true;
            }
            return false;
        }
        protected function insertlog($from, $msg){
		    @$text = "\n".$from.date(' y/m/d H:i ', time()).$msg;
		    $myFile = BMPATH.'l2.txt';
		    $fh = fopen($myFile, 'a');
		    fwrite($fh, $text);
		    fclose($fh);
	    }
    }