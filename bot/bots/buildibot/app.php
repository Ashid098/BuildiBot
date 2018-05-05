<?php
    define('THEURL', 'https://buildibot.ir/bot/');
    function run()
    {
        global $user, $chattype;
        if($chattype != 'private'){
            return ;
        }
        if(!$user->session){
            signup();
        }
        elseif($user->lang){
            require_once(BMPATH.'bots/buildibot/'.$user->lang.'.php');
            terminal();
        }
        else{
            detect();
        }
    }
    function signup()
    {
        global $user, $post;
        $name = $post->input['message']['from']['first_name'];
        if(isset($post->input['message']['from']['last_name'])){
            $name .= ' '.$post->input['message']['from']['last_name'];
        }
        $username = '';
        if(isset($post->input['message']['from']['username'])){
            $username = ' '.$post->input['message']['from']['username'];
        }
        $user->session = array(
            'chatid' => $post->input['message']['from']['id'],
            'stat'=>1,
            'point'=>100,
            'bot'=> 1
        );
        $user->info = [
            'user' => $post->input['message']['from']['id'],
            'name' => $name,
            'username' => $username,
            'date' => 'NOW()'
        ];
        $user->insertuser();
        $post->sendMessage("<b>Wellcome</b>\nPlease choose your language:", 'HTML');
        languages();
        $post->sendlast();
    }
    function languages()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('English🇺🇸');
        $post->ReplyButton('русский🇷🇺',1);
        $post->ReplyButton('فارسی🇮🇷');
        //$post->ReplyButton('العربية🇮🇶');
    }
    function detect()
    {
        global $user, $post;
        if(isset($post->input['message']['text'])){
            switch ($post->input['message']['text']) {
                case 'English🇺🇸':
                    $user->updateuser(['lang'=>'en']);
                    $post->sendMessage('✔️Your language successfully updated.');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('LogIn☑️');
                    break;
                case 'русский🇷🇺':
                    $user->updateuser(['lang'=>'ru']);
                    $post->sendMessage('✔️Ваш язык успешно обновлен.');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('Авторизоваться☑️');
                    break;
                case 'العربية🇮🇶':
                    $user->updateuser(['lang'=>'ar']);
                    $post->sendMessage('✔️تم تحديث لغتك بنجاح.');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('تسجيل الدخول☑️');
                    break;
                case 'فارسی🇮🇷':
                    $user->updateuser(['lang'=>'pr']);
                    $post->sendMessage('✔️زبان شما با موفقیت ثبت شد.');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('ورود☑️');
                    break;
                default:
                    $post->sendMessage("<b>Wellcome</b>\nPlease choose your language:", 'HTML');
                    languages();
                    break;
            }
        }else{
            $post->sendMessage("<b>Wellcome</b>\nPlease choose your language:", 'HTML');
            languages();
        }
        $post->sendAll();        
    }
    function applist($t = false)
    {
        global $user, $post, $db;
        if($t){
            $apps = $db->fetch_all('SELECT * FROM apps WHERE cust < \''.($user->session['point']*2).'\' AND stat = 1');
        }else{
            $apps = $db->fetch_all('SELECT * FROM apps WHERE stat = 1');
        }
        if(!$apps){
            return false;
        }
        $u = $user->session['point']*2;
        $post->InlineKeyboard();
        foreach($apps as $k=>$app){
            if($t && $app['cust'] > $u){
                $post->InlineButton(['text'=>$app[$user->lang], 'callback_data'=>'e'], $k%3 == 2);
            }else{
                $post->InlineButton(['text'=>$app[$user->lang], 'callback_data'=>' '.$app['id'].' '], $k%3 == 2);
            }
        }
        return true;
    }

    function userbots()
    {
        global $user, $post, $db;
        $apps = $db->fetch_all('SELECT * FROM bots WHERE `admin` = '.$user->session['chatid']);
        if(!$apps){
            return false;
        }
        $post->InlineKeyboard();
        foreach($apps as $k=>$app){
            $post->InlineButton(['text'=>$app['username'], 'callback_data'=>' '.$app['id'].' '], $k%4==1);
        }
        return true;
    }
    function setexp($bid, $m, $vip=false){
        global $db;
        $bot = $db->first(dbselect('bots', 'id = '.$bid));
        if($vip){
            $data = ['expire' => ($m*2592000),
            'vip' => 1];
        }else{
            $data['expire'] = ($m*2592000) + $bot['expire'];
        }
        if($bot['vip'] || $vip){
            $app = $db->getValue('vip', 'apps', 'id = '.$bot['app']);
        }else{
            $app = $db->getValue('cust', 'apps', 'id = '.$bot['app']);
        }
        $db->update('bots', $data, 'id = '.$bid);
        return $app;
    }
    function periodicity($id, $b = false)
    {
        global $user, $post, $db;
        if($b){
            $bot = $db->first('SELECT app, vip FROM bots WHERE id = '.$id);
            if($bot['vip']){
                $app = $db->getValue('vip', 'apps', 'id = '.$bot['app']);
            }else{
                $app = $db->getValue('cust', 'apps', 'id = '.$bot['app']);
            }
        }else{
            $app = $db->getValue('cust', 'apps', 'id = '.$id);
        }
        if(floor($app/9) > $user->session['point']){
            return false;
        }
        $post->InlineKeyboard();
        $post->InlineButton(['text'=>floor($app/9).'/Month', 'callback_data'=>'1']);
        $post->InlineButton(['text'=>floor($app/3).'/3Month', 'callback_data'=>'3']);
        if($user->session['point'] > $app/2){
            $post->InlineButton(['text'=>floor($app/2).'/6Month', 'callback_data'=>'6'], 1);
            if($user->session['point'] > $app){
                $post->InlineButton(['text'=>$app.'/Year', 'callback_data'=>'12']);
            }
        }
        return true;
    }

    function periodicityvip($id)
    {
        global $user, $post, $db;
        $bot = $db->first('SELECT app FROM bots WHERE id = '.$id);
        $app = $db->getValue('vip', 'apps', 'id = '.$bot['app']);
        if(floor($app/9) > $user->session['point']){
            return false;
        }
        $post->InlineKeyboard();
        $post->InlineButton(['text'=>floor($app/9).'/Month', 'callback_data'=>'1']);
        $post->InlineButton(['text'=>floor($app/3).'/3Month', 'callback_data'=>'3']);
        if($user->session['point'] > $app/2){
            $post->InlineButton(['text'=>floor($app/2).'/6Month', 'callback_data'=>'6'], 1);
            if($user->session['point'] > $app){
                $post->InlineButton(['text'=>$app.'/Year', 'callback_data'=>'12']);
            }
        }
        return true;
    }
    function msg($pre = '', $post = '')
    {
        global $post;
        if(isset($post->input['message']['caption'])){
            $caption = $pre.$post->input['message']['caption'].$post;
        }else{
            $caption = ($pre || $post ? $pre.$post : false);
        }
        if(isset($post->input['message']['photo'])){
            $post->sendPhoto($post->input['message']['photo'][0]['file_id'], $caption);
        }elseif(isset($post->input['message']['audio'])){
            $post->sendAudio($post->input['message']['audio']['file_id'], $caption);
        }elseif(isset($post->input['message']['voice'])){
            $post->sendVoice($post->input['message']['voice']['file_id'], $caption);
        }elseif(isset($post->input['message']['sticker'])){
            $post->sendSticker($post->input['message']['sticker']['file_id']);
        }elseif(isset($post->input['message']['document'])){
            $post->sendDocument($post->input['message']['document']['file_id'], $caption);
        }elseif(isset($post->input['message']['video'])){
            $post->sendVideo($post->input['message']['video']['file_id'], $caption);
        }elseif(isset($post->input['message']['text'])){
            $post->sendMessage($pre.$post->input['message']['text'].$post);
        }
    }
    function osender()
    {
        global $post, $robot, $db, $user;
        $post->newPost();
        if(isset($post->input['message']['caption'])){
            $caption = $post->input['message']['caption'];
        }else{
            $caption = false;
        }
        if(isset($post->input['message']['photo'])){
            $post->sendPhoto($post->input['message']['photo'][0]['file_id'], $caption);
        }elseif(isset($post->input['message']['audio'])){
            $post->sendAudio($post->input['message']['audio']['file_id'], $caption);
        }elseif(isset($post->input['message']['voice'])){
            $post->sendVoice($post->input['message']['voice']['file_id'], $caption);
        }elseif(isset($post->input['message']['sticker'])){
            $post->sendSticker($post->input['message']['sticker']['file_id']);
        }elseif(isset($post->input['message']['document'])){
            $post->sendDocument($post->input['message']['document']['file_id'], $caption);
        }elseif(isset($post->input['message']['video'])){
            $post->sendVideo($post->input['message']['video']['file_id'], $caption);
        }elseif(isset($post->input['message']['text'])){
            $post->sendMessage($post->input['message']['text']);
        }
        $users = $db->fetch_all('SELECT chatid FROM bot_users WHERE bot='.$robot->bot['id']);
        foreach ($users as $ms) {
            $post->setChatid($ms['chatid']);
            $post->sendlast();
            $user->session['point'] -= 3;
        }
        $user->updateuser(['point' => $user->session['point']]);
        $post->popPost();
    }