<?php
   include("db-config.php");
   $error =  "";
   $displayErr = "$(\"#err\").css(\"display\",\"none\")";
   session_start();
   if(isset($_SESSION['login_user'])!=""){
    header("Location: my-photos.php");
   }
   if (isset($_POST['login']) 
               && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
      $username = mysqli_real_escape_string($db,$_POST['username']);
      $pass = mysqli_real_escape_string($db,$_POST['password']);
      $sql = "SELECT * FROM users WHERE username = '".$username."'";
      $result = mysqli_query($db,$sql);
      if(mysqli_num_rows($result) == 1){
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
         if (password_verify($pass, $row['password'])) {
            $_SESSION['login_user'] = $username;
            $_SESSION['login_user_id'] = $row['id'];
            $_SESSION['login_user_level'] = $row['level'];
            $_SESSION['login_user_ava'] = $row['profile_photo'];
            $_SESSION['login_user_email'] = $row['email'];

            $sqlUpdate = "UPDATE users SET ts_login=".time()." WHERE username = '".$username."'";
            mysqli_query($db,$sqlUpdate);

            header("Location: my-photos.php");
         }else{
            $error = "Mật khẩu không đúng!";
         }
      }else{
         $error = "Sai tên đăng nhập";
      }
      if(!empty($error)){
         $displayErr = "$(\"#err\").css(\"display\",\"block\")";
      }
    }
    
?>
<!DOCTYPE HTML>
<html>
   <head>
      <title>Đăng nhập thành viên || Photo sharing</title>
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
      <script type="text/javascript">
      $(document).ready(function(){
         $("#login_register_btn").click()   
         <?php
            echo $displayErr;
         ?>
             
      });
         
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
                     <form method="post" role="form" action="" autocomplete="on">
                        <div class="form-header">
                           <h3 class="form-title"><i class="fa fa-user"></i>Đăng nhập thành viên</h3>
                           
                        </div>
                        <div class="form-body">
                           <div class="form-group">
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                                 <input name="username" type="text" class="form-control" placeholder="Tài khoản" required autofocus>
                              </div>
                              
                           </div>
                           <div class="form-group">
                              <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                                    <input name="password" id="password" type="password" class="form-control" placeholder="Mật khấu" value="" required autofocus>
                                 </div>           
                           </div>
                        </div>
                        
                        <div class="alert alert-danger alert-dismissible" id="err" role="alert" style="margin: 20px">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>
                           <?php
                              echo $error;
                           ?>
                          </strong> 
                        </div>
                       
                        <div class="form-footer">
                           <div>
                                <button type="submit" name="login" class="btn btn-primary btn-lg btn-block">Đăng nhập</button>
                           </div>
                           <div>
                             <!--  <button id="login_lost_btn" type="button" class="btn btn-link">Quên mật khẩu?</button> -->
                              <a id="login_register_btn" href="register.php" type="button" class="btn btn-link">Đăng kí thành viên</a>
                           </div>
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