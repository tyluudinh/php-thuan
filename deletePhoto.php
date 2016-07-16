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
		$sql = "SELECT * FROM photos WHERE id = ".$id."";
		$result = mysqli_query($db,$sql);
		if(mysqli_num_rows($result) == 1){
		    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		    if($row['id_owen'] == $_SESSION['login_user_id']
		    	|| $_SESSION['login_user_level'] == 999){
		    	$sqlDel = "DELETE FROM photos WHERE id = ".$id."";

		    	$retval = mysqli_query($db,$sqlDel);
		        if($retval){
		        	mysqli_query($db,"DELETE FROM comments WHERE id_photo = ".$id."");
		          	return response(1,"Xóa hình ảnh thành công");
		        }
		        return response(-100,"Lỗi Server");
		    }
		    return response(0,"Bạn không có quyền xóa hình ảnh này");
		}
		return response(-2,"Không tìm thấy hình ảnh");
    }
    
?>