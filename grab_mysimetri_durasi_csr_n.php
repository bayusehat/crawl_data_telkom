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
$first = date('Ym01',strtotime($periode));
$end = date('Ymd',strtotime($periode));

echo $periode;

$url_req ="https://mysimetri.telkom.co.id/data/dataRekapDurasi";
$data_to_post = "from=$first&to=$end&prioritas=&c_plasa=0&c_reg=0202&c_witel=0";

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
    $source = explode('/',$d['mart_period']);
    $mart_period = $source[2].'-'.$source[1].'-'.$source[0];
    $query_insert = "INSERT INTO REPORT_MYSIMETRI_DURASI_CSR (MART_TOTAL,C_SUBLAYANAN,SERVE_TIME,C_LAYANAN,MART_PERIOD,SUB_TOTAL,L_SUBLAYANAN,L_LAYANAN,L_PLASA,L_WITEL,TO_SERVE,JUMLAH_TRANSAKSI,AVERAGE,SLA,TANGGAL,PERIODE,TGL_GRAB) VALUES (
        '".$d['mart_total']."',
        '".$d['c_sublayanan']."',
        '".$d['serve_time']."',
        '".$d['c_layanan']."',
        '".date('Y-m-d',strtotime($mart_period))."',
        '".$d['sub_total']."',
        '".$d['l_sublayanan']."',
        '".$d['l_layanan']."',
        '".$d['l_plasa']."',
        '".$d['l_witel']."',
        '".$d['to_serve']."',
        '".$d['jumlah_transaksi']."',
        '".$d['average']."',
        '".$d['sla']."',
        '".date('Y-m-d H:i:s')."',
        '".$periode."',
        '".date('Y-m-d H:i:s')."'
    )";

    $db->runQuery($query_insert);
}

echo 'Grab Sukses!';
?>