<!DOCTYPE HTML>
<html>
   <head>
       <title>Trang chủ || Photo sharing</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/grids.css" type="text/css" media="all" />
       <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script src="js/ios-orientationchange-fix.js"></script>
      <script type="text/javascript">
         $(document).ready(function(){
         
            
             
         });
      </script>
   </head>
   <body>
      <div class="main">
         <div class="wrap">
            <?php
               session_start();
              include("include/menu-left.php");
            ?>
            <div class="right-content">
               <?php
                  include("include/menu-top.php");
               ?>
               <div id="content">
                  <div id="main" role="main">
                     <ul id="tiles">
                        <?php
                          include("db-config.php");
                          include("UltilFunction.php");
                          $query = mysqli_query($db,"SELECT * FROM photos ORDER BY time_created DESC");

                          if (mysqli_num_rows($query)==0) {
                              echo "<div class='alert alert-info' role='alert'>Chưa có hình ảnh nào.</div>";
                          }
                          while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
                            echo "<a href='detail-photo.php?id=".$row['id']."' title='".$row['title']."' >
                               <li>
                                  <p class='title-photo-homepage'>".$row['title']."</p>
                                  <img src='".$row['url']."' alt='".$row['title']."'  class='photo-homepage' />
                                  <p class='date-created'><span>".nicetime($row['time_created'])."</span>
                                     <img src='images/blog-icon1.png' title='posted date' alt='' />
                                  <div class='clear'></div>
                                  </p>
                                  <p class='view-count'><span>".$row['view']." View</span>
                                     <img src='images/blog-icon2.png' title='views' alt='' />
                                  <div class='clear'></div>
                                  </p>
                               </li>
                            </a>";
                          }
                          ?>
                        <!-- <a href="#" title="Chạy thể dục buổi sáng tại hồ đá nè">
                           <li>
                              <p class="title-photo-homepage">Đắng lòng thanh niên mặc áo Đức cổ vũ Pháp vì muốn Bồ vô địch Euro 2016 theo cách mà Hy Lạp đã làm ở Euro 2004 trên chính sân nhà của BĐN.</p>
                              <img src="images/12.jpg" alt=""  class="photo-homepage" />
                              <p class="date-created"><span>6 giờ trước</span>
                                 <img src="images/blog-icon1.png" title="posted date" alt="" />
                              <div class="clear"></div>
                              </p>
                              <p class="view-count"><span>636 View</span>
                                 <img src="images/blog-icon2.png" title="views" alt="" />
                              <div class="clear"></div>
                              </p>
                           </li>
                        </a> -->
                        
                        
                        
                        <!-- End of grid blocks -->
                     </ul>
                  </div>
                  <!-- Include the imagesLoaded plug-in -->
                  <script src="js/jquery.imagesloaded.js"></script>
                  <script src="js/jquery.wookmark.js"></script>
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
            <div class="clear"></div>
         </div>
      </div>
   </body>
</html>