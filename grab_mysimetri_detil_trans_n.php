<?php
$start = microtime(true);
set_time_limit(0);
date_default_timezone_set("Asia/Jakarta");
require 'init.php';

$db = DB::getInstance();

//Get Cookie DB
$query = "select cookie,site from tbl_cookie where site = 'mysimetri'";
$get = $db->runQuery($query)->fetchAll();
$cookie = $get[0]['cookie'];

$per = isset($_GET['periode']) ? $_GET['periode'] : date('Ym');
// $periode = date('Ym',strtotime($kemarin));
$periode = $per;
//getrange
// $first = date('Ym01',strtotime($periode));
// $end = date('Ymd',strtotime($periode));
$first = '20220101';
$end = '20220131';

$url_req = "https://mysimetri.telkom.co.id/data/dataTransdetail";
$data_to_post = "draw=1&columns%5B0%5D%5Bdata%5D=WAKTU_INPUT&columns%5B0%5D%5Bname%5D=waktu_input&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=WAKTU_TERIMA&columns%5B1%5D%5Bname%5D=waktu_terima&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=WAKTU_SELESAI&columns%5B2%5D%5Bname%5D=waktu_selesai&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=L_USER&columns%5B3%5D%5Bname%5D=l_user&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=C_USER&columns%5B4%5D%5Bname%5D=c_user&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=L_PLASA&columns%5B5%5D%5Bname%5D=l_plasa&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=NO_ANTRIAN&columns%5B6%5D%5Bname%5D=announce_call&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=VISIT_ND&columns%5B7%5D%5Bname%5D=visit_nd&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=true&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=VISIT_MSISDN&columns%5B8%5D%5Bname%5D=visit_msisdn&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=true&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=WAKTU_LAYANAN&columns%5B9%5D%5Bname%5D=waktu_layanan&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=10&columns%5B10%5D%5Bname%5D=trans1&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=false&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=11&columns%5B11%5D%5Bname%5D=trans2&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=false&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B12%5D%5Bdata%5D=12&columns%5B12%5D%5Bname%5D=trans3&columns%5B12%5D%5Bsearchable%5D=true&columns%5B12%5D%5Borderable%5D=false&columns%5B12%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B12%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B13%5D%5Bdata%5D=13&columns%5B13%5D%5Bname%5D=trans4&columns%5B13%5D%5Bsearchable%5D=true&columns%5B13%5D%5Borderable%5D=false&columns%5B13%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B13%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=50000&search%5Bvalue%5D=&search%5Bregex%5D=false&from=".$first."&to=".$end."&prioritas=0&c_plasa=0&c_reg=0202&c_witel=0";
echo $cookie;

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

echo '<pre>';
print_r($data);
echo '</pre>';
exit;

foreach ($data['data'] as $i => $d) {
        if(count($d['DETAIL']) > 0){
            $detail = $d['DETAIL'][0]['c_sublayanan'].':'.$d['DETAIL'][0]['l_sublayanan'];
        }else{
            $detail = '';
        }
    $l_user = str_replace("'","",$d['L_USER']);
    $query_insert = "INSERT INTO REPORT_MYSIMETRI_DETIL_TRANS (WAKTU_INPUT,WAKTU_TERIMA,WAKTU_SELESAI,C_USER,C_PLASA,C_ANTRIAN,C_SUBLAYANAN,PELANGGAN,NO_ANTRIAN,STATUS,L_USER,VISIT_MSISDN,VISIT_ND,L_LAYANAN,L_PLASA,L_SUBLAYANAN,C_REG,C_WITEL,RNUM,WAKTU_LAYANAN,LAYANAN_2,LAYANAN_3,DETAIL,TANGGAL,PERIODE,TGL_GRAB) VALUES (
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
        '".$d['L_LAYANAN']."',
        '".$d['L_PLASA']."',
        '".$d['L_SUBLAYANAN']."',
        '".$d['C_REG']."',
        '".$d['C_WITEL']."',
        '".$d['RNUM']."',
        '".$d['WAKTU_LAYANAN']."',
        '".$d['LAYANAN_2']."',
        '".$d['LAYANAN_3']."',
        '".$detail."',
        '".date('Y-m-d')."',
        '".$periode."',
        '".date('Y-m-d H:i:s')."'
    )";

    $db->runQuery($query_insert);
}

echo 'Grab Sukses!';
?>