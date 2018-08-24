<?php $page = 'cardsS' ?>
<?php require_once '../../../config.php'; ?>
<?php include('../../includes/cn-head.php');  ?>

<?php include('../../includes/cn-sidbar.php');  ?>

<!-- tiêu đề page-->
<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-9">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Cửa hàng thẻ</font></font></h2>
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
      <div class="">
   <div class="line"></div>
   <!-- uiView:  -->
   <div class="content" ">
      <!-- ngIf: discount != null -->
      <div class="row">
        <?php
                if($_SESSION['msgec']) {
                    echo '<div class="alert alert-danger">';
                
                            echo "- " . $_SESSION['msgec'] . "<br/>";
              
                    echo '</div>';
                }
            ?>
            <?php
                if($_SESSION['msgsc']) {
                    echo '<div class="alert alert-success">';
                    
                            echo "- " . $_SESSION['msgsc'] . "<br/>";
                 
                    echo '</div>';
                }
                unset($_SESSION['msgec']);
                unset($_SESSION['msgsc']);
            ?>
      </div>
      <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Upload File</h4>
                </div>
                <form method="post" enctype="multipart/form-data" class="form-inline" action="<?=$base_url?>/upload_card.php">
                    <div class="form-group">
                        <label class="control-label">Select File</label>
                        <input type="file" class="file" name="fileToUpload">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Upload</button>
                    <a href="<?=$base_url?>/demoupload_card.xlsx">Download File Mẫu</a>
                    <br>
                    <p>Loại thẻ: Viettel(1),Mobi(2),Vina(3)
                    </p>
                </form>
            </div>
         </div>
<div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Danh sách thẻ cào</h4>
                </div>
                <div class="row">
                     <div class="form-horizontal col-sm-2">
               <div class="form-group">
                  <label class="control-label col-sm-2">Tìm:</label>
                  <div class="col-sm-10"><input type="text" class="form-control" placeholder="Seri hoặc mã thẻ" name="target"></div>
               </div>
            </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="input-group ">
                                    <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" value="<?=date('d/m/Y')?>" name="start_date"  id="startDate">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" value="<?=date('d/m/Y')?>" name="end_date"  id="endDate">
                                    
                                </div>
                            </div>
                        </div>
   <div class="col-md-1">
       <div class="form-group">
           <select class="form-control" name="status">
               <option value="100"> Tất cả</option>
               <option value="0"> Đã sử dụng</option>
               <option value="1"> Chưa sử dụng</option>
               <option value="2"> Thẻ sai</option>
               <option value="3"> Lỗi</option>
               <option value="4"> Đang xử lí</option>



           </select>
       </div>
   </div>
   <div class="col-md-2">
       <div class="form-group">
           <select class="form-control" name="orders">
               <option value="0"> Xếp theo thời gian nhập</option>
               <option value="1"> Xếp theo thời gian xuất</option>
               <option value="2"> Xếp theo mệnh giá</option>
               <option value="3"> Xếp theo loại thẻ</option>
           </select>
       </div>
   </div>
                        <div class="col-md-1" id="btn-search">
                            <button onclick="load(1)"  class="btn btn-primary">Search</button>
                        </div>
                       
                    
                </div>
            </div>
        </div>
        <div class="row" style="margin-right: 35px !important">
            <div class="col-xs-12 col-md-12" id="result-table">
<table class="table table-bordered table-hover">
    <tbody><tr>
        <td>#</td>
        <td>ID</td>
        <td>Loại thẻ</td>
        <td>Pin</td>
        <td>Seri</td>
        <td>Mệnh giá</td>
        <td>Ngày hết hạn</td>
        <td>Nhập vào</td>
        <td>Trạng thái</td>
        <td>Des</td>

<?php 
$models_card = new Models_CardStores();

  $cards = $models_card->getList();

$i=0;
 ?>
  <?php foreach ($cards as $card):$i++ ?>
    <tr>
      <td><?=$i?></td>
      <td><?=$card->getId()?></td>
      <td><?=$card->card_type?></td>
      <td><?=Library_String::decryptstr($card->pin,PIN_PASS)?></td>
      <td><?=Library_String::decryptstr($card->seri,SERI_PASS)?></td>
      <td><?=$card->price?></td>
      <td><?=$card->expire_date?></td>
      <td><?=date('h:i:s d/m/Y',$card->time_in)?></td>
      <td><?=$card->status==0?'đã sử dụng':'chưa sử dụng'?></td>
      <td><?=$card->result?></td>

    </tr>
  <?php endforeach ?>
</tbody></table>
</div>
        </div>

      <!-- end ngIf: discount != null -->
   </div>
</div>  
<!-- ./ noi dung--> 

<?php include('../../includes/cn-footer.php'); ?>
<script>
   $(function() {
load(1);
                          });
                          function load(cur_page) {
                                                
                                    $.ajax({
                url : '../../ajax/card/get.php',
                dataType: 'html',
                type : 'GET',

                data : {
                           status : $("select[name=status]").val(),
                              target : $("input[name=target]").val(),
                           order : $("select[name=orders]").val(),
                  start_date : $("#startDate").val(),
                  end_date : $("#endDate").val(),
cur_page :cur_page

                },
                beforeSend : function() {
                    $('#result-table').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                error : function() {
                    $("#result-table").html('Loading data lỗi, Vui lòng refresh lại trang!');
                },
                success : function(data) {
                    $("#result-table").html(data);
                }
            });
                       
                          }
</script>

