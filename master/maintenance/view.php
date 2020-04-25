<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $data = anti_injection($con, $_GET['data']);
    if ($data == "jenis") {

        require_once('jenis/view.php');
    } else if ($data == "kategori") {

        require_once('kategori/view.php');
    } else {
        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
