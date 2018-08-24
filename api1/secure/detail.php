<?php
include '../config.php';

$phone_id = intval($_GET['phone_id']);
$models_phones = new Models_Phones();
$phones = $models_phones->getObjectByCondition('', array('id' => $phone_id));
if(is_object($phones)) {
    $models_cards = new Models_Cards();
    $list = $models_cards->customFilter('', array('phone_id' => $phone_id, ''));
}
else {
    exit('error');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>

    <script type="text/javascript" src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="../node_modules/jquery-form/dist/jquery.form.min.js"></script>
    <script type="text/javascript" src="../node_modules/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $(function() {
            // xu ly form
            $("form").ajaxForm({
                  dataType : 'json',
                  url: 'update_card.php',
                  beforeSubmit : function() {
                      $("#loading_napthe").show();
                  },
                  success: function(data) {        
                      alert(data.msg);
                      if(data.code === 0) {
                          location.reload();
                      }
                  }
            });
        });
        
        function delCard(cardid) {
            $.ajax({
                url : 'delcard.php',
                type : 'post',
                dataType : 'json',
                data : {
                    cardid : cardid
                },
                success : function(data) {
                    alert(data.msg);
                    if(data.code === 0) {
                        location.reload();
                    }
                }
            });
        }
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h3>Danh sách mã thẻ đã thanh toán <small><?= $users->name ?> ( <?= date('m/Y') ?> )</small></h3>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <td class="col-fixed-10">STT</td>
                        <td>Phone</td>
                        <td>Kiểu</td>
                        <td>Pin</td>
                        <td>Seri</td>
                        <td>Mệnh giá</td>
                        <td>Thanh toán lúc</td>
                        <td>Trạng thái</td>
                        <td></td>
                    </tr>
                    <?php
                        $stt = 1;
                        foreach ($list as $obj) {
                            $tong += $obj->price;
                    ?>  
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>"><?= $phones->phone . "<br/>(Tạo lúc : " . date('d/m/Y - H:i:s', $phones->time) . ")" ?></td>
                        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>"><?= $phones->type == 0 ? 'Tra sau' : 'Tra truoc' ?></td>
                        <td><?= $obj->pin ?></td>
                        <td><?= $obj->seri ?></td>
                        <td><?= number_format($obj->price) ?></td>
                        <td><?= date('d/m/Y - H:i:s', $obj->time) ?></td>
                        <td><?= "<span class='text-success'>Hoàn thành</span>"; ?></td>
                        <td><button type="button" onclick="delCard(<?= $obj->getId() ?>)" class="btn btn-danger btn-xs">Xoá</button></td>
                    </tr>
                    <?php
                        }
                    ?>
                    <tr>
                        <td colspan="5">Tổng thanh toán</td>
                        <td class="text-success"><?= number_format($tong) ?></td>
                        <td colspan="3"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <button type="button" onclick="history.back()" class="btn btn-success pull-right">Quay lại</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <form method="post" action="#" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="phone_id" value="<?= $phone_id ?>">
                    </div>
                    <div class="form-group">
                        <label for="pin" class="sr-only">Pin</label>
                        <input type="text" class="form-control" name="pin" placeholder="Pin...">
                    </div>
                    <div class="form-group">
                        <label for="seri" class="sr-only">Seri</label>
                        <input type="text" class="form-control" name="seri" placeholder="Seri...">
                    </div>
                    <div class="form-group">
                        <label for="price" class="sr-only">Price</label>
                        <input type="text" class="form-control" name="price" placeholder="Price...">
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm Mới</button>
                  </form>
            </div>
        </div>
    </div>
</body>
</html>