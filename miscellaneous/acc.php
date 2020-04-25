<?php

require_once('../koneksi.php');
require_once('../notif.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id = anti_injection($con, $_POST['id']);
    if ($tipe == "maintenance") {
        $maintenance = "UPDATE maintenance SET  status = '2'  where id_maintenance = '$id'";
        if (mysqli_query($con, $maintenance)) {
            $token = mysqli_fetch_array(mysqli_query($con, "SELECT a.token,c.no_pol
            FROM notif_token a 
            LEFT OUTER JOIN maintenance b on a.id_perusahaan = b.id_perusahaan 
            LEFT OUTER JOIN kendaraan c on  b.id_kendaraan = c.id_kendaraan
            WHERE b.id_maintenance = '$id_maintenance' and a.hak_akses = '8' LIMIT  1"));
            notif(
                "Maintenance " . $token['no_pol'] . " Telah Selesai",
                "Dokumen/ Nota telah terupload, silahkan mereview kembali",
                "Maintenance",
                "maintenance_complete",
                $token['token']
            );
            $response["success"] = 1;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "maintenance_biaya") {
        $maintenance = "UPDATE maintenance SET  status = '4'  where id_maintenance = '$id'";
        if (mysqli_query($con, $maintenance)) {
            $token = mysqli_fetch_array(mysqli_query($con, "SELECT a.token,c.no_pol
            FROM notif_token a 
            LEFT OUTER JOIN maintenance b on a.id_perusahaan = b.id_perusahaan 
            LEFT OUTER JOIN kendaraan c on  b.id_kendaraan = c.id_kendaraan
            WHERE b.id_maintenance = '$id_maintenance' and a.hak_akses = '8' LIMIT  1"));
            notif(
                "Pengajuan Biaya Maintenance " . $token['no_pol'],
                "Disetujui, anda dapat melanjutkan proses pengajuan",
                "Maintenance",
                "maintenance_acc_biaya",
                $token['token']
            );
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
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
