
<?php 
$page = 'card-store';

include 'module/head.php';
include 'module/top-nav.php';
if ($adminuser->group_id!=1) {
   header('location:index.php');
    exit(); 
}

 ?>
       <div class="container">
   <div class="line"></div>
   <!-- uiView:  -->
   <div class="content">
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
                <form method="post" enctype="multipart/form-data" class="form-inline" action="upload_card.php">
                    <div class="form-group">
                        <label class="control-label">Select File</label>
                        <input type="file" class="file" name="fileToUpload">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Upload</button>
                    <a href="demoupload_card.xlsx">Download File Mẫu</a>
                    <br>
                    <p>Loại thẻ: Viettel(1),
 Mobi(2),
 Vina(3)

                    </p>
                </form>
            </div>
         </div>
<div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Danh sách số điện thoại cần thanh toán</h4>
                </div>
                <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group ">
                                    <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" value="<?=date('d/m/Y')?>" name="start_date"  id="startDate">

                                </div>
                            </div>
                        </div>
 <div class="col-sm-1" style="margin-right: -47px; margin-top:7px"><i class="fa fa-arrow-right"></i></div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" value="<?=date('d/m/Y')?>" name="end_date"  id="endDate">
                                    
                                </div>
                            </div>
                        </div>
   <div class="col-md-2">
       <div class="form-group">
           <select class="form-control" name="status">
               <option value="100"> -- Tất cả --</option>
               <option value="0"> -- Đã sử dụng --</option>
               <option value="1"> -- Chưa sử dụng --</option>
               <option value="2"> -- Thẻ sai --</option>
               <option value="3"> -- Thẻ lỗi --</option>
               <option value="3"> -- Đang xử lí --</option>



           </select>
       </div>
   </div>
   <div class="col-md-3">
       <div class="form-group">
           <select class="form-control" name="orders">
               <option value="0"> -- Xếp theo thời gian nhập --</option>
               <option value="1"> -- Xếp theo thời gian xuất --</option>

               <option value="2"> -- Xếp theo mệnh giá --</option>
               <option value="3"> -- Xếp theo loại thẻ --</option>
           </select>
       </div>
   </div>
                        <div class="col-md-2" id="btn-search">
                            <button onclick="load(1)"  class="btn btn-primary">Search</button>
                        </div>
                       
                    
                </div>
            </div>
        </div>
        <div class="row">
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
    </tr>
  <?php endforeach ?>
</tbody></table>
</div>
        </div>

      <!-- end ngIf: discount != null -->
   </div>
</div>
<script>
   $(function() {
load(1);
                          });
                          function load(cur_page) {
                                                
                                    $.ajax({
                url : '/ajax/card/get.php',
                dataType: 'html',
                type : 'GET',

                data : {
                           status : $("select[name=status]").val(),
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
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
