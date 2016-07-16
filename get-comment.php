<?php
    include("UltilFunction.php");
    if(isset($_POST['id_photo']) && !empty($_POST['id_photo'])) {
        include("db-config.php");
        $id_photo = isset($_POST['id_photo']) ? $_POST['id_photo'] : false;
        $id_photo = str_replace('/[^0-9]/', '', $id_photo);
        $sql = "SELECT * FROM comments WHERE id_photo = ".$id_photo." ORDER BY time_created DESC";
        $result = mysqli_query($db,$sql);
        if(mysqli_num_rows($result) == 0){
            return response(0,"Not Comments");
        }
        $res = array();
        session_start();
        while($rows = mysqli_fetch_assoc($result)) {
            $rows['time_created'] = nicetime($rows['time_created']);
            $rowUser = getDataFromDB($db,'users',$rows['id_owen']);
            $rows['user'] = $rowUser['user'];
            $rows['user_ava'] = $rowUser['user_ava'];
            $rows["login"]=false;
            if(isset($_SESSION['login_user'])
                && $_SESSION['login_user_id'] == $rows['id_owen']) {
                $rows["login"]=true;
            }
            $res[] = $rows;
        }
        return response(1,$res);
        
    }
    return response(-10,"ERROR!!! Not Found Photo");
    
?>