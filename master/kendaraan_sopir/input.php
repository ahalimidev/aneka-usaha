<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $nama = anti_injection($con, $_POST['nama']);
    $no_handphone = anti_injection($con, $_POST['no_handphone']);
    $no_rekening = anti_injection($con, $_POST['no_rekening']);
    $id_kendaraan_sopir = anti_injection($con, $_POST['id_kendaraan_sopir']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO kendaraan_sopir (nama,no_handphone,no_rekening) values ('$nama','$no_handphone','$no_rekening')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_kendaraan_sopir'] = mysqli_insert_id($con);
            $hasil['nama'] = $nama;
            $hasil['no_handphone'] = $no_handphone;
            $hasil['no_rekening'] = $no_rekening;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $cari = mysqli_query($con, "SELECT * FROM kendaraan_sopir WHERE id_kendaraan_sopir ='$id_kendaraan_sopir'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE kendaraan_sopir set nama ='$nama', no_handphone='$no_handphone', no_rekening='$no_rekening' where id_kendaraan_sopir ='$id_kendaraan_sopir'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_kendaraan_sopir'] = $id_kendaraan_sopir;
                $hasil['nama'] = $nama;
                $hasil['no_handphone'] = $no_handphone;
                $hasil['no_rekening'] = $no_rekening;
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
        $cari = mysqli_query($con, "SELECT * FROM kendaraan_sopir WHERE id_kendaraan_sopir ='$id_kendaraan_sopir'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus = "DELETE FROM kendaraan_sopir WHERE id_kendaraan_sopir = '$id_kendaraan_sopir'";
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
