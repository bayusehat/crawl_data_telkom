<?php

class GrabData {

    private $db = null;
    private $cookieTbl = 'tbl_cookie';
    private $addonTable = 'cbd_detil_sales_all_addon';
    private $churnAddonTable = 'cbd_detil_churn_all_addon';
    private $salesIndihomeTable = 'cbd_sales_indihome';
    private $churnIndihomeTable = 'cbd_churn_indihome';
    private $paramWitelTable = 'p_m_wilayah';
    private $vaAddonTable = 'cbd_va_addon';
    private $cookie = '';
    private $wilayahList = [];
    // private $wilayahList = ["ACEH","BABEL","BENGKULU","JAMBI","LAMPUNG","MEDAN","RIDAR","RIKEP","SUMBAR","SUMSEL","SUMUT","BANTEN","BEKASI","BOGOR","JAKBAR","JAKPUS","JAKSEL","JAKTIM","JAKUT","TANGERANG","BANDUNG","BANDUNGBRT","CIREBON","KARAWANG","SUKABUMI","TASIKMALAYA","KUDUS","MAGELANG","PEKALONGAN","PURWOKERTO","SEMARANG","SOLO","YOGYAKARTA","DENPASAR","JEMBER","KEDIRI","MADIUN","MADURA","MALANG","NTB","NTT","PASURUAN","SIDOARJO","SINGARAJA","SURABAYA%20SELATAN","SURABAYA%20UTARA","BALIKPAPAN","KALBAR","KALSEL","KALTARA","KALTENG","SAMARINDA","GORONTALO","MAKASSAR","MALUKU","PAPUA","PAPUA%20BARAT","SULSELBAR","SULTENG","SULTRA","SULUTMALUT"];
    private $channelList = ['INBOUND+147', 'MOSS', 'MYINDIHOME', 'OTHERS', 'PLASA', 'TAM', 'USSD'];
    private $digitalAddonList = [
        'PLC' => '19',
        'WIFIEXT' => '20',
        'OTT' => '80',
        'MUSIK' => '66',
        'IH SMART' => '1005',
        'GAMER' => '18',
        'MOVIN' => '9',
        'VIDEO CALL' => '16',
        'Usee TV' => '106'
    ];

    public function __construct() {
        $this->db = DB::getInstance();
        // $this->fillWilayahList();
    }

    // private function fillWilayahList() {
    //     $result = $this->db->runQuery('select cwitel, witel_cbd from p_m_wilayah')->fetchAll();
        
    //     foreach ($result as $row) {
    //         $this->wilayahList[$row[0]] = $row[1];
    //     }
    // }

    public function getWilayahList($kawasan) {

        $q_kawasan = '';
        $bv = [];

        if ($kawasan === 'ALL') {
            $q_kawasan = '1 = 1';
        } else {
            $q_kawasan = 'reg = :kawasan';
            $bv[':kawasan'] = $kawasan;
        }

        $query = "select cwitel, witel_cbd from p_m_wilayah where $q_kawasan and cwitel < 1000";
        $result = $this->db->runQuery($query, $bv)->fetchAll();
        
        foreach ($result as $row) {
            $this->wilayahList[$row[0]] = $row[1];
        }

        return $this->wilayahList;
    }

    public function getCWitel($witel) {
        return array_search($witel, $this->wilayahList);
    }

    public function getMasterCAddonList() {
        return $this->db->runQuery('select caddon, addon from p_m_addon')->fetchAll();
    }

    public function getDigitalAddonList() {
        return $this->digitalAddonList;
    }

    public function getChannelList() {
        return $this->channelList;
    }

    public function getCookie($site) {
        $this->cookie = $this->db->select('cookie')->getWhereOnce($this->cookieTbl, ['site', '=', $site])->cookie;
    }

    public function deleteData($tableName, $tgl) {
        $query = "DELETE FROM $tableName WHERE TO_CHAR(tgl_ps, 'YYYY-MM-DD') = :tgl_ps";
        return $this->db->runQuery($query, [':tgl_ps' => $tgl])->rowCount();
    }

    public function deleteDataRange($tableName, $tgl1, $tgl2) {
        $query = "DELETE FROM $tableName WHERE tgl_ps BETWEEN :tgl1 AND :tgl2";
        return $this->db->runQuery($query, [':tgl1' => $tgl1, ':tgl2' => $tgl2])->rowCount();
    }

    public function deleteDataRangeSales($p, $tgl1, $tgl2, $kawasan) {
        
        $q_kawasan = '';
        $bv = [];

        if ($kawasan === 'ALL') {
            $q_kawasan = "1 = 1";
        } else {
            $q_kawasan = 'b.reg = :reg';
            $bv[':reg'] = $kawasan;
        }

        $query = "
        DELETE FROM {$this->salesIndihomeTable} a
        USING p_m_wilayah b
        WHERE a.cwitel = b.cwitel AND a.p = :p and (a.tgl_ps between :tgl1 and :tgl2) and $q_kawasan";

        $bv += [':p' => $p, ':tgl1' => $tgl1, ':tgl2' => $tgl2];
        
        return $this->db->runQuery($query, $bv)->rowCount();
    }

    public function deleteDataRangeChurnIH($p, $tgl1, $tgl2, $kawasan) {
        
        $q_kawasan = '';
        $bv = [];

        if ($kawasan === 'ALL') {
            $q_kawasan = "1 = 1";
        } else {
            $q_kawasan = 'b.reg = :reg';
            $bv[':reg'] = $kawasan;
        }

        $query = "
        DELETE FROM {$this->churnIndihomeTable} a
        USING p_m_wilayah b
        WHERE a.cwitel = b.cwitel AND a.p = :p and (a.tgl_ps between :tgl1 and :tgl2) and $q_kawasan";

        $bv += [':p' => $p, ':tgl1' => $tgl1, ':tgl2' => $tgl2];
        
        return $this->db->runQuery($query, $bv)->rowCount();
    }

    public function deleteDataRangeAddon($caddon, $tgl1, $tgl2, $kawasan) {

        $q_kawasan = '';
        $bv = [];

        if ($kawasan === 'ALL') {
            $q_kawasan = "1 = 1";
        } else {
            $q_kawasan = 'b.reg = :reg';
            $bv[':reg'] = $kawasan;
        }

        $query = "
        DELETE FROM {$this->addonTable} a
        USING p_m_wilayah b
        WHERE a.cwitel = b.cwitel AND a.caddon = :caddon and (a.tgl_ps between :tgl1 and :tgl2) and $q_kawasan";

        $bv += [':caddon' => $caddon, ':tgl1' => $tgl1, ':tgl2' => $tgl2];
        
        return $this->db->runQuery($query, $bv)->rowCount();
    }

    public function deleteDataPeriode($tableName, $periode) {
        $query = "DELETE FROM $tableName WHERE periode = :periode";
        return $this->db->runQuery($query, [':periode' => $periode])->rowCount();
    }

    public function deleteVaAddon($addon) {
        $query = "DELETE FROM cbd_detil_va_addon WHERE addon = :addon";
        return $this->db->runQuery($query, [':addon' => $addon])->rowCount();
    }
    
    public function deleteDataGrabRacing($jenis, $periode) {
        $query = "DELETE FROM najib_addon_racing WHERE jenis = :jenis AND periode = :periode";
        return $this->db->runQuery($query, [':periode' => $periode, ':jenis' => $jenis]);
    }

    public function curl($url, $post = []) {
        $flag_curl = true;
        do {
            $cr = curl_init();
            curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cr, CURLOPT_URL, $url);
            curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($cr, CURLOPT_HTTPHEADER, array($this->cookie));
            curl_setopt($cr, CURLOPT_POST, sizeof($post));
	        curl_setopt($cr, CURLOPT_POSTFIELDS, $post);
            $content = curl_exec($cr);
            $flag_curl = $content === false ? false : true;
            // var_dump($flag_curl);
        } while ($flag_curl === false);
        
        return $content;

        // try {
        //     $cr = curl_init();

        //     if ($cr === false) {
        //         throw new Exception('Failed to initialize curl.');
        //     }
        //     curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($cr, CURLOPT_URL, $url);
        //     curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "GET");
        //     curl_setopt($cr, CURLOPT_HTTPHEADER, array($this->cookie));
        //     $content = curl_exec($cr);

        //     if ($content === false) {
        //         throw new Exception(curl_error($cr), curl_errno($cr));
        //     }
        //     return $content;
        // }
        // catch (Exception $e) {
        //     die("kode error " . $e->getMessage() . " kode: " . $e->getCode());
        // }        
    }

    public function prepareQueryForInsert($query) {
        $this->db->prepareQuery($query);
    }

    public function executePreparedQuery($bv = []) {
        $this->db->executeQuery($bv);
    }


    // Function untuk meng-update reg setelah digrab
    public function updateReg($tableName) {

        $param_reg = $this->db->runQuery('select witel_cbd witel, reg from p_m_wilayah')->fetchAll();

        foreach($param_reg as $row) {
    
            $query = "UPDATE $tableName SET REG = :reg WHERE witel = :witel";
            $bv = [':reg' => $row['reg'], ':witel' => $row['witel']];
            $this->db->runQuery($query, $bv);
        }
    }

    public function truncateTableVA() {
        return $this->db->runQuery("truncate table {$this->vaAddonTable}");
    }

    public function deleteUselessColumnDigital() {
        return $this->db->runQuery("DELETE FROM cbd_rekap_sales_digital_service WHERE witel like '%DIVRE%'");
    }

    public function updateStoAddon() {

        $query = "
        update {$this->addonTable} a 
        set sto = b.sto
        from p_prefix_internet_r5 b
        where substring(a.inet, 1, 6) = substring(b.batas_bawah, 1, 6) and a.sto is null";

        return $this->db->runQuery($query);
    }

    // public function getWilayahList() {

    //     $wilayah = [];
    //     $result = $this->db->runQuery('select cwitel, witel_cbd from p_m_wilayah')->fetchAll();
        
    //     foreach ($result as $row) {
    //         $wilayah[$row['cwitel']] = str_replace(' ','%20', $row['witel_cbd']);
    //     }

    //     return $wilayah;

    // }

    public function getGrabList() {
        return $this->db->runQuery('SELECT nama_file, nama_grab FROM p_m_grab_table ORDER BY id')->fetchAll();
    }

    public function insertUpdateLogSales($p, $namaGrab) {

        $query = "
        update log_last_update_grab
        set max_date = b.max_date, last_grab = b.last_grab
        from (
            select max(tgl_ps) max_date, max(created_at) last_grab
            from cbd_sales_indihome
            where p = :p
        ) b
        where nama_grab = :nama_grab";

        $this->db->runQuery($query, [':p' => $p, ':nama_grab' => $namaGrab]);
    }

    public function prepareQueryForInsertAddon($jenis) {

        $table = '';

        if ($jenis === 'SALES') {
            $table = $this->addonTable;
        } else if ($jenis === 'CHURN') {
            $table = $this->churnAddonTable;
        } else {
            die('JENIS tidak sesuai.');
        }
        $query = "
        INSERT INTO {$table}(cwitel, sto, caddon, ncli, ndos, ndem, inet, item, price, tgl_va, tgl_ps, kcontact, periode, datel, addon_name_cbd, order_type, tti, tti_comply, channel_cbd, cagent) VALUES(:cwitel, :sto, :caddon, :ncli, :ndos, :ndem, :inet, :item, :price, :tgl_va, :tgl_ps, :kcontact, :periode, :datel, :addon_name_cbd, :order_type, :tti, :tti_comply, :channel_cbd, :cagent)";

        $this->db->prepareQuery($query);
    }

    public function prepareQueryForInsertSales() {

        $query = "INSERT INTO {$this->salesIndihomeTable}(p, cwitel, datel, sto, ncli, ndos, ndem, nd_internet, nd, chanel, citem, speed, deskripsi, tgl_reg, tgl_ps, status, nama, kcontact, status_order, alpro, ccat, jalan, distrik, kota, cpack, cseg, order_id, periode) ";
        $query .= "VALUES(:p, :cwitel, :datel, :sto, :ncli, :ndos, :ndem, :nd_internet, :nd, :chanel, :citem, :speed, :deskripsi, :tgl_reg, :tgl_ps, :status, :nama, :kcontact, :status_order, :alpro, :ccat, :jalan, :distrik, :kota, :cpack, :cseg, :order_id, :periode)";

        $this->db->prepareQuery($query);
    }

    public function prepareQueryForInsertChurnIH() {

        $query = "INSERT INTO {$this->churnIndihomeTable}(p, cwitel, datel, sto, ncli, ndos, ndem, nd_internet, nd, chanel, citem, speed, deskripsi, tgl_reg, tgl_ps, status, nama, kcontact, status_order, alpro, ccat, jalan, distrik, kota, periode) ";
        $query .= "VALUES(:p, :cwitel, :datel, :sto, :ncli, :ndos, :ndem, :nd_internet, :nd, :chanel, :citem, :speed, :deskripsi, :tgl_reg, :tgl_ps, :status, :nama, :kcontact, :status_order, :alpro, :ccat, :jalan, :distrik, :kota, :periode)";

        $this->db->prepareQuery($query);
    }

    public function getAddon($caddon)
    {
        $queryAddon = "SELECT * FROM p_m_addon WHERE caddon = $caddon";
        $getAddon = $this->db->runQuery($queryAddon)->fetchAll();
        $ga = $getAddon[0];
        return $ga;
    }

    public function deleteDataRangeChurnAddon($caddon, $tgl1, $tgl2, $kawasan) {

        $q_kawasan = '';
        $bv = [];

        if ($kawasan === 'ALL') {
            $q_kawasan = "1 = 1";
        } else {
            $q_kawasan = 'b.reg = :reg';
            $bv[':reg'] = $kawasan;
        }

        $query = "
        DELETE FROM {$this->churnAddonTable} a
        USING p_m_wilayah b
        WHERE a.cwitel = b.cwitel AND a.caddon = :caddon and (a.tgl_ps between :tgl1 and :tgl2) and $q_kawasan";

        $bv += [':caddon' => $caddon, ':tgl1' => $tgl1, ':tgl2' => $tgl2];
        
        return $this->db->runQuery($query, $bv)->rowCount();
    }

    public function getCwitelMyBrains($witel) {

        $result = $this->db->runQuery("SELECT cwitel FROM {$this->paramWitelTable} WHERE witel_mybrains = :witel", ['witel' => $witel])->fetchAll();
       
        if (!empty($result)) {
            return $result[0][0];
        } else {
            return false;
        }
    }

    public function getAllAddonList() {

        return $this->db->runQuery('SELECT caddon, addon FROM p_m_addon')->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function deleteDataRekapAddlisIH($p, $tgl1, $tgl2) {
        $query = "DELETE FROM cbd_rekap_addlis_indihome WHERE p = :p AND (tgl BETWEEN :tgl1 AND :tgl2)";
        return $this->db->runQuery($query, [':p' => $p, ':tgl1' => $tgl1, ':tgl2' => $tgl2])->rowCount();
    }

    public function getAddonName($caddon) {

        return $this->db->getWhereOnceQuery('SELECT addon FROM p_m_addon WHERE caddon = :caddon', ['caddon' => $caddon]);
    }

    public function updateChannelKcontact() {

        $query = "
        UPDATE
            cbd_detil_sales_all_addon a
        SET
            channel_kcontact = b.channel_kcontact
        FROM (
            SELECT
                id,
                get_channel_name (UPPER(kcontact)) channel_kcontact
            FROM
                cbd_detil_sales_all_addon
            WHERE
                COALESCE(channel_kcontact, '') = ''
            LIMIT 100000) b
        WHERE
            a.id = b.id";
        
        return $this->db->runQuery($query)->rowCount();
    }
}