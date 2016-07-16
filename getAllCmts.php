<?php
    
    session_start();
    include("UltilFunction.php");
    if(!empty($_SESSION['login_user_level']) && $_SESSION['login_user_level']==999){
        include("db-config.php");
            $sql = "SELECT * FROM comments ORDER BY time_created DESC";
            $result = mysqli_query($db,$sql);
            if(mysqli_num_rows($result) == 0){
                return response(0,"Chưa có nhận xét nào");
            }
            $data = array();
            while($rows = mysqli_fetch_assoc($result)) {
                $rows['time_created'] = nicetime($rows['time_created']);
                $rowUser = getDataFromDB($db,'users',$rows['id_owen']);
                $rowPhoto = getDataFromDBPhoto($db,$rows['id_photo']);
                $rows['title'] = $rowPhoto['title'];
                $rows['url_photo'] = $rowPhoto['url'];
                $rows['user'] = $rowUser['user'];
                $data[] = $rows;
            }
            return response(1,$data);
    }
    return response(-111,"Bạn không có quyền truy cập");    
?>