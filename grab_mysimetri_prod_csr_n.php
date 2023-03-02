<?php
$start = microtime(true);
set_time_limit(0);
date_default_timezone_set("Asia/Jakarta");
require 'init.php';

$db = DB::getInstance();

function login($url,$data) {
	$login = curl_init();
	curl_setopt($login, CURLOPT_TIMEOUT, 40000);
	curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($login, CURLOPT_URL, $url);
	curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($login, CURLOPT_POST, TRUE);
    curl_setopt($login, CURLOPT_POSTFIELDS, $data);
    curl_setopt($login, CURLOPT_HEADER, 1);
	ob_start();
    $result = curl_exec ($login);
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);        // get cookie
    $cookies = 'Cookie: '.$matches[1][1].'; '.$matches[1][0];
    return $cookies;
	ob_end_clean();
	curl_close ($login);
    unset($login);    
}
$info = login("https://mysimetri.telkom.co.id/user/authenticate","username=admin&password=010101&counter=0&type=Platinum");

//Set Cookie DB
$query_update = "update tbl_cookie set cookie = '$info', input_date='".date('Y-m-d H:i:s')."' where site = 'mysimetri-prod'";
$db->runQuery($query_update);

//Get Cookie DB
$query = "select cookie,site from tbl_cookie where site = 'mysimetri-prod'";
$get = $db->runQuery($query)->fetchAll();
$cookie = $get[0]['cookie'];

$kemarin = date('Ymd',strtotime("-1 days"));
$periode = date('Ym',strtotime($kemarin));

$url_req ="https://mysimetri.telkom.co.id/data/productivity";
$data_to_post = "from=".$kemarin."&to=".$kemarin."&c_reg=0202&c_witel=0&c_plasa=0";

$cr = curl_init();
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cr, CURLOPT_URL, $url_req);
curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($cr, CURLOPT_POST, true);
curl_setopt($cr, CURLOPT_HTTPHEADER, array($cookie));
curl_setopt($cr, CURLOPT_POSTFIELDS, $data_to_post);
curl_setopt($cr, CURLOPT_VERBOSE, true);
$result = curl_exec($cr);

$data = json_decode($result,true);

foreach ($data['data'] as $i => $d) {
    // echo '<pre>';
    // print_r($d);
    // echo '</pre>';
    $l_user = str_replace("'","",$d['l_user']);
    $query_insert = "INSERT INTO REPORT_MYSIMETRI_PROD_CSR (C_REG,L_REG,C_WITEL,L_WITEL,C_PLASA,L_PLASA,C_USER,L_USER,ACCEPTED,FINISHED,TRANSFERRED,PERSENTASI_SELESAI,PENDING,TANGGAL,PERIODE,TGL_GRAB) VALUES (
        '".$d['c_reg']."',
        '".$d['l_reg']."',
        '".$d['c_witel']."',
        '".$d['l_witel']."',
        '".$d['c_plasa']."',
        '".$d['l_plasa']."',
        '".$d['c_user']."',
        '".$l_user."',
        '".$d['accepted']."',
        '".$d['finished']."',
        '".$d['transferred']."',
        '".$d['persentasi_selesai']."',
        '".$d['pending']."',
        '".date('Y-m-d')."',
        '".$periode."',
        '".date('Y-m-d H:i:s')."'
    )";
    $db->runQuery($query_insert);
}

echo 'Grab Sukses!';
?>