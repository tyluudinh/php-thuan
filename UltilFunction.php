<?php
	function checkEmptyInput($name, $displayName){
		$result="";
		if(!empty($_POST[$name])){
         	$result = "* ".$displayName."không được bỏ trống";
      	}
      	return $result;
	}
	function response($code,$res){
		echo json_encode(array(
		      	"code" => $code,
		      	"res"=>$res));
	}
	function nicetime($ts){
      $time=time()-$ts;
      $result="";
      if($time>=86400*2){
         $result = date('d',$ts)." ".getMonth(date('m',$ts))." lúc ".date('H-i',$ts);
      }else{
         if($time>=86400){
            $result = "Hôm qua lúc ".date('H',$ts)."h".date('i',$ts);
         }else{
            if($time>=3600){
               $result = (int) ($time/3600)." giờ trước";
            }else{
               if($time>=60){
                  $result = (int) ($time/60)." phút trước";
               }else{
                  $result = $time." giây trước";
               }
            }
         }
      }
      return $result;
   }
   function notSpecial($string) {
      //Them chuoi nay de co the viet tieng viet co dau áàạảãăắằặẳẵâấầậẩẫóòọỏõôốồộổỗơớờợởỡéèẹẻẽêếềệểễúùụủũưứừựửữíìịỉĩýỳỵỷỹđĐƯỮÚỤỦÈẸẼÀĂẶÂÒỌỎỜỞÔỒỘỎỖÊẾỀỆỂỀÌỊỶ ;
       if(preg_match('/^[a-z0-9\-_]+$/i', $string)){
         return true;
      }
      return false;
   }
   function getMonth($value) {
      $result = "Tháng ";
      switch ($value) {
         case '01':
            $result = "Tháng 1";
            break;
         case '02':
            $result = "Tháng 2";
            break;
         case '03':
            $result = "Tháng 3";
            break;
         case '04':
            $result = "Tháng 4";
            break;
         case '05':
            $result = "Tháng 5";
            break;
         case '06':
            $result = "Tháng 6";
            break;

         case '07':
            $result = "Tháng 7";
            break;
         case '08':
            $result = "Tháng 8";
            break;
         case '09':
            $result = "Tháng 9";
            break;
         case '10':
            $result = "Tháng 10";
            break;

         case '11':
            $result = "Tháng 11";
            break;
         case '12':
            $result = "Tháng 12";
            break;         
         default:
            break;
      }
      return $result;
   }
   function getDataFromDB($db,$table,$id){
      $sql = "SELECT * FROM ".$table." WHERE id = ".$id."";
      $result = mysqli_query($db,$sql);
      $res = array();
      if(mysqli_num_rows($result) == 1){
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
         $res['user'] = $row['username'];
         $res['user_ava'] = $row['profile_photo'];
         return $res;
      } 
   }
   function getDataFromDBPhoto($db,$id){
      $sql = "SELECT * FROM photos WHERE id = ".$id."";
      $result = mysqli_query($db,$sql);
      $res = array();
      if(mysqli_num_rows($result) == 1){
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
         $res['title'] = $row['title'];
         $res['url'] = $row['url'];
         return $res;
      } 
   }
   function updateView($db,$id){
      $sql = "SELECT * FROM photos WHERE id = ".$id."";
      $result = mysqli_query($db,$sql);
      if(mysqli_num_rows($result) == 1){
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
         $view = (int) ($row['view']+1);
         $sqlUpdate = "UPDATE photos SET view = ".$view." WHERE id= ".$id."";
         $retval = mysqli_query($db,$sqlUpdate);
         // if($retval){
         //    return true;
         // }
      }
      // return false; 
      
   }

?>
