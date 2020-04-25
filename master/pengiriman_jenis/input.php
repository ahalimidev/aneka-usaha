<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_pengiriman_jenis = anti_injection($con, $_POST['id_pengiriman_jenis']);
    $jenis = anti_injection($con, $_POST['nama']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO pengiriman_jenis (jenis) values ('$jenis')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_pengiriman_jenis'] = mysqli_insert_id($con);
            $hasil['nama'] = $jenis;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $cari = mysqli_query($con, "SELECT * FROM  pengiriman_jenis where id_pengiriman_jenis = '$id_pengiriman_jenis'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE pengiriman_jenis set jenis = '$jenis' where id_pengiriman_jenis ='$id_pengiriman_jenis'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_pengiriman_jenis'] = $id_pengiriman_jenis;
                $hasil['nama'] = $jenis;
                $response['data'] = $hasil;
                echo json_encode($response);
            } else {
                $response["success"] = 0;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        $cari = mysqli_query($con, "SELECT * FROM  pengiriman_jenis where id_pengiriman_jenis = '$id_pengiriman_jenis'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus = "DELETE FROM pengiriman_jenis WHERE id_pengiriman_jenis = '$id_pengiriman_jenis'";
            if (mysqli_query($con, $hapus)) {
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
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
