<?php
    
    session_start();
    include("UltilFunction.php");
    if(!empty($_SESSION['login_user_level']) && $_SESSION['login_user_level']==999){
        // if(isset($_POST['getAll'])) {
            
        // }
        // return response(-10,"ERROR!!! Not Found Photo");
        include("db-config.php");
            $sql = "SELECT * FROM photos ORDER BY time_created DESC";
            $result = mysqli_query($db,$sql);
            if(mysqli_num_rows($result) == 0){
                return response(0,"Chưa có hình ảnh nào");
            }
            $data = array();
            while($rows = mysqli_fetch_assoc($result)) {
                $rows['time_created'] = nicetime($rows['time_created']);
                $rowUser = getDataFromDB($db,'users',$rows['id_owen']);
                $rows['user'] = $rowUser['user'];
                $data[] = $rows;
            }
            return response(1,$data);
    }
    return response(-111,"Bạn không có quyền truy cập");    
?>