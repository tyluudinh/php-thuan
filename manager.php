<?php
  session_start();
  if(!isset($_SESSION['login_user']) || $_SESSION['login_user_level']<999){
      header("location:404");
  }
 
  include("db-config.php");
 

?>
<!DOCTYPE HTML>
<html>
   <head>
      <title>Quản lý || Photo sharing</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
      <link rel="stylesheet" href="css/grids.css" type="text/css" media="all" />
       <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="all"  />
     
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script src="js/ios-orientationchange-fix.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>

      <link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
      <link href="css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
      <link href="css/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" src="js/dataTables.tableTools.js"></script>
      <script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
      <script type="text/javascript" src="js/numeral.min.js"></script>
      <script type="text/javascript" src="js/bootstrap-dialog.js"></script>
      <script src="js/notify.min.js"></script>
      <script type="text/javascript">
        var dataSetPhotos = [] , dataSetUser = [] , dataSetCmt = [];
        var dataTablePhotos, dataTableUser, dataTableCmt;
         $(document).ready(function(){
            <?php
            if(!empty($notify)){
               echo $notify;
            }
            ?>
            $("#ava-upload").change(function(){
              readURL(this);
            });
            initDataTableForPhotos();
            initDataTableForUser();
            initDataTableForCmt();
            bindEvent();
             
         });
         function initDataTableForPhotos() {
              dataTablePhotos = $('#photos-dataTable').DataTable({
                  initComplete: function () {
                      getAndSetDataForTable("photos");
                  },
                  "iDisplayLength": 100,
                  "bFilter": true,
                  "data": dataSetPhotos,
                  "ordering": true,
                  "paging": true,
                  "columns": [
                      {"title": "ID"},
                      {"title": "Hình ảnh"},
                      {"title": "Thành viên đăng"},
                      {"title": "Tiêu đề"},
                      {"title": "Nội dung",visible:true},
                      {"title": "Thời gian đăng"},
                      {"title": "Lượt view"},
                      {"title": "Công cụ"}
                  ],
                  "aoColumnDefs": [
                      {
                          "aTargets": [7],
                          "mData": null,
                          "mRender": function (data, type, full) {
                              return '<button type="button" id="editPhoto" action="update" class="btn btn-warning">Sửa</button>' +
                                      '<button type="button" id="delPhoto" action="update" class="btn btn-danger">Xóa</button>'
                          }
                      }
                  ]
              });
          }
          function initDataTableForUser() {
              dataTableUser = $('#users-dataTable').DataTable({
                  initComplete: function () {
                      getAndSetDataForTable("users");
                  },
                  "iDisplayLength": 100,
                  "bFilter": true,
                  "data": dataSetUser,
                  "ordering": true,
                  "paging": true,
                  "columns": [
                      {"title": "ID"},
                      {"title": "Avatar"},
                      {"title": "UserName"},
                      {"title": "Email"},
                      {"title": "Tình trạng",visible:true},
                      {"title": "Lần cuối truy cập"},
                      {"title": "Loại User"},
                      {"title": "Công cụ"}
                  ],
                  "aoColumnDefs": [
                      {
                          "aTargets": [7],
                          "mData": null,
                          "mRender": function (data, type, full) {
                              return '<button type="button" id="editUser" action="update" class="btn btn-warning">Sửa</button>' +
                                      '<button type="button" id="delUser" action="update" class="btn btn-danger">Xóa</button>'
                          }
                      }
                  ]
              });
          }
          function initDataTableForCmt() {
              dataTableCmt = $('#cmt-dataTable').DataTable({
                  initComplete: function () {
                      getAndSetDataForTable("cmt");
                  },
                  "iDisplayLength": 100,
                  "bFilter": true,
                  "data": dataSetCmt,
                  "ordering": true,
                  "paging": true,
                  "columns": [
                      {"title": "ID"},
                      {"title": "Nội dung"},
                      {"title": "Thành viên bình luận"},
                      {"title": "Bài viết"},
                      {"title": "Bài viết ID",visible:false},
                      {"title": "Thời gian bình luận"},
                      {"title": "Công cụ"}
                  ],
                  "aoColumnDefs": [
                      {
                          "aTargets": [6],
                          "mData": null,
                          "mRender": function (data, type, full) {
                              return '<button type="button" id="editCmt" action="update" class="btn btn-warning">Sửa</button>' +
                                      '<button type="button" id="delCmt" action="update" class="btn btn-danger">Xóa</button>'
                                      // '<button type="button" id="accessPost" action="update" class="btn btn-success">Truy cập bài viết</button>'
                          }
                      }
                  ]
              });
          }
          function deleteType(type,id){
            var url = "";
            switch (type) {
                case "photos" :
                    url = "deletePhoto.php";
                    break;
                case "users" :
                    url = "deleteUser.php";
                    break;
                case "cmt" :
                    url = "deleteComment.php";
                    break;
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {postData: id},
                dataType:'JSON', 
                  success: function(data){
                    
                     $('#formConfirm').modal('hide');
                     var success="success";
                     if(data.code<0){
                        success="error";
                     }
                     document.getElementById('frm_submit').disabled = false;
                     updateNotification(data.res,success);
                     setInterval(function(){ location.reload(); }, 2000);
                     
                     
                  }
              });

          }
          function bindEvent() {
            $('#photos-dataTable tbody').on('click', 'button', function () {
                var rowData = dataTablePhotos.row($(this).parents('tr')).data();
                var id = $(this).attr('id');
                switch (id) {
                    case "editPhoto" :
                        console.log(rowData);
                        window.open("edit-photo.php?id=" + rowData[0]);
                        break;
                    case  "delPhoto" :
                        console.log(rowData);
                        $('#frm_body').css('display','none');
                        $('#formConfirm').modal('show');
                        $('#frm_submit').on('click', function () {
                              deleteType("photos",rowData[0]);
                        });
                        break;
                }
            });
            $('#users-dataTable tbody').on('click', 'button', function () {
                var rowData = dataTableUser.row($(this).parents('tr')).data();
                var id = $(this).attr('id');
                switch (id) {
                    case "editUser" :
                        console.log(rowData);
                        window.open("edit-photo.php?id=" + rowData[0]);
                        break;
                    case  "delUser" :
                        console.log(rowData);
                        $('#frm_body').css('display','block');
                        $('#formConfirm').modal('show');
                        $('#frm_submit').on('click', function () {
                              // document.getElementById('frm_submit').disabled = true;
                              deleteType("users",rowData[0]);
                        });
                        break;
                }
            });
            $('#cmt-dataTable tbody').on('click', 'button', function () {
                var rowData = dataTableCmt.row($(this).parents('tr')).data();
                var id = $(this).attr('id');
                switch (id) {
                    case "editCmt" :
                        console.log(rowData);
                        window.open("edit-photo.php?id=" + rowData[0]);
                        break;
                    case  "delCmt" :
                        console.log(rowData);
                        $('#frm_body').css('display','none');
                        $('#formConfirm').modal('show');
                        $('#frm_submit').on('click', function () {
                              // document.getElementById('frm_submit').disabled = true;
                              deleteType("cmt",rowData[0]);
                        });
                        break;
                    // case "accessPost":
                    //     window.open("detail-photo.php?id=" + rowData[4]);
                    //     break;
                }
            });
        }
          function getAndSetDataForTable(type) {
            switch (type) {
                case "photos":
                   $.ajax({
                      type: "POST",
                      url: "getAllPhotos.php",
                      data: {getAll: "getAll"},
                      dataType:'JSON', 
                        success: function(data){
                           var beanListMember = data.res;
                            if (data.code!=1) {
                                return;
                            }
                            dataSetPhotos = [];
                            for (var index = 0; index < beanListMember.length; index++) {
                                var tempObject = [];
                                tempObject.push(beanListMember[index].id);
                                var imageSource ='<img alt=""  src='+beanListMember[index].url+' style="height:100px;width:100px" />';
                                tempObject.push(imageSource);
                                tempObject.push(beanListMember[index].user);
                                var accessPost = '<a href="detail-photo.php?id='+beanListMember[index].id+'">'+beanListMember[index].title+'</a>'
                                tempObject.push(accessPost);
                                
                                tempObject.push(beanListMember[index].content);
                                tempObject.push(beanListMember[index].time_created);
                                tempObject.push(beanListMember[index].view);
                                dataSetPhotos.push(tempObject);
                            }
                            var table = $('#photos-dataTable');
                            table.dataTable().fnClearTable();
                            table.dataTable().fnAddData(dataSetPhotos);
                        }
                    });
                    break;
                case "users":
                    $.ajax({
                      type: "POST",
                      url: "getAllUsers.php",
                      data: {getAll: "getAll"},
                      dataType:'JSON', 
                        success: function(data){
                           var beanListMember = data.res;
                             if (data.code!=1) {
                                return;
                            }
                            dataSetUser = [];
                            for (var index = 0; index < beanListMember.length; index++) {
                                var tempObject = [];
                                tempObject.push(beanListMember[index].id);
                                var imageSource ='<img alt=""  src='+'images/ava-users/'+beanListMember[index].profile_photo+' style="height:100px;width:100px" />';
                                tempObject.push(imageSource);
                                tempObject.push(beanListMember[index].username);
                                tempObject.push(beanListMember[index].email);
                                var status = "<p style='color: red'>Khóa</p>";
                                if(beanListMember[index].status==1){
                                  status = "Hoạt động";
                                }
                                tempObject.push(status);
                                tempObject.push(beanListMember[index].ts_login);
                                var level = "<p style='color: blue'>User quản lý</p>";
                                if(beanListMember[index].level==0 ){
                                    level = "User thường";
                                }
                                tempObject.push(level);
                                dataSetUser.push(tempObject);
                            }
                            var table = $('#users-dataTable');
                            table.dataTable().fnClearTable();
                            table.dataTable().fnAddData(dataSetUser);
                        }
                    });
                    break;
                case "cmt" :
                    $.ajax({
                      type: "POST",
                      url: "getAllCmts.php",
                      data: {getAll: "getAll"},
                      dataType:'JSON', 
                        success: function(data){
                           var beanListMember = data.res;
                             if (data.code!=1) {
                                return;
                            }
                            dataSetCmt = [];
                            for (var index = 0; index < beanListMember.length; index++) {
                                var tempObject = [];
                                tempObject.push(beanListMember[index].id);
                                
                                var accessPost = '<a href="detail-photo.php?id='+beanListMember[index].id_photo+'">'+beanListMember[index].title+'</a>'
                                tempObject.push(beanListMember[index].contents);
                                tempObject.push(beanListMember[index].user);
                                tempObject.push(accessPost);
                                tempObject.push(beanListMember[index].id_photo);
                             
                                tempObject.push(beanListMember[index].time_created);
                               
                                dataSetCmt.push(tempObject);
                            }
                            var table = $('#cmt-dataTable');
                            table.dataTable().fnClearTable();
                            table.dataTable().fnAddData(dataSetCmt);
                        }
                    });
                    break;
            }
             
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
                <h2>Chào mừng <strong>
                  <?php
                    echo $_SESSION['login_user'];
                  ?>
                </strong> đã đến với trang quản trị</h2>
                <ul class="nav nav-tabs">

                  <li class="active"><a data-toggle="tab" href="#lphotos">Quản lý hình ảnh</a></li>
                  <li><a data-toggle="tab" href="#lusers">Quản lý thành viên</a></li>
                  <li><a data-toggle="tab" href="#lcmts">Quản lý bình luận của thành viên</a></li>
                </ul>

                <div class="tab-content">
                  <div id="lphotos" class="tab-pane fade in active">
                    <div class="table-responsive tblmanager">
                      <table id="photos-dataTable" class="table table-striped table-bordered table-hover"
                             cellspacing="0" width="100%">
                      </table>
                    </div>
                  </div>
                  <div id="lusers" class="tab-pane fade">
                    <div class="table-responsive tblmanager">
                      <table id="users-dataTable" class="table table-striped table-bordered table-hover"
                             cellspacing="0" width="100%">
                      </table>
                    </div>
                  </div>
                  <div id="lcmts" class="tab-pane fade">
                    <div class="table-responsive tblmanager">
                      <table id="cmt-dataTable" class="table table-striped table-bordered table-hover"
                             cellspacing="0" width="100%">
                      </table>
                    </div>
                  </div>
                </div>
                </div>
                
               
            </div>
            <div class="clear"></div>
         </div>
      </div>
      <div class="modal fade" id="formConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="frm_title">Bạn có thực sự muốn xóa không?</h4>
                </div>
                <div class="notificationDelete">
                    <div class="modal-body" >
                       <center><h4 style="color: red;margin: auto" id="frm_body">Khi xóa User thì tất cả hình ảnh và nhận xét của user này cũng sẽ xóa theo! Hãy cân nhắc trước khi Xóa</h4></center>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger col-sm-2 pull-right" data-dismiss="modal" id="frm_cancel">Không</button>
                    <button style="margin-right:20px;" type="button" class="btn btn-primary col-sm-2 pull-right" id="frm_submit">Có</button>
                </div>
            </div>
        </div>
    </div>
   </body>
</html>