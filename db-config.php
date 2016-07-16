<?php
	
	 
	

   define('DB_SERVER', 'localhost:3306');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'photos_share');
   
   // define('DB_SERVER', 'mysql.hostinger.vn:3306');
   // define('DB_USERNAME', 'u368412663_vcl');
   // define('DB_PASSWORD', 'FZtrtK5jVFFq');
   // define('DB_DATABASE', 'u368412663_photo');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   date_default_timezone_set("Asia/Ho_Chi_Minh");
   mysqli_set_charset($db,"utf8");

   
   
?>