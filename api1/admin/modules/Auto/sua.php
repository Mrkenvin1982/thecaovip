<?php $page = 'auto' ?>
<?php require_once '../../../config.php'; ?>
<?php include('../../includes/cn-head.php');  ?>
<?php include('../../includes/cn-sidbar.php');  ?>
<?php 
       $models_advs = new Models_AutoCards();
       $obj = $models_advs->getObject(1); 
?>
    <div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-10">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Sửa menus</font></font></h2>
   </div>
   <div class="col-lg-2">
   </div>
</div>
<style type="text/css">
HTML  CSS   Result
Edit on 
.switch-field {
  font-family: "Lucida Grande", Tahoma, Verdana, sans-serif;
  padding: 40px;
    overflow: hidden;
    
}
.switch-field{
  float: left;
    width: 100%;
}
.switch-title {
  margin-bottom: 6px;
}

.switch-field input {
    position: absolute !important;
    clip: rect(0, 0, 0, 0);
    height: 1px;
    width: 1px;
    border: 0;
    overflow: hidden;
}

.switch-field label {
  float: left;
}

.switch-field label {
  display: inline-block;
  width: 60px;
  background-color: #e4e4e4;
  color: rgba(0, 0, 0, 0.6);
  font-size: 14px;
  font-weight: normal;
  text-align: center;
  text-shadow: none;
  padding: 6px 14px;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  -webkit-transition: all 0.1s ease-in-out;
  -moz-transition:    all 0.1s ease-in-out;
  -ms-transition:     all 0.1s ease-in-out;
  -o-transition:      all 0.1s ease-in-out;
  transition:         all 0.1s ease-in-out;
}

.switch-field label:hover {
    cursor: pointer;
}

.switch-field input:checked + label {
  background-color: #1ab394;
  -webkit-box-shadow: none;
  box-shadow: none;
  color: #fff
}

.switch-field label:first-of-type {
  border-radius: 4px 0 0 4px;
}

.switch-field label:last-of-type {
  border-radius: 0 4px 4px 0;
}
.switch-title{
  font-weight: bold;
  font-size: 15px;
  padding: 5px;
}
.switch-title i{
  font-weight: 300;
}
.setTime{
  width: 100%;
  float: left;
  margin-bottom: 15px
}
.btn-sussces{
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 32px;
    text-decoration: none;
    margin: 4px 2px;
    cursor: pointer;
}
</style>
<div class="ibox-content">
    <form method="post" action="/router.php" name="adminForm" id="adminForm" enctype="multipart/form-data">

<div class="setTime">
       <div class="switch-title">Chế độ auto</div>
 <label class="radio-inline"><input type="radio" name="orders" value="0" <?= ($obj->orders == 0) ? 'checked' : '' ?>>Trả sau</label>
 <label class="radio-inline"><input type="radio" name="orders" value="1" <?= ($obj->orders == 1) ? 'checked' : '' ?> >Trả trước </label>
<label class="radio-inline"><input type="radio" name="orders" value="2" <?= ($obj->orders == 2) ? 'checked' : '' ?>>FTTH</label>
</div> 
<div class="switch-field">
      <div class="switch-title">Chế độ auto</div>
      <input type="radio" id="switch_left" name="status" value="1" <?= ($obj->status == 1) ? 'checked' : '' ?>/>
      <label for="switch_left"> Bật </label>
      <input type="radio" id="switch_right" name="status" value="0" <?= ($obj->status == 0) ? 'checked' : '' ?>/>
      <label for="switch_right"> Tắt </label>
</div>
<div class="setTime">
    <div class="switch-title">Thời gian khớp <i>(tính bằng phút)</i></div>
     <input class="form-control" type="text" size="130" name="time" style="width: 100%;" value="<?= $obj->time ?>"/>
</div> 
<button type="submit" class="btn btn-sussces" name="ok">Lưu</button>
<input type="hidden" name="controller" value="AutoCards">
<input type="hidden" name="id" value="1">
<input type="hidden" name="action" value="save">        
   </form>
</div>

<?php include('../../includes/cn-footer.php'); ?>
<script>
        $(function() {
            var socket = io.connect('http://123.17.153.33:30000');
            socket.on('connect', function() {
                $("#data").html('Socket connected');
            });
            
            $("#btnon").click(function() {
                $("#data").html("");
                socket.emit('on');
            });
            
            $("#btnoff").click(function() {
                $("#data").html("");
                socket.emit('off');
            });
        });
    </script>