<?php

class Tools {

    public static function generatePeriode($awal, $akhir) {
        $periodeList = [];
        $begin = new DateTime($awal.'01');
        $end = new DateTime($akhir.'31');
        $end = $end->modify('+1 day');

        $interval = new DateInterval('P1M');
        $daterange = new DatePeriod($begin, $interval, $end);

        foreach($daterange as $date) {
            array_push($periodeList, $date->format("Ym"));
        }

        return $periodeList;
    }

    public static function getWitelList($reg) {

        $db = DB::getInstance();
        return $db->runQuery('SELECT cwitel, witel_cbd witel FROM public.p_m_wilayah WHERE reg = :reg AND cwitel < 1000', ['reg' => '5'])->fetchAll();
    }

    public static function dd($data) {
        echo '<pre>';
        die(var_dump($data));
        echo '</pre>';
    }
}