<?php
    function run()
    {
        global $post, $robot;
        switch($robot->ln()){
            case 'pr':
                $post->sendMessage('این ربات به علت نقض قوانین buildibot مسدود شده است');
                $post->sendlast();
                break;
            default:
                $post->sendMessage('این ربات به علت نقض قوانین buildibot مسدود شده است');
                $post->sendlast();
                break;
        }
    }