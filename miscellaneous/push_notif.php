<?php
require_once('../koneksi.php');
require_once('../notif.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ke = anti_injection($con, $_POST['ke']);
    $id_pengguna = anti_injection($con, $_POST['id_pengguna']);
    $keterangan = anti_injection($con, $_POST['keterangan']);
    $hak_akses;
    if ($ke == "dir_utama") {
        $hak_akses = "3";
    } else if ($ke == "dir_operasional") {
        $hak_akses = "4";
    } else if ($ke == "kepala_cabang") {
        $hak_akses = "9";
    }
    $sql = mysqli_query($con, "SELECT * FROM notif_token WHERE id_pengguna = '$id_pengguna' and hak_akses = '$hak_akses' LIMIT 1");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        notif("Aneka Usaha", $keterangan, "Push Notif", null, $row['token']);
        $response["success"] = 1;
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
