<?php
    function cash_save($file, $data, $id = 0){
        $file = __DIR__.'/storage/'.$file;
        if(file_exists($file)){
            if($id){
                $tmp = (array) json_decode(file_get_contents($file),true);
                $tmp[$id] = $data;
                $fh = fopen($file, 'w');
	            fwrite($fh, json_encode($tmp,true));
        	    fclose($fh);
            }else{
                $data = json_encode($data,true);
                file_put_contents($file,$data);
            }
        }else{
            $tmp[$id] = $data;
            $fh = fopen($file, 'w');
            fwrite($fh, json_encode($tmp,true));
            fclose($fh);
        }
    }
    function cash_read($file, $id = 0){
        $file = __DIR__.'/storage/'.$file;
        if(file_exists($file)){
            $tmp = (array) json_decode(file_get_contents($file),true);
            if($id){
                if(isset($tmp[$id])){
                    return $tmp[$id];
                }
                return false;
            }
            return $tmp;
        }
        return false;
    }
    function cash_update($file, $data, $id = 0){
        $file = __DIR__.'/storage/'.$file;
        if(file_exists($file)){
            $tmp = (array) json_decode(file_get_contents($file),true);
            if($tmp[$id]){
                foreach($data As $k => $v){
                    $tmp[$id][$k] = $v;
                }
                $fh = fopen($file, 'w');
	            fwrite($fh, json_encode($tmp,true));
    	        fclose($fh);
            }
        }
        return false;
    }