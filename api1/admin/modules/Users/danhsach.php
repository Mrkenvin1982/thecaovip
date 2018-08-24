<?php $page = 'users' ?>
<?php require_once '../../../config.php'; ?>
<?php include('../../includes/cn-head.php');  ?>
<?php include('../../includes/cn-sidbar.php');  ?>
<!-- tiêu đề page-->
<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-9">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Users</font></font></h2>
   </div>
   <div class="col-lg-3">
      <?php include('../../includes/toolbar.php');  ?>
   </div>
</div>
<!-- tiêu đề page-->
<style type="text/css">
  table.table-bordered.dataTable th{
    font-size: 15px;
    font-weight: 600;
  }
</style>    
<!-- nội dung-->
<div class="ibox-content">
                  <form method="post" action="/router.php" name="adminForm" id="adminForm">
                        <div class="table-responsive">
                    <table id="tableData" class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th width="10"><input type="checkbox" name="checkAll" id="checkAll"/></th>
                        <th>Tên</th>     
                        <th>Phone</th>
                        <th>Balance</th>
                        <th>Group Id</th>
                        <th>Time</th>
                        <th>Status</th> 
                        <th>Thao tác</th>  
                    </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $models_advs = new Models_Users();
                        $list = $models_advs->customFilter('',array(''),'group_id asc');
                        $stt = 0;
                    foreach ($list as $obj):$stt++;?>
                        <tr class="active">
                        <td><?= $stt ?></td>
                        <td><input type="checkbox" class="checkItem" name="cid[]" value="<?php echo $obj->getId(); ?>"/></td>
                        <td><a href="sua.php?id=<?= $obj->getId() ?>"><?= $obj->name ?></a></td>       
                        <td><?= $obj->phone ?></td>
                        <td><?= number_format($obj->balance) ?></td>
                        <?php 
                        if($obj->group_id == 1){ ?>
                        <td>Admin</td>
                         <?php  
                            }elseif ($obj->group_id == 2) {?>
                          <td>Đại lý cấp 1</td>   
                           <?php }elseif ($obj->group_id == 3) {?>
                          <td>Đại lý cấp 2</td>  
                            <?php
                           }?>
                        <td><?= date('d-m-Y',$obj->time) ?></td>
                         <?php include('../../includes/add_status.php');  ?>  
                         <td><a name="<?= $obj->getId() ?>" title="Đổi mật khẩu cho : <?= $obj->name ?>" type="1" class="change-pass"><img src="<?= $base_url ?>/admin/images/changepass.png"></a>
                          <a name="<?= $obj->getId() ?>" title="Đổi mật khẩu giao dịch cho : <?= $obj->name ?>" type="2" class="change-pass"><img src="<?= $base_url ?>/admin/images/ph.png"></a></td>
                      </tr>
                        <?php 
                            endforeach;
                        ?>
                    </tbody>
                    </table>
                      <?php include('../../includes/hidden.php');  ?> 
                     </div>
                     
                      </form>
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
                      <input type="hidden" id="utype">

                          <div class="mdl-textfield mdl-js-textfield">
                            <input class="form-control" id="new_pass" type="password" name="new_pass" placeholder="Mật khẩu mới">
                          </div>
                          <div class="mdl-textfield mdl-js-textfield">
                            <input class="form-control" id="re_new_pass" type="password" name="re_new_pass" placeholder="Nhập lại mật khẩu mới">
                          </div>
                        <div class="modal-footer">  
                          <button type="button" class="btn btn-white" data-dismiss="modal">Đóng</button>
                          <button id="savePass" type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                        </div>  
                    </div>
</div>
</div>
</div>   
<style type="text/css">
  .inmodal .modal-header{
    padding: 10px 15px;
  }
  .namev{
    color: red !important;
  }
  .mdl-textfield{
    padding: 10px 15px;
  }
  .modal-body{
    padding: 20px 30px 0px 30px;
  }
</style>                 
<!-- ./ noi dung--> 
<?php include('../../includes/cn-footer.php'); ?>
<script type="text/javascript">

  $(document).on('click','.change-pass',function() {
    $("#utype").val($(this).attr('type'));
    $("#uid").val($(this).attr('name'));
    $(".modal-header h4").html($(this).attr('title'));
    $('#new_pass').val('');
    $('#re_new_pass').val('')
    $("#myModal1").modal();


});
    $("#savePass").on("click", function(e){  
    var new_pass=$('#new_pass').val();
    var re_new_pass = $('#re_new_pass').val()
           $.ajax({
           dataType : 'json',
               type : 'post',
               url : 'changepass.php',
               data : {
                    id : $("#uid").val(),
                    type:$("#utype").val(),
                    new_pass : new_pass,
                    re_new_pass : re_new_pass,
               },
               success : function(data){
                        alert(data.msg);

               }
        });
    });
</script>
