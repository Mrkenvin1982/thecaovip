<?php

function curl($url,$data){
    $fp = fopen("cookie.txt", "w");
    fclose($fp);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($curl, CURLOPT_TIMEOUT, 40000);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    ob_start();
    return curl_exec ($curl);
    ob_end_clean();
    curl_close ($curl);
    unset($curl);    
}   
function grab_page($site){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($ch, CURLOPT_URL, $site);
    ob_start();
    return curl_exec ($ch);
    ob_end_clean();
    curl_close ($ch);
}


$dom = new DOMDocument();
$page=grab_page('http://125.235.4.202:8982/passportv3/login?appCode=BulkCP&service=http%3A%2F%2F125.235.4.202%3A8983%2FBulkCP');
$page = str_replace('/passportv3/login', 'http://125.235.4.202:8982/passportv3/login', $page);
echo $page;
exit;
$dom->loadHTML();


# Iterate over all the <input> tags
$lt = '';
foreach($dom->getElementsByTagName('input') as $input) {
        # Show the attribute value
     if ($input->getAttribute('name') == 'lt') {
        $lt= $input->getAttribute('value');
      }
}
$data =array('username'=>'bulk_vnet','password'=>'vnet@1234','lt'=>$lt,'_eventId'=>'submit','submit'=>'ĐĂNG NHẬP','loginCount'=> '');
echo http_build_query($data);
curl('http://125.235.4.202:8982/passportv3/login?appCode=BulkCP&service=http%3A%2F%2F125.235.4.202%3A8983%2FBulkCP',$data);
echo grab_page('http://125.235.4.202:8983/BulkCP');

exit;


$page = str_replace('/Home/GenerateCaptcha', 'http://naptien.vinaphone.com.vn/Home/GenerateCaptcha', grab_page('http://naptien.vinaphone.com.vn/Home/AddCard'));


 
$page = str_replace('href="', 'href="http://naptien.vinaphone.com.vn', $page);


$page = str_replace('cache: false,', 'cache: false,beforeSend: function (data) {data.setRequestHeader("Access-Control-Allow-Origin", "*"); },', $page);

$page = str_replace('/node_modules/bootstrap/dist/js/bootstrap.min.js', 'http://naptien.vinaphone.com.vn/node_modules/bootstrap/dist/js/bootstrap.min.js', $page);



echo $page;
/*echo  curl('http://naptien.vinaphone.com.vn/Home/AddPrepaid','PhoneNum=01232306430&MaThe=34563456345345345&Answer=MTB12m');*/
 ?>