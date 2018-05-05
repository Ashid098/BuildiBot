<?php
    function terminal()
    {
        global $user, $post;
        if(isset($post->input['message']['text']))
        {
            if(strpos($post->input['message']['text'], 'start')){
                $post->sendMessage("سلام به ربات شخصی من خوش اومدید\nشما میتونید پیامتون رو اینجا برای من بنویسید من در اولین فرصت به شما پاسخ میدم");
                $post->ReplyKeyboard();
                $post->ReplyButton('ارسال به صورت ناشناس');
                $post->ReplyButton('ساخت ربات', 1);
                $post->sendlast();
                return;
            }elseif($post->input['message']['text'] == 'بستن'){
                $user->cleanact();
                $post->sendMessage('ارتباط بسته شد');
                $post->ReplyKeyboard();
                $post->ReplyButton('ساخت ربات');
                $post->sendlast();
                return;
            }
        }
        if($user->act() == -1){
            switch($post->input['message']['text']){
                case 'ساخت ربات':
                    $post->sendMessage("شما می توانید در ربات زیر رباتی مشابه این ربات بسازید:\n@BuildiBot");
                    break;
                case 'ارسال به صورت ناشناس':
                    $user->pushact(1);
                    $post->sendMessage('لطفا پیام خود را بنویسید:');
                    break;
                default:
                    sendpv();
                    break;
            }
        }else{
            switch($user->act(0)){
                case '1':
                    priv();
                    break;
                default:
                    sendpv();
                    break;
            }
        }
        $post->sendAll();
    }
    function msg()
    {
        global $post;
        if(isset($post->input['message']['caption'])){
            $caption = $post->input['message']['caption'];
        }else{
            $caption = '';
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
    }
    function sendpv()
    {
        global $user, $post, $robot, $db;
        if(!isset($post->input['message'])){
            return ;
        }
        $post->sendMessage('پیام شما ارسال شد.');
        $post->newPost($robot->bot['admin']);
        $post->forwardMessage($post->input['message']['chat']['id'],$post->input['message']['message_id']);
    }
    function priv()
    {
        global $user, $post, $robot, $db;
        if(!isset($post->input['message'])){
            return ;
        }
        $post->sendMessage('پیام شما بدون هیچ نشانه ای از شما ارسال شد.');
        $post->newPost($robot->bot['admin']);
        if($robot->bot['vip']){
            $post->forwardMessage($post->input['message']['chat']['id'],$post->input['message']['message_id']);
        }else{
            msg();
        }
    }