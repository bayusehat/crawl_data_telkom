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
$query_update = "update tbl_cookie set cookie = '$info', input_date='".date('Y-m-d H:i:s')."' where site = 'mysimetri-feedback'";
$db->runQuery($query_update);

//Get Cookie DB
$query = "select cookie,site from tbl_cookie where site = 'mysimetri-feedback'";
$get = $db->runQuery($query)->fetchAll();
$cookie = $get[0]['cookie'];

$kemarin = date('Ymd',strtotime("-1 days"));
$periode = date('Ym',strtotime($kemarin));

$url_req ="https://mysimetri.telkom.co.id/data/datadetailExperience";
$data_to_post = "draw=1&columns%5B0%5D%5Bdata%5D=WAKTU_INPUT&columns%5B0%5D%5Bname%5D=WAKTU_INPUT&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=L_USER&columns%5B1%5D%5Bname%5D=L_USER&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=L_LAYANAN&columns%5B2%5D%5Bname%5D=L_LAYANAN&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=L_SUBLAYANAN&columns%5B3%5D%5Bname%5D=L_SUBLAYANAN&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=FEED_AWAL&columns%5B4%5D%5Bname%5D=DATA3&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=FEED_AKHIR&columns%5B5%5D%5Bname%5D=DATA7&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=10000&search%5Bvalue%5D=&search%5Bregex%5D=false&from=".$kemarin."&to=".$kemarin."&prioritas=&c_plasa=0&c_reg=0202&c_witel=0";

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
    $l_user = str_replace("'","",$d['L_USER']);
    $query_insert = "INSERT INTO REPORT_MYSIMETRI_FEEDBACK (WAKTU_INPUT,WAKTU_TERIMA,WAKTU_SELESAI,C_USER,C_PLASA,C_ANTRIAN,C_SUBLAYANAN,PELANGGAN,NO_ANTRIAN,STATUS,L_USER,VISIT_MSISDN,VISIT_ND,DATA3,DATA7,L_LAYANAN,L_PLASA,L_SUBLAYANAN,C_REG,C_WITEL,L_WITEL,RNUM,FEED_AWAL,FEED_AKHIR,TGL_GRAB,PERIODE,TANGGAL) VALUES (
        '".date('Y-m-d H:i:s',strtotime($d['WAKTU_INPUT']))."',
        '".date('Y-m-d H:i:s',strtotime($d['WAKTU_TERIMA']))."',
        '".date('Y-m-d H:i:s',strtotime($d['WAKTU_SELESAI']))."',
        '".$d['C_USER']."',
        '".$d['C_PLASA']."',
        '".$d['C_ANTRIAN']."',
        '".$d['C_SUBLAYANAN']."',
        '".$d['PELANGGAN']."',
        '".$d['NO_ANTRIAN']."',
        '".$d['STATUS']."',
        '".$l_user."',
        '".$d['VISIT_MSISDN']."',
        '".$d['VISIT_ND']."',
        '".$d['DATA3']."',
        '".$d['DATA7']."',
        '".$d['L_LAYANAN']."',
        '".$d['L_PLASA']."',
        '".$d['L_SUBLAYANAN']."',
        '".$d['C_REG']."',
        '".$d['C_WITEL']."',
        '".$d['L_WITEL']."',
        '".$d['RNUM']."',
        '".$d['FEED_AWAL']."',
        '".$d['FEED_AKHIR']."',
        '".date('Y-m-d H:i:s')."',
        '".$periode."',
        '".date('Y-m-d')."'
    )";

    $db->runQuery($query_insert);
}
echo 'Grab Sukses!';
?>