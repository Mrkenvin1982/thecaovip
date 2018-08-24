
<style type="text/css">
    .search_1{
    float: right !important;
    padding: 10px 20px  !important;
    border: 1px solid !important;
    font-weight: bold !important
    }
    #ui-datepicker-div{
    z-index:3000 !important;
    background: #fff
    }
    #date_modified #ui-datepicker-div{
        display:none;
    }
    .cw{
        display: none;
    }
    .giahan h3{
        padding: 5px;
        color: red
    }
    .content-tk{
            margin: 10px 0px;
    border-top: 1px solid red;
    padding: 10px 0px;
    }
    .title-chuabiet{
    font-size: 16px;
    color: red;
    }
    .noidungchuabiet small{
    font-size: 13px !important;
    font-weight: bold;
    }
    .title-tk{
    text-transform: uppercase;
    color: red;
    font-weight: bold;
    padding: 15px 0px;
    font-size: 25px;
    }
    .title-tn{
            font-size: 15px;
    text-transform: uppercase;
    text-align: center;
    color: red;
    font-weight: bold;
    }
    .count-dn{
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    }
    .modal-header{
        padding: 15px !important
    }
    .modal-header h4{
        text-transform: uppercase;
        font-weight: bold
    }
</style>  
<div class="row  border-bottom white-bg dashboard-header ecommerce">
    <div class="row">
        <div class="col-md-6">
            <div class="giahan">
                <h3>

               <?php 
               $time='';
                       if (isset($ngay)) {
            $time= 'ngày '. implode('/', $ngay);
            }elseif (isset($thang)) {
             $time= 'tháng '. implode('/', $thang);
            }else{
             $time= 'tháng '. date('m/Y');
            } 
echo 'Thống kê '.$time;
            ?>
            <?php 
            $model_trans = new Models_TransactionHistories(); 
//
           $models_dn = new Models_DonationHistories();         
                  
            if (isset($ngay)) {
                $dn = $models_dn->getObjectByCondition('IFNULL(sum(money), 0) as sumdn',array("DATE_FORMAT( CONVERT_TZ(FROM_UNIXTIME(time), @@session.time_zone,'+07:00'),'%d/%m/%Y')"=>implode('/', $ngay)));
                $inj ="DATE_FORMAT( CONVERT_TZ(FROM_UNIXTIME(time), @@session.time_zone,'+07:00'),'%d/%m/%Y')='".implode('/', $ngay)."' ";
            }elseif (isset($thang)) {
                $inj="DATE_FORMAT( CONVERT_TZ(FROM_UNIXTIME(time), @@session.time_zone,'+07:00'),'%m/%Y')='".implode('/', $thang)."' ";
                  $dn = $models_dn->getObjectByCondition('IFNULL(sum(money), 0) as sumdn',array("DATE_FORMAT( CONVERT_TZ(FROM_UNIXTIME(time), @@session.time_zone,'+07:00'),'%m/%Y')"=>implode('/', $thang)));
        
            }else{
                $inj="DATE_FORMAT( CONVERT_TZ(FROM_UNIXTIME(time), @@session.time_zone,'+07:00'),'%m/%Y')='".date('m/Y')."' ";
              $dn = $models_dn->getObjectByCondition('IFNULL(sum(money), 0) as sumdn',array("DATE_FORMAT( CONVERT_TZ(FROM_UNIXTIME(time), @@session.time_zone,'+07:00'),'%m/%Y')"=>date('m/Y')));
            }

            $query=$query="SELECT COUNT(*) as soluong,type,SUM(price) as doanhthu FROM TransactionHistories WHERE type!=0 and ".$inj."GROUP BY type";
 $list_dn = $model_trans->customQuery($query);
 $thunhap=0;
 $soluong=array('bank'=>0,'card'=>0);
foreach ($list_dn as $value) {
    $thunhap+=$value->doanhthu;
    if ($value->type==1) {
       $soluong['card']=$value->soluong;
    }else{
 $soluong['bank']=$value->soluong;
    }
}
$thunhap=$dn->sumdn-$thunhap;
             ?>

            </h3>
            </div>
        </div>
        <div class="col-md-6" style="float:right;">
            <div class="search_time">
                <div class ="search_month">
                     <button type="button" class="btn btn-primary search_1" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-calendar"></i>  Tìm theo ngày
                    </button>
                </div>
                <!-- nội dung tìm theo tháng-->
                <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4>Tìm theo ngày</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_added" type="text" class="form-control" value="<?=date('d-m-Y')?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Đóng</button>
                                            <button type="button" class="btn btn-primary" onclick="filterRedirect(0,'<?=$admin_url.'/dashboard/'?>')">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                   <!-- nội dung tìm theo tháng-->         
                <div class="search_day">
                    <button type="button" class="btn btn-primary search_1" data-toggle="modal" data-target="#myModal1">
                    <i class="fa fa-calendar"></i>  Tìm theo tháng
                    </button>
                </div>
                <!-- nội dung tìm theo tháng-->
                <div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4>Tìm theo tháng</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_modified" type="text" class="form-control" value="<?=date('m-Y')?>">
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Đóng</button>
                                            <button type="button" class="btn btn-primary" onclick="filterRedirect(1,'<?=$admin_url.'/dashboard/'?>')">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                   <!-- nội dung tìm theo ngày--> 
            </div>
        </div>
    </div>
<div class="content-tk">
    <p class="title-chuabiet"><b><i class="fa fa-lightbulb-o fa-1x"></i> Có thể bạn chưa biết: </b></p>
    <p class="noidungchuabiet"><small>Tỉ lệ nhận được donate sẽ cao hơn rất nhiều khi bạn sử dụng dịch vụ donate của chúng tôi. Phần lớn cộng đồng người xem chia sẽ với chúng tôi rằng: "Họ thường thích và dễ donate nhiều hơn khi Streamer sử dụng dịch vụ của Donate.com.vn".</small></p>
    <p class="noidungchuabiet"><small>Chỉ những thành viên của Donate.com.vn mới được tham gia vào những dự án mới và hấp dẫn sắp ra được mắt của chúng tôi.</small></p>
    <h3 class="title-tk">
<?php 
echo 'Thống kê '.$time;?>
   </h3>
    <div class="row">
        <div class="col-md-4">
            <h5 class="title-tn"> Thẻ tín dụng <?=$time?> </h5>
            <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-credit-card fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span class="count-dn"> Số lượng donate </span>
                            <h2 class="font-bold"> <?=$soluong['bank']?></h2>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-4">
            <h5 class="title-tn"> Thẻ cào <?=$time?> </h5>
            <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-edit fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span class="count-dn"> Số lượng donate </span>
                            <h2 class="font-bold"><?=$soluong['card']?></h2>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-4">
            <h5 class="title-tn"> Tổng thu nhập <?=$time?> </h5>
            <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-dropbox fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span class="count-dn"> Tính theo VNĐ </span>
                            <h2 class="font-bold"><?=number_format($thunhap)?></h2>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
</div>                                
    
</div>
</div>
</div>
</div>
</div>
