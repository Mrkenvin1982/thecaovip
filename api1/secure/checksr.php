<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
ini_set('max_execution_time', 600);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

function getDbConnector($host, $username, $password, $dbName){
  $connect = new mysqli($host, $username, $password, $dbName);

  if ($connect->connect_errno) { 
      echo "Failed to connect to MySQL: (" . $connect->connect_errno . ") " . $connect->connect_error; 
      return null;
  }
  mysqli_set_charset($connect,"utf8");
  return $connect;
}

function checkCard($serial){
  $postData = array(
    'card_serial' => $serial,
    'partner_id' => 1,
    'type' => 5,
    'api_key' => "iOv7bCTy4fla7Ms4Qr6QF"
  );
  $fieldsString = '';
  foreach($postData as $key=>$value) {
     $fieldsString .= $key.'='.$value.'&'; 
  }
  $fieldsString = rtrim($fieldsString,'&');
  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch, CURLOPT_URL, 'http://103.68.81.141:7000/checkCard');
  curl_setopt($ch, CURLOPT_POST, count($postData));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  return curl_exec($ch);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['randcheck']) && $_POST['randcheck']==$_SESSION['rand'] && 
  isset($_POST['serial']) && !empty($_POST['serial'])){
  $serial = trim($_POST['serial']);
  $checkCardResponse = checkCard($serial);
  $checkCardJson = json_decode($checkCardResponse, TRUE);
  $vtResponse = json_decode(base64_decode($checkCardJson['msg']), TRUE);
  if(empty($vtResponse)){
    $errorMsg = $checkCardJson['msg'];
  }
}

$connect = getDbConnector('103.68.81.141', 'root', 'Ut8j8OfsyCfRH8@@!', 'vietteltools');

if(isset($_POST['fromDate']) && !empty($_POST['fromDate'])){
  $date = DateTime::createFromFormat('d-m-Y', $_POST['fromDate']);
  $fromDate = $date->format("Y-m-d");
}
else{
  $date = date_create(date("Y-m-d"));
  $fromDate = $date->format("Y-m-d");
}

if(isset($_POST['toDate']) && !empty($_POST['toDate'])){
  $date = DateTime::createFromFormat('d-m-Y', $_POST['toDate']);
  $toDate = $date->format("Y-m-d");
}
else{
  $date = date_create(date("Y-m-d"));
  $toDate = $date->format("Y-m-d");
}

$checkLogs = $connect->query('SELECT * FROM check_logs WHERE created_date>"'.$fromDate.' 00:00:00" AND created_date<"'.$toDate.' 23:59:59" ORDER BY id DESC');
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Kiểm tra thẻ cào Viettel</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.vi.min.js"></script>
    </head>

    <body>
        <div class="container text-center">
            <h2>Serial thẻ Viettel có 11 hoặc 14 số</h2>
            <hr>
            <form method="post">
            <?php
              $rand=rand();
              $_SESSION['rand']=$rand;
            ?>
                <div class="row form-group">
                    <div class="col-md-4 col-xs-6 col-md-offset-2">
                        <label class="col-md-4 col-xs-4 custom-label">
                          Từ: 
                      </label>
                        <div class="col-md-8 col-xs-8 input-group date datepicker fromDate" data-provide="datepicker">
                            <input type="text" class="form-control" name="fromDate">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <label class="col-md-4 col-xs-4 custom-label">
                          Đến: 
                      </label>
                        <div class="col-md-8 col-xs-8 input-group date datepicker toDate" data-provide="datepicker">
                            <input type="text" class="form-control" name="toDate">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                  <label class="sr-only" for="serial">Serial:</label>
                  <input type="text" class="form-control center-block" id="serial" placeholder="Nhập serial" name="serial" style="max-width: 250px;">
                </div>
                <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
                <button type="submit" class="btn btn-primary">Kiểm Tra</button>
            </form>
            <?php
    if(isset($serial) && !empty($serial)){
      echo '<hr><h4>Serial: '. $serial.'</h4>';
    }
    if(isset($errorMsg)){
      echo '<hr><p class="bg-danger">'.$errorMsg.'</p>';
    }
    else if(isset($vtResponse) && !empty($vtResponse)){
      if($vtResponse['errorCode'] != '0'){
        echo '<hr><p class="bg-danger">'.$vtResponse['message'].'</p>';
      }
      else{
        echo '<hr><p class="bg-success">'.$vtResponse['message'].'</p>';
        if(isset($vtResponse['data']) && !empty($vtResponse['data'])){
          echo '<table class="table table-bordered"><tbody>';
          $amount = $vtResponse['data']['amount'];
          $cardState = $vtResponse['data']['dateUse'];
          $expireDate = $vtResponse['data']['datExp'];
          $topupPhone = $vtResponse['data']['isdn'];
          if(empty($cardState) || empty($topupPhone)){
            echo '<tr><td>Mệnh giá</td><td>'. number_format($amount) .'đ</td></tr>';
            echo '<tr><td>Trạng thái thẻ</td><td>Thẻ chưa sử dụng</td></tr>';
            echo '<tr><td>Ngày hết hạn</td><td>'. $expireDate .'</td></tr>';
          }
          else{
            echo '<tr><td>Mệnh giá</td><td>'. number_format($amount) .'đ</td></tr>';
            echo '<tr><td>Ngày sử dụng</td><td>'. $cardState .'</td></tr>';
            echo '<tr><td>Ngày hết hạn</td><td>'. $expireDate .'</td></tr>';
            echo '<tr><td>Nạp vào</td><td>'. $topupPhone .'</td></tr>';
          }
          echo '</tbody></table>';
        }
      }
    }
  ?>
        <hr>
        <?php if(isset($checkLogs) && $checkLogs->num_rows > 0){?>
        <h3>Lịch sử check thẻ</h3>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>STT</th>
              <th>Serial</th>
              <th>Mệnh Giá</th>
              <th>Trạng thái</th>
              <th>Data</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $count = 1;
          while ($row = $checkLogs->fetch_assoc()){
            $dataArr = json_decode($row['data'], true);
            if(isset($dataArr) && !empty($dataArr)){
              $data = json_encode($dataArr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            }
            else{
              $data = $row['data'];
            }
            if($row['state'] == 0){
                echo '<tr class="success">';
            }
            else{
                echo '<tr class="danger">';
            }
            echo '<td>'.$count.'</td>';
            echo '<td>'.$row['card_serial'].'</td>';
            echo '<td>'.number_format($row['amount']).'</td>';
            echo '<td>'.$row['msg'].'</td>';
            echo '<td>'.$data.'</td>';
            echo '</tr>';
            $count++;
          }
          ?>
          </tbody>
        <?php }?>
      </div>
        <script>
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            $('.fromDate').datepicker('update', '<?php echo DateTime::createFromFormat('Y-m-d', $fromDate)->format('d-m-Y');?>');
            $('.toDate').datepicker('update', '<?php echo DateTime::createFromFormat('Y-m-d', $toDate)->format('d-m-Y');?>');
        </script>
  </body>
</html>