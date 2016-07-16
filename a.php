<?php

	$id = $_POST['id'];
	header('Content-type: text/plain; charset=utf-8');

	
	
	if(notSpecial($id)){
		echo "Yes";
	}else{
		echo "NO";
	}

	// test($id);
	function notSpecial( $string ) {
	    if(preg_match('/^[a-z0-9\-_]+$/i', $string)){
         return true;
      }
      return false;
	}
	// echo is_clean($id);
	function is_clean($string){
	    $pattern = "/([a-z]|[A-Z]|[0-9]|-|_)*/";
	    preg_match($pattern, $string, $return);
	    $pass = (($string == $return[0]) ? TRUE : FALSE);
	    return $pass;
	}
?>