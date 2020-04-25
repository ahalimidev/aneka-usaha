<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id_perusahaan = anti_injection($con, $_GET['id_perusahaan']);

    $asal = anti_injection($con, $_GET['asal']);
    $tujuan = anti_injection($con, $_GET['tujuan']);
    $jenis = anti_injection($con, $_GET['jenis']);

    $aksi = mysqli_query($con, "SELECT id_perusahaan FROM perusahaan where id_perusahaan ='$id_perusahaan'");

    if (mysqli_num_rows($aksi) > 0) {

        $sql = mysqli_query($con, "SELECT a.id_daftar_biaya,b.nama as asal_pengiriman,c.nama as tujuan_pengiriman, d.jenis as jenis_pengiriman, a.jarak,a.biaya_jasa,a.biaya_operasional,a.biaya_gaji,a.biaya_gendongan
        FROM daftar_biaya a
        LEFT OUTER JOIN sub_client b on a.asal = b.id_sub_client
        LEFT OUTER JOIN sub_client c on a.tujuan = c.id_sub_client
        LEFT OUTER JOIN pengiriman_jenis d on a.id_pengiriman_jenis = d.id_pengiriman_jenis
        where a.asal = '$asal' and a.tujuan = '$tujuan' and a.id_pengiriman_jenis = '$jenis' ");
        if (mysqli_num_rows($sql) > 0) {
            $response["success"] = 1;
            $row = mysqli_fetch_array($sql);
            $hasil = array();
            $hasil['id_daftar_biaya'] = $row['id_daftar_biaya'];
            $hasil['asal_pengiriman'] = $row['asal_pengiriman'];
            $hasil['tujuan_pengiriman'] = $row['tujuan_pengiriman'];
            $hasil['jenis_pengiriman'] = $row['jenis_pengiriman'];
            $hasil['jarak'] = $row['jarak'] . 'km';
            $hasil['biaya_jasa'] = $row['biaya_jasa'];
            $hasil['biaya_operasional'] = $row['biaya_operasional'];
            $hasil['biaya_gaji'] = $row['biaya_gaji'];
            $hasil['biaya_gendongan'] = $row['biaya_gendongan'];
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            //tidak ada data
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
