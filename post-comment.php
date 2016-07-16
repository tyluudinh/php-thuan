<?php
	session_start();
	include("UltilFunction.php");
    if(isset($_SESSION['login_user'])) {
    	if(empty($_POST['cmt'])){
    		return response(-3,"Bạn chưa nhập nội dung nhận xét");
    	}
		include("db-config.php");
    if (strpos($_POST['cmt'], '<script>') !== false) {
      return response(-11,"Lỗi!!! Comment của bạn không được chấp nhận");
    }
		$cmt = mysqli_real_escape_string($db,$_POST['cmt']);
      	$id_owen = $_SESSION['login_user_id'];
      	$ts_created = time();
      	$id_photo = isset($_POST['id_photo']) ? $_POST['id_photo'] : false;
	    $id_photo = str_replace('/[^0-9]/', '', $id_photo);
	    $sql = "SELECT * FROM photos WHERE id = ".$id_photo."";
	    $result = mysqli_query($db,$sql);
	    if(mysqli_num_rows($result) == 0){
        	return response(-10,"ERROR!!! Not Respone");
      	}
      	$sqlInsert = "INSERT INTO comments (id_owen,id_photo,time_created,contents) VALUES ('".$id_owen."','".$id_photo."','".$ts_created."','".$cmt."')";
      	$retval = mysqli_query($db,$sqlInsert);
      	if($retval){
      		return response(1,"Nhận xét của bạn đã được ghi lại");
      	}
      	return response(-100,"Lỗi Server");
    }
    return response(-1,"Bạn chưa login");
?>