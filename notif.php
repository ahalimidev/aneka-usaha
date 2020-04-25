<?php
require_once ('koneksi.php');

function notif($title, $message, $option, $click_action, $token)
{
    $msg = array(
        "title"   => "$title",
        "message"  => "$message",
        "option" => "$option",
        "sound"   => "default",
        "click_action" => "$click_action",
    );
    $fields = array(

        "data"      => $msg,
        "to"  => "$token"
    );
    $data_string = json_encode($fields);

    $headers = [
        'Content-Type: application/json',
        'Authorization: key=' . API_ACCESS_KEY
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
}
