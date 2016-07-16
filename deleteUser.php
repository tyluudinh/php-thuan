<?php
    if(isset($_POST['postData']) && !empty($_POST['postData'])) {
    	session_start();
    	include("UltilFunction.php");
		if(!isset($_SESSION['login_user'])){
		      return response(-1,"Bạn chưa login");
		}
		include("db-config.php");
		$id = isset($_POST['postData']) ? $_POST['postData'] : false;
		$id = str_replace('/[^0-9]/', '', $id);
		$sql = "SELECT * FROM users WHERE id = ".$id."";
		$result = mysqli_query($db,$sql);
		if(mysqli_num_rows($result) == 1){
		    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		    if($_SESSION['login_user_level'] == 999){
		    	if($_SESSION['login_user_id'] == $id){
		    		return response(-1,"Lỗi!!!Bạn không thể xóa chính bạn");
		    	}
		    	$sqlDel = "DELETE FROM users WHERE id = ".$id."";
		    	$retval = mysqli_query($db,$sqlDel);
		        if($retval){
		        	mysqli_query($db,"DELETE FROM photos WHERE id_owen = ".$id."");
		    		mysqli_query($db,"DELETE FROM comments WHERE id_owen = ".$id."");
		          	return response(1,"Xóa users thành công");
		        }
		        return response(-100,"Lỗi Server");
		    }
		    return response(0,"Bạn không có quyền xóa users này");
		}
		return response(-2,"Không tìm thấy users");
    }
?>