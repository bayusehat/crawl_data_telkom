<?php
set_time_limit(0);
include "conf/connection.php";
//include "conf/password.php";
//include "conf/cookie-mysimetri.php";

function login($url,$data) {
	$fp = fopen("cookie-mysimetri-prod.txt", "w");
	fclose($fp);
	$login = curl_init();
	curl_setopt($login, CURLOPT_COOKIEJAR, "cookie-mysimetri-prod.txt");
	curl_setopt($login, CURLOPT_COOKIEFILE, "cookie-mysimetri-prod.txt");
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

for($d = 1; $d <= 7; $d++)
{
	if($d < 10)
	{
		$dd = "0".$d;
	}
	else
	{
		$dd = $d;
	}
	$tgl = "201908".$dd;
	echo $tgl."</br>";
	$url_req ="https://mysimetri.telkom.co.id/data/productivity";
	$data_to_post = "from=".$tgl."&to=".$tgl."&c_reg=0202&c_witel=0&c_plasa=0";

	$cr = curl_init();
	curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cr, CURLOPT_URL, $url_req);
	curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($cr, CURLOPT_POST, true);
	curl_setopt($cr, CURLOPT_COOKIEFILE, "cookie-mysimetri-prod.txt");
	//curl_setopt($cr, CURLOPT_HTTPHEADER, array($cookie));
	curl_setopt($cr, CURLOPT_POST, sizeof($data_to_post));
	curl_setopt($cr, CURLOPT_POSTFIELDS, $data_to_post);
	$result = curl_exec($cr);
	//echo $result;

	$xyz = "<table> ";
	$js = json_decode($result, true);
	foreach ($js['data'] as $dt) 
	{
		$xyz .= "<tr> ";
		foreach ($dt as $value) 
		{
			$xyz .= "<td>$value</td> ";
		}
			$xyz .= "</tr> ";
	}
	$minus = (string)$i." days";
	$tanggal = date('d-m-Y',strtotime($minus));
	$i--;
	$xyz .= "</table>";
	//echo $xyz;

	$zyx = explode("</table>",$xyz);
	$baris= explode("</tr>",$zyx[0]);
	$panjang = count($baris)-2;
	//echo $table[1];
	echo "NO | C REG | L REG | C WITEL | L WITEL | C PLASA | L PLASA | C USER | L USER | ACCEPTED | FINISHED | TRANSFERRED | PRESENTASI SELESAI | PENDING | TANGGAL | PERIODE<br>";
	for($j=0; $j<=$panjang; $j++)
	{
		$kolom = explode("</td>",$baris[$j]);
		$creg = trim(str_replace("<table>","",str_replace("<tr>","",str_replace("</td>","",str_replace("<td>","",$kolom[0])))));
		$lreg = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[1]))))));
		$cwitel = trim(str_replace("</td>","",str_replace("<td>","",$kolom[2])));
		$lwitel = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[3]))))));
		$cplasa = trim(str_replace("</td>","",str_replace("<td>","",$kolom[4])));
		$lplasa = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[5]))))));
		$cuser = trim(str_replace("</td>","",str_replace("<td>","",$kolom[6])));
		$luser = trim(str_replace("/","'||'/'||'",str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[7]))))));
		$accepted = trim(str_replace("</td>","",str_replace("<td>","",$kolom[8])));
		$finished = trim(str_replace("</td>","",str_replace("<td>","",$kolom[9])));
		//$luser = trim(str_replace("&","'||'&'||'",str_replace("'","''",str_replace("</td>","",str_replace("<td>","",$kolom[10])))));
		$transferred = trim(str_replace("</td>","",str_replace("<td>","",$kolom[10])));
		$psn_selesai = trim(str_replace("</td>","",str_replace("<td>","",$kolom[11])));
		$pending = trim(str_replace("</td>","",str_replace("<td>","",$kolom[12])));
		// TANGGAL UNTUK GRAB MANUAL
		$tanggal = substr($tgl,6,2)."-".substr($tgl,4,2)."-".substr($tgl,0,4);
		// TANGGAL UNTUK GRAB CRONTAB
		// $tanggal = substr($kemarin,6,2)."-".substr($kemarin,4,2)."-".substr($kemarin,0,4);
		$tglx = "TO_DATE('".$tanggal."','DD-MM-YYYY')";
		$periode = substr($tanggal,6,4).substr($tanggal,3,2);
		
		echo htmlentities($j + 1)." | ".htmlentities($creg)." | ".htmlentities($lreg)." | ".htmlentities($cwitel)." | ".htmlentities($lwitel)." | ".htmlentities($cplasa)." | ".htmlentities($lplasa)." | ".htmlentities($cuser)." | ".htmlentities($luser)." | ".htmlentities($accepted)." | ".htmlentities($finished)." | ".htmlentities($transferred)." | ".htmlentities($psn_selesai)." | ".htmlentities($pending)." | ".htmlentities($tanggal)." | ".htmlentities($periode)."<br>";

		$sql = "INSERT INTO REPORT_MYSIMETRI_PROD_CSR (C_REG, L_REG, C_WITEL, L_WITEL, C_PLASA, L_PLASA, C_USER, L_USER, ACCEPTED, FINISHED, TRANSFERRED, PERSENTASI_SELESAI, PENDING, TANGGAL, PERIODE, TGL_GRAB) VALUES ('".$creg."', '".$lreg."', '".$cwitel."', '".$lwitel."', '".$cplasa."', '".$lplasa."', '".$cuser."', '".$luser."', '".$accepted."', '".$finished."', '".$transferred."', '".$psn_selesai."', '".$pending."', ".$tglx.", '".$periode."', SYSDATE)";
		// echo $sql."<br>";
		oci_execute(oci_parse($connect,$sql));
	}
}
?>