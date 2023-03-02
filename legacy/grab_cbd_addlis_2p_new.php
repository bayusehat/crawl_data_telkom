<?php
$start = microtime(true);
set_time_limit(1200);

require 'init.php';
$tableName = 'cbd_sales_indihome';
$gd = new GrabData();
$gd->getCookie('CBD');
$p = '2P';
$namaGrab = 'GRAB_2P';
$hari_ini = new DateTime(null, new DateTimeZone('Asia/Jakarta'));
$kemarin = new DateTime('yesterday', new DateTimeZone('Asia/Jakarta'));

$delete_data = $gd->deleteDataRangeSales($tableName, $p, $kemarin->format('Y-m-d'), $hari_ini->format('Y-m-d'), 'ALL');
echo "Sebanyak " . $delete_data . " data dihapus dari tanggal " . $kemarin->format('d-m-Y') . " sampai " . $hari_ini->format('d-m-Y') . ".<br>";

$query = "INSERT INTO $tableName(p, cwitel, datel, sto, ncli, ndos, ndem, nd_internet, nd, chanel, citem, speed, deskripsi, tgl_reg, tgl_ps, status, nama, kcontact, status_order, alpro, ccat, jalan, distrik, kota, cpack, cseg, order_id, periode) ";
$query .= "VALUES(:p, :cwitel, :datel, :sto, :ncli, :ndos, :ndem, :nd_internet, :nd, :chanel, :citem, :speed, :deskripsi, :tgl_reg, :tgl_ps, :status, :nama, :kcontact, :status_order, :alpro, :ccat, :jalan, :distrik, :kota, :cpack, :cseg, :order_id, :periode)";

$gd->prepareQueryForInsert($query);

for ($d = 1; $d <= 2; $d++) { // 1 

	echo $d === 1 ? $kemarin->format('d-m-Y') : $hari_ini->format('d-m-Y');
	echo "<br>";

	for ($i = 1; $i <= 7; $i++) { //divre

		if ($d === 1) {
            $url ="https://dashboard.telkom.co.id/cbd/summaryeksekutif/detilnetaddlokasi?header=PSBLN&level=REG&kolom=DIVRE%20".$i."&startdate=".$kemarin->format('d/m/Y')."&enddate=".$kemarin->format('d/m/Y')."&TEMATIK=2P&JENIS=ALL&ACTIVATION=ALL&DIVRE=ALL&WITEL=ALL&CHANEL=ALL&jenisalpro=ALL&segment=ALL&CCAT=ALL&dl=true";
            $periode = $kemarin->format('Ym');
		} else {
            $url ="https://dashboard.telkom.co.id/cbd/summaryeksekutif/detilnetaddlokasi?header=PSBLN&level=REG&kolom=DIVRE%20".$i."&startdate=".$hari_ini->format('d/m/Y')."&enddate=".$hari_ini->format('d/m/Y')."&TEMATIK=2P&JENIS=ALL&ACTIVATION=ALL&DIVRE=ALL&WITEL=ALL&CHANEL=ALL&jenisalpro=ALL&segment=ALL&CCAT=ALL&dl=true";
            $periode = $hari_ini->format('Ym');
        }

		$curl_result = $gd->curl($url);
        $table = explode("</tbody>", $curl_result);
		$baris = explode("</tr>", $table[0]);
		$panjang = count($baris) - 2;
		echo "REG " . $i . " : " . $panjang . "<br>";
		$no = 1;
		for($j = 1; $j <= $panjang; $j++) {
			$kolom = explode("</td>",$baris[$j]);
			$kawasan = substr(trim(str_replace('<tr><td class="str">', '', str_replace('</thead><tbody><tr><td class="str">', '', $kolom[0]))),-1);
            $witel = trim(str_replace('<td class="str">', '', $kolom[1]));
            $cwitel = $gd->getCWitel($witel);
            if ($cwitel === false) {
                die('CWITEL tidak ditemukan.');
            }
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
            $speed_ = is_numeric($speed) ? $speed : 0;
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
            $cpack = trim(str_replace('<td class="str">', '', $kolom[25]));
            $cseg = trim(str_replace('<td class="str">', '', $kolom[26]));
            $order_id = trim(str_replace('<td class="str">', '', $kolom[27]));
			
            // echo $no." / ".htmlentities($kawasan)." / ".htmlentities($witel)." / ".htmlentities($cwitel)." / ".htmlentities($datel)." / ".htmlentities($sto)." / ".htmlentities($ncli)." / ".htmlentities($ndos)." / ".htmlentities($ndem)." / ".htmlentities($nd_internet)." / ".htmlentities($nd)." / ".htmlentities($chanel)." / ".htmlentities($citem)." / ".htmlentities($speed)." / ".htmlentities($deskripsi)." / ".htmlentities($tgl_reg)." / ".htmlentities($tgl_etat)." / ".htmlentities($status)." / ".htmlentities($nama)." / ".htmlentities($kcontact)." / ".htmlentities($status_order)." / ".htmlentities($alpro)." / ccat: ".htmlentities($ccat)." / jalan: ".htmlentities($jalan)." / distrik: ".htmlentities($distrik)." / kota: ".htmlentities($kota)." / cpack: ".htmlentities($cpack)." / caseg: ".htmlentities($cseg)." / order_id: ".htmlentities($order_id)." / "."<br>";

            $bv = [
                ':p' => $p,
                ':cwitel' => $cwitel,
                ':datel' => $datel,
                ':sto' => $sto,
                ':ncli' => $ncli,
                ':ndos' => $ndos_,
                ':ndem' => $ndem,
                ':nd_internet' => $nd_internet,
                ':nd' => $nd,
                ':chanel' => $chanel,
                ':citem' => $citem,
                ':speed' => $speed_,
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
                ':cpack' => $cpack, 
                ':cseg' => $cseg,
                ':order_id' => $order_id,
				':periode' => $periode
			];
			
            $gd->executePreparedQuery($bv);
		}
	}
}
$gd->insertUpdateLogSales($p, $namaGrab);
$end = microtime(true) - $start;
echo $end ."<br>";
