<?php
  session_start();
  if(!isset($_SESSION['login_user'])){
      header("location:404");
  }
  $id = isset($_GET['id']) ? $_GET['id'] : false;
  $id = str_replace('/[^0-9]/', '', $id);
  include("db-config.php");
  header("Content-type: text/html; charset=utf-8");
  $error  = array(
    'title' => "",
    'photo' => "" ,
    'profile' => ""
  );
 
  $notify = "";
  $sql = "SELECT * FROM photos WHERE id = ".$id."";
  $result = mysqli_query($db,$sql);
  $title = "";
  $content = "";
  $id_owen = "";
  $photoUrl = "";
  $permission = -1;
  $check=1;
  if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      if($row['id_owen'] == $_SESSION['login_user_id']
            || $_SESSION['login_user_level'] == 999){
        $title = $row['title'];
        $content = $row['content'];
        $photoUrl = $row['url'];
        $id_owen = $_SESSION['login_user_id'];
        $user_owen = $_SESSION['login_user'];
        $permission=1;
      }else{
         $permission=0;
      }
  }else{
      header("location:404.php");
  }
  if (isset($_POST['postphoto']) 
               && !empty($_POST['title'])) {   
    if(isset($_FILES['photo'])){
         $file_name = $_FILES['photo']['name'];
         $file_size = $_FILES['photo']['size'];
         $file_tmp = $_FILES['photo']['tmp_name'];
         $file_type = $_FILES['photo']['type'];
         $file_ext=strtolower(end(explode('.',$_FILES['photo']['name'])));
         $expensions= array("jpeg","jpg","png","bmp");
         
         if(in_array($file_ext,$expensions)=== false){
            $error['photo']="* Định dạng hình ảnh không hỗ trở.";
            $check=0;
         }
         
         if($file_size > 5242880) {
            $error['photo']='* Hình ảnh lớn hơn 5MB';
            $check=0;
         }
         
         $file_name = md5(time().$_SESSION['login_user'].$file_tmp).".".$file_ext;
         if($check) {
            move_uploaded_file($file_tmp,"images/post/".$file_name);
            $photoUrl = "images/post/".$file_name;
         }
         if(empty($error)){
            $notify = "updateNotification(\"Update hình ảnh thành công\");";
         }else{
           $notify = "updateNotification('".$error['photo']."',\"error\");";
           $check=0;
         }
      }
      $title = $_POST['title'];
        $content = $_POST['content'];
       
        $sqlUpdate = "UPDATE photos SET title='".$title."',content = '".$content."',url='".$photoUrl."' WHERE id= ".$id."";
        echo $sqlUpdate;
        $retval = mysqli_query($db,$sqlUpdate);
        if($retval){
           $notify = "updateNotification(\"Update hình ảnh thành công\");";
        }else{
          $notify = "updateNotification(\"Lỗi\",\"error\");";
        }
  }

?>
<!DOCTYPE HTML>
<html>
   <head>
      <title>Sửa bài viết || Photo sharing</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/grids.css" type="text/css" media="all" />
       <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="all"  />
     
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script src="js/ios-orientationchange-fix.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script src="js/notify.min.js"></script>
      <script type="text/javascript">
         $(document).ready(function(){
            <?php
            if(!empty($notify)){
               echo $notify;
            }
            ?>
            $("#ava-upload").change(function(){
              readURL(this);
            });
             
         });
         function updateNotification(data,type){
              type = typeof type !== 'undefined' ? type : 'success';
              $.notify( data,{
                  position:"top center",
                  autoHide:true,
                  autoHideDelay:2000,
                  className:type
              });
          }
         <?php
          include("include/script.php");
         ?>
      </script>
   </head>
   <body>
      <div class="main">
         <div class="wrap">
            <?php
              include("include/menu-left.php");
            ?>
            <div class="right-content">
              <?php
                include("include/menu-top.php");
              ?>
              <div class="containers">
                <h2>Xin chào <strong>
                  <?php
                    echo $_SESSION['login_user'];
                  ?>
                </strong></h2>
                <ul class="nav nav-tabs">

                  <li class="active"><a data-toggle="tab" href="#edit">Sửa hình ảnh</a></li>
                </ul>

                <div class="tab-content">
                  <div id="edit" class="tab-pane fade in active">
                    <div class="box comment" id="post-photo" >
                    <?php 
                     if($permission==0){
                       echo "<div class='alert alert-danger' role='alert'>Bạn không có quyền với trang này</div>";
                     }else{
                        echo "<ul class='list'>
                              <li>
                                 <div class='preview' style='width: 96px'><a href='#'><img src='images/ava-users/".$_SESSION['login_user_ava']."' alt=''></a></div>
                                    <div class='data'>
                                       <form method='post' role='form' action='' enctype='multipart/form-data'>
                                        <input name='title' type='text' class='form-control title-post' placeholder='Tiêu đề hình ảnh' value='".$title."' required autofocus>
                                          <p>
                                             <textarea placeholder='Nội dung'name='content'>".$content."</textarea>
                                          </p>
                                          <div class='form-group '>
                                          <div class='panel panel-default'>
                                             <div class='panel-heading'>
                                                <h3 class='panel-title'>Upload photo (nhỏ hơn 5MB)</h3>
                                             </div>
                                             <div class='panel-body'>
                                                 <img id='ava-preview' src='".$photoUrl."' title='".$title."' class='img-responsive img-rounded' />
                                             </div>
                                             <div class='panel-footer'>
                                             <label class='btn btn-default btn-file'>
                                                 Browse <input type='file' id='ava-upload' name='photo'>
                                             </label>
                                             </div>
                                          </div>
                                           <span class='error' id='error-photo'>
                                             
                                           </span>                  
                                       </div>
                                          <p>
                                             <input type='submit' value='Cập nhật' name='postphoto'>
                                          </p>
                                       </form>
                                    </div>
                                    <div class='clear'></div>
                                 </li>
                              </ul>";
                        }
                     ?>
                     
                  </div>
                  </div>
                </div>
              </div>
               
            </div>
            <div class="clear"></div>
         </div>
      </div>
   </body>
</html>