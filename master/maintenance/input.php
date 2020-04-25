<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = anti_injection($con, $_POST['data']);

    if ($data == "jenis") {
        require_once('jenis/input.php');
    } else if ($data == "kategori") {
        require_once('kategori/input.php');
    } else {
        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
