<?php
  class Database
  {
      private $server,
        $user,
        $database,
        $pass,
        $error = "",
        $errno = 0;
      public $affected_rows = 0,
        $query_counter = 0,
        $link_id = 0,
        $query_id = 0;
      
      function __construct($server, $user, $pass, $database)
      {
          $this->server = $server;
          $this->user = $user;
          $this->pass = $pass;
          $this->database = $database;
      }
      
      function connect()
      {
          $this->link_id = $this->connect_db($this->server, $this->user, $this->pass);
          
          if (!$this->link_id)
              $this->error('Database Error: Connection to Database ' . $this->database . ' Failed');

          if (!$this->select_db($this->database, $this->link_id))
              $this->error('Database Error: mysqli database (' . $this->database . ')cannot be used');
          
          $this->query("SET NAMES 'utf8mb4'", $this->link_id);
          $this->query("SET CHARACTER SET 'utf8mb4'", $this->link_id);

          unset($this->pass);
      }

      function connect_db($server, $user, $pass)
      {
          return mysqli_connect($server, $user, $pass);

      }
	  
      function select_db($database, $link_id)
      {
          return mysqli_select_db($link_id, $database);
      }
	  
      function query($sql)
      {
          if (trim($sql != "")) {
              $this->query_counter++;
              $this->query_id = mysqli_query($this->link_id, $sql);
          }
          
          if (!$this->query_id)
              $this->error('mysqli Error on Query : ' . $sql);
          
          return $this->query_id;
		  
      }
      
      function first($string)
      {
          $query_id = $this->query($string);
          $record = $this->fetch($query_id);
          $this->free($query_id);
          return $record;
      }
      
      function fetch($query_id = fulse)
      {
          if ($query_id)
              $this->query_id = $query_id;
          
          if (isset($this->query_id)) {
              $record = mysqli_fetch_array($this->query_id);
          } else
              $this->error('Invalid query_id: '.$this->query_id.'. Records could not be fetched.');
          
          return $record;
      }
      
      function fetch_all($sql)
      {
          $query_id = $this->query($sql);
          $record = array();

          while ($row = $this->fetch($query_id, $sql)) :
              $record[] = $row;
          endwhile;

          $this->free($query_id);
          return $record;
      }
      
      function free($query_id = fulse)
      {
          if ($query_id)
              $this->query_id = $query_id;
          
          return mysqli_free_result($this->query_id);
      }
	  
      function insert($table = null, $data)
      {
		  if ($table === null or empty($data) or !is_array($data)) {
		  $this->error('Invalid array for table: '.$table);
		  return false;
		  }
          
		  $q = "INSERT INTO `" . $table . "` ";
          $v = '';
          $k = '';
          
          foreach ($data as $key => $val) :
              $k .= "`$key`, ";
              if (strtolower($val) == 'null')
                  $v .= "NULL, ";
              elseif (strtolower($val) == 'now()')
                  $v .= "NOW(), ";
              else
                  $v .= "'" . $this->escape($val,true) . "', ";
          endforeach;

          $q .= "(" . rtrim($k, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";
          
          if ($this->query($q)) {
              return $this->insertid();
          } else
              return false;
      }
	  
      function update($table = null, $data, $where = '1')
      {
		  if ($table === null or empty($data) or !is_array($data)) {
		  $this->error('Invalid array for table: '.$table);
		  return false;
		  }
		  
		  $q = "UPDATE `" . $table . "` SET";
          foreach ($data as $key => $val) :
              if (strtolower($val) == 'null')
                  $q .= "`$key` = NULL, ";
              elseif (strtolower($val) == 'now()')
                  $q .= "`$key` = NOW(), ";
              elseif (strtolower($val) == 'default()')
                  $q .= "`$key` = DEFAULT($val), ";
              elseif(preg_match("/^inc\((\-?\d+)\)$/i",$val,$m))
                  $q.= "`$key` = `$key` + $m[1], ";
              else
                  $q .= "`$key`='" . $this->escape($val,true) . "', ";
          endforeach;
          $q = rtrim($q, ', ') . ' WHERE ' . $where . ';';
          
          return $this->query($q);
      }
      
      function delete($table, $where = '')
      {
          
		  $q = !$where ? 'DELETE FROM ' . $table : 'DELETE FROM ' . $table . ' WHERE ' . $where;
          return $this->query($q);
      }

      function countEntries($table, $where = '') {
        if (!empty($where)) {
            $q = "SELECT COUNT(*) FROM " . $table . "  WHERE " . $where . " LIMIT 1";
        }
        else
            $q = "SELECT COUNT(*) FROM " . $table . " LIMIT 1";
        $record = $this->query($q);
        $total = $this->fetchrow($record);
        return $total[0];
      }
      function sumEntries($table, $where = '', $sum) {
        $where = ($where) ? "  WHERE " . $where : "";
        if (isset($sum) && $sum) {
            $q = $this->query("SELECT SUM($sum) FROM " . "$table $where");
        $total = $this->fetchrow($q);
        return $total[0];
        }
        else
          return 0;
      }
      function getValue($what, $table, $where) {
        $row = $this->first("SELECT $what FROM " . "$table WHERE $where");
        return $row[$what];
      }
      
      function insertid()
      {   
          return mysqli_insert_id($this->link_id);
      }

	  function affected() {
		  return mysqli_affected_rows($this->link_id);
      }
      
      function fetchrow($query_id = fulse)
      {
          if ($query_id)
              $this->query_id = $query_id;
          
          $this->fetch_row = mysqli_fetch_row($this->query_id);
          return $this->fetch_row;
      }
	  
      function close()
      {
          if (!mysqli_close($this->link_id))
              $this->error('Connection close failed.');
      }
	  
      function escape($string, $do = false)
      {
          if (is_array($string)) {
			  foreach ($string as $key => $value) :
				  $string[$key] = $this->escape_($value, $do);
			  endforeach;
		  } else
			  $string = $this->escape_($string, $do);
		  
		  return $string;
      }
	  
	  function escape_($string, $do = false)
	  {
		  if (isset($string)) {
			  if (get_magic_quotes_gpc())
				  $string = stripslashes($string);
			 
			 if ($do) 
				  $string = mysqli_real_escape_string($this->link_id, $string);
		  }
		  return $string;
      }
      
      function error($msg = '')
      {
          global $WOJODebug, $_SERVER;
          if ($this->link_id) {
              $this->error_desc = mysqli_error($this->link_id);
              $this->error_no = mysqli_errno($this->link_id);
          } else {
              $this->error_desc = mysqli_error();
              $this->error_no = mysqli_errno();
          }
          if ($WOJODebug){
	        $text = "\nDB: ".date(' y/m/d H:i ', time()).'mysqli Error : ' . $this->error_no .
                 "\n mysqli Error no #: ". $this->error_desc."\n$msg";
	        $myFile = BMPATH.'dblogs.txt';
	        $fh = fopen($myFile, 'a');
	        fwrite($fh, $text);
	        fclose($fh);
          }
          die();
      }
  }
?>