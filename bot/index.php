<?php
  if(!isset($_GET['do']))
    exit(0);
  $BMBASE = str_replace('bot/index.php', '', realpath(__FILE__));
  define("BMPATH", $BMBASE.'bot/');
  require_once($BMBASE . 'core/config.php');
  require_once($BMBASE . 'core/functions.php');
  require_once($BMBASE . 'core/class_db.php');
  require_once(BMPATH . 'cash/manager.php');
  $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
  $db->connect();
  require_once(BMPATH . 'bot.php');
  $db->close();
?>