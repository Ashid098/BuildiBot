<?php
    function terminal()
    {
        global $user, $post;
        if(isset($post->input['message']['text']))
        {
            if(strpos($post->input['message']['text'], 'start')){
                $post->sendMessage("wellcome to our robot.\nHere you can send your message for us.\nWe will respond to you as soon as possible");
                $post->ReplyKeyboard();
                $post->ReplyButton('Send anonymously');
                $post->ReplyButton('Create my bot', 1);
                $post->sendlast();
                return;
            }elseif($post->input['message']['text'] == 'Close'){
                $user->cleanact();
                $post->sendMessage('Connection closed');
                $post->ReplyKeyboard();
                $post->ReplyButton('Create my bot');
                $post->sendlast();
                return;
            }
        }
        if($user->act() == -1){
            switch($post->input['message']['text']){
                case 'Create my bot':
                    $post->sendMessage("You can create a bot like this in here:\n@BuildiBot");
                    break;
                case 'Send anonymously':
                    $user->pushact(1);
                    $post->sendMessage('Please write your message:');
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
        $post->sendMessage('Your message has been sended.');
        $post->newPost($robot->bot['admin']);
        $post->forwardMessage($post->input['message']['chat']['id'],$post->input['message']['message_id']);
    }
    function priv()
    {
        global $user, $post, $robot, $db;
        if(!isset($post->input['message'])){
            return ;
        }
        $post->sendMessage('Your message has been sended anonymously');
        $post->newPost($robot->bot['admin']);
        if($robot->bot['vip']){
            $post->forwardMessage($post->input['message']['chat']['id'],$post->input['message']['message_id']);
        }else{
            msg();
        }
    }