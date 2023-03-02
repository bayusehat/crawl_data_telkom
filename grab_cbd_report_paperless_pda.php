<?php
$start = microtime(true);
set_time_limit(0);
date_default_timezone_set("Asia/Jakarta");
require 'init.php';

$db = DB::getInstance();
//Get Cookie DB
$query = "select cookie,site from tbl_cookie where site = 'CBD'";
$get = $db->runQuery($query)->fetchAll();
$cookie = $get[0]['cookie'];

$per = isset($_GET['periode']) ? $_GET['periode'] : date('Ym');
// $periode = date('Ym',strtotime($kemarin));
$periode = $per;
//getrange
$first = date('Ym01',strtotime($periode));
$end = date('Ymd',strtotime($periode));

//Delete all record by periode
$query_delete = "delete from cbd_report_paperless where periode = '$periode' and jenis_kontrak = 'PDA'";
$db->runQuery($query_delete);

//COMPLETE
$url_complete = "https://dashboard.telkom.co.id/cbd/paperless/qualityindihomedetilajax/data/all?PERIODE=$periode&KAWASAN=ALL&WITEL=ALL&SEGMEN=CONS&CHANNEL=ALL&OPERASI=2&keterangan=COMPLETE&header=01&sEcho=1&iColumns=20&sColumns=&iDisplayStart=0&iDisplayLength=200000&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&mDataProp_6=6&mDataProp_7=7&mDataProp_8=8&mDataProp_9=9&mDataProp_10=10&mDataProp_11=11&mDataProp_12=12&mDataProp_13=13&mDataProp_14=14&mDataProp_15=15&mDataProp_16=16&mDataProp_17=17&mDataProp_18=18&mDataProp_19=19&sSearch=&bRegex=false&sSearch_0=&bRegex_0=false&bSearchable_0=true&sSearch_1=&bRegex_1=false&bSearchable_1=true&sSearch_2=&bRegex_2=false&bSearchable_2=true&sSearch_3=&bRegex_3=false&bSearchable_3=true&sSearch_4=&bRegex_4=false&bSearchable_4=true&sSearch_5=&bRegex_5=false&bSearchable_5=true&sSearch_6=&bRegex_6=false&bSearchable_6=true&sSearch_7=&bRegex_7=false&bSearchable_7=true&sSearch_8=&bRegex_8=false&bSearchable_8=true&sSearch_9=&bRegex_9=false&bSearchable_9=true&sSearch_10=&bRegex_10=false&bSearchable_10=true&sSearch_11=&bRegex_11=false&bSearchable_11=true&sSearch_12=&bRegex_12=false&bSearchable_12=true&sSearch_13=&bRegex_13=false&bSearchable_13=true&sSearch_14=&bRegex_14=false&bSearchable_14=true&sSearch_15=&bRegex_15=false&bSearchable_15=true&sSearch_16=&bRegex_16=false&bSearchable_16=true&sSearch_17=&bRegex_17=false&bSearchable_17=true&sSearch_18=&bRegex_18=false&bSearchable_18=true&sSearch_19=&bRegex_19=false&bSearchable_19=true&_=1613703952083";

$cr = curl_init();
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cr, CURLOPT_URL, $url_complete);
curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($cr, CURLOPT_HTTPHEADER, array($cookie));
curl_setopt($cr, CURLOPT_VERBOSE, true);
$result = curl_exec($cr);
$result = json_decode($result,true);

echo 'COMPLETE STATUS '.count($result['aaData']).' Rows get <br>';

foreach($result['aaData'] as $rc){
    $q_cwitel = "select * from p_m_wilayah where witel_cbd = '$rc[7]'";
    $cwitel = $db->runQuery($q_cwitel)->fetchAll();
    $cw = $cwitel[0]['cwitel'];
    $nama = str_replace("'","",$rc[10]);
    $q_insert_rc = "insert into cbd_report_paperless (data1,data2,data3,data4,tanggal,cwitel,datel,sto,nama,email,no_hp,tanggal2,url_kontrak,url_ktp,url_ttd,url_selfie,jenis_kontrak,channel,periode,status) 
        values 
        ('$rc[1]',
        '$rc[2]','$rc[3]',
        '$rc[4]','$rc[5]',
        $cw,'$rc[8]',
        '$rc[9]','$nama',
        '$rc[11]','$rc[12]',
        '$rc[13]','$rc[14]',
        '$rc[15]','$rc[16]',
        '$rc[17]','$rc[18]',
        '$rc[19]','$periode','COMPLETE')";
    $db->runQuery($q_insert_rc);
}

echo '------- <br>';
echo 'OTHER<br>';
echo '------- <br>';

// //NOT COMPLETE
$url_not_complete = "https://dashboard.telkom.co.id/cbd/paperless/qualityindihomedetilajax/data/all?PERIODE=$periode&KAWASAN=ALL&WITEL=ALL&SEGMEN=CONS&CHANNEL=ALL&OPERASI=2&keterangan=NOT%20COMPLETE&header=01&sEcho=1&iColumns=20&sColumns=&iDisplayStart=0&iDisplayLength=200000&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&mDataProp_6=6&mDataProp_7=7&mDataProp_8=8&mDataProp_9=9&mDataProp_10=10&mDataProp_11=11&mDataProp_12=12&mDataProp_13=13&mDataProp_14=14&mDataProp_15=15&mDataProp_16=16&mDataProp_17=17&mDataProp_18=18&mDataProp_19=19&sSearch=&bRegex=false&sSearch_0=&bRegex_0=false&bSearchable_0=true&sSearch_1=&bRegex_1=false&bSearchable_1=true&sSearch_2=&bRegex_2=false&bSearchable_2=true&sSearch_3=&bRegex_3=false&bSearchable_3=true&sSearch_4=&bRegex_4=false&bSearchable_4=true&sSearch_5=&bRegex_5=false&bSearchable_5=true&sSearch_6=&bRegex_6=false&bSearchable_6=true&sSearch_7=&bRegex_7=false&bSearchable_7=true&sSearch_8=&bRegex_8=false&bSearchable_8=true&sSearch_9=&bRegex_9=false&bSearchable_9=true&sSearch_10=&bRegex_10=false&bSearchable_10=true&sSearch_11=&bRegex_11=false&bSearchable_11=true&sSearch_12=&bRegex_12=false&bSearchable_12=true&sSearch_13=&bRegex_13=false&bSearchable_13=true&sSearch_14=&bRegex_14=false&bSearchable_14=true&sSearch_15=&bRegex_15=false&bSearchable_15=true&sSearch_16=&bRegex_16=false&bSearchable_16=true&sSearch_17=&bRegex_17=false&bSearchable_17=true&sSearch_18=&bRegex_18=false&bSearchable_18=true&sSearch_19=&bRegex_19=false&bSearchable_19=true&_=1613703952084";

$cn = curl_init();
curl_setopt($cn, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cn, CURLOPT_URL, $url_not_complete);
curl_setopt($cn, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($cn, CURLOPT_HTTPHEADER, array($cookie));
curl_setopt($cn, CURLOPT_VERBOSE, true);
$result_cn = curl_exec($cn);
$result_cn = json_decode($result_cn,true);

echo 'NOT COMPLETE STATUS '.count($result_cn['aaData']).' Rows get <br>';

foreach($result_cn['aaData'] as $rn){
    $q_cwitel_rn = "select * from p_m_wilayah where witel_cbd = '$rn[7]'";
    $cwitel_rn = $db->runQuery($q_cwitel_rn)->fetchAll();
    $cwitel_rn = $cwitel_rn[0]['cwitel'];
    $nama_rn = str_replace("'","",$rn[10]);
    $q_insert_rn = "insert into cbd_report_paperless (data1,data2,data3,data4,tanggal,cwitel,datel,sto,nama,email,no_hp,tanggal2,url_kontrak,url_ktp,url_ttd,url_selfie,jenis_kontrak,channel,periode,status) 
        values ('$rn[1]','$rn[2]',
        '$rn[3]','$rn[4]',
        '$rn[5]',$cwitel_rn,
        '$rn[8]','$rn[9]',
        '$nama_rn','$rn[11]',
        '$rn[12]','$rn[13]',
        '$rn[14]','$rn[15]',
        '$rn[16]','$rn[17]',
        '$rn[18]','$rn[19]',
        '$periode','NOT COMPLETE')";
    $db->runQuery($q_insert_rn);
}

echo 'GRAB SELESAI!';
// echo '<pre>';
// print_r($result_cn);
// echo '</pre>';
// echo count($result_cn[0]).'<br>';
?>