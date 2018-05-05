<?php 
    function mkey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('Create Bot');
        $post->ReplyButton('My account');
        $post->ReplyButton('Order your bot',1);
        $post->ReplyButton('Support',1);
        $post->ReplyButton('Help');
    }
    function ckey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('Register bot');
        $post->ReplyButton('My bots');
        $post->ReplyButton('Applications',1);
        $post->ReplyButton('🏠Home',1);
    }
    function pkey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('Buy points');
        $post->ReplyButton('Transfer points');
        $post->ReplyButton('Change language',1);
        $post->ReplyButton('🏠Home',1);
    }
    function terminal()
    {
        global $user, $post, $Bot;
            if(isset($post->input['message']['text'])){
                if(strpos($post->input['message']['text'], 'start')){
                    $user->cleanact();
                    $post->sendMessage('🌷🌷Welcome');
                    mkey();
                    $post->sendAll();
                    return;
                }elseif($post->input['message']['text'] == '🔙Back'){
                    $user->popact();
                }elseif($post->input['message']['text'] == '🏠Home'){
                    $user->cleanact();
                    $post->sendMessage('Thank you');
                    mkey();
                    $post->sendlast();
                    return;
                }
            }
            if($user->act() == -1){
                switch ($post->input['message']['text']) {
                    case 'LogIn☑️':
                        $post->sendMessage('🌷🌷Welcome');
                        mkey();
                        break;
                    case 'Create Bot':
                        $user->pushact(1);
                        botcreate();
                        break;
                    case 'My account':
                        $user->pushact(2);
                        myaccont();
                        break;
                    case 'Order your bot':
                        $user->pushact(3);
                        ordering();
                        break;
                    case 'Support':
                        $post->sendMessage("To submit the ticker to the Bot support below\n🔻@Buildi_en_Bot\n🔻@Buildi_pr_Bot\n🔻@Buildi_ru_Bot");
                        mkey();
                        break;
                    case 'Help':
                        $post->sendMessage("You can create your Bot without the knowledge of the Bot you want to create \n \n▌️. To create a Bot, you first need to enter the Create Bot section, and after reading the Bot specification in the Applications section, enter the Register Bot section. And register a new Bot \n \n💢 For Register bot it is necessary first to create a bot in a boot \n To create a bot in your boot, first enter the boot font @botfather \n then enter the / newbot command and then the Bot information Enter your username and username in sequence \nWhen the Create Bot is completed, the bot will give you a text containing the token \n In this text box only select and copy the Token section and in the Bild Send the bot in the Create Bot section (note that you do not have to send the message to your fader) \n \n🔘 If your Bot is not available in Applications, you can order it from the Order your bot section to make it free for you.");
                        mkey();
                        break;
                    default:
                        $post->sendMessage('The command entered is not a concept!');
                        mkey();
                        break;
                }
            }else{
                switch ($user->act(0)) {
                    case '1':
                        botcreate();
                        break;
                    case '2':
                        myaccont();
                        break;
                    case '3':
                        ordering();
                        break;
                }
            }
            $post->sendAll();
    }
    function botcreate()
    {
        global $user, $post;
        if($user->act() == 0){
            switch ($post->input['message']['text']) {
                case '🔙Back':
                case 'Create Bot':
                    $post->sendMessage("▪ In this section you can check your Bots or create a new Bot \n \nWe recommend you read the description in the Applications section before creating the bot.");
                    ckey();
                    break;
                case 'Register bot':
                    $user->pushact(1);
                    addbot();
                    break;
                case 'My bots':
                    $user->pushact(2);
                    mybots();
                    break;
                case 'Applications':
                    $user->pushact(3);
                    apps();
                    break;

                default:
                    $post->sendMessage('The command entered is not a concept!');
                    break;
            }
        }else{
            switch ($user->act(1)) {
                case '1':
                    addbot();
                    break;
                case '2':
                    mybots();
                    break;
                case '3':
                    apps();
                    break;
            }
        }
    }
    function addbot()
    {
        global $user, $post, $Bot, $db;
        if($user->act() == 1){
            $post->sendMessage('First choose the Bot you want');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙Back');
            $post->ReplyButton('🏠Home',1);
            $post->newPost();
            if(applist(true)){
                $post->sendMessage('Bots: ');
            }else{
                $post->sendMessage('Your credit for creating a new bot is not enough');
            }
            $user->pushact(1);
        }elseif($user->act() == 2){
            if(!isset($post->input['callback_query']['data'])){return;}
            $id = $post->input['callback_query']['data'];
            $post->answerCallbackQuery($post->input['callback_query']['id'], 'app selected');
            $post->newPost();
            $post->sendMessage('Please select your course');
            periodicity($id);
            $user->pushact($id);
        }elseif($user->act() == 3){
            if(!isset($post->input['callback_query']['data'])){return;}
            $exp = $post->input['callback_query']['data'];
            $post->answerCallbackQuery($post->input['callback_query']['id'], 'periodicity selected');
            $post->newPost();
            $post->sendMessage('Please send the Bot to Token');
            $user->pushact($exp);
        }else{
            $r = false;
            if(isset($post->input['message']['text'])){
                $data['token'] = sanitize($post->input['message']['text']);
                $r = $post->getme($data['token']);
            }
            if($r && $r['is_bot'] === true){
                if($db->first(dbselect('bots', 'token = \''.$data['token'].'\''))){
                    $post->sendMessage('This Bot has already been registered!');
                    return ;
                }
                $data['username'] = $r['username'];
                $data['app'] = $user->act(3);
                $data['admin'] = $user->session['chatid'];
                $data['lang'] = 'en';
                $data['expire'] = (intval($user->act(4))*2592000) + time();
                $id = $db->insert('bots', $data);
                $app = $db->getValue('cust', 'apps', 'id = '.$data['app']);
                switch($user->session['act'][4]){
                    case '1':
                        $point = $user->session['point']-round($app/9);
                    break;
                    case '3':
                        $point = $user->session['point']-round($app/3);
                    break;
                    case '6':
                        $point = $user->session['point']-round($app/2);
                    break;
                    case '12':
                        $point = $user->session['point']-$app;
                    break;
                }
                $user->updateuser(['point'=>$point]);
                $post->setwebhook($post->input['message']['text'], THEURL.'?do='.base64_encode($id+1));
                $db->insert('bot_users', ['chatid'=>$user->session['chatid'], 'lang'=>'en', 'stat'=>1, 'bot'=>$id]);
                $post->sendMessage($r['username']."\nThe Bot was successfully recorded");
                mkey();
                $user->cleanact();
            }else{
                $post->sendMessage('Token is not valid!');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);
            }
        }
    }
    function apps()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query'])){
            $app = $db->first(dbselect('appinfo', 'app = \''.$post->input['callback_query']['data'].'\' AND ln = \'en\''));
            $post->answerCallbackQuery($post->input['callback_query']['id'], 'app info');
            $post->newPost();
            $post->editMessage($app['info'], $post->input['callback_query']['message']['message_id'], false, 'HTML');            
            applist();
        }else{
            $post->sendMessage('Select the Bot you want');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙Back');
            $post->ReplyButton('🏠Home',1);
            $post->newPost();
            $post->sendMessage('Bots:');
            applist();
        }
    }
    function botkeyboard()
    {
        global $user, $post, $db;
        $app = $db->first(dbselect('bots', 'id = '.$user->session['act'][2]));
        $post->editMessage('<b>'.$app['username']."</b>\nToken: ".$app['token']."\nExpire: ".date('Y/m/d',$app['expire'])."\nEarm money: ".($app['adv'] ? 'Active': 'InActive'), $post->input['callback_query']['message']['message_id'], false, 'HTML');
        $post->InlineKeyboard();
        $post->InlineButton(['text'=>'Change Token', 'callback_data'=>'1']);
        $post->InlineButton(['text'=>'Charge', 'callback_data'=>'2']);
        $post->InlineButton(['text'=>'Broadcast Message', 'callback_data'=>'3'],1);
        $post->InlineButton(['text'=>'Earm money', 'callback_data'=>'5']);
        $post->InlineButton(['text'=>'Change administrator', 'callback_data'=>'6'],1);
        $post->InlineButton(['text'=>'Delete', 'callback_data'=>'4'],1);
        $post->InlineButton(['text'=>'Back to list', 'callback_data'=>'back'],1);
    }
    function mybots()
    {
        global $user, $post, $db;
        if($user->act() == 1){
            if(isset($post->input['callback_query'])){
                $user->pushact($post->input['callback_query']['data']);
                botkeyboard();
            }else{
                if(userbots()){
                    $post->sendMessage('Please select a Bot');
                    userbots();
                }else{
                    $post->sendMessage('You have not registered any Bots');
                    $user->popact();
                    ckey();
                }
            }
        }elseif($user->act() == 2){
            if(isset($post->input['callback_query'])){
                switch($post->input['callback_query']['data']){
                    default:
                        $user->popact();
                        $post->editMessage('Please select a Bot', $post->input['callback_query']['message']['message_id']);
                        userbots();
                        break;
                    case '1':
                        $user->pushact('1');
                        tokenedit();
                        break;
                    case '2':
                        $user->pushact('2');
                        charging();
                        break;
                    case '3':
                        $user->pushact('3');
                        sendmessage();
                        break;
                    case '5':
                        $user->pushact('5');
                        $post->editMessage('If you activate this section, the ads will automatically be sent to your Bot users and you will receive points in proportion to the number of your users.', $post->input['callback_query']['message']['message_id']);
                        $post->InlineKeyboard();
                        if($db->getValue('adv', 'bots', 'id = '.$user->session['act'][2])){
                            $post->InlineButton(['text'=> 'InActive', 'callback_data'=>'0']);
                        }else{
                            $post->InlineButton(['text'=>'Active', 'callback_data'=>'1']);
                        }
                        break;
                    case '6':
                        changeadmin();
                        $user->pushact('6');
                        break;
                    case '4':
                        $user->pushact('4');
                        $post->editMessage('Are you sure?', $post->input['callback_query']['message']['message_id']);
                        $post->InlineKeyboard();
                        $post->InlineButton(['text'=>'No', 'callback_data'=>'0']);
                        $post->InlineButton(['text'=>'Yes', 'callback_data'=>'1']);
                        break;
                }
            }else{
                if($post->input['message']['text'] == '🔙Back'){
                    botkeyboard();
                    return;
                }
                $post->sendMessage('The entered command is not valid');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);
            }
        }else{
            switch($user->session['act'][3]){
                default:
                    $user->popact();
                    botkeyboard();
                    break;
                case '1':
                    tokenedit();
                    break;
                case '2':
                    charging();
                    break;
                case '3':
                    sendmessage();
                    break;
                case '4':
                    if($post->input['callback_query']['data'] == '1'){
                        $db->delete('bots', 'id = '.$user->session['act'][2]);
                        $user->popact();
                        $user->popact();
                        $post->editMessage('The bot deleted successfuly.', $post->input['callback_query']['message']['message_id']);
                        userbots();
                    }else{
                        $user->popact();
                        botkeyboard();
                    }
                    break;
                case '5':
                    $db->update('bots', ['adv'=> $post->input['callback_query']['data']], 'id = '.$user->session['act'][2]);
                    $user->popact();
                    botkeyboard();
                    break;
                case '6':
                    changeadmin();
                    break;
            }
        }
    }
    function changeadmin()
    {
        global $user, $post, $db;
        if($user->act() == 2){
            $post->sendMessage("First, you need to be a member of your bot and your Bot \n Then send the \"My account\" section to your username. \nIf you do not have access to the Bot with the transfer of your management, Back Management will give you No refundable \n Are you sure about the transfer?");
            $post->ReplyKeyboard();
            $post->ReplyButton('Yes Im sure');
            $post->ReplyButton('No');
            $post->ReplyButton('🏠Home',1);
        }elseif($user->act() == 3){
            if($post->input['message']['text'] == 'Yes Im sure'){
                $post->sendMessage('Now enter the username of the user');
                $post->ReplyKeyboard();
                $post->ReplyButton('🏠Home');
            }else{
                $user->popact();
                $user->popact();
                $post->sendMessage('Ok');
                userbots();
            }
        }else{
            $chatid = $db->getValue('chatid', 'bot_users', 'id ='.$post->input['message']['text']);
            if($chatid){
                if($db->update('bots', ['admin'=>$chatid],'id = '.$user->session['act'][2])){
                    if($db->update('bot_users', ['admin'=>1],'chatid = '.$chatid.' AND bot ='.$user->session['act'][2])){
                        $user->popact();
                        $user->popact();
                        $post->sendMessage('Bot Successfully Moved ');
                        userbots();
                        return ;
                    }
                }
            }
            $post->sendMessage('This is not a valid ID !!! If the Bot is out of reach, send it to the support team for help');
        }
    }
    function tokenedit()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query']['data']) && $post->input['callback_query']['data'] == '1'){
            $post->editMessage('Submit a new Token', $post->input['callback_query']['message']['message_id']);
        }else{
            $data['token'] = sanitize($post->input['message']['text']);
            $r = $post->getme($data['token']);
            if($r && $r['is_bot'] === true){
                $db->update('bots', $data, 'id = '.$user->session['act'][2]);
                $post->setwebhook($data['token'], THEURL.'?do='.base64_encode($user->session['act'][2]+1));
                $user->popact();
                $user->popact();
                $post->sendMessage('The Bot has been updated successfully');
                userbots();
            }else{
                $post->sendMessage("Token is not valid!\nPlease try again");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);
            }
        }
    }
    function charging()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query']['data'])){
            switch($user->session['act'][4]){
                case '1':
                    $cust = setexp($user->session['act'][2], 1);
                    $user->updateuser(['point' => $user->session['point']-round($cust/9)]);
                break;
                case '3':
                    $cust = setexp($user->session['act'][2], 3);
                    $user->updateuser(['point' => $user->session['point']-round($cust/3)]);
                break;
                case '6':
                    $cust = setexp($user->session['act'][2], 6);
                    $user->updateuser(['point' => $user->session['point']-round($cust/2)]);
                break;
                case '12':
                    $cust = setexp($user->session['act'][2], 12);
                    $user->updateuser(['point' => $user->session['point']-$cust]);
                break;
                case '2':
                    $post->sendMessage('Please select your course');
                    periodicity($user->session['act'][2]);
                break;
            }
        }else{
            $post->sendMessage('The entered command is not valid');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙Back');
            $post->ReplyButton('🏠Home',1);
        }
    }
    function sendmessage()
    {
        global $user, $post, $db;
        switch ($user->act()) {
            case 3:
                $post->sendMessage('If you want to send a message to a person or a channel, send a single message, and if you want to send a message to the Bot members send a group');
                $post->ReplyKeyboard();
                $post->ReplyButton('Send Single');
                $post->ReplyButton('Broadcast');
                $post->ReplyButton('🔙Back',1);
                $post->ReplyButton('🏠Home');
                $user->pushact(1);
                break;
            case 4:
                if($post->input['message']['text'] == 'Send Single'){
                    $post->sendMessage('Enter the ID of the channel or person (the person must have started the bot and the Bot should be in the admin channel)');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('🔙Back');
                    $post->ReplyButton('🏠Home',1);
                    $user->pushact(1);                    
                }elseif($post->input['message']['text'] == 'Broadcast'){
                    $post->sendMessage("If you want to send your message to a limited number of users, enter the required number (Latin numeric), otherwise, enter 0 to send all users \n \n Due to the compression experienced by the mass server for the Broadcast Message, For all three messages, some credits will be deducted from your account");
                    $post->ReplyKeyboard();
                    $post->ReplyButton('🔙Back');
                    $post->ReplyButton('🏠Home',1);
                    $user->pushact(2);
                }else{
                    $post->sendMessage('The request is not valid!');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('Send Single');
                    $post->ReplyButton('Broadcast');
                    $post->ReplyButton('🔙Back',1);
                    $post->ReplyButton('🏠Home');
                }
                break;
            case 5:
                if($user->session['act'][5] == '2'){
                    if($user->session['point'] < 3){
                        $post->sendMessage('Your rating for posting is not enough');
                        mkey();
                        $user->cleanact();
                        return;
                    }else{
                        $s = $user->session['point'] / 3;
                        $post->sendMessage("Your credentials are for $s message to Broadcast Message \n \n Now enter your message to submit \n Message can be anything.");
                        $user->pushact($post->input['message']['text']);
                    }
                }else{
                    $post->sendMessage('Now please send your message:');
                    $user->pushact($post->input['message']['text']);
                }
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);
                break;
            case 6:
                if($user->session['act'][5] == '2'){
                    $user->pushact(1);
                    $post->sendMessage('Your message is being sent Please be patient at the end of the post');
                    osender();
                    $post->sendMessage('Message has been sent');
                    mkey();
                    $user->cleanact();
                }else{
                    $post->sendMessage('Message has been sent');
                    mkey();
                    $post->newPost($user->session['act'][6]);
                    msg();
                    $user->cleanact();
                }
                break;
            case 7:
                $post->sendMessage('Your message is being sent Please be patient at the end of the post');
                break;
        }

    }
    function myaccont()
    {
        global $user, $post, $Bot;
        if($user->act() == 0){
            switch ($post->input['message']['text']) {
                case '🔙Back':
                case 'My account':
                    $post->sendMessage('Your Account ID: '.$user->session['id']."\nYour credits: ".$user->session['point']);
                    pkey();
                    break;
                case 'Buy points':
                    $post->sendMessage("Stay connected to this ID to buy points\n@Buildi_pr_Bot\n@Raya_9074");
                    pkey();
                    break;
                case 'Change language':
                    detect();
                    $user->pushact(2);
                    $user->flusher();
                    exit(0);
                    break;
                case 'Transfer points':
                    if($user->session['point'] > 100){
                        $user->pushact(1);
                        tridpoint();
                    }else{
                        $post->sendMessage('Transferring rating of less than 100 units is not possible');
                        pkey();
                    }
                    break;
                default:
                    $post->sendMessage('The command entered is not a concept!');
                    break;
            }
        }else{
            switch ($user->act(1)) {
                case '1':
                    tridpoint();
                    break;
                case '2':
                    detect();
                    exit(0);
                    break;
                default:
                    $user->cleanact();
                    $post->sendMessage('Your Account ID: '.$user->session['id']."\nYour credits Your credits: ".$user->session['point']);
                    pkey();
                    break;
            }
        }
    }
    function tridpoint()
    {
        global $user, $post, $db;
        if($post->input['message']['text'] == '🔙Back'){
            $user->session['act'] = ['2', '1'];
        }
        if($user->act() == 1){
            $user->pushact(1);
            $post->sendMessage('Enter the rate you want');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙Back');
            $post->ReplyButton('🏠Home',1);
            return;
        }
        if($user->act() == 2){
            $point = sanitize($post->input['message']['text']);
            if($point > $user->session['point']){
                $post->sendMessage('Your inventory is not enough for this amount Please enter a new value');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);

            }else{
                $user->pushact($point);
                $post->sendMessage('Enter the user ID of your choice');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);
            }
            return;
        }
        if($user->act() == 3){
            $id = sanitize($post->input['message']['text']);
            $user->pushact($id);
            $n = $db->first('SELECT name FROM user_info WHERE user = (SELECT chatid FROM bot_users WHERE id = '.$id.')');
            $post->sendMessage('amount of credit :'. $user->session['act'][3] ."\nTo : \n".$n['name']);
            $post->ReplyKeyboard();
            $post->ReplyButton('Do it');
            $post->ReplyButton('🔙Back',1);
            $post->ReplyButton('🏠Home');
            return;
        }else{
            if($post->input['message']['text'] == 'Do it'){
                $user->pushact($id);
                $n = $db->query('UPDATE `bot_users` SET `point`= point+'.$user->session['act'][3].' WHERE id = '.$user->session['act'][4]);
                if($n){
                    $user->updateuser(['point' => $user->session['point']-$user->session['act'][3]]);
                    $post->sendMessage('Transfer successfully');
                    pkey();
                }
            }
            return;
        }
    }
    function ordering()
    {
        global $user, $post;
        switch ($user->act()) {
            case 0:
                $user->pushact(1);
                $post->sendMessage("In this section, you can order your own project. \n \n \nProgramming and launching a website \n scripting Dedicated \n_ Dedicated Bot programming \n_ Android app programming \n \nProgramming applications under Linux and network \n \nConsult and run startup projects \n \n 〰〰〰〰〰〰〰 \n▫️ Your projects will be done by our experienced team with multiple workplaces at the best quality and at the lowest cost.");
                $post->ReplyKeyboard();
                $post->ReplyButton('ادامه');
                $post->ReplyButton('🔙Back',1);
                $post->ReplyButton('🏠Home');
                break;
            case 1:
                $user->pushact(1);
                $post->sendMessage("🔘To order the project, please write a brief description of your project on the first page");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);
                break;
            case 2:
                $user->pushact($post->input['message']['text']);
                $post->sendMessage("🔘Now list your features and features in a message as a tattoo \n At the bottom of the message, write down your budget and time.");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙Back');
                $post->ReplyButton('🏠Home',1);
                break;
            case 3:
                $user->pushact($post->input['message']['text']);
                $post->sendMessage('🔘Now, please enter a phone number (including country code)');
                $post->ReplyKeyboard();
                $post->ReplyButton(['text'=>'send my phone', 'request_contact'=>true]);
                $post->ReplyButton('🔙Back',1);
                $post->ReplyButton('🏠Home');
                break;
            case 4:
                if(isset($post->input['message']['contact'])){
                    $user->pushact($post->input['message']['contact']['phone_number']);
                }else{
                    $user->pushact($post->input['message']['text']);
                }
                $post->sendMessage("Your order has been successfully registered. \nOur employees will contact you within 24 hours");
                mkey();
                insertorder();
                $user->cleanact();
                break;
        }
    }
    function insertorder()
    {
        global $post, $user, $db;
        $data = [
            'user' => $user->session['id'],
            'description' => $user->act(3)."\n".$user->act(4),
            'number' => $user->act(5)
        ];
        $id = $db->insert('orders', $data);
        $post->newPost('321968531');
        $post->sendMessage('یک سفارش جدید ثبت شد: '.$id);
    }
