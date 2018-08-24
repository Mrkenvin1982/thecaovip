<?php 
include 'config.php';
 ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Thẻ Cào VIP</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
      <link href="./node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<!--       <link href="/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/> -->

      
      <link href="css/style.css?v4" rel="stylesheet">

      <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <script type="text/javascript" src="./node_modules/jquery/dist/jquery.min.js"></script>
      <script type="text/javascript" src="./node_modules/jquery-form/dist/jquery.form.min.js"></script>
      <script type="text/javascript" src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>

<!--       <script type="text/javascript" src="/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> -->

   </head>
   <body>
    <style type="text/css">
      .content-tb{
      text-align: center;
      }
      .content-tb .col-md-6{
        float: none;
        margin: 0 auto;
      }
    </style>

     <div class ="content-tb">
          <?php 
    $models_phones = new Models_Phones();
    $card_type_id=1;
  if ($_GET['card_type_id']) {
      $card_type_id =intval($_GET['card_type_id']);
  }
    $arr_type = array(
    1 => 'Viettel',
    2 => 'Mobiphone',
    3 => 'Vinaphone'
);
    if(!in_array($card_type_id, array_keys($arr_type))) {
    echo "---";
    exit();
}
$total = $models_phones->getSumByColumn('canthanhtoan', array('status' => 1, 'loai' => $card_type_id, 'canthanhtoan' => array(0 , '>=')));
$max = $models_phones->getMaxByColumn('canthanhtoan', array('status' => 1, 'loai' => $card_type_id, 'canthanhtoan' => array(0 , '>=')));
              $models_tb = new Models_Notification();
              $obj = $models_tb->getObjectByCondition('',array('orders'=>1,'status'=>1));
              $content=$obj->content;
              $content=str_replace('{nhamang}', $arr_type[$card_type_id], str_replace('{total}', number_format($total), str_replace('{max}', number_format($max), $content)));
              echo $content;

          ?>
     </div>
   </body>
</html>   