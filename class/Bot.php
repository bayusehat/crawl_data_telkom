<?php

class Bot {

    public static function sendMessage($token, $chat_id, $message) {

        // proses pengiriman report
        $url = "https://api.telegram.org/".$token."/sendmessage?chat_id=".$chat_id."&text=" . $message . "&parse_mode=markdown";

        $ctg = curl_init();
        curl_setopt($ctg, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ctg, CURLOPT_URL, $url);
        $htg = curl_exec($ctg);

        return $htg;
    }
}