<?php 
date_default_timezone_set("Asia/Jakarta");
set_time_limit(0);
require 'init.php';

$db = DB::getInstance();
$query = "select cookie,site from tbl_cookie where site = 'provin'";
$get = $db->runQuery($query)->fetchAll();
$cookie = $get[0]['cookie'];

for ($i=1; $i <= 902; $i++) { 

$url_req = "https://tr4.telkom.co.id/provin/admin/provinas/kw1_usage_detail?&kawasan=5&page=$i";
$cr = curl_init();
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cr, CURLOPT_URL, $url_req);
curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($cr, CURLOPT_POST, true);
curl_setopt($cr, CURLOPT_HTTPHEADER, array($cookie));
curl_setopt($cr, CURLOPT_VERBOSE, true);
$result = curl_exec($cr);
curl_close($cr);
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($result);
libxml_clear_errors();
$xpath = new DOMXpath($dom);
$data = array();
// get all table rows and rows which are not headers
$table_rows = $xpath->query('//tr');
foreach($table_rows as $row => $tr) {
    foreach($tr->childNodes as $td) {
        echo $td->nodeValue;
        $data[$row][] = preg_replace('~[\r\n]+~', '', trim($td->nodeValue));
    }
    $data[$row] = array_values(array_filter($data[$row]));
}
// echo '<pre>';
// print_r($data);
// echo '</pre>';
// exit;
foreach ($data as $ic => $v) {
if ($ic != 0) {
    $query = "insert into cc_provin(witel, datel, sto, nd, no, billing, tinjut, kategori, pl, paket, usage_inet, ont_status, status, follow_up, pelanggan, page) 
    values(
        '".str_replace('Regional 5', '', $v[0])."',
        '".substr(str_replace('Datel ', '', $v[1]), 0, -3)."',
        '".substr(str_replace('Datel ', '', $v[1]), -3)."',
        '".substr($v[3], 0, 12)."',
        '".substr($v[3], 12, strlen($v[3]))."',
        '".$v[4]."',
        '".$v[5]."',
        '".substr($v[6], 0, -2)."',
        '".substr($v[6], -2)."',
        '".$v[7]."',
        '".$v[8]."',
        '".$v[9]."',
        '".$v[10]."',
        '".$v[11]."',
        '".str_replace("'", '', $v[2])."',
        '".$i."'
        )";
    $db->runQuery($query);
        }
    }
}
?>