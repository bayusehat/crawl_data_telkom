<?php
// include "conf/connection.php";
//include "conf/password.php";
//include "conf/cookie-mysimetri.php";
require 'init.php';
$db = DB::getInstance();

ini_set('max_execution_time', '0');

function login($url,$data) {
	$fp = fopen("cookie-mysimetri-durasi.txt", "w");
	fclose($fp);
	$login = curl_init();
	curl_setopt($login, CURLOPT_COOKIEJAR, "cookie-mysimetri-durasi.txt");
	curl_setopt($login, CURLOPT_COOKIEFILE, "cookie-mysimetri-durasi.txt");
	curl_setopt($login, CURLOPT_TIMEOUT, 40000);
	curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($login, CURLOPT_URL, $url);
	curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($login, CURLOPT_POST, TRUE);
	curl_setopt($login, CURLOPT_POSTFIELDS, $data);
	return curl_exec ($login);
	curl_close ($login);
	unset($login);    
}
login("https://mysimetri.telkom.co.id/user/authenticate","username=admin&password=010101&counter=0&type=Platinum");

// START VARIABLE UNTUK GRAB CRONTAB
$kemarin = date('Ymd',strtotime("-1 days"));
//echo $kemarin."<br>";

$url_req ="https://mysimetri.telkom.co.id/data/dataRekapDurasi";
$data_to_post = "from=".$kemarin."&to=".$kemarin."&c_reg=0202";
// END VARIABLE UNTUK GRAB CRONTAB

/*
// START VARIABLE UNTUK GRAB MANUAL
//$tgl_start = "20190102"; //ganti periode awal
//$tgl_end = "20190102"; //ganti periode akhir
$tgl = "20190311"; //ganti periode
//echo $tgl_start."<br>";
//echo $tgl_end."<br>";

$url_req ="https://mysimetri.telkom.co.id/data/dataRekapDurasi";
$data_to_post = "from=".$tgl."&to=".$tgl."&c_reg=0202";
// END VARIABLE UNTUK GRAB MANUAL
*/
$cr = curl_init();
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cr, CURLOPT_URL, $url_req);
curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($cr, CURLOPT_POST, true);
curl_setopt($cr, CURLOPT_COOKIEFILE, "cookie-mysimetri-durasi.txt");
//curl_setopt($cr, CURLOPT_HTTPHEADER, array($cookie));
curl_setopt($cr, CURLOPT_POST, $data_to_post);
curl_setopt($cr, CURLOPT_POSTFIELDS, $data_to_post);
$result = curl_exec($cr);
//echo $result;

$xyz = "<table> ";
$js = json_decode($result, true);
// echo '<pre>';
// print_r($js);
// echo '</pre>';
// echo '<table>';
foreach ($js['data'] as $dd) {
	$tanggal = date('Y',strtotime($kemarin)).'-'.date('m',strtotime($kemarin)).'-'.date('d',strtotime($kemarin));
	$periode = date('Ym',strtotime($kemarin));
	$que  = "
		INSERT INTO report_mysimetri_durasi_csr (mart_total,c_sublayanan,serve_time,c_layanan,mart_period,sub_total,l_sublayanan,l_layanan,l_plasa,l_witel,to_serve,jumlah_transaksi,average,sla,tanggal,periode,tgl_grab) 
		VALUES ('".$dd['mart_total']."','".$dd['c_sublayanan']."','".$dd['serve_time']."','".$dd['c_layanan']."','".date('Y-m-d',strtotime($dd['mart_period']))."','".$dd['sub_total']."','".$dd['l_sublayanan']."','".$dd['l_layanan']."','".$dd['l_plasa']."','".$dd['l_witel']."','".$dd['to_serve']."','".$dd['jumlah_transaksi']."','".$dd['average']."','".$dd['sla']."','".$tanggal."','".$periode."','".date('Y-m-d H:i:s')."')";
	$db->runQuery($que);
}
// echo '</table>';
// foreach ($js['data'] as $dt) 
// {
// 	$xyz .= "<tr> ";
// 	foreach ($dt as $value) 
// 	{
// 		$xyz .= "<td>$value</td> ";
// 	}
// 		$xyz .= "</tr> ";
// }
// $minus = (string)$i." days";
// $tanggal = date('d-m-Y',strtotime($minus));
// $i--;
// $xyz .= "</table>";
// //echo $xyz;

// $zyx = explode("</table>",$xyz);
// $baris= explode("</tr>",$zyx[0]);
// $panjang = count($baris)-2;
// //echo $table[1];
// echo "NO | MART TOTAL | C SUBLAYANAN | SERVE TIME | C LAYANAN | MART PERIOD | SUB TOTAL | L SUBLAYANAN | L LAYANAN | L PLASA | L WITEL | TO SERVE | JUMLAH TRANSAKSI | AVERAGE | SLA | TANGGAL | PERIODE<br>";
// for($j=0; $j<=$panjang; $j++)
// {
// 	$kolom = explode("</td>",$baris[$j]);
// 	$mart_total = trim(str_replace("<table>","",str_replace("<tr>","",str_replace("</td>","",str_replace("<td>","",$kolom[0])))));
// 	$csublayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[1])));
// 	$serve_time = trim(str_replace("</td>","",str_replace("<td>","",$kolom[2])));
// 	$clayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[3])));
// 	$mart_period = trim(str_replace("</td>","",str_replace("<td>","",$kolom[4])));
// 	$martx = "TO_DATE('".$mart_period."','DD/MM/YYYY')";
// 	$sub_total = trim(str_replace("</td>","",str_replace("<td>","",$kolom[5])));
// 	//$lsublayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[6])));
// 	$lsublayanan = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[6]))))));
// 	//$llayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[7])));
// 	$llayanan = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[7]))))));
// 	//$lplasa = trim(str_replace("</td>","",str_replace("<td>","",$kolom[8])));
// 	$lplasa = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[8]))))));
// 	//$lwitel = trim(str_replace("</td>","",str_replace("<td>","",$kolom[9])));
// 	$lwitel = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[9]))))));
// 	$to_serve = trim(str_replace("</td>","",str_replace("<td>","",$kolom[10])));
// 	$jml_transaksi = trim(str_replace("</td>","",str_replace("<td>","",$kolom[11])));
// 	$average = trim(str_replace("</td>","",str_replace("<td>","",$kolom[12])));
// 	$sla = trim(str_replace("</td>","",str_replace("<td>","",$kolom[13])));
// 	// TANGGAL UNTUK GRAB MANUAL
// 	//$tanggal = substr($tgl,6,2)."-".substr($tgl,4,2)."-".substr($tgl,0,4);
// 	// TANGGAL UNTUK GRAB CRONTAB
// 	$tanggal = substr($kemarin,6,2)."-".substr($kemarin,4,2)."-".substr($kemarin,0,4);
// 	$tglx = "TO_DATE('".$tanggal."','DD-MM-YYYY')";
// 	$periode = substr($tanggal,6,4).substr($tanggal,3,2);
	
// 	echo htmlentities($j + 1)." | ".htmlentities($mart_total)." | ".htmlentities($csublayanan)." | ".htmlentities($serve_time)." | ".htmlentities($clayanan)." | ".htmlentities($mart_period)." | ".htmlentities($sub_total)." | ".htmlentities($lsublayanan)." | ".htmlentities($llayanan)." | ".htmlentities($lplasa)." | ".htmlentities($lwitel)." | ".htmlentities($to_serve)." | ".htmlentities($jml_transaksi)." | ".htmlentities($average)." | ".htmlentities($sla)." | ".htmlentities($tanggal)." | ".htmlentities($periode)."<br>";

// 	$sql = "INSERT INTO REPORT_MYSIMETRI_DURASI_CSR (MART_TOTAL, C_SUBLAYANAN, SERVE_TIME, C_LAYANAN, MART_PERIOD, SUB_TOTAL, L_SUBLAYANAN, L_LAYANAN, L_PLASA, L_WITEL, TO_SERVE, JUMLAH_TRANSAKSI, AVERAGE, SLA, TANGGAL, PERIODE, TGL_GRAB) VALUES ('".$mart_total."', '".$csublayanan."', '".$serve_time."', '".$clayanan."', ".$martx.", '".$sub_total."', '".$lsublayanan."', '".$llayanan."', '".$lplasa."', '".$lwitel."', '".$to_serve."', '".$jml_transaksi."', '".$average."', '".$sla."', ".$tglx.", '".$periode."', SYSDATE)";
// 	//echo $sql."<br>";
// 	oci_execute(oci_parse($connect,$sql));	
// } 
?>