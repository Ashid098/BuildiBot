<?php
    require_once(BMPATH . 'lib/methods.php');
    $d = base64_decode($_GET['do'])-1;
    require_once(BMPATH . 'lib/botcontrol.php');
    $robot = new botcontrol($d);
    if($robot->expire()){
        require_once(BMPATH . 'lib/messagecreator.php');
        require_once(BMPATH . 'lib/usercontrol.php');
        $post = new message($robot->token());
        $chatid = false;
        if (isset($post->input['callback_query'])) {
            $chatid =  $post->input['callback_query']['from']['id'];
            $chattype = $post->input['callback_query']['message']['chat']['type'];
        }elseif(isset($post->input['inline_query'])){
            $chatid = $post->input['inline_query']['from']['id'];
            $chattype = $post->input['inline_query']['message']['chat']['type'];
        }elseif(isset($post->input['message'])){
            $chatid = $post->input['message']['from']['id'];
            $chattype = $post->input['message']['chat']['type'];
        }elseif(isset($post->input['edited_message'])){
            $chatid = $post->input['edited_message']['from']['id'];
            $chattype = $post->input['edited_message']['chat']['type'];
        }elseif(isset($post->input['channel_post'])){
            $chatid = $post->input['channel_post']['chat']['id'];
            $chattype = 'channel';
        }
        if($chatid){
            $post->newPost($chatid);
            $user = new usercontrol();
            if($chattype == 'private'){
                if($robot->bot['app'] == 1){
                    $user->setSession($chatid, 1);
                }else{
                    $user->setSession($chatid, $robot->bot['id']);
                    if(isset($user->session['stat'])){
                        $robot->isAdmin = $user->session['stat'];
                    }
                }
            }
            require_once($robot->apppath());
            run();
            $user->flusher();
        }
    }