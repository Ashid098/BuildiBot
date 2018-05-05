<?php 
    function mkey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('ساخت ربات');
        $post->ReplyButton('حساب من');
        $post->ReplyButton('سفارش ربات',1);
        $post->ReplyButton('پشتیبانی',1);
        $post->ReplyButton('راهنما');
    }
    function ckey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('ثبت ربات');
        $post->ReplyButton('ربات های من');
        $post->ReplyButton('برنامه ها',1);
        $post->ReplyButton('🏠خانه',1);
    }
    function pkey()
    {
        global $post;
        $post->ReplyKeyboard();
        $post->ReplyButton('خرید اعتبار');
        $post->ReplyButton('انتقال اعتبار');
        $post->ReplyButton('🏠خانه',1);
    }
    function terminal()
    {
        global $user, $post, $robot;
            if(isset($post->input['message']['text'])){
                if(strpos($post->input['message']['text'], 'start')){
                    $user->cleanact();
                    $post->sendMessage('🌷🌷خوش آمدید');
                    mkey();
                    $post->sendAll();
                    return;
                }elseif($post->input['message']['text'] == '🔙بازگشت'){
                    $user->popact();
                }elseif($post->input['message']['text'] == '🏠خانه'){
                    $user->cleanact();
                    $post->sendMessage('از بردباری شما متشکریم');
                    mkey();
                    $post->sendlast();
                    return;
                }
            }
            if($user->act() == -1){
                switch ($post->input['message']['text']) {
                    case 'ورود☑️':
                        $post->sendMessage('🌷🌷خوش آمدید');
                        mkey();
                        break;
                    case 'ساخت ربات':
                        $user->pushact(1);
                        botcreate();
                        break;
                    case 'حساب من':
                        $user->pushact(2);
                        myaccont();
                        break;
                    case 'سفارش ربات':
                        $user->pushact(3);
                        ordering();
                        break;
                    case 'پشتیبانی':
                        $post->sendMessage("جهت ارسال تیکت به پشتیبانی از ربات زیر اقدام کنید\n🔻@Buildi_en_Bot\n🔻@Buildi_pr_Bot\n🔻@Buildi_ru_Bot");
                        mkey();
                        break;
                    case 'راهنما':
                        $post->sendMessage("🔴در بیلدی بات شما میتوانید بدون دانش برنامه نویسی ربات مورد نظر خود را بسازید\n\n☑️برای ساخت یک ربات کافی است ابتدا وارد بخش ساخت ربات شده و پس از مطالعه ی مشخصات ربات مورد نظر در بخش برنامه ها وارد بخش ثبت ربات شوید و ربات جدیدی ثبت نمایید\n\n💢برای ثبت ربات لازم است ابتدا یک ربات در بات فادر بسازید\nبرای ساخت ربات در بات فادر ابتدا وارد بات فادر شده @botfather \nسپس دستور /newbot را وارد نمایید و پس از ان اطلاعات ربات خود را به ترتیب نام و یوزرنیم را وارد نمایید\nپس از اتمام ساخت ربات، بات فادر یک متن شامل توکن به شما میدهد\nدر این متن فقط بخش توکن را انتخاب و کپی نمایید و در بیلدی بات در بخش ساخت ربات ارسال نمایید(دقت کنید که نباید پیام بات فادر را فروارد نمایید)\n\n🔘اگر ربات مورد نظرتان در برنامه ها موجود نبود میتوانید از بخش سفارش ربات، ان را سفارش دهید تا به صورت رایگان برای شما تهیه شود");
                        mkey();
                        break;
                    default:
                        $post->sendMessage('دستور وارد شده مفهوم نیست!');
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
                case '🔙بازگشت':
                case 'ساخت ربات':
                    $post->sendMessage("▪️دراین بخش میتوانید ربات های خود را برسی کنید ویا ربات جدیدی بسازید\n🔻پیشنهاد میکنیم قبل از ساخت ربات توضیحات موجود در بخش برنامه ها را مطالعه نمایید");
                    ckey();
                    break;
                case 'ثبت ربات':
                    $user->pushact(1);
                    addbot();
                    break;
                case 'ربات های من':
                    $user->pushact(2);
                    mybots();
                    break;
                case 'برنامه ها':
                    $user->pushact(3);
                    apps();
                    break;

                default:
                    $post->sendMessage('دستور وارد شده مفهوم نیست!');
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
            $post->sendMessage('ابتدا ربات مورد نظر خود را انتخاب کنید');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙بازگشت');
            $post->ReplyButton('🏠خانه',1);
            $post->newPost();
            if(applist(true)){
                $post->sendMessage('Bots: ');
            }else{
                $post->sendMessage('اعتبار شما برای ساخت ربات جدید کافی نیست');
            }
            $user->pushact(1);
        }elseif($user->act() == 2){
            if(!isset($post->input['callback_query']['data'])){return;}
            $id = $post->input['callback_query']['data'];
            if($id == 'e'){
                $post->answerCallbackQuery($post->input['callback_query']['id'], 'برای ثبت این ربات لازم است ابتدا اعتبار حسابتان را افزایش دهید. همچنین سایر اطلاعات این ربات را در بخش برنامه ها میتوانید مطالعه کنید.', true);
            }else{
                $post->answerCallbackQuery($post->input['callback_query']['id'], 'app selected');
                $post->newPost();
                $post->sendMessage('لطفا دوره مد نظرتان را انتخاب کنید');
                periodicity($id);
                $user->pushact($id);
            }
        }elseif($user->act() == 3){
            if(!isset($post->input['callback_query']['data'])){return;}
            $exp = $post->input['callback_query']['data'];
            $post->answerCallbackQuery($post->input['callback_query']['id'], 'periodicity selected');
            $post->newPost();
            $post->sendMessage('لطفا توکن ربات را ارسال نمایید');
            $user->pushact($exp);
        }else{
            $r = false;
            $post->sendMessage('لطفا کمی صبر کنید');
            $post->sendlast();
            if(isset($post->input['message']['text'])){
                $data['token'] = sanitize($post->input['message']['text']);
                $r = $post->getme($data['token']);
            }
            if($r && $r['is_bot'] === true){
                if($db->first(dbselect('bots', 'token = \''.$data['token'].'\''))){
                    $post->sendMessage('این ربات قبلا ثبت شده!');
                    return ;
                }
                $data['username'] = $r['username'];
                $data['app'] = $user->act(3);
                $data['admin'] = $user->session['chatid'];
                $data['lang'] = 'pr';
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
                $db->insert('bot_users', ['chatid'=>$user->session['chatid'], 'lang'=>'pr', 'stat'=>1, 'bot'=>$id]);
                $post->sendMessage($r['username']."\nربات با موفقیت ثبت شد");
                mkey();
                $user->cleanact();
            }else{
                $post->sendMessage('توکن ارسال شده معتبر نیست!');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);
            }
        }
    }
    function apps()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query'])){
            $app = $db->first(dbselect('appinfo', 'app = \''.$post->input['callback_query']['data'].'\' AND ln = \'pr\''));
            $post->answerCallbackQuery($post->input['callback_query']['id'], 'app info');
            $post->newPost();
            $post->editMessage($app['info'], $post->input['callback_query']['message']['message_id'], false, 'HTML');
            applist();
        }else{
            $post->sendMessage('ربات مورد نظر خود را انتخاب کنید');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙بازگشت');
            $post->ReplyButton('🏠خانه',1);
            $post->newPost();
            $post->sendMessage('Bots:');
            applist();
        }
    }
    function botkeyboard()
    {
        global $user, $post, $db;
        $app = $db->first(dbselect('bots', 'id = '.$user->session['act'][2]));
        $post->editMessage('<b>'.$app['username']."</b>\nتوکن: ".$app['token']."\nانقضا: ".date('Y/m/d',$app['expire'])."\nکسب درامد: ".($app['adv'] ? 'فعال': 'غیرفعال'), $post->input['callback_query']['message']['message_id'], false, 'HTML');
        $post->InlineKeyboard();
        $post->InlineButton(['text'=>'تعویض توکن', 'callback_data'=>'1']);
        $post->InlineButton(['text'=>'شارژ کردن', 'callback_data'=>'2']);
        $post->InlineButton(['text'=>'ارسال پیام', 'callback_data'=>'3'],1);
        $post->InlineButton(['text'=>'کسب درامد', 'callback_data'=>'5']);
        $post->InlineButton(['text'=>'انتقال مدیریت', 'callback_data'=>'6'],1);
        if($app['vip'] == 0){
            $post->InlineButton(['text'=>'Premium', 'callback_data'=>'7'],1);
        }
        $post->InlineButton(['text'=>'حذف', 'callback_data'=>'4'],1);
        $post->InlineButton(['text'=>'بازگشت به لیست', 'callback_data'=>'back'],1);
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
                    $post->sendMessage('لطفا یک ربات انتخاب نمایید');
                    userbots();
                }else{
                    $post->sendMessage('شما هیچ رباتی ثبت نکرده اید');
                    $user->popact();
                    ckey();
                }
            }
        }elseif($user->act() == 2){
            if(isset($post->input['callback_query'])){
                switch($post->input['callback_query']['data']){
                    default:
                        $user->popact();
                        $post->editMessage('لطفا یک ربات انتخاب نمایید', $post->input['callback_query']['message']['message_id']);
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
                    case '7':
                        $user->pushact('7');
                        premium();
                        break;
                    case '3':
                        $user->pushact('3');
                        sendmessage();
                        break;
                    case '5':
                        $user->pushact('5');
                        $post->editMessage('درصورتی که این بخش را فعال نمایید به صورت خودکار برای کاربران ربات شما تبلیغات ارسال میشود و شما متناسب با تعداد کاربرانتان اعتبار دریافت خواهید کرد', $post->input['callback_query']['message']['message_id']);
                        $post->InlineKeyboard();
                        if($db->getValue('adv', 'bots', 'id = '.$user->session['act'][2])){
                            $post->InlineButton(['text'=> 'غیرفعال کردن', 'callback_data'=>'0']);
                        }else{
                            $post->InlineButton(['text'=>'فعال کردن', 'callback_data'=>'1']);
                        }
                        break;
                    case '6':
                        changeadmin();
                        $user->pushact('6');
                        break;
                    case '4':
                        $user->pushact('4');
                        $post->editMessage('ایا از حذف این ربات مطمئنید؟', $post->input['callback_query']['message']['message_id']);
                        $post->InlineKeyboard();
                        $post->InlineButton(['text'=>'خیر', 'callback_data'=>'0']);
                        $post->InlineButton(['text'=>'بله', 'callback_data'=>'1']);
                        break;
                }
            }else{
                if($post->input['message']['text'] == '🔙بازگشت'){
                    botkeyboard();
                    return;
                }
                $post->sendMessage('دستور وارد شده معتبر نیست');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);
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
                        $post->editMessage('ربات با موفقیت حذف شد', $post->input['callback_query']['message']['message_id']);
                        userbots();
                    }else{
                        $user->popact();
                        botkeyboard();
                    }
                    break;
                case '5':
                    $db->update('bots', ['adv'=> $post->input['callback_query']['data']], 'id = '.$user->session['act'][2]);
                    $post->answerCallbackQuery($post->input['callback_query']['id'], 'ارسال خودکار تبلیغات برای این ربات فعال شد و درامد آن به موجودی اعتبار شما افزوده خواهد شد.', true);
                    $user->popact();
                    botkeyboard();
                    break;
                case '6':
                    changeadmin();
                    break;
                case '7':
                    premium();
                    break;
            }
        }
    }
    function changeadmin()
    {
        global $user, $post, $db;
        if($user->act() == 2){
            $post->sendMessage("ابتدا لازم است کاربر مورد نظر شما عضو بیلدی بات و همینطور ربات شما باشد\nسپس از بخش «حساب من» شناسه ی کاربری خودش را برای شما ارسال کند\nبا انتقال مدیریت دیگر شما هیچ دسترسی به ربات نخواهید داشت و بازگشت مدیریت به شما به هیچ وجه قابل بازگشت نیست\nآیا نسبت به انتقال مطمئن هستید؟");
            $post->ReplyKeyboard();
            $post->ReplyButton('بله مطمئنم');
            $post->ReplyButton('خیر');
            $post->ReplyButton('🏠خانه',1);
        }elseif($user->act() == 3){
            if($post->input['message']['text'] == 'بله مطمئنم'){
                $post->sendMessage('اکنون شناسه کاربر مورد نظر را وارد کنید');
                $post->ReplyKeyboard();
                $post->ReplyButton('🏠خانه');
            }else{
                $user->popact();
                $user->popact();
                $post->sendMessage('بسیار خب!');
                userbots();
            }
        }else{
            $chatid = $db->getValue('chatid', 'bot_users', 'id ='.$post->input['message']['text']);
            if($chatid){
                if($db->update('bots', ['admin'=>$chatid],'id = '.$user->session['act'][2])){
                    if($db->update('bot_users', ['admin'=>1],'chatid = '.$chatid.' AND bot ='.$user->session['act'][2])){
                        $user->popact();
                        $user->popact();
                        $post->sendMessage('ربات با موفقیت منتقل شد');
                        userbots();
                        return ;
                    }
                }
            }
            $post->sendMessage('این شناسه صحیح نیست!!! درصورتی که ربات از دسترس شما خارج شد توکن آن را برای تیم پشتیبانی ارسال نمایید تا برسی نمایند');
        }
    }
    function tokenedit()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query']['data']) && $post->input['callback_query']['data'] == '1'){
            $post->editMessage('توکن جدید را ارسال نمایید', $post->input['callback_query']['message']['message_id']);
        }else{
            $data['token'] = sanitize($post->input['message']['text']);
            $r = $post->getme($data['token']);
            if($r && $r['is_bot'] === true){
                $db->update('bots', $data, 'id = '.$user->session['act'][2]);
                $post->setwebhook($data['token'], THEURL.'?do='.base64_encode($user->session['act'][2]+1));
                $user->popact();
                $user->popact();
                $post->sendMessage('ربات با موفقیت بروزرسانی شد');
                userbots();
            }else{
                $post->sendMessage("توکن ارسال شده معتبر نیست!\nدوباره امتحان کنید");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);
            }
        }
    }
    function charging()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query']['data'])){
            switch($user->session['act'][3]){
                case '1':
                    $cust = setexp($user->session['act'][2], 1);
                    $user->updateuser(['point' => $user->session['point']-round($cust/9)]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '3':
                    $cust = setexp($user->session['act'][2], 3);
                    $user->updateuser(['point' => $user->session['point']-round($cust/3)]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '6':
                    $cust = setexp($user->session['act'][2], 6);
                    $user->updateuser(['point' => $user->session['point']-round($cust/2)]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '12':
                    $cust = setexp($user->session['act'][2], 12);
                    $user->updateuser(['point' => $user->session['point']-$cust]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '2':
                    $post->sendMessage('لطفا دوره مد نظرتان را انتخاب کنید');
                    periodicity($user->session['act'][2], 1);
                break;
            }
        }else{
            $post->sendMessage('دستور وارد شده معتبر نیست');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙بازگشت');
            $post->ReplyButton('🏠خانه',1);
        }
    }
    function premium()
    {
        global $user, $post, $db;
        if(isset($post->input['callback_query']['data'])){
            switch($post->input['callback_query']['data']){
                case '1':
                    $cust = setexp($user->session['act'][2], 1,1);
                    $user->updateuser(['point' => $user->session['point']-round($cust/9)]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '3':
                    $cust = setexp($user->session['act'][2], 3,1);
                    $user->updateuser(['point' => $user->session['point']-round($cust/3)]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '6':
                    $cust = setexp($user->session['act'][2], 6,1);
                    $user->updateuser(['point' => $user->session['point']-round($cust/2)]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '12':
                    $cust = setexp($user->session['act'][2], 12,1);
                    $user->updateuser(['point' => $user->session['point']-$cust]);
                    $post->sendmessage('بروزرسانی با موفقیت انجام شد.');
                    $user->popact();
                    botkeyboard();
                break;
                case '7':
                    $post->sendMessage('لطفا دوره مد نظرتان را انتخاب کنید');
                    periodicityvip($user->session['act'][2]);
                break;
            }
        }else{
            $post->sendMessage('دستور وارد شده معتبر نیست');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙بازگشت');
            $post->ReplyButton('🏠خانه',1);
        }
    }
    function sendmessage()
    {
        global $user, $post, $db;
        switch ($user->act()) {
            case 3:
                $post->sendMessage('اگر میخواهید برای یک شخص یا یک کانال پیام ارسال کنید ارسال تکی، و در صورتی که میخواهید برای اعضای ربات پیام ارسال کنید ارسال گروهی را انتخاب کنید');
                $post->ReplyKeyboard();
                $post->ReplyButton('ارسال تکی');
                $post->ReplyButton('ارسال گروهی');
                $post->ReplyButton('🔙بازگشت',1);
                $post->ReplyButton('🏠خانه');
                $user->pushact(1);
                break;
            case 4:
                if($post->input['message']['text'] == 'ارسال تکی'){
                    $post->sendMessage('آی دی کانال یا فرد مورد نظر را وارد نمایید(فرد باید قبلا ربات را استارت کرده باشد و ربات باید در کانال ادمین باشد(');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('🔙بازگشت');
                    $post->ReplyButton('🏠خانه',1);
                    $user->pushact(1);                    
                }elseif($post->input['message']['text'] == 'ارسال گروهی'){
                    $post->sendMessage("اگر میخواهید پیامتان به تعداد محدودی از کاربران ارسال شود، تعداد مورد نظر را (با اعداد لاتین) وارد نمایید، درغیر اینصورت برای ارسال به تمام کاربران عدد 0 را وارد نمایید\n\nبه علت فشاری که برای ارسال پیام انبوه سرور متحمل میشود به ازای هر سه پیام یک اعتبار از حسابتان کسر خواهد شد");
                    $post->ReplyKeyboard();
                    $post->ReplyButton('🔙بازگشت');
                    $post->ReplyButton('🏠خانه',1);
                    $user->pushact(2);
                }else{
                    $post->sendMessage('درخواست معتبر نیست!');
                    $post->ReplyKeyboard();
                    $post->ReplyButton('ارسال تکی');
                    $post->ReplyButton('ارسال گروهی');
                    $post->ReplyButton('🔙بازگشت',1);
                    $post->ReplyButton('🏠خانه');
                }
                break;
            case 5:
                if($user->session['act'][5] == '2'){
                    if($user->session['point'] < 3){
                        $post->sendMessage('اعتبار شما برای ارسال کافی نیست');
                        mkey();
                        $user->cleanact();
                        return;
                    }else{
                        $s = $user->session['point'] / 3;
                        $post->sendMessage("اعتبار شما برای ارسال پیام انبوه $s پیام است\n\nاکنون پیامتان را برای ارسال وارد کنید\nپیام میتواند هرچیزی باشد");
                        $user->pushact($post->input['message']['text']);
                    }
                }else{
                    $post->sendMessage('اکنون پیامتان را بنویسید:');
                    $user->pushact($post->input['message']['text']);
                }
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);
                break;
            case 6:
                if($user->session['act'][5] == '2'){
                    $user->pushact(1);
                    $post->sendMessage('پیام شما درحال ارسال است لطفا تا پایان ارسال شکیبا باشید');
                    $post->sendlast();
                    osender();
                    $post->sendMessage('پیام ارسال شد');
                    mkey();
                    $user->cleanact();
                }else{
                    $post->sendMessage('پیام ارسال شد');
                    mkey();
                    $user->cleanact();
                    $post->newPost($user->session['act'][6]);
                    msg();
                }
                break;
            case 7:
                $post->sendMessage('پیام شما درحال ارسال است لطفا تا پایان ارسال شکیبا باشید');
                break;
        }

    }
    function myaccont()
    {
        global $user, $post, $robot;
        if($user->act() == 0 && isset($post->input['message']['text'])){
            switch ($post->input['message']['text']) {
                case '🔙بازگشت':
                case 'حساب من':
                    $post->sendMessage('شناسه حساب شما: '.$user->session['id']."\nموجودی اعتبار شما: ".$user->session['point']);
                    pkey();
                    break;
                case 'خرید اعتبار':
                    payment();
                    $user->pushact(2);
                    break;
                case 'انتقال اعتبار':
                    if($user->session['point'] > 100){
                        $user->pushact(1);
                        tridpoint();
                    }else{
                        $post->sendMessage('انتقال اعتبار کمتر از 100 واحد ممکن نیست');
                        pkey();
                    }
                    break;
                default:
                    $post->sendMessage('دستور وارد شده مفهوم نیست!');
                    break;
            }
        }else{
            switch ($user->act(1)) {
                case '1':
                    tridpoint();
                    break;
                case '2':
                    payment();
                    break;
                default:
                    $user->cleanact();
                    $post->sendMessage('شناسه حساب شما: '.$user->session['id']."\nموجودی اعتبار شما: ".$user->session['point']);
                    pkey();
                    break;
            }
        }
    }
    function payment(){
        global $user, $post;
        if($user->act() == 0){
            $post->sendMessage("لطفا مبلغ مورد نظرتان را (به تومان) وارد کنید\nدقت کنید کیبورد شما حتما در حالت en باشد و فقط از اعداد لاتین استفاده کنید\n<code>هر یک هزار تومان معادل 100 واحد اعتبار است.</code>",'HTML');
            $post->ReplyKeyboard();
            $post->ReplyButton('🏠خانه');
        }elseif(isset($post->input['message']['text']) && intval($post->input['message']['text'])){
            $post->sendMessage(($post->input['message']['text']/10)." اعتبار دریافت میکنید\nجهت پرداخت آنلاین کلید پرداخت را لمس کنید");
            $post->InlineKeyboard();
            $post->InlineButton(['text'=>'پرداخت', 'url'=>'https://buildibot.ir/pay/zarinpal/?pid=1&payer='.$user->session['id'].'&amount='.($post->input['message']['text'])]);
        }else{
            $post->sendMessage("مقدار وارد شده معتبرنیست\nدقت کنید کیبورد شما حتما در حالت en باشد و فقط از اعداد لاتین استفاده کنید\n<code>هر یک هزار تومان معادل 100 واحد اعتبار است.</code>",'HTML');
            $post->ReplyKeyboard();
            $post->ReplyButton('🏠خانه');
        }
    }
    function tridpoint()
    {
        global $user, $post, $db;
        if($post->input['message']['text'] == '🔙بازگشت'){
            $user->session['act'] = ['2', '1'];
        }
        if($user->act() == 1){
            $user->pushact(1);
            $post->sendMessage('میزان اعتبار مورد نظرتان را وارد نمایید');
            $post->ReplyKeyboard();
            $post->ReplyButton('🔙بازگشت');
            $post->ReplyButton('🏠خانه',1);
            return;
        }
        if($user->act() == 2){
            $point = sanitize($post->input['message']['text']);
            if($point > $user->session['point']){
                $post->sendMessage('موجودی شما برای این مقدار کافی نیست لطفا یک مقدار جدید وارد نمایید');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);

            }else{
                $user->pushact($point);
                $post->sendMessage('شناسه کاربر مورد نظرتان را وارد نمایید');
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);
            }
            return;
        }
        if($user->act() == 3){
            $id = sanitize($post->input['message']['text']);
            $user->pushact($id);
            $n = $db->first('SELECT name FROM user_info WHERE user = (SELECT chatid FROM bot_users WHERE id = '.$id.')');
            $post->sendMessage('میزان اعتبار :'. $user->session['act'][3] ."\nبه حساب : \n".$n['name']);
            $post->ReplyKeyboard();
            $post->ReplyButton('تایید');
            $post->ReplyButton('🔙بازگشت',1);
            $post->ReplyButton('🏠خانه');
            return;
        }else{
            if($post->input['message']['text'] == 'تایید'){
                $user->pushact($id);
                $n = $db->query('UPDATE `bot_users` SET `point`= point+'.$user->session['act'][3].' WHERE id = '.$user->session['act'][4]);
                if($n){
                    $user->updateuser(['point' => $user->session['point']-$user->session['act'][3]]);
                    $post->sendMessage('انتقال با موقیت انجام شد');
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
                $post->sendMessage("◾️در این قسمت شما میتوانید پروژه ی اختصاصی خود را سفارش دهید\n〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰\n➖برنامه نویسی و راه اندازی وب سایت\n➖اسکریپت نویسی اختصاصی\n➖برنامه نویسی ربات اختصاصی\n➖برنامه نویسی اپلیکیشن اندروید\n➖برنامه نویسی برنامه های تحت شبکه و لینوکس\n➖مشاوره و اجرای پروژه های استارت اپی\n〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰〰\n▫️پروژه های شما توسط تیم های مجرب ما با نمونه کار های متعدد ، در بهترین کیفیت و کمترین هزینه انجام میشود");
                $post->ReplyKeyboard();
                $post->ReplyButton('ادامه');
                $post->ReplyButton('🔙بازگشت',1);
                $post->ReplyButton('🏠خانه');
                break;
            case 1:
                $user->pushact(1);
                $post->sendMessage("🔘جهت سفارش پروژه لطفا #فقط_در_یک_پیام ابتدا توضیح مختصری از پروژه خود بنویسید");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);
                break;
            case 2:
                $user->pushact($post->input['message']['text']);
                $post->sendMessage("🔘اکنون بصورت تیتروار امکانات و ویژگی های مدنظرتان را در یک پیام لیست کنید\nدر انتهای پیام نیز میزان بودجه و زمان مدنظرتان را بنویسید");
                $post->ReplyKeyboard();
                $post->ReplyButton('🔙بازگشت');
                $post->ReplyButton('🏠خانه',1);
                break;
            case 3:
                $user->pushact($post->input['message']['text']);
                $post->sendMessage('🔘اکنون لطفا یک شماره تماس (به همراه کد کشور) وارد نمایید');
                $post->ReplyKeyboard();
                $post->ReplyButton(['text'=>'ارسال شماره', 'request_contact'=>true]);
                $post->ReplyButton('🔙بازگشت',1);
                $post->ReplyButton('🏠خانه');
                break;
            case 4:
                if(isset($post->input['message']['contact'])){
                    $user->pushact($post->input['message']['contact']['phone_number']);
                }else{
                    $user->pushact($post->input['message']['text']);
                }
                $post->sendMessage("سفارش شما با موفقیت ثبت شد.\nتا 24 ساعت اینده کارکنان ما با شما تماس خواهند گرفت");
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
