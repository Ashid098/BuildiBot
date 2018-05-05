<?php 
    function mkey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('Создать Бот');
        $post->ReplyButton('Мой аккаунт');
        $post->ReplyButton('Заказать бот',1);
        $post->ReplyButton('Поддержка',1);
        $post->ReplyButton('Помогите');
    }
    function ckey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('Регистрация бота');
        $post->ReplyButton('Мои боты');
        $post->ReplyButton('Приложения',1);
        $post->ReplyButton('🏠Главная',1);
    }
    function pkey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('Купить очки');
        $post->ReplyButton('Перенос точек');
        $post->ReplyButton('🏠Главная',1);
    }
    function terminal()
    {
        global $user, $post, $robot;
            if(isset($post->input['message']['text'])){
                if(strpos($post->input['message']['text'], 'start')){
                    $user->cleanact();
                    $post->sendMessage('🌷🌷желанный');
                    mkey();
                    $post->sendAll();
                    return;
                }elseif($post->input['message']['text'] == '🔙назад'){
                    $user->popact();
                }elseif($post->input['message']['text'] == '🏠Главная'){
                    $user->cleanact();
                    $post->sendMessage('спасибо');
                    mkey();
                    $post->sendlast();
                    return;
                }
            }
            if($user->act() == -1){
                switch ($post->input['message']['text']) {
                    case 'Авторизоваться☑️':
                        $post->sendMessage('🌷🌷желанный');
                        mkey();
                        break;
                    case 'Создать Бот':
                        $user->pushact(1);
                        botcreate();
                        break;
                    case 'Мой аккаунт':
                        $user->pushact(2);
                        myaccont();
                        break;
                    case 'Заказать бот':
                        $user->pushact(3);
                        ordering();
                        break;
                    case 'Поддержка':
                        $post->sendMessage("Чтобы отправить билет роботу Поддержка ниже \ n🔻 @ Buildi_en_Bot \ n🔻 @ Buildi_pr_Bot \ n🔻 @ Buildi_ru_Bot");
                        mkey();
                        break;
                    case 'Помогите':
                        $post->sendMessage("Вы можете создать своего робота без знания робота, который вы хотите создать \ n \ n▌️. Чтобы создать робота, сначала необходимо ввести раздел «Создать Бот», а после прочтения спецификации робота в разделе «Приложения» введите раздел «Регистрация бота». И зарегистрируйте новый робот \ n \ n💢 Для регистрации бота необходимо сначала создать бот в boot \ n Чтобы создать бот в вашей загрузке, сначала введите загрузочный шрифт @botfather \ n, затем введите команду / newbot и затем информация о роботе Введите свое имя пользователя и имя пользователя в последовательности \ nКогда команда «Создать Бот» будет завершена, бот предоставит вам текст, содержащий токен. \ n В этом текстовом поле выберите и скопируйте раздел «Токен» и в «Bild» Отправить бот в раздел «Создать Бот» (обратите внимание, что вам не нужно отправлять сообщение своему фейдеру) \ n \ n🔘 Если ваш робот недоступен в Приложениях, вы можете заказать его в разделе «Заказать бот», чтобы сделать его бесплатным для вас.");
                        mkey();
                        break;
                    default:
                        $post->sendMessage('Введенная команда не является понятием!');
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
                case '🔙назад':
                case 'Создать Бот':
                    $post->sendMessage("▪В этом разделе вы можете проверить свои роботы или создать новый робот. \ N \ nМы рекомендуем вам прочитать описание в разделе «Приложения» перед созданием бота.");
                    ckey();
                    break;
                case 'Регистрация бота':
                    $user->pushact(1);
                    addbot();
                    break;
                case 'Мои боты':
                    $user->pushact(2);
                    mybots();
                    break;
                case 'Приложения':
                    $user->pushact(3);
                    apps();
                    break;

                default:
                    $post->sendMessage('Введенная команда не является понятием!');
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
        global $user, $post, $robot, $db;
        if($user->act() == 1){
            $post->sendMessage('Сначала выберите робота, которого хотите');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙назад');
            $post->ReplyButton('🏠Главная',1);
            $post->newPost();
            if(applist(true)){
                $post->sendMessage('Bots: ');
            }else{
                $post->sendMessage('Вашей кредитной для создания нового бота недостаточно');
            }
            $user->pushact(1);
        }elseif($user->act() == 2){
            if(!isset($post->input['callback_query']['data'])){return;}
            $id = $post->input['callback_query']['data'];
            $post->answerCallназадQuery($post->input['callback_query']['id'], 'app selected');
            $post->newPost();
            $post->sendMessage('Please select your course');
            periodicity($id);
            $user->pushact($id);
        }elseif($user->act() == 3){
            if(!isset($post->input['callback_query']['data'])){return;}
            $exp = $post->input['callback_query']['data'];
            $post->answerCallназадQuery($post->input['callback_query']['id'], 'periodicity selected');
            $post->newPost();
            $post->sendMessage('Пожалуйста, отправьте робота в Token');
            $user->pushact($exp);
        }else{
            $r = false;
            if(isset($post->input['message']['text'])){
                $data['token'] = sanitize($post->input['message']['text']);
                $r = $post->getme($data['token']);
            }
            if($r && $r['is_bot'] === true){
                if($db->first(dbselect('bots', 'token = \''.$data['token'].'\''))){
                    $post->sendMessage('Этот робот уже зарегистрирован!');
                    return ;
                }
                $data['username'] = $r['username'];
                $data['app'] = $user->act(3);
                $data['admin'] = $user->session['chatid'];
                $data['lang'] = 'ru';
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
                $db->insert('bot_users', ['chatid'=>$user->session['chatid'], 'lang'=>'ru', 'stat'=>1, 'bot'=>$id]);
                $post->sendMessage($r['username']."\nРобот был успешно зарегистрирован");
                mkey();
                $user->cleanact();
            }else{
                $post->sendMessage('Токен недействителен!');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);
            }
        }
    }
    function apps()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query'])){
            $app = $db->first(dbselect('appinfo', 'app = \''.$post->input['callback_query']['data'].'\' AND ln = \'ru\''));
            $post->answerCallназадQuery($post->input['callback_query']['id'], 'app info');
            $post->newPost();
            $post->editMessage($app['info'], $post->input['callback_query']['message']['message_id'], false, 'HTML');            
            applist();
        }else{
            $post->sendMessage('Выберите робота, которого хотите');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙назад');
            $post->ReplyButton('🏠Главная',1);
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
        $post->InlineButton(['text'=>'назад to list', 'callback_data'=>'назад'],1);
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
                    $post->sendMessage('Please select a robot');
                    userbots();
                }else{
                    $post->sendMessage('You have not registered any robots');
                    $user->popact();
                    ckey();
                }
            }
        }elseif($user->act() == 2){
            if(isset($post->input['callback_query'])){
                switch($post->input['callback_query']['data']){
                    default:
                        $user->popact();
                        $post->editMessage('Please select a robot', $post->input['callback_query']['message']['message_id']);
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
                        $post->editMessage('If you activate this section, the ads will automatically be sent to your robot users and you will receive points in proportion to the number of your users.', $post->input['callback_query']['message']['message_id']);
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
                if($post->input['message']['text'] == '🔙назад'){
                    botkeyboard();
                    return;
                }
                $post->sendMessage('The entered command is not valid');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);
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
            $post->sendMessage("First, you need to be a member of your bot and your robot \n Then send the \"Мой аккаунт\" section to your username. \nIf you do not have access to the robot with the transfer of your management, назад Management will give you No refundable \n Are you sure about the transfer?");
            $post->ReplyKeyboard();
            $post->ReplyButton('Yes Im sure');
            $post->ReplyButton('No');
            $post->ReplyButton('🏠Главная',1);
        }elseif($user->act() == 3){
            if($post->input['message']['text'] == 'Yes Im sure'){
                $post->sendMessage('Now enter the username of the user');
                $post->ReplyKeyboard();
                $post->ReplyButton('🏠Главная');
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
                        $post->sendMessage('Robot Successfully Moved ');
                        userbots();
                        return ;
                    }
                }
            }
            $post->sendMessage('This is not a valid ID !!! If the robot is out of reach, send it to the Поддержка team for Помогите');
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
                $post->sendMessage('The robot has been updated successfully');
                userbots();
            }else{
                $post->sendMessage("Токен недействителен!\nPlease try again");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);
            }
        }
    }
    function setexp($bid, $m){
        global $db;
        $bot = $db->first(dbselect('bots', 'id = '.$bid));
        $data['expire'] = ($m*2592000) + $bot['expire'];
        $db->update('bots', $data, 'id = '.$bid);
        return $db->getValue('cust', 'apps', 'id = '.$bot['app']);
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
            $post->ReplyButton('🔙назад');
            $post->ReplyButton('🏠Главная',1);
        }
    }
    function sendmessage()
    {
        global $user, $post, $db;
        switch ($user->act()) {
            case 3:
                $post->sendMessage('If you want to send a message to a person or a channel, send a single message, and if you want to send a message to the robot members send a group');
                $post->ReplyKeyboard();
                $post->ReplyButton('Send Single');
                $post->ReplyButton('Broadcast');
                $post->ReplyButton('🔙назад',1);
                $post->ReplyButton('🏠Главная');
                $user->pushact(1);
                break;
            case 4:
                if($post->input['message']['text'] == 'Send Single'){
                    $post->sendMessage('Enter the ID of the channel or person (the person must have started the bot and the robot should be in the admin channel)');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('🔙назад');
                    $post->ReplyButton('🏠Главная',1);
                    $user->pushact(1);                    
                }elseif($post->input['message']['text'] == 'Broadcast'){
                    $post->sendMessage("If you want to send your message to a limited number of users, enter the required number (Latin numeric), otherwise, enter 0 to send all users \n \n Due to the compression experienced by the mass server for the Broadcast Message, For all three messages, a score will be deducted from your account");
                    $post->ReplyKeyboard();
                    $post->ReplyButton('🔙назад');
                    $post->ReplyButton('🏠Главная',1);
                    $user->pushact(2);
                }else{
                    $post->sendMessage('The request is not valid!');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('Send Single');
                    $post->ReplyButton('Broadcast');
                    $post->ReplyButton('🔙назад',1);
                    $post->ReplyButton('🏠Главная');
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
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);
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
        global $user, $post, $robot;
        if($user->act() == 0){
            switch ($post->input['message']['text']) {
                case '🔙назад':
                case 'Мой аккаунт':
                    $post->sendMessage('Your Account ID: '.$user->session['id']."\nYour Score Your Score: ".$user->session['point']);
                    pkey();
                    break;
                case 'Купить очки':
                    $post->sendMessage("Stay connected to this ID to Купить очки\n@Buildi_pr_Bot\n@Raya_9074");
                    pkey();
                    break;
                case 'Перенос точек':
                    if($user->session['point'] > 100){
                        $user->pushact(1);
                        tridpoint();
                    }else{
                        $post->sendMessage('Transferring rating of less than 100 units is not possible');
                        pkey();
                    }
                    break;
                default:
                    $post->sendMessage('Введенная команда не является понятием!');
                    break;
            }
        }else{
            switch ($user->act(1)) {
                case '1':
                    tridpoint();
                    break;
                default:
                    $user->cleanact();
                    $post->sendMessage('Your Account ID: '.$user->session['id']."\nYour Score Your Score: ".$user->session['point']);
                    pkey();
                    break;
            }
        }
    }
    function tridpoint()
    {
        global $user, $post, $db;
        if($post->input['message']['text'] == '🔙назад'){
            $user->session['act'] = ['2', '1'];
        }
        if($user->act() == 1){
            $user->pushact(1);
            $post->sendMessage('Enter the rate you want');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙назад');
            $post->ReplyButton('🏠Главная',1);
            return;
        }
        if($user->act() == 2){
            $point = sanitize($post->input['message']['text']);
            if($point > $user->session['point']){
                $post->sendMessage('Your inventory is not enough for this amount Please enter a new value');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);

            }else{
                $user->pushact($point);
                $post->sendMessage('Enter the user ID of your choice');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);
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
            $post->ReplyButton('🔙назад',1);
            $post->ReplyButton('🏠Главная');
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
                $post->sendMessage("In this section, you can order your own project. \n \n \nProgramming and launching a website \n scripting Dedicated \n_ Dedicated robot programming \n_ Android app programming \n \nProgramming Приложения under Linux and network \n \nConsult and run startup projects \n \n 〰〰〰〰〰〰〰 \n▫️ Your projects will be done by our experienced team with multiple workplaces at the best quality and at the lowest cost.");
                $post->ReplyKeyboard();
                $post->ReplyButton('ادامه');
                $post->ReplyButton('🔙назад',1);
                $post->ReplyButton('🏠Главная');
                break;
            case 1:
                $user->pushact(1);
                $post->sendMessage("🔘To order the project, please write a brief description of your project on the first page");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);
                break;
            case 2:
                $user->pushact($post->input['message']['text']);
                $post->sendMessage("🔘Now list your features and features in a message as a tattoo \n At the bottom of the message, write down your budget and time.");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙назад');
                $post->ReplyButton('🏠Главная',1);
                break;
            case 3:
                $user->pushact($post->input['message']['text']);
                $post->sendMessage('🔘Now, please enter a phone number (including country code)');
                $post->ReplyKeyboard();
                $post->ReplyButton(['text'=>'send my phone', 'request_contact'=>true]);
                $post->ReplyButton('🔙назад',1);
                $post->ReplyButton('🏠Главная');
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
