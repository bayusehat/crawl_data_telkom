<?php
$start = microtime(true);
set_time_limit(0);
require 'init.php';
$db = DB::getInstance();

//GANTI PERIODE DAN VARIABLE TANGGAL DI BAWAH

function login($url,$data) {
	$fp = fopen("cookie-mysimetri.txt", "w");
	fclose($fp);
	$login = curl_init();
	curl_setopt($login, CURLOPT_COOKIEJAR, "cookie-mysimetri.txt");
	curl_setopt($login, CURLOPT_COOKIEFILE, "cookie-mysimetri.txt");
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
$kemarin = date('Ymd');
//echo $kemarin."<br>";

$url_req ="https://mysimetri.telkom.co.id/data/datadetailExperience";
$data_to_post = "draw=1&columns%5B0%5D%5Bdata%5D=WAKTU_INPUT&columns%5B0%5D%5Bname%5D=WAKTU_INPUT&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=L_USER&columns%5B1%5D%5Bname%5D=L_USER&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=L_LAYANAN&columns%5B2%5D%5Bname%5D=L_LAYANAN&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=L_SUBLAYANAN&columns%5B3%5D%5Bname%5D=L_SUBLAYANAN&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=FEED_AWAL&columns%5B4%5D%5Bname%5D=DATA3&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=FEED_AKHIR&columns%5B5%5D%5Bname%5D=DATA7&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=100000&search%5Bvalue%5D=&search%5Bregex%5D=false&from=".$kemarin."&to=".$kemarin."&c_reg=0202";
// END VARIABLE UNTUK GRAB CRONTAB
$cr = curl_init();
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cr, CURLOPT_URL, $url_req);
curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($cr, CURLOPT_POST, true);
curl_setopt($cr, CURLOPT_COOKIEFILE, "cookie-mysimetri.txt");
curl_setopt($cr, CURLOPT_POSTFIELDS, $data_to_post);
$result = curl_exec($cr);
$js = json_decode($result,true);
// echo '<pre>';
// print_r($js);
// echo '</pre>';

if(empty($js['data'])){
	echo 'No data Found!';
}else{
	foreach ($js['data'] as $v) {
		$l_user = str_replace("'","",$v['L_USER']);
		$pelanggan = str_replace("'","",$v['PELANGGAN']);
		if($v['WAKTU_SELESAI'] == ''){
			$ws = '1970-01-01 00:00:00';
		}else{
			$ws = $v['WAKTU_SELESAI'];
		}
		if($v['WAKTU_INPUT'] == ''){
			$wi = '1970-01-01 00:00:00';
		}else{
			$wi = $v['WAKTU_INPUT'];
		}
		if($v['WAKTU_TERIMA'] == ''){
			$wt = '1970-01-01 00:00:00';
		}else{
			$wt = $v['WAKTU_TERIMA'];
		}
		$sql = "
			INSERT INTO report_mysimetri_feedback_test(waktu_input,waktu_terima,waktu_selesai,c_user,c_plasa,c_antrian,c_sublayanan,pelanggan,no_antrian,status,l_user,visit_msisdn,visit_nd,data3,data7,l_layanan,l_plasa,l_sublayanan,c_reg,c_witel,l_witel,rnum,feed_awal,feed_akhir,tgl_grab,periode,tanggal)
				VALUES(
					'".$wi."',
					'".$wt."',
					'".$ws."',
					'".$v['C_USER']."',
					'".$v['C_PLASA']."',
					'".$v['C_ANTRIAN']."',
					'".$v['C_SUBLAYANAN']."',
					'".$pelanggan."',
					'".$v['NO_ANTRIAN']."',
					'".$v['STATUS']."',
					'".$l_user."',
					'".$v['VISIT_MSISDN']."',
					'".$v['VISIT_ND']."',
					'".$v['DATA3']."',
					'".$v['DATA7']."',
					'".$v['L_LAYANAN']."',
					'".$v['L_PLASA']."',
					'".$v['L_SUBLAYANAN']."',
					'".$v['C_REG']."',
					'".$v['C_WITEL']."',
					'".$v['L_WITEL']."',
					'".$v['RNUM']."',
					'".$v['FEED_AWAL']."',
					'".$v['FEED_AKHIR']."',
					'".date('Y-m-d H:i:s')."',
					'".date('Ym',strtotime($kemarin))."',
					'".date('Y-m-d',strtotime($kemarin))."')";
		$db->runQuery($sql);
	}
	$end = microtime(true) - $start;
	echo 'SELESAI! dengan waktu '.$end;
}
//echo $result;

// $xyz = "<table> ";
// $js = json_decode($result, true);
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
// echo "NO | WAKTU INPUT | WAKTU TERIMA | WAKTU SELESAI | C USER | C PLASA | C ANTRIAN | C SUBLAYANAN | PELANGGAN | NO ANTRIAN | STATUS | L USER | VISIT MSISDN | VISIT ND | DATA3 | DATA7 | L LAYANAN | L PLASA | L SUBLAYANAN | C REG | C WITEL | L WITEL | RNUM | FEED AWAL | FEED AKHIR | TANGGAL | PERIODE<br>";
// for($j=0; $j<=$panjang; $j++)
// {
// 	$kolom = explode("</td>",$baris[$j]);
// 	$input = trim(str_replace("<table>","",str_replace("<tr>","",str_replace("</td>","",str_replace("<td>","",$kolom[0])))));
// 	$terima = trim(str_replace("</td>","",str_replace("<td>","",$kolom[1])));
// 	$selesai = trim(str_replace("</td>","",str_replace("<td>","",$kolom[2])));
// 	$cuser = trim(str_replace("</td>","",str_replace("<td>","",$kolom[3])));
// 	$cplasa = trim(str_replace("</td>","",str_replace("<td>","",$kolom[4])));
// 	$cantrean = trim(str_replace("</td>","",str_replace("<td>","",$kolom[5])));
// 	$csublayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[6])));
// 	$pelanggan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[7])));
// 	$noantrean = trim(str_replace("</td>","",str_replace("<td>","",$kolom[8])));
// 	$status = trim(str_replace("</td>","",str_replace("<td>","",$kolom[9])));
// 	$luser = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[10]))))));
// 	$msisdn = trim(str_replace("</td>","",str_replace("<td>","",$kolom[11])));
// 	$nd = trim(str_replace("</td>","",str_replace("<td>","",$kolom[12])));
// 	$data3 = trim(str_replace("</td>","",str_replace("<td>","",$kolom[13])));
// 	$data7 = trim(str_replace("</td>","",str_replace("<td>","",$kolom[14])));
// 	$llayanan = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[15]))))));
// 	$lplasa = trim(str_replace("</td>","",str_replace("<td>","",$kolom[16])));
// 	$lsublayanan = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[17]))))));
// 	$creg = trim(str_replace("</td>","",str_replace("<td>","",$kolom[18])));
// 	$cwitel = trim(str_replace("</td>","",str_replace("<td>","",$kolom[19])));
// 	$lwitel = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[19]))))));
// 	$rnum = trim(str_replace("</td>","",str_replace("<td>","",$kolom[21])));
// 	$feedawal = trim(str_replace("</td>","",str_replace("<td>","",$kolom[22])));
// 	$feedakhir = trim(str_replace("</td>","",str_replace("<td>","",$kolom[23])));
// 	// TANGGAL UNTUK GRAB MANUAL
// 	//$tanggal = substr($tgl,6,2)."-".substr($tgl,4,2)."-".substr($tgl,0,4);
// 	// TANGGAL UNTUK GRAB CRONTAB
// 	$tanggal = substr($kemarin,6,2)."-".substr($kemarin,4,2)."-".substr($kemarin,0,4);
// 	$tglx = "TO_DATE('".$tanggal."','DD-MM-YYYY')";
// 	$periode = substr($tanggal,6,4).substr($tanggal,3,2);
	
// 	echo htmlentities($j + 1)." | ".htmlentities($input)." | ".htmlentities($terima)." | ".htmlentities($selesai)." | ".htmlentities($cuser)." | ".htmlentities($cplasa)." | ".htmlentities($cantrean)." | ".htmlentities($csublayanan)." | ".htmlentities($pelanggan)." | ".htmlentities($noantrean)." | ".htmlentities($status)." | ".htmlentities($luser)." | ".htmlentities($msisdn)." | ".htmlentities($nd)." | ".htmlentities($data3)." | ".htmlentities($data7)." | ".htmlentities($llayanan)." | ".htmlentities($lplasa)." | ".htmlentities($lsublayanan)." | ".htmlentities($creg)." | ".htmlentities($cwitel)." | ".htmlentities($lwitel)." | ".htmlentities($rnum)." | ".htmlentities($feedawal)." | ".htmlentities($feedakhir)." | ".htmlentities($tglx)." | ".htmlentities($periode)."<br>";

// 	$sql = "insert into REPORT_MYSIMETRI_FEEDBACK (WAKTU_INPUT, WAKTU_TERIMA, WAKTU_SELESAI, C_USER, C_PLASA, C_ANTRIAN, C_SUBLAYANAN, PELANGGAN, NO_ANTRIAN, STATUS, L_USER, VISIT_MSISDN, VISIT_ND, DATA3, DATA7, L_LAYANAN, L_PLASA, L_SUBLAYANAN, C_REG, C_WITEL, L_WITEL, RNUM, FEED_AWAL, FEED_AKHIR, TGL_GRAB, TANGGAL, PERIODE) values (to_date('".$input."', 'YYYY-MM-DD HH24:MI:SS'),to_date('".$terima."', 'YYYY-MM-DD HH24:MI:SS'),to_date('".$selesai."', 'YYYY-MM-DD HH24:MI:SS'),'".$cuser."','".$cplasa."','".$cantrean."','".$csublayanan."','".$pelanggan."','".$noantrean."','".$status."','".$luser."','".$msisdn."','".$nd."','".$data3."','".$data7."','".$llayanan."','".$lplasa."','".$lsublayanan."','".$creg."','".$cwitel."','".$lwitel."','".$rnum."','".$feedawal."','".$feedakhir."',SYSDATE,".$tglx.",'".$periode."')";
// 	//echo $sql."<br>";
// 	oci_execute(oci_parse($connect,$sql));
// }	
?>