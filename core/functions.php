<?php
    date_default_timezone_set('Asia/Tehran');
function dbselect($table, $where = '', $select = '*', $order = '') {
  ($where) ? $where = ' WHERE ' . $where : "";
  if (isset($table) && $table) {
    $q = "SELECT " . $select . " FROM " .$table . $where . $order;
    return $q;
  }
}
function sanitize($string, $trim = false) {
  $string = filter_var($string, FILTER_SANITIZE_STRING);
  $string = trim($string);
  $string = stripslashes($string);
  $string = strip_tags($string);
  $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);
  if ($trim)
    $string = substr($string, 0, $trim);
  return $string;
}
function is_url_exist($url){
    $ch = curl_init($url);    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200){
       $status = true;
    }else{
      $status = false;
    }
    curl_close($ch);
   return $status;
}
function insertlog($msg, $from, $where = 'logs'){
	$text = "\n".$from.date(' y/m/d H:i ', time()).$msg;
	$myFile = BMPATH.$where.'.txt';
	$fh = fopen($myFile, 'a+');
	fwrite($fh, $text);
	fclose($fh);
}
function customError($errno, $errstr, $line, $lnum) {
	insertlog($errstr.' In '.$line.' Line '.$lnum.' #REQUEST_URI: '.$_SERVER['REQUEST_URI'], "ERROR[$errno]");
	$error = $errno;
}
set_error_handler("customError");
function display_msg() {
  global $msgOk, $msgError, $msgAlert, $msgInfo, $msgSys, $showMsg;
  if (!empty($msgOk)) {
    insertlog($msgOK, 'DB ok: ');
  }else
  if (!empty($msgError)) {
    insertlog($msgError, 'DB error: ');
  }else
  if (!empty($msgAlert)) {
    insertlog($msgAlert, 'DB alert: ');
  }else
  if (!empty($msgInfo)) {
    insertlog($msgInfo, 'DB Info: ');
  }else{
      insertlog($showMsg, 'DB : ');
  }
}
?>