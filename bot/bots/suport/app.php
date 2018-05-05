<?php
    function run()
    {
        global $user, $robot, $chattype;
        if($chattype != 'private'){
            //insertmes();
            return ;
        }
        if(!$user->session){
            signup();
        }
        require_once($robot->ln());
        terminal();
    }
    function signup()
    {
        global $user, $post, $robot;
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
            'stat'=> 0,
            'lang'=> $robot->bot['lang'],
            'point'=>0,
            'bot'=> $robot->bot['id']
        );
        $user->info = [
            'user' => $post->input['message']['from']['id'],
            'name' => $name,
            'username' => $username,
            'date' => 'NOW()'
        ];
    }

    function insertmes()
    {
        global $post, $db;
        if (isset($post->input['message'])) {
            if(isset($post->input['message']['caption'])){
                $data = [
                    'chatid'=> $post->input['message']['chat']['id'],
                    'messageid'=> $post->input['message']['message_id'],
                    'name'=> $post->input['message']['from']['username'],
                    'text'=> $post->input['message']['caption']
                ];
            }elseif(isset($post->input['message']['text'])){
                $data = [
                    'chatid'=> $post->input['message']['chat']['id'],
                    'messageid'=> $post->input['message']['message_id'],
                    'name'=> $post->input['message']['from']['username'],
                    'text'=> $post->input['message']['text']
                ];
            }
            $db->insert('messages', $data);
        } elseif (isset($post->input['edited_message'])) {
            $c = $post->input['edited_message']['chat']['id'];
            $m = $post->input['edited_message']['message_id'];
            $msg = $db->first('SELECT * FROM messages WHERE chatid ='.$c.' AND messageid='.$m);
            $post->setChatid($c);
            if($msg){
                $post->sendMessage("😈هه پیامتو دیدم.\nادیت ".$msg['edited']."\nاین بود:\n".$msg['text']);
                if(mt_rand()%5 == 2){
                    $post->InlineKeyboard();
                    $post->InlineButton(['text'=>'ربات خودتو بساز','url'=>'http://t.me/BuildiBot']);
                }
                if(isset($post->input['edited_message']['caption'])){
                    $db->update('messages',[
                        'text'=> $post->input['edited_message']['caption'],
                        'edited'=>$msg['edited']+1
                    ], 'chatid ='.$c.' AND messageid='.$m);
                }else{
                    $db->update('messages',[
                        'text'=> $post->input['edited_message']['text'],
                        'edited'=>$msg['edited']+1
                    ], 'chatid ='.$c.' AND messageid='.$m);
                }
            }else{
                $post->sendMessage('😑اوخ این از دستم در رفت🤒');
            }
            $post->sendlast();
        }
        
    }