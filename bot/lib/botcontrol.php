<?php
    class botcontrol extends Database
    {
        public $bot, $app, $isAdmin;

        function __construct($id)
        {
            global $db;
            $this->bot = $db->first('SELECT * FROM bots WHERE id = '.$id);
            if(!$this->bot){
                insertlog($id, 'robot not found!', 'botlog');
            }elseif(Debugmode){
                insertlog($id, 'Turn ', 'botlog');
            }
            $this->app();
        }
        public function updatebot($data){
            global $db;
            $db->update('bots', $data, 'id = '.$bot['id']);
        }
        public function token()
        {
            return $this->bot['token'];
        }
        public function username()
        {
            return $this->bot['username'];
        }
        public function app()
        {
            global $db;
            $this->app = cash_read('apps', $this->bot['app']);
            if(!$this->app){
                $app = $db->first('SELECT * FROM apps WHERE id = '.$this->bot['app']);
                $this->app = [
                    'id' => $app['id'],
                    'name' => $app['name'],
                    'pr' => $app['pr'],
                    'en' => $app['en'],
                    'ru' => $app['ru'],
                    'vip' => $app['vip'],
                    'cust' => $app['cust'],
                    'stat' => $app['stat']
                ];
                if(!$this->app){
                    insertlog($this->bot['id'], 'robot app not found!', 'botlog');
                }
                cash_save('apps', $this->app, $this->app['id']);
            }
            return $this->app['name'];
        }
        public function apppath()
        {
            if(Debugmode){
                insertlog($this->app['id'], 'Path load ', 'botlog');
            }
            if($this->isAdmin == 1){
                return BMPATH.'bots/'.$this->app['name'].'/admin.php';
            }
            return BMPATH.'bots/'.$this->app['name'].'/app.php';
        }
        public function ln()
        {
            return BMPATH.'bots/'.$this->app['name'].'/'.$this->bot['lang'].'.php';
        }
        public function expire()
        {
            return $this->bot['expire'] > time();
        }
    }
