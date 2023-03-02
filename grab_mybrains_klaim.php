<?php
$start = microtime(true);
set_time_limit(0);
date_default_timezone_set("Asia/Jakarta");
require 'init.php';

$db = DB::getInstance();
//Get Cookie DB
$query = "select cookie,site from tbl_cookie where site = 'MYBRAINS'";
$get = $db->runQuery($query)->fetchAll();
$cookie = $get[0]['cookie'];

// $month_begin = date('Ym').'01';
// $now = date('Ymd');
$month_begin = '20210501';
$now = '20210531';

echo $cookie;

//DELETE BEFORE
$query_delete = "DELETE FROM MYBRAINS_KLAIM WHERE PERIODE = '".date('Ym',strtotime($month_begin))."'";
$db->runQuery($query_delete);

$url_req = "http://mybrains.telkom.co.id/mybrains/menu/pots/collectionreport/collectionsummary/collectionsummary.php";
$data_to_post = "username=&ccat%5B%5D=&ccat%5B%5D=Apartment&ccat%5B%5D=Bisnis&ccat%5B%5D=Dinas+Kantor&ccat%5B%5D=Dinas+Rumah&ccat%5B%5D=Konsulat&ccat%5B%5D=OLO&ccat%5B%5D=Pelanggan+Bulk&ccat%5B%5D=Pemerintah+%2F+TNI+%2F+Polri&ccat%5B%5D=Prime+Cluster&ccat%5B%5D=Residensial&ccat%5B%5D=Rumah+Kost%2F+Rent+and+Collective+House&ccat%5B%5D=Rumah+Susun+%2F+Flat&ccat%5B%5D=Sosial&ccat%5B%5D=Telum&ccat%5B%5D=Warnet&ccat%5B%5D=Wartel&summ_produk=&produk=&divisi=Divre+05&witel=&datel=&ubis=DCS&segmen=Consumer&subsegmen=CON&bisnis_area=&indihome=&jenis_indihome=&bundling=&nper=&tanggal_dari=$month_begin&tanggal_ke=$now&cl_type%5B%5D=-All-&cl_type%5B%5D=03+-+Collective+Bill&cl_type%5B%5D=AA+-+Invoices+Corporate&cl_type%5B%5D=AB+-+General+Document&cl_type%5B%5D=AC+-+Automatic+Clearing&cl_type%5B%5D=AN+-+Accrue+Non+POTS&cl_type%5B%5D=AP+-+Payable+CIS&cl_type%5B%5D=AX+-+Interest+Document&cl_type%5B%5D=BA+-+Sec.+Dep.+Clearing&cl_type%5B%5D=BD+-+CIS+Bundling&cl_type%5B%5D=CC+-+Claim+Clearing&cl_type%5B%5D=CD+-+Claim+Document&cl_type%5B%5D=CN+-+CIS-Non+Net+Off&cl_type%5B%5D=CP+-+Claim+Positive&cl_type%5B%5D=CR+-+Currency+Revaluation&cl_type%5B%5D=CS+-+CIS+-+Sec+Deposit&cl_type%5B%5D=DC+-+CIS+-+Difference&cl_type%5B%5D=DE+-+Deferred+Revenue&cl_type%5B%5D=DP+-+Down+Payment&cl_type%5B%5D=DR+-+Doubtful+Receivable&cl_type%5B%5D=EE+-+Credits+3rd+party&cl_type%5B%5D=GB+-+Fees&cl_type%5B%5D=GG+-+Cash+Desk&cl_type%5B%5D=HH+-+Payment+lot&cl_type%5B%5D=IC+-+Invoice+CIS&cl_type%5B%5D=IF+-+Invoices+FLEXI&cl_type%5B%5D=II+-+Payment+run&cl_type%5B%5D=IN+-+Invoices+Non+POTS&cl_type%5B%5D=IP+-+Invoices+POTS&cl_type%5B%5D=JJ+-+Payment+run&cl_type%5B%5D=KK+-+General&cl_type%5B%5D=LF+-+CIS+Late+Fee&cl_type%5B%5D=LL+-+Fees&cl_type%5B%5D=MM+-+Dunning+run&cl_type%5B%5D=NN+-+Payment+run&cl_type%5B%5D=NO+-+Net+off&cl_type%5B%5D=OO+-+Transfer+posting&cl_type%5B%5D=PB+-+Payment+Pasang+Baru&cl_type%5B%5D=PC+-+Payment+Coklit+SPH&cl_type%5B%5D=PN+-+Inc.+Pay.+Non+POTS&cl_type%5B%5D=PO+-+Inc.+Pay.+%28IPC%29+POTS&cl_type%5B%5D=PP+-+Passive+acrual&cl_type%5B%5D=PR+-+Payment+AP+in+R%2F3&cl_type%5B%5D=PT+-+PSB+PPLT&cl_type%5B%5D=PW+-+Inc.+Payments+W%2FO&cl_type%5B%5D=PX+-+Inc.+Pay.%28IPC%29+FLEXI&cl_type%5B%5D=RA+-+Installments&cl_type%5B%5D=RD+-+Revenue+Distribution&cl_type%5B%5D=RF+-+CIS+Receivables+R%2F3&cl_type%5B%5D=RI+-+Reclass+IN&cl_type%5B%5D=RP+-+Rev.Sharing+PPLT-Col&cl_type%5B%5D=RR+-+Value+correction&cl_type%5B%5D=RS+-+Revenue+Sharing&cl_type%5B%5D=RT+-+Rev.Sharing+PPLT-Bil&cl_type%5B%5D=SB+-+Special+Bus.Request&cl_type%5B%5D=SC+-+SBR+Clearing&cl_type%5B%5D=SD+-+Security+Deposit&cl_type%5B%5D=ST+-+Reversal+document&cl_type%5B%5D=SW+-+SISKA+Write-Off&cl_type%5B%5D=TI+-+Transfer+Items&cl_type%5B%5D=TT+-+Reversals&cl_type%5B%5D=TX+-+CIS-PPH&cl_type%5B%5D=VV+-+Installments&cl_type%5B%5D=WO+-+Write+Off&cl_type%5B%5D=WS+-+WO+Statistical+Doc.&cl_type%5B%5D=WW+-+Security+deposits&cl_type%5B%5D=XX+-+Account+maintenance&cl_type%5B%5D=YP+-+Migration+docs+A%2FP&cl_type%5B%5D=YY+-+Migration+docs+A%2FR&cl_type%5B%5D=ZG+-+Migration+doc.+Paymt&cl_type%5B%5D=ZP+-+Migration+doc.+IP&cl_type%5B%5D=ZY+-+Migration+doc.+Paymt&cl_type%5B%5D=ZZ+-+Migration+doc.+Adj&cl_status=&show1=WITEL&show2=&show3=&submit=Search";

$cr = curl_init();
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cr, CURLOPT_URL, $url_req);
curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($cr, CURLOPT_POST, true);
curl_setopt($cr, CURLOPT_HTTPHEADER, array($cookie));
curl_setopt($cr, CURLOPT_POSTFIELDS, $data_to_post);
curl_setopt($cr, CURLOPT_VERBOSE, true);
$result = curl_exec($cr);

echo '<pre>';
print_r($result);
echo '</pre>';
exit;

$table = explode("</tbody>", $result);
$baris = explode("</tr>", $table[0]);
$panjang = count($baris) - 2;
$no = 1;

echo '<table>';
    for ($i = 0; $i <= $panjang; $i++) {
        $kolom = explode("</td>",$baris[$i]);
        if(isset($kolom[0])){
            $witel = trim(strip_tags(str_replace(",","",$kolom[0])));
            if(preg_match("[W34|W35|W35|W36|W37|W38|W39|W40|W41|W48|W49|W50|W51|W63]", $witel) === 1){
                $witel = $witel;
            }
        }

        if(isset($kolom[5])){
            $bayar = trim(strip_tags(str_replace(",","",$kolom[5])));
            if(is_numeric($bayar)){
                $bayar = $bayar;
            }else{
                $bayar = null;
            }
        }else{
            $bayar = null;
        }
        
        if(isset($kolom[6])){
            $klaim = trim(strip_tags(str_replace(",","",$kolom[6])));
            if(is_numeric($klaim)){
                $klaim = $klaim;
            }else{
                $klaim = null;
            }
        }else{
            $klaim = null;
        }

        if($witel != '' && $klaim != '' && $bayar != ''){
            $query_witel = "SELECT CWITEL FROM MYBRAINS_WITEL WHERE SOURCE = '$witel'";
            $isExist = $db->runQuery($query_witel)->fetchAll();

            if(count($isExist)){
                $witel = $isExist[0]['cwitel'];
            }else{
                $witel = 'GRAND TOTAL';
            }

            echo '<tr>';
                echo '<td>'.$witel.'</td>';
                echo '<td>'.$bayar.'</td>';
                echo '<td>'.$klaim.'</td>';
            echo '</tr>';

            $query_insert = "INSERT INTO MYBRAINS_KLAIM(CWITEL,BAYAR,KLAIM,PERIODE,TANGGAL) 
                VALUES (
                    ".$witel.",
                    ".$bayar.",
                    ".$klaim.",
                    '".date('Ym',strtotime($month_begin))."',
                    '".date('Y-m-d',strtotime($now))."'
                )";
            $db->runQuery($query_insert);
        }
    }
echo '</table> <br>';

?>