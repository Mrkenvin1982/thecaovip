
<?php require_once '../config.php'; ?>
<?php include('includes/cn-head.php');  ?>
<?php include('includes/cn-sidbar.php');  ?>
<!-- tiêu đề page-->
<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-9">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Quản lí admin</font></font></h2>
   </div>
</div>
<?php 


 ?><!-- tiêu đề page-->
 
<!-- nội dung-->
<div class="ibox-content">
  <h1>Thêm tài khoản</h1>
<form id="addAdmin">
  <div class="form-group">
    <label for="">Tên tài khoản</label>
    <input type="text" class="form-control" name="usn"  placeholder="Nhập tên tài khoản">
  </div>
  <div class="form-group">
    <label for="">Password</label>
    <input type="text" class="form-control" name="pwd" placeholder="Mật khẩu">
  </div>
  <button type="submit" class="btn btn-primary">Add</button>
</form>
</div>
<div class="ibox-content">

      <div class="row">
    <div class="col-md-12 col-xs-12">
      <table class="table table-hover">
       <thead>
          <th>STT</th>
        <th>Tên tài khoản</th>
        <th>Trạng thái</th>
        <th>Action</th>

       </thead>
       <tbody>
        <?php $models_admin = new Models_AdminUsers();
$list_admin = $models_admin->customFilter();
$i=0; ?>
         <?php foreach ($list_admin as $admin):$i++ ?>
           <tr>
           <td><?=$i?></td>
           <td><?=$admin->username?></td>
           <td><?=$admin->status==1?'Đang hoạt động':'Đã khóa'?></td>
           <td><a name="<?=$admin->getId()?>" title="Đổi mật khẩu cho : <?=$admin->username?>" class="change-pass"><img src="images/changepass.png"></a></td>

         </tr>
        <?php endforeach ?>
       </tbody>
      </table>
      
            </div>
     
</div>
                    </div>
                    <div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content animated bounceInRight">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4></h4>
              </div>
                    <div class="modal-body" id="mod-body">
                      <input type="hidden" id="uid">
                          <div class="mdl-textfield mdl-js-textfield">
                            <input class="form-control" id="password" type="text" name="password" placeholder="Mật khẩu mới">
                          </div>
                        <div class="modal-footer">  
                          <button type="button" class="btn btn-white" data-dismiss="modal">Đóng</button>
                          <button id="savePass" type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                        </div>  
                    </div>
</div>
</div>
</div> 

<!-- ./ noi dung--> 
<?php include('includes/cn-footer.php'); ?>
 <script>
     $(document).on('click','.change-pass',function() {
    $("#uid").val($(this).attr('name'));
    $(".modal-header h4").html($(this).attr('title'));
    $('#password').val('');
    $("#myModal1").modal();
});
         $("#savePass").on("click", function(e){  

           $.ajax({
           dataType : 'json',
               type : 'post',
               url : 'ajax/admin/change-pass.php',
               data : {
                    id : $("#uid").val(),
                    password :$('#password').val(),
               },
               success : function(data){
                alert(data.msg);
                      if (data.code==0) {
                        $("#myModal1").modal('toggle');
                      }

               }
        });
    });
           $("#addAdmin").ajaxForm({
    url: 'ajax/admin/add.php',
    type : 'post',
    dataType:'json',
    beforeSubmit : function() {
                    
                },
    success: function(data) {        
                alert(data.msg);
if (data.code==0) location.reload();

                }
  })
 </script>