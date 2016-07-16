<?php
   session_start(); 
   $id = isset($_GET['id']) ? $_GET['id'] : false;
   $id = str_replace('/[^0-9]/', '', $id);
   include("db-config.php");
   include("UltilFunction.php");
   $query = mysqli_query($db,"SELECT * FROM photos WHERE id = ".$id."");    
   if (mysqli_num_rows($query)==0) {
      header("location:404");
   }else{
      updateView($db,$id);
      $query = mysqli_query($db,"SELECT * FROM photos WHERE id = ".$id."");
      $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
      
   }

?>
<!DOCTYPE HTML>
<html>
   <head>
      <title><?php echo $row['title']; ?></title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/grids.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/jquery-ui.css" type="text/css" media="all" />
      

      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/jquery-ui.js"></script>
      
      <script src="js/notify.min.js"></script>
      <script type="text/javascript">
         $(document).ready(function(){
            
            getAllComment();
            editCmt();
            
            $('#post-cmt').on("click",function(e){
               var cmt = $('#cmt').val();
               var id_photo = $(this).attr("label");
               if(cmt==""){
                  updateNotification("Bạn chưa nhập nội dung nhận xét","error");
                  return;
               }
               $.ajax({
                  type: "POST",
                  url: "post-comment.php",
                  data: {cmt: cmt,id_photo:id_photo},
                  dataType:'JSON', 
                  success: function(response){
                     $('#cmt').val('');
                     getAllComment();
                     var type="success";
                     if(response.code<0){
                        type="error";
                     }
                     updateNotification(response.res,type);

                  }
              });
            });
             
         });
         function editCmt(){
            $('.edit-cmt').on("click",function(e){
               alert("dbsadbsad");

            });

            // $( "#dialog-message" ).dialog({
            //    modal: true,
            //    buttons: {
            //      Sửa: function() {
            //        $( this ).dialog( "close" );
            //      },
            //      Hủy: function() {
            //        $( this ).dialog( "close" );
            //      }
            // }
            // });

         }
         function getAllComment(){
            <?php 
               echo "var id_photo = ".$id.";\r\n";
            ?>
            $.ajax({
                  type: "POST",
                  url: "get-comment.php",
                  data: {id_photo:id_photo},
                  dataType:'JSON', 
                  success: function(response){
                     
                     var list = "";
                     if(response.code==1){

                        $('#count-cmt').html(response.res.length+" nhận xét");
                        $('.comments').html(response.res.length+" nhận xét");
                        for(var i=0;i<response.res.length;i++){
                        console.log(response.res[i]);
                        var option="";
                        if(response.res[i].login){
                           // option = '<input class="edit-cmt" style="background: -webkit-linear-gradient(top, #df9924, #df9924);text-transform: none;" type="submit" value="Sửa" textContent="' + response.res[i].contents+'" name="' + response.res[i].id+'">'+
                           //             '<input class="del-cmt" style="background: -webkit-linear-gradient(top, red, red);text-transform: none;margin-left:20px" type="submit" textContent="' + response.res[i].contents+'" label="' + response.res[i].id+'" value="Xóa">';
                           // editCmt();
                         
                           }
                           list += '<li>'+
                                    '<div class="preview">'+
                                       '<a href="#"><img src="images/ava-users/'+response.res[i].user_ava+'" alt="">'+
                                       '</a>'+
                                    '</div>'+
                                     '<div class="data">'+
                                         '<div class="title">'+response.res[i].user + ' <a href="#"> ' + response.res[i].time_created+'</a>'+
                                         '</div>'+
                                         '<p>'+response.res[i].contents+'</p>'+
                                         option+
                                     '</div>'+
                                     '<div class="clear"></div>'+
                                 '</li>';
                           }
                           
                     }else{
                        list="Hãy là người bình luận đầu tiên";
                        $('#count-cmt').html("chưa có nhận xét nào");
                        $('.comments').html("chưa có nhận xét nào");
                     }
                     $('#list-cmt').html(list);
                  }
              });

         }
         function updateNotification(data,type){
              type = typeof type !== 'undefined' ? type : 'success';
              $.notify( data,{
                  position:"top center",
                  autoHide:true,
                  autoHideDelay:2000,
                  className:type
              });
          }
         
         
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
               <div class="content" >
                  <div class="box1">
                     <h3><?php echo $row['title']; ?></h3>
                     <span><?php echo "Đăng bởi ".$row['user_owen']." - ".nicetime($row['time_created']); ?><span class="comments"></span><strong style="color: #1514d9">
                        <?php echo " - ".$row['view']." view" ?>
                     </strong></span> 
                     <div class="blog-img">
                        <?php 
                           echo "<img src='".$row['url']."'";
                        ?>
                     </div>
                     <div class="blog-data">
                        <p><?php echo $row['content'] ?></p>
                     </div>
                     <div class="clear"></div>
                  </div>
                  <div class="box comment">
                     <ul class="list">
                     <?php 
                         if(isset($_SESSION['login_user'])){
                           echo "<li>
                                 <div class='preview'>
                                    <img src='images/ava-users/".$_SESSION['login_user_ava']."' alt=''>

                                 </div>
                                 <div class='data'>
                                  
                                       <p>
                                          <textarea name='cmt' id='cmt' placeholder='Đăng nhận xét'></textarea>
                                       </p>
                                       <p>
                                          <input id='post-cmt' type='submit' name='comment' label='".$id."' value='Post'>
                                       </p>
                                   
                                 </div>
                                 <div class='clear'></div>
                              </li>";
                         }
                         else{
                           echo "<a href='login.php' class='not-login'>Đăng nhập để đăng nhận xét bạn nhé!</a>";
                         }
                     ?>
                      
                        
                     </ul>
                  </div>
                  <div class="box comment">
                     <h2><span id='count-cmt'></span> </h2>
                     <ul class="list" id='list-cmt'>
                        
                        <li>
                           <div class="preview"><a href="#"><img src="http://lorempixel.com/50/50" alt=""></a></div>
                           <div class="data">
                              <div class="title">admin <a href="#"> 6 giờ trước</a></div>
                              <p>adssdasad</p>
                              <input class="edit-cmt" style="background: -webkit-linear-gradient(top, #df9924, #df9924);text-transform: none;" type="submit" value="Sửa" contextmenu="">
                              <input class="del-cmt" style="background: -webkit-linear-gradient(top, red, red);text-transform: none;" type="submit" value="Xóa">
                           </div>
                           <div class="clear"></div>
                        </li>

                        <li>
                           <div class="preview"><a href="#"><img src="images/ava-users/c484e1f32ba278f058a8dcda2f1dee59.jpg" alt=""></a></div>
                           <div class="data">
                              <div class="title">admin <a href="#"> 6 giờ trước</a></div>
                              <p>adssdasad</p>
                           </div>
                           <div class="clear"></div>
                        </li>
                     </ul>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"> </div>
               </div>
            </div>
            <div class="clear"></div>
         </div>
      </div>

      <div id="dialog-message" title="Sửa nhận xét">
         <p>
            <textarea id='cmt-edit' placeholder='' style="width: 100%;height: 120px;display: none;"></textarea>
         </p>
      </div>
      <div id="dialog-confirm-del" title="Xóa nhận xét">
         
      </div>
   </body>
</html>
