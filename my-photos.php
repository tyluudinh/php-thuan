<?php
  session_start();
  if(!isset($_SESSION['login_user'])){
      header("location:login.php");
  }
  include("db-config.php");
  header("Content-type: text/html; charset=utf-8");
  $error  = array(
    'title' => "",
    'photo' => "" ,
    'profile' => ""
  );
  $photoUrl = "";
  $notify = "";
  if (isset($_POST['postphoto']) 
               && !empty($_POST['title'])) {
    $t = time();
    $check=true;
    if(isset($_FILES['photo'])){
         $file_name = $_FILES['photo']['name'];
         $file_size = $_FILES['photo']['size'];
         $file_tmp = $_FILES['photo']['tmp_name'];
         $file_type = $_FILES['photo']['type'];
         $file_ext=strtolower(end(explode('.',$_FILES['photo']['name'])));
         $expensions= array("jpeg","jpg","png","bmp");
         
         if(in_array($file_ext,$expensions)=== false){
            $error['photo']="* Định dạng hình ảnh không hỗ trở.";
            $check=false;
         }
         
         if($file_size > 5242880) {
            $error['photo']='* Hình ảnh lớn hơn 5MB';
            $check=false;
         }
         
         $file_name = md5($t.$_SESSION['login_user'].$file_tmp).".".$file_ext;
         if($check) {
            move_uploaded_file($file_tmp,"images/post/".$file_name);
            $photoUrl = "images/post/".$file_name;
         }
         if(empty($error)){
            $notify = "updateNotification(\"Post hình ảnh thành công\");";
         }else{
           $notify = "updateNotification('".$error['photo']."',\"error\");";
         }
      }else{
        $error['photo']='Chưa có hình ảnh!';
        $check=false;
      }
      if($check){
        $title = $_POST['title'];
        $content = $_POST['content'];
        $id_owen = $_SESSION['login_user_id'];
        $user_owen = $_SESSION['login_user'];
        $ts_created = $t;
        $sqlInsert = "INSERT INTO photos (title,content, id_owen,time_created,url,user_owen) VALUES ('".$title."','".$content."','".$id_owen."','".$ts_created."','".$photoUrl."','".$user_owen."')";

        $retval = mysqli_query($db,$sqlInsert);
        if($retval){
           $notify = "updateNotification(\"Post hình ảnh thành công\");";
        }
      }
  }
  if (isset($_POST['update_ava']) 
               && !empty($_POST['username'])
               && !empty($_POST['email'])) {
   
    $username = mysqli_real_escape_string($db,$_POST['username']);
    $pass = mysqli_real_escape_string($db,$_POST['pass']);
    $email =  mysqli_real_escape_string($db,$_POST['email']);

    $sql = "SELECT * FROM users WHERE username = '".$username."'";
    $result = mysqli_query($db,$sql);
    if(mysqli_num_rows($result) == 1){
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

         // if (password_verify($pass, $row['password'])) {
         //    $_SESSION['login_user'] = $username;
         //    $_SESSION['login_user_id'] = $row['id'];
         //    $_SESSION['login_user_level'] = $row['level'];
         //    $_SESSION['login_user_ava'] = $row['profile_photo'];
         //    $_SESSION['login_user_email'] = $row['email'];

         //    header("Location: my-photos.php");
         // }else{
         //    $error = "Mật khẩu không đúng!";
         // }
      }else{
         // $error = "Sai tên đăng nhập";
        // echo "Sai";
      }
   
  }

?>


<!DOCTYPE HTML>
<html>
   <head>
       <title>Me || Photo sharing</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/grids.css" type="text/css" media="all" />
       <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="all"  />
     
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script src="js/ios-orientationchange-fix.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
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
            $("#ava-upload-edit").change(function(){
              readURLEdit(this);
            });
            $('.detail-post').on("click",function(e){
               window.location.href = "detail-photo.php?id="+$(this).attr("label");
            });
            $('.btnDelete').on("click",function(e){
              $.ajax({
                  type: "POST",
                  url: "deletePhoto.php",
                  data: {postData: $(this).attr("label")},
                  dataType:'JSON', 
                  success: function(response){

                    updateNotification(response.res);
                    window.setTimeout(function(){
                        window.location.href ="my-photos.php";
                    }, 2000);
                    
                  }
              });
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
                  <li class="active"><a data-toggle="tab" href="#list">Quản lý hình ảnh</a></li>
                  <li><a data-toggle="tab" href="#add">Thêm hình ảnh</a></li>
                  <li><a data-toggle="tab" href="#profile">Thông tin cá nhân</a></li>
                </ul>

                <div class="tab-content">
                  <div id="list" class="tab-pane fade in active">
                  <div id="content">

                    <div id="main" role="main">
                    <!-- <ul class="list-group" id="my-photo-inf">
                      <li class="list-group-item"><span class="badge">12</span> Tổng số hình ảnh: </li>
                      <li class="list-group-item"><span class="badge">5</span> Hình có lượt view cao nhất: </li> 
                      <li class="list-group-item"><span class="badge">3</span> Hình có lượt comment nhiều nhất: </li> 
                    </ul> -->
                       <ul id="tiles">
                       <?php
                          $query = mysqli_query($db,"SELECT * FROM photos WHERE id_owen=" .$_SESSION['login_user_id']. " ORDER BY view DESC");

                          if (mysqli_num_rows($query)==0) {
                              echo "<div class='alert alert-info' role='alert'>Chưa có hình ảnh nào.</div>";
                          }
                          while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
                            echo "<div>
                               <li>
                                  <p class='title-photo-homepage detail-post' label='".$row['id']."'>".$row['title']."</p>
                                  <img src='".$row['url']."' alt='".$row['title']."'  class='photo-homepage detail-post' label='".$row['id']."' />

                                  <p class='photo-view'>
                                    <span>".$row['view']." View</span>
                                        <img src='images/blog-icon2.png' title='views' alt='' />
                                     <div class='clear'></div>
                                  </p>
                                  <p class='my-options'>
                                      <a href='edit-photo.php?id=".$row['id']."'>
                                        <button type='button' class='btn btn-warning btn-sm'>Sửa</button>
                                      </a>
                                       <button type='button' class='btn btn-danger btn-sm btnDelete' label='".$row['id']."'>Xóa</button>
                                     <div class='clear'></div>
                                  </p>
                               </li>
                            </div>";
                          }


                          
                        ?>
                         
                          <!-- End of grid blocks -->
                       </ul>
                    </div>
                    <div class="modal fade" id="formConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <h4 class="modal-title" id="frm_title">Bạn có thực sự muốn xóa không?</h4>
                              </div>
                              <div class="notificationDelete">
                                  <div class="modal-body" id="frm_body">
                                      <!--<center><h4 style="color: red;margin: auto">XÓA THẤT BẠI!</h4></center>-->
                                  </div>
                              </div>

                              <div class="modal-footer">
                                  <button type="button" class="btn btn-danger col-sm-2 pull-right" data-dismiss="modal" id="frm_cancel">Không</button>
                                  <button style="margin-right:20px;" type="button" class="btn btn-primary col-sm-2 pull-right" id="frm_submit">Có</button>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- Include the imagesLoaded plug-in -->
                  <script src="js/jquery.imagesloaded.js"></script>
                  <script src="js/jquery.wookmark.js"></script>
                  <script src="js/notify.min.js"></script>
                  
                  <!-- Once the page is loaded, initalize the plug-in. -->
                  <script type="text/javascript">

                     (function ($){
                       $('#tiles').imagesLoaded(function() {
                         // Prepare layout options.
                         var options = {
                           autoResize: true, // This will auto-update the layout when the browser window is resized.
                           container: $('#main'), // Optional, used for some extra CSS styling
                           offset: 15, // Optional, the distance between grid items
                           itemWidth:310 // Optional, the width of a grid item
                         };
                     
                         // Get a reference to your grid items.
                         var handler = $('#tiles li'),
                             filters = $('#filters li');
                     
                         // Call the layout function.
                         handler.wookmark(options);
                     
                         /**
                          * When a filter is clicked, toggle it's active state and refresh.
                          */
                         var onClickFilter = function(event) {
                           var item = $(event.currentTarget),
                               activeFilters = [];
                           item.toggleClass('active');
                     
                           // Collect active filter strings
                           filters.filter('.active').each(function() {
                             activeFilters.push($(this).data('filter'));
                           });
                     
                           handler.wookmarkInstance.filter(activeFilters, 'or');
                         }
                     
                         // Capture filter click events.
                         filters.click(onClickFilter);
                       });
                     })(jQuery);
                  </script>
               </div>
                  </div>
                  <div id="add" class="tab-pane fade">
                    <!-- <h3>Thêm hình ảnh</h3> -->
                    <div class="box comment" id="post-photo" >
                     <ul class="list">
                        <li>
                           <div class="preview" style="width: 96px"><a href="#"><img src="<?php echo "images/ava-users/".$_SESSION['login_user_ava']?>" alt=""></a></div>
                           <div class="data">
                              <!-- <div class="title">Jake Sully</div> -->
                              <form method="post" role="form" action="" enctype="multipart/form-data">
                               <input name="title" type="text" class="form-control title-post" placeholder="Tiêu đề hình ảnh" required autofocus>
                                 <p>
                                    <textarea placeholder="Nội dung" name="content"></textarea>
                                 </p>
                                 <div class="form-group ">
                                 <div class="panel panel-default">
                                    <div class="panel-heading">
                                       <h3 class="panel-title">Upload photo (nhỏ hơn 5MB)</h3>
                                    </div>
                                    <div class="panel-body">
                                        <img id="ava-preview" src="images/icon-upload-photo.png" alt="your image" class="img-responsive img-rounded" />
                                    </div>
                                    <div class="panel-footer">
                                    <label class="btn btn-default btn-file">
                                        Browse <input type="file" id="ava-upload" name="photo">
                                    </label>
                                    </div>
                                 </div>
                                  <span class="error" id="error-photo">
                                    
                                  </span>                  
                              </div>
                                 <p>
                                    <input type="submit" value="Post" name="postphoto">
                                 </p>
                              </form>
                           </div>
                           <div class="clear"></div>
                        </li>
                     </ul>
                  </div>
                  </div>
                  <div id="profile" class="tab-pane fade">
                    <div class="signup-form-container">
   <!-- form start -->
                     <form class="form-horizontal" method="post" role="form" action="" enctype="multipart/form-data">
                      <div class="form-group" style="margin: 10px; ">
                          <label class="control-label col-sm-2" for="user-name">Username:</label>
                          <div class="col-sm-10">
                            <input type="user-name" name="username" class="form-control" id="user-name" placeholder="Username" readonly="readonly" value="<?php echo $_SESSION['login_user'];?>">
                          </div>
                        </div>
                       <!--  <div class="form-group" style="margin: 10px;" >
                          <label class="control-label col-sm-2" for="pwd">Password:</label>
                          <div class="col-sm-10"> 
                            <input type="password" class="form-control" id="pwd" name="pass" placeholder="Enter password">
                          </div>
                        </div> -->
                        <div class="form-group" style="margin: 10px; ">
                          <label class="control-label col-sm-2" for="email">Email:</label>
                          <div class="col-sm-10">
                            <input type="email" name="email" readonly="readonly" class="form-control" id="email" value="<?php echo $_SESSION['login_user_email'];?>" placeholder="Email">
                          </div>
                        </div>
                        
                        <div class="form-group" style="margin: 20px; "> 
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                       <h3 class="panel-title">Hình đại diện</h3>
                              </div>
                              <div class="panel-body">
                                        <img id="ava-preview-edit" src="<?php echo "images/ava-users/".$_SESSION['login_user_ava']?>" alt="your image" class="img-responsive img-rounded" />
                              </div>
                                    <div class="panel-footer">
                                    <!-- <label class="btn btn-default btn-file">
                                        Browse <input type="file" id="ava-upload-edit" style="display: none;" name="photo_ava">
                                    </label> -->
                                    </div>
                                 </div>
                                 <span class="error" id="error-photo-edit">
                                    
                                  </span>  
                        </div>
                        <div class="form-group" > 
                          <!-- <div class="col-sm-offset-2 col-sm-10">
                            <button style="margin-left: 102px;margin-bottom: 20px " type="submit" class="btn btn-warning" name="update_ava">Cập nhật</button>
                          </div> -->
                        </div>
                     </form>
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