<?php
include "conf/connection.php";

function login($url,$data) {
	$fp = fopen("cookie-mysimetri-detil.txt", "w");
	fclose($fp);
	$login = curl_init();
	curl_setopt($login, CURLOPT_COOKIEJAR, "cookie-mysimetri-detil.txt");
	curl_setopt($login, CURLOPT_COOKIEFILE, "cookie-mysimetri-detil.txt");
	curl_setopt($login, CURLOPT_TIMEOUT, 40000);
	curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($login, CURLOPT_URL, $url);
	curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($login, CURLOPT_POST, TRUE);
	curl_setopt($login, CURLOPT_POSTFIELDS, $data);
	ob_start();
	return curl_exec ($login);
	ob_end_clean();
	curl_close ($login);
	unset($login);    
}
login("https://mysimetri.telkom.co.id/user/authenticate","username=admin&password=010101&counter=0&type=Platinum");
//echo "sukses";

// START VARIABLE UNTUK GRAB CRONTAB
// $kemarin = date('Ymd',strtotime("-1 days"));
$kemarin = "20191007";
//echo $kemarin."<br>";

$url_req ="https://mysimetri.telkom.co.id/data/dataTransdetail";
$data_to_post = "draw=1&columns%5B0%5D%5Bdata%5D=WAKTU_INPUT&columns%5B0%5D%5Bname%5D=waktu_input&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=WAKTU_TERIMA&columns%5B1%5D%5Bname%5D=waktu_terima&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=WAKTU_SELESAI&columns%5B2%5D%5Bname%5D=waktu_selesai&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=L_USER&columns%5B3%5D%5Bname%5D=l_user&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=C_USER&columns%5B4%5D%5Bname%5D=c_user&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=L_PLASA&columns%5B5%5D%5Bname%5D=l_plasa&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=NO_ANTRIAN&columns%5B6%5D%5Bname%5D=announce_call&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=VISIT_ND&columns%5B7%5D%5Bname%5D=visit_nd&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=true&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=VISIT_MSISDN&columns%5B8%5D%5Bname%5D=visit_msisdn&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=true&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=WAKTU_LAYANAN&columns%5B9%5D%5Bname%5D=waktu_layanan&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=10&columns%5B10%5D%5Bname%5D=trans1&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=false&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=11&columns%5B11%5D%5Bname%5D=trans2&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=false&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B12%5D%5Bdata%5D=12&columns%5B12%5D%5Bname%5D=trans3&columns%5B12%5D%5Bsearchable%5D=true&columns%5B12%5D%5Borderable%5D=false&columns%5B12%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B12%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B13%5D%5Bdata%5D=13&columns%5B13%5D%5Bname%5D=trans4&columns%5B13%5D%5Bsearchable%5D=true&columns%5B13%5D%5Borderable%5D=false&columns%5B13%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B13%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=10000&search%5Bvalue%5D=&search%5Bregex%5D=false&from=".$kemarin."&to=".$kemarin."&c_reg=0202";

// $data_to_post = "draw=1&columns%5B0%5D%5Bdata%5D=WAKTU_INPUT&columns%5B0%5D%5Bname%5D=waktu_input&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=WAKTU_TERIMA&columns%5B1%5D%5Bname%5D=waktu_terima&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=WAKTU_SELESAI&columns%5B2%5D%5Bname%5D=waktu_selesai&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=L_USER&columns%5B3%5D%5Bname%5D=l_user&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=C_USER&columns%5B4%5D%5Bname%5D=c_user&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=L_PLASA&columns%5B5%5D%5Bname%5D=l_plasa&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=NO_ANTRIAN&columns%5B6%5D%5Bname%5D=announce_call&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=VISIT_ND&columns%5B7%5D%5Bname%5D=visit_nd&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=true&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=VISIT_MSISDN&columns%5B8%5D%5Bname%5D=visit_msisdn&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=true&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=WAKTU_LAYANAN&columns%5B9%5D%5Bname%5D=waktu_layanan&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=10&columns%5B10%5D%5Bname%5D=trans1&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=false&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=11&columns%5B11%5D%5Bname%5D=trans2&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=false&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B12%5D%5Bdata%5D=12&columns%5B12%5D%5Bname%5D=trans3&columns%5B12%5D%5Bsearchable%5D=true&columns%5B12%5D%5Borderable%5D=false&columns%5B12%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B12%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B13%5D%5Bdata%5D=13&columns%5B13%5D%5Bname%5D=trans4&columns%5B13%5D%5Bsearchable%5D=true&columns%5B13%5D%5Borderable%5D=false&columns%5B13%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B13%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=10&search%5Bvalue%5D=&search%5Bregex%5D=false&from=".$kemarin."&to=".$kemarin."&c_reg=0202";
// END VARIABLE UNTUK GRAB CRONTAB


// START VARIABLE UNTUK GRAB MANUAL
// $tgl_start = "20190901"; //ganti periode awal
//  $tgl_end = "20190901"; //ganti periode akhir
// $tgl = "20190901"; //ganti periode
//echo $tgl_start."<br>";
//echo $tgl_end."<br>";

// $url_req ="https://mysimetri.telkom.co.id/data/dataTransdetail";
// $data_to_post = "draw=1&columns%5B0%5D%5Bdata%5D=WAKTU_INPUT&columns%5B0%5D%5Bname%5D=waktu_input&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=WAKTU_TERIMA&columns%5B1%5D%5Bname%5D=waktu_terima&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=WAKTU_SELESAI&columns%5B2%5D%5Bname%5D=waktu_selesai&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=L_USER&columns%5B3%5D%5Bname%5D=l_user&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=C_USER&columns%5B4%5D%5Bname%5D=c_user&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=L_PLASA&columns%5B5%5D%5Bname%5D=l_plasa&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=NO_ANTRIAN&columns%5B6%5D%5Bname%5D=announce_call&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=VISIT_ND&columns%5B7%5D%5Bname%5D=visit_nd&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=true&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=VISIT_MSISDN&columns%5B8%5D%5Bname%5D=visit_msisdn&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=true&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=WAKTU_LAYANAN&columns%5B9%5D%5Bname%5D=waktu_layanan&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=10&columns%5B10%5D%5Bname%5D=trans1&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=false&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=11&columns%5B11%5D%5Bname%5D=trans2&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=false&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B12%5D%5Bdata%5D=12&columns%5B12%5D%5Bname%5D=trans3&columns%5B12%5D%5Bsearchable%5D=true&columns%5B12%5D%5Borderable%5D=false&columns%5B12%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B12%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B13%5D%5Bdata%5D=13&columns%5B13%5D%5Bname%5D=trans4&columns%5B13%5D%5Bsearchable%5D=true&columns%5B13%5D%5Borderable%5D=false&columns%5B13%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B13%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=1000000&search%5Bvalue%5D=&search%5Bregex%5D=false&from=".$tgl_start."&to=".$tgl_end."&c_reg=0202";
// END VARIABLE UNTUK GRAB MANUAL

$cr = curl_init();
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cr, CURLOPT_URL, $url_req);
curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($cr, CURLOPT_POST, true);
curl_setopt($cr, CURLOPT_COOKIEFILE, "cookie-mysimetri-detil.txt");
//curl_setopt($cr, CURLOPT_HTTPHEADER, array($cookie));
curl_setopt($cr, CURLOPT_POST, sizeof($data_to_post));
curl_setopt($cr, CURLOPT_POSTFIELDS, $data_to_post);
$result = curl_exec($cr);
echo $result;


$xyz = "<table> ";
$js = json_decode($result, true);

foreach ($js['data'] as $dt) 
{
	$xyz .= "<tr> ";
	foreach ($dt as $value) 
	{
		if (is_array($value)) {
			foreach ($value[0] as $val) {
				$xyz .= "<td>$val</td> ";
			}
		} else {
			$xyz .= "<td>$value</td> ";
		}
		
	}
		$xyz .= "</tr> ";
}

// $minus = (string)$i." days";
// $tanggal = date('d-m-Y',strtotime($minus));
// $i--;
$xyz .= "</table>";
// echo $xyz;

$zyx = explode("<table>",$xyz);
$baris= explode("<tr>",$zyx[1]);
$panjang = count($baris)-1;
// echo $panjang;

echo "NO | WAKTU INPUT | WAKTU TERIMA | WAKTU SELESAI | C USER | C PLASA | C ANTRIAN | C SUBLAYANAN | PELANGGAN | NO ANTRIAN | STATUS | L USER | VISIT MSISDN | VISIT ND | L LAYANAN | L PLASA | L SUBLAYANAN | C REG | C WITEL | RNUM | WAKTU LAYANAN | LAYANAN 2 | LAYANAN 3 | DETAIL | TANGGAL | PERIODE</br>";

$sql = "INSERT INTO REPORT_MYSIMETRI_DETIL_TRANS (WAKTU_INPUT, WAKTU_TERIMA, WAKTU_SELESAI, C_USER, C_PLASA, C_ANTRIAN, C_SUBLAYANAN, PELANGGAN, NO_ANTRIAN, STATUS, L_USER, VISIT_MSISDN, VISIT_ND, L_LAYANAN, L_PLASA, L_SUBLAYANAN, C_REG, C_WITEL, RNUM, WAKTU_LAYANAN, LAYANAN_2, LAYANAN_3, DETAIL, TANGGAL, PERIODE, TGL_GRAB) VALUES (:winput, :wterima, :wselesai, :cuser, :cplasa, :cantrian, :csublayanan, :pelanggan, :noantrian, :status, :luser, :vmsisdn, :vnd, :llayanan, :lplasa, :lsublayanan, :creg, :cwitel, :rnum, :wlayanan, :layanan2, :layanan3, :detail, :tang, :periode, SYSDATE)";
$stmt = oci_parse($connect,$sql);	
//echo $sql."<br>";

for($j=1; $j<=$panjang; $j++)
{
	$kolom = explode("</td>",$baris[$j]);
	$winput = trim(str_replace("<table>","",str_replace("<tr>","",str_replace("</td>","",str_replace("<td>","",$kolom[0])))));
	$winputx = "TO_DATE('".$winput."','YYYY-MM-DD HH24:MI:SS')";
	$wterima = trim(str_replace("</td>","",str_replace("<td>","",$kolom[1])));
	$wterimax = "TO_DATE('".$wterima."','YYYY-MM-DD HH24:MI:SS')";
	$wselesai = trim(str_replace("</td>","",str_replace("<td>","",$kolom[2])));
	$wselesaix = "TO_DATE('".$wselesai."','YYYY-MM-DD HH24:MI:SS')";
	$cuser = trim(str_replace("</td>","",str_replace("<td>","",$kolom[3])));
	$cplasa = trim(str_replace("</td>","",str_replace("<td>","",$kolom[4])));
	$cantrian = trim(str_replace("</td>","",str_replace("<td>","",$kolom[5])));
	$csublayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[6])));
	$pelanggan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[7])));
	$noantrian = trim(str_replace("</td>","",str_replace("<td>","",$kolom[8])));
	$status = trim(str_replace("</td>","",str_replace("<td>","",$kolom[9])));
	$luser = trim(str_replace("</td>","",str_replace("<td>","",$kolom[10])));
	$vmsisdn = trim(str_replace("</td>","",str_replace("<td>","",$kolom[11])));
	$vnd = trim(str_replace("</td>","",str_replace("<td>","",$kolom[12])));
	$nllayanann = trim(str_replace("</td>","",str_replace("<td>","",$kolom[13])));
	$lplasa = trim(str_replace("</td>","",str_replace("<td>","",$kolom[14])));
	$lsublayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[15])));
	$creg = trim(str_replace("</td>","",str_replace("<td>","",$kolom[16])));
	$cwitel = trim(str_replace("</td>","",str_replace("<td>","",$kolom[17])));
	$rnum = trim(str_replace("</td>","",str_replace("<td>","",$kolom[18])));
	$wlayanan = trim(str_replace("</td>","",str_replace("<td>","",$kolom[19])));
	$layanan2 = trim(str_replace("</td>","",str_replace("<td>","",$kolom[20])));
	$layanan3 = trim(str_replace("</td>","",str_replace("<td>","",$kolom[21])));
	$detail = trim(str_replace("</td>","",str_replace("<td>","",$kolom[22]))) . ":" . trim(str_replace("</td>","",str_replace("<td>","",$kolom[23])));
	// TANGGAL UNTUK GRAB MANUAL
	// $tanggal = substr($tgl,6,2)."-".substr($tgl,4,2)."-".substr($tgl,0,4);
	// $tglx = "TO_DATE('".$aa."','YYYY-MM-DD HH24:MI:SS')";
	// TANGGAL UNTUK GRAB CRONTAB
	$tanggal = substr($kemarin,6,2)."-".substr($kemarin,4,2)."-".substr($kemarin,0,4);
	$tang = "TO_DATE('".$tanggal."','DD-MM-YYYY')";
	$periode = substr($tanggal,6,4).substr($tanggal,3,2);

	echo htmlentities($j)." | ".htmlentities($winputx)." | ".htmlentities($wterimax)." | ".htmlentities($wselesaix)." | ".htmlentities($cuser)." | ".htmlentities($cplasa)." | ".htmlentities($cantrian)." | ".htmlentities($csublayanan)." | ".htmlentities($pelanggan)." | ".htmlentities($noantrian)." | ".htmlentities($status)." | ".htmlentities($luser)." | ".htmlentities($vmsisdn)." | ".htmlentities($vnd)." | ".htmlentities($llayanan)." | ".htmlentities($lplasa)." | ".htmlentities($lsublayanan)." | ".htmlentities($creg)." | ".htmlentities($cwitel)." | ".htmlentities($rnum)." | ".htmlentities($wlayanan)." | ".htmlentities($layanan2)." | ".htmlentities($layanan3)." | ".htmlentities($detail)." | ".htmlentities($tang)." | ".htmlentities($periode)."<br>";
	
	oci_bind_by_name($stmt, ":winput", $winputx);
	oci_bind_by_name($stmt, ":wterima", $wterimax);
	oci_bind_by_name($stmt, ":wselesai", $wselesaix);
	oci_bind_by_name($stmt, ":cuser", $cuser);
	oci_bind_by_name($stmt, ":cplasa", $cplasa);
	oci_bind_by_name($stmt, ":cantrian", $cantrian);
	oci_bind_by_name($stmt, ":csublayanan", $csublayanan);
	oci_bind_by_name($stmt, ":pelanggan", $pelanggan);
	oci_bind_by_name($stmt, ":noantrian", $noantrian);
	oci_bind_by_name($stmt, ":status", $status);
	oci_bind_by_name($stmt, ":luser", $luser);
	oci_bind_by_name($stmt, ":vmsisdn", $vmsisdn);
	oci_bind_by_name($stmt, ":vnd", $vnd);
	oci_bind_by_name($stmt, ":llayanan", $llayanan);
	oci_bind_by_name($stmt, ":lplasa", $lplasa);
	oci_bind_by_name($stmt, ":lsublayanan", $lsublayanan);
	oci_bind_by_name($stmt, ":creg", $creg);
	oci_bind_by_name($stmt, ":cwitel", $cwitel);
	oci_bind_by_name($stmt, ":rnum", $rnum);
	oci_bind_by_name($stmt, ":wlayanan", $wlayanan);
	oci_bind_by_name($stmt, ":layanan2", $layanan2);
	oci_bind_by_name($stmt, ":layanan3", $layanan3);
	oci_bind_by_name($stmt, ":detail", $detail);
	oci_bind_by_name($stmt, ":tang", $tang);
	oci_bind_by_name($stmt, ":periode", $periode);

	$r = oci_execute($stmt);
	if (!$r) {
		echo "masuk if </br>";
		$e = oci_error($stmt);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";
	}
} 
echo "GRAB DATA SUKSES";
//*/
?>