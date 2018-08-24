<?php $page = 'phones' ?>
<?php require_once '../../../config.php'; ?>
<?php include('../../includes/cn-head.php');  ?>

<?php include('../../includes/cn-sidbar.php');  ?>

<!-- tiêu đề page-->
<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-9">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Cards</font></font></h2>
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
        
      </div>

<div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Danh sách thẻ đã nạp</h4>
                </div>
           <div class="row">
           
            
            <div class="form-horizontal col-sm-4">
               <div class="form-group">
                  <div class="col-sm-5" ><input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" id="startDate" value="<?=date('d/m/Y')?>"></div>
                  <div class="col-sm-2" style="padding: 0px;margin-right: -47px; margin-top:7px"><i class="fa fa-arrow-right"></i></div>
                  <div class="col-sm-5"><input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" id="endDate" value="<?=date('d/m/Y')?>"></div>
               </div>
            </div>
            <div class="form-horizontal col-sm-1">
               <div class="form-group">
                  <div class="col-sm-12"><button  class="btn search-button" onclick="load(1)"><i class="fa fa-search"></i></button></div>
               </div>
            </div>
      </div>
            </div>
        </div>
        <div class="row" style="margin-right: 35px !important">
            <div class="col-xs-12 col-md-12" id="result-table">

</div>
        </div>

      <!-- end ngIf: discount != null -->
   </div>
</div>  
<!-- ./ noi dung--> 
<script>
             $('.datepicker').datetimepicker({
             weekStart: 1,
             todayBtn: 1,
             autoclose: 1,
             todayHighlight: 1,
             startView: 2,
             minView: 2,
             forceParse: 0
         });
  
</script>
<?php include('../../includes/cn-footer.php'); ?>
       <script type="text/javascript">

                          $(function() {
load(1);
                          });
                          function load(cur_page) {
                                                
                                    $.ajax({
                url : '../../ajax/Cards/get.php',
                dataType: 'html',
                type : 'GET',

                data : {
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
