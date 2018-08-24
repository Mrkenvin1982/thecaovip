<?php 
if (intval($_GET['id'])==1) {
   $page = 'tbnap';
 }elseif(intval($_GET['id'])==2){
  $page = 'tb';
 }
 else{
  $page = 'tbkm';
 } ?>
<?php require_once '../../../config.php'; ?>
<?php include('../../includes/cn-head.php');  ?>
<?php include('../../includes/cn-sidbar.php');  ?>
<?php include('../../includes/construct.php');  ?>
<?php 
 ?>
	<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-10">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Sửa thông báo</font></font></h2>
   </div>
   <div class="col-lg-2">
   </div>
</div>
<div class="ibox-content">
	<form method="post" action="/router.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
                <div class="table-responsive">
                <table class="admintable table">
   
               <tr>
                  <td class="key" width="10%">Nội dung</td>
                  <td width="90%">
                         <textarea style="width: 100%" name="content"><?=$obj->content?></textarea>
   <?php if ($obj->getId()==1): ?>
      <script>
      CKEDITOR.replace( 'content' );
    </script>
   <?php endif ?>
                  </td>
                </tr>
                <?php include('../../includes/status.php');  ?>   
                </table>
                </div>
                <button type="submit" class="btn btn-default" name="ok">Lưu</button>
				<input type="hidden" name="controller" value="<?= $entity ?>">
				<input type="hidden" name="id" value="<?= isset($dan) ? $obj->getId() : '' ?>">
				<input type="hidden" name="action" value="save">
                </form>
</div>
<?php include('../../includes/cn-footer.php'); ?>