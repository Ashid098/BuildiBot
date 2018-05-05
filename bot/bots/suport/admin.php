<?php
    function run(){
        global $user;
        switch($user->lang){
            case 'pr':
                pr_run();
                break;
            default:
                en_run();
                break;
        }
    }
    function en_run()
    {
        global $user, $post;
        if(isset($post->input['message']['text']))
        {
            if(strpos($post->input['message']['text'], 'start')){
                $post->sendMessage('Wellcom');
                $post->ReplyKeyboard();
                $post->ReplyButton('Send to ID');
                $post->sendlast();
                return;
            }elseif($post->input['message']['text'] == 'close'){
                $user->cleanact();
                $post->sendMessage('Connection closed');
                $post->ReplyKeyboard();
                $post->ReplyButton('Send to ID');
                $post->sendlast();
                return;
            }
        }
        if($user->act() == -1){
            switch($post->input['message']['text']){
                case 'Send to ID':
                    $user->pushact(1);
                    priv();
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
        global $user, $post, $robot;
        if(isset($post->input['message']['reply_to_message'])){
            $user->cleanact();
            $user->pushact($post->input['message']['reply_to_message']['forward_from']['id']);
        }
        if($user->act() == 0){
            $post->sendMessage('Your message has been sended');
            $post->ReplyKeyboard();
            $post->ReplyButton('close');
            $post->newPost($user->session['act'][0]);
            msg();
        }else{
            $post->sendMessage("Contact Not Defined!\nTo send the message to the desired contact, you must first <b>Reply</b> the message of him", 'HTML');
            $post->ReplyKeyboard();
            $post->ReplyButton('close');
        }
    }
    function priv()
    {
        global $user, $post, $robot;
        if($user->act() == 0){
            $post->sendMessage("In this section, you can enter the Telegram ID (as a number without @) or the Username (with @) of the user or channel you want.\nExample\ n @BuidiBot \ n or \ n1234567");
            $post->ReplyKeyboard();
            $post->ReplyButton('close');
            $user->pushact(1);
        }elseif($user->act() == 1){
            $post->sendMessage('Enter your message (the message can contain anything like photo and etc.)');
            $post->ReplyKeyboard();
            $post->ReplyButton('close');
            $user->pushact($post->input['message']['text']);            
        }else{
            $post->sendMessage("Your message has been sended.\nYou can send send an other message to this contact.");
            $post->ReplyKeyboard();
            $post->ReplyButton('close');
            $post->newPost($user->act(2));
            msg();
        }
    }

    function pr_run()
    {
        global $user, $post;
        if(isset($post->input['message']['text']))
        {
            if(strpos($post->input['message']['text'], 'start')){
                $post->sendMessage('خوش آمدید');
                $post->ReplyKeyboard();
                $post->ReplyButton('ارسال به آی دی');
                $post->sendlast();
                return;
            }elseif($post->input['message']['text'] == 'بستن'){
                $user->cleanact();
                $post->sendMessage('ارتباط بسته شد');
                $post->ReplyKeyboard();
                $post->ReplyButton('ارسال به آی دی');
                $post->sendlast();
                return;
            }
        }
        if($user->act() == -1){
            switch($post->input['message']['text']){
                case 'ارسال به آی دی':
                    $user->pushact(1);
                    pr_priv();
                    break;
                default:
                    pr_sendpv();
                    break;
            }
        }else{
            switch($user->act(0)){
                case '1':
                    pr_priv();
                    break;
                default:
                    pr_sendpv();
                    break;
            }
        }
        $post->sendAll();
    }
    function pr_sendpv()
    {
        global $user, $post, $robot;
        if(isset($post->input['message']['reply_to_message'])){
            $user->cleanact();
            $user->pushact($post->input['message']['reply_to_message']['forward_from']['id']);
        }
        if($user->act() == 0){
            $post->sendMessage('پیام شما ارسال شد.');
            $post->ReplyKeyboard();
            $post->ReplyButton('بستن');                
            $post->newPost($user->session['act'][0]);
            msg();
        }else{
            $post->sendMessage("مخاطب تعریف نشده است!\nبرای ارسال پیام به مخاطب مورد نظر ابتدا باید روی پیام او ریپلای کنید");
            $post->ReplyKeyboard();
            $post->ReplyButton('بستن');
        }
    }
    function pr_priv()
    {
        global $user, $post, $robot;
        if($user->act() == 0){
            $post->sendMessage("در این بخش شما می توانید شناسه تلگرام (به صورت عدد بدون @) و یا یوزنیم(آی دی با @) کاربر یا کانال مورد نظرتان را وارد کنید\nمثال\n@BuidiBot\nیا\n1234567");
            $post->ReplyKeyboard();
            $post->ReplyButton('بستن');
            $user->pushact(1);
        }elseif($user->act() == 1){
            $post->sendMessage('پیام خود را وارد کنید (پیام میتواند شامل هر چیزی مانند عکس و .. باشد)');
            $post->ReplyKeyboard();
            $post->ReplyButton('بستن');
            $user->pushact($post->input['message']['text']);            
        }else{
            $post->sendMessage("پیام شما ارسال شد.\nشما میتوانید پیام بعدی را نیز ارسال نمایید");
            $post->ReplyKeyboard();
            $post->ReplyButton('بستن');
            $post->newPost($user->act(2));
            msg();
        }
    }