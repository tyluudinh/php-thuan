<?php
    
    session_start();
    include("UltilFunction.php");
    if(!empty($_SESSION['login_user_level']) && $_SESSION['login_user_level']==999){
        // if(isset($_POST['getAll'])) {
            
        // }
        // return response(-10,"ERROR!!! Not Found Photo");
        include("db-config.php");
            $sql = "SELECT * FROM users ORDER BY ts_login DESC";
            $result = mysqli_query($db,$sql);
            if(mysqli_num_rows($result) == 0){
                return response(0,"Chưa có User nào");
            }
            $data = array();
            while($rows = mysqli_fetch_assoc($result)) {
                $rows['ts_login'] = nicetime($rows['ts_login']);
                $rows['password'] = false;
                $data[] = $rows;
            }
            return response(1,$data);
    }
    return response(-111,"Bạn không có quyền truy cập");    
?>