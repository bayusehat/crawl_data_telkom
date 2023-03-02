<?php
$start = microtime(true);
set_time_limit(3600);
require 'init.php';
$tableName = 'cbd_addlis_3p';

$a = "17-02-2020";
$b = "22-02-2020";

$tgl_awal = new DateTime($a, new DateTimeZone('Asia/Jakarta'));
$tgl_akhir = new DateTime($b, new DateTimeZone('Asia/Jakarta'));
$periode = $tgl_akhir->format('Ym');
$hari_awal = $tgl_awal->format('d');
$hari_akhir = $tgl_akhir->format('d');
$bln_thn = $tgl_akhir->format('m/Y');

echo "Periode " . $a . " - " . $b . "<br>";

$gd = new GrabData();

$delete_data = $gd->deleteDataRange($tableName, $tgl_awal->format('Y-m-d'), $tgl_akhir->format('Y-m-d'));
echo "Sebanyak " . $delete_data . " data dihapus dari tanggal " . $tgl_awal->format('Y-m-d'). " s/d " . $tgl_akhir->format('Y-m-d') . ". <br>";

$cookie = $gd->getCookie('CBD');

$query = "INSERT INTO $tableName(reg, witel, datel, sto, ncli, ndos, ndem, nd_internet, nd, chanel, citem, speed, deskripsi, tgl_reg, tgl_ps, status, nama, kcontact, status_order, alpro, ccat, jalan, distrik, kota, periode) ";
$query .= "VALUES(:reg, :witel, :datel, :sto, :ncli, :ndos, :ndem, :nd_internet, :nd, :chanel, :citem, :speed, :deskripsi, :tgl_reg, :tgl_ps, :status, :nama, :kcontact, :status_order, :alpro, :ccat, :jalan, :distrik, :kota, :periode)";

$gd->prepareQueryForInsert($query);

for ($d = (int)$hari_awal; $d <= (int)$hari_akhir; $d++) {

    if ($d < 10) {
        $dd = "0".$d;
    } else {
        $dd = $d;
    }
    
    $tgl = $dd . '/' . $bln_thn;
	
	for ($i = 1; $i <= 7; $i++) { //divre
		
		echo $tgl . " divre " . $i . "<br>";
        $url ="https://dashboard.telkom.co.id/cbd/summaryeksekutif/detilnetaddlokasi?header=PSBLN&level=REG&kolom=DIVRE%20".$i."&startdate=".$tgl."&enddate=".$tgl."&TEMATIK=3P&JENIS=ALL&ACTIVATION=ALL&DIVRE=ALL&WITEL=ALL&CHANEL=ALL&jenisalpro=ALL&segment=ALL&CCAT=ALL&dl=true";

        $curl_result = $gd->curl($url);

        $table = explode("</tbody>", $curl_result);
		$baris = explode("</tr>", $table[0]);
		$panjang = count($baris) - 2;
		
		$no = 1;
		for($j = 1; $j <= $panjang; $j++) {
			$kolom = explode("</td>",$baris[$j]);
			$kawasan = substr(trim(str_replace('<tr><td class="str">', '', str_replace('</thead><tbody><tr><td class="str">', '', $kolom[0]))),-1);
			$witel = trim(str_replace('<td class="str">', '', $kolom[1]));
			$datel = trim(str_replace('<td class="str">', '', $kolom[2]));
			$sto = trim(str_replace('<td class="str">', '', $kolom[3]));
			$ncli = trim(str_replace('<td class="str">', '', $kolom[4]));
			$ndos = trim(str_replace('<td class="str">', '', $kolom[5]));
			$ndos_ = is_numeric($ndos) ? $ndos : 0;
			$ndem = trim(str_replace('<td class="str">', '', $kolom[6]));
			$nd_internet = trim(str_replace('<td class="str">', '', $kolom[7]));
			$nd = trim(str_replace('<td class="str">', '', $kolom[8]));
			$chanel = strtoupper(trim(str_replace('<td class="str">', '', $kolom[9])));
			$citem = trim(str_replace('<td class="str">', '', $kolom[10]));
			$speed = trim(str_replace('<td class="str">', '', $kolom[11]));
			$deskripsi = trim(str_replace('<td class="str">', '', $kolom[12]));
			$tgl_reg = trim(str_replace('<td class="str">', '', $kolom[13]));
			$tgl_etat = substr(trim(str_replace('<td class="str">', '', $kolom[14])),0,9);
			$status = trim(str_replace('<td class="str">', '', $kolom[15]));
			$nama = strtoupper(trim(str_replace('<td class="str">', '', $kolom[16])));
			$kcontact = trim(str_replace('<td class="str">', '', $kolom[17]));
			$status_order = trim(str_replace('<td class="str">', '', $kolom[18]));
			$alpro = trim(str_replace('<td class="str">', '', $kolom[19]));
			$ccat = trim(str_replace('<td class="str">', '', $kolom[20]));
			$jalan = trim(str_replace('<td class="str">', '', $kolom[21]));
			$distrik = trim(str_replace('<td class="str">', '', $kolom[22]));
			$kota = trim(str_replace('<td class="str">', '', $kolom[23]));
			
            // echo $no." / ".htmlentities($kawasan)." / ".htmlentities($witel)." / ".htmlentities($datel)." / ".htmlentities($sto)." / ".htmlentities($ncli)." / ".htmlentities($ndos)." / ".htmlentities($ndem)." / ".htmlentities($nd_internet)." / ".htmlentities($nd)." / ".htmlentities($chanel)." / ".htmlentities($citem)." / ".htmlentities($speed)." / ".htmlentities($deskripsi)." / ".htmlentities($tgl_reg)." / ".htmlentities($tgl_etat)." / ".htmlentities($status)." / ".htmlentities($nama)." / ".htmlentities($kcontact)." / ".htmlentities($status_order)." / ".htmlentities($alpro)." / ".htmlentities($ccat)." / ".htmlentities($jalan)." / ".htmlentities($distrik)." / ".htmlentities($kota)." / "."<br>";
            
            $bv = [
                ':reg' => $kawasan,
                ':witel' => $witel,
                ':datel' => $datel,
                ':sto' => $sto,
                ':ncli' => $ncli,
                ':ndos' => $ndos,
                ':ndem' => $ndem,
                ':nd_internet' => $nd_internet,
                ':nd' => $nd,
                ':chanel' => $chanel,
                ':citem' => $citem,
                ':speed' => $speed,
                ':deskripsi' => $deskripsi,
				':tgl_reg' => $tgl_reg,
				':tgl_ps' => $tgl_etat,
				':status' => $status,
				':nama' => $nama,
				':kcontact' => $kcontact,
				':status_order' => $status_order,
				':alpro' => $alpro,
				':ccat' => $ccat,
				':jalan' => $jalan,
				':distrik' => $distrik,
				':kota' => $kota,
				':periode' => $periode
			];
			
            $gd->executePreparedQuery($bv);
		}
    }
}