<?php
   include("db-config.php");
   $error = array(
      "username" => "",
      "pass" => "",
      "email" => "",
      "photo" => ""

   );
   $notify = "";
   include("UltilFunction.php");
   if (isset($_POST['register']) 
               && !empty($_POST['username']) 
               && !empty($_POST['password'])
               && !empty($_POST['email'])
               && !empty($_POST['cpassword'])) {
      $check=true;
      $username = $_POST['username'];
      $pass = $_POST['password'];
      $email = $_POST['email']; 
      $photoAvatar = ""  ;
      
      if(!notSpecial($username)){
        $error['username'] = "Username phải là tiếng việt không dấu và không chứa dấu cách";
        $check=false;
      }
   
      if(strlen($pass)>=6){
         if($pass != $_POST['cpassword']){
            $error['pass'] = "Mật khẩu không khớp";
            $check=false;
         }
      }else{
         $error['pass'] = "Mật khẩu phải tối thiệu 6 kí tự";
         $check=false;
      }
      if(isset($_FILES['avatar'])){
         $file_name = $_FILES['avatar']['name'];
         $file_size = $_FILES['avatar']['size'];
         $file_tmp = $_FILES['avatar']['tmp_name'];
         $file_type = $_FILES['avatar']['type'];
         $file_ext=strtolower(end(explode('.',$_FILES['avatar']['name'])));
         
         $expensions= array("jpeg","jpg","png","bmp");
         if(in_array($file_ext,$expensions)=== false){
            $error['photo'] ="* Định dạng hình ảnh không hỗ trở.";
            $check=false;
         }
         if($file_size > 2097152) {
            $error['photo'] ='* Hình ảnh quá lớn';
            $check=false;
         }
         $file_name = md5(time().$file_tmp).".".$file_ext;

         if($check) {
            move_uploaded_file($file_tmp,"images/ava-users/".$file_name);
            $photoAvatar = $file_name;
         }
      }

      if($check){
         $username = mysqli_real_escape_string($db,$_POST['username']);
         $sql = "SELECT * FROM users WHERE username = '".$username."'";
         $result = mysqli_query($db,$sql);
         if(mysqli_num_rows($result) == 0){
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $sqlInsert = "INSERT INTO users (username,email, password,profile_photo) VALUES ('".$username."','".$email."','".$pass."','".$photoAvatar."')";

            $retval = mysqli_query($db,$sqlInsert);
            if($retval){
                $notify = "updateNotification(\"Đăng kí thành công, bạn đang được đưa tới trang login\");";
            }
         }else{
            $error['username'] = "User <strong>".$username."</strong> đã tồn tại";
            $check=false;
         } 
         
      }
      
      
      
    }
    
?>
<!DOCTYPE HTML>
<html>
   <head>
       <title>Đăng kí thành viên || Photo sharing</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <!-- css -->
      <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/grids.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="all"  />
     
      <!-- javascript -->      
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script src="js/ios-orientationchange-fix.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script src="js/notify.min.js"></script>
      <script type="text/javascript">
       $(document).ready(function(){
         
          $("#ava-upload").change(function(){
              readURL(this);
          });
          <?php
              echo $notify;
         ?>
            
             
         });
         function updateNotification(data,type){
              type = typeof type !== 'undefined' ? type : 'success';
              $.notify( data,{
                  position:"top center",
                  autoHide:true,
                  autoHideDelay:2000,
                  className:type
              });
              window.setTimeout(function(){

               window.location.replace("login.php");
             }, 5000);
              
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
               <div class="data-in-here">
                   <div class="signup-form-container">
   <!-- form start -->
                     <form method="post" role="form" action="" autocomplete="on" enctype="multipart/form-data">
                        <div class="form-header">
                           <h3 class="form-title"><i class="fa fa-user"></i>Đăng kí thành viên</h3>
                           
                        </div>
                        <div class="form-body">
                           <div class="form-group">
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                                 <input name="username" type="text" class="form-control" placeholder="Tài khoản" required autofocus>
                              </div>
                              <span class="error" id="error-username">
                                 <?php
                                    echo $error['username'];
                                 ?>
                              </span>
                           </div>
                           <div class="form-group">
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                                 <input name="email" type="text" class="form-control" placeholder="Email" required autofocus>
                              </div>
                              <span class="error" id="error-email">
                                  <?php
                                    echo $error['email'];
                                 ?>
                              </span>                     
                           </div>
                           <div class="row">
                              <div class="form-group col-lg-6">
                                 <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                                    <input name="password" id="password" type="password" class="form-control" placeholder="Mật khấu" value="" required autofocus>
                                 </div>
                                 <span class="error" id="error-pass">
                                    <?php
                                       echo $error['pass'];
                                    ?>
                                 </span>                    
                              </div>
                              <div class="form-group col-lg-6">
                                 <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                                    <input name="cpassword" type="password" class="form-control" placeholder="Nhập lại mật khẩu" value="" required autofocus>
                                 </div>           
                              </div>
                              <div class="form-group form-upload-ava">
                                 <div class="panel panel-default">
                                    <div class="panel-heading">
                                       <h3 class="panel-title">Upload hình đại diện (nhỏ hơn 2MB)</h3>
                                    </div>
                                    <div class="panel-body">
                                        <img id="ava-preview" src="images/default_avatar_male.png" alt="your image" class="img-responsive img-rounded" />
                                    </div>
                                    <div class="panel-footer">
                                    <label class="btn btn-default btn-file">
                                        Browse <input type="file" id="ava-upload" name="avatar">
                                    </label>
                                    </div>
                                 </div>
                                  <span class="error" id="error-photo">
                                    <?php
                                       echo $error['photo'];
                                    ?>
                                  </span>                  
                              </div>
                              

                           </div>
                        </div>
                        <div class="form-footer">
                           <button type="submit" class="btn btn-info" name="register">
                              <span class="glyphicon glyphicon-log-in"></span>  Đăng kí
                           </button>
                        </div>
                     </form>
                  </div> 
                 
               </div>
            </div>
            <div class="clear"></div>
         </div>
      </div>
   </body>
</html>