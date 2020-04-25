<?php

require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pembayaran = anti_injection($con, $_POST['pembayaran']);
    $id_pengiriman = anti_injection($con, $_POST['id_pengiriman']);

    $get_biaya = mysqli_query($con, "SELECT p.biaya_operasional AS b_operasional, p.biaya_gaji AS b_gaji, k.no_pol AS no_pol FROM pengiriman p INNER JOIN pengiriman_kendaraan pk ON p.id_pengiriman = pk.id_pengiriman INNER JOIN kendaraan k ON pk.id_kendaraan = k.id_kendaraan WHERE p.id_pengiriman = '$id_pengiriman'");
    $row_biaya = mysqli_fetch_array($get_biaya);

    if ($pembayaran == "1") {
        $update_1 = "UPDATE pengiriman SET pembayaran_operasional = '1' WHERE id_pengiriman = '$id_pengiriman'";
        if (mysqli_query($con, $update_1)) {

            $response["success"] = 1;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($pembayaran == "2") {
        $update_2 = "UPDATE pengiriman_kendaraan SET pembayaran = '1' WHERE id_pengiriman = '$id_pengiriman'";
        if (mysqli_query($con, $update_2)) {

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
