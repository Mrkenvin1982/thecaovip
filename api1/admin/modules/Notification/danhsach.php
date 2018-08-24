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
<!-- ./ noi dung--> 
<?php include('../../includes/cn-footer.php'); ?>
