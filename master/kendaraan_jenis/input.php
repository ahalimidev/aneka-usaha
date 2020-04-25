<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipe = anti_injection($con, $_POST['tipe']);
    $id_kendaraan_jenis = anti_injection($con, $_POST['id_kendaraan_jenis']);
    $nama = anti_injection($con, $_POST['nama']);

    if ($tipe == "tambah") {
        $simpan = "INSERT INTO kendaraan_jenis (nama) VALUES ('$nama')";
        if (mysqli_query($con, $simpan)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_kendaraan_jenis'] = mysqli_insert_id($con);
            $hasil['nama'] = $nama;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $cari = mysqli_query($con, "SELECT * FROM kendaraan_jenis where id_kendaraan_jenis = '$id_kendaraan_jenis'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE kendaraan_jenis SET nama = '$nama' where id_kendaraan_jenis = '$id_kendaraan_jenis'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_kendaraan_jenis'] = $id_kendaraan_jenis;
                $hasil['nama'] = $nama;
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
        $cari = mysqli_query($con, "SELECT * FROM kendaraan_jenis where id_kendaraan_jenis = '$id_kendaraan_jenis'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus = "DELETE FROM kendaraan_jenis WHERE id_kendaraan_jenis = '$id_kendaraan_jenis'";
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
