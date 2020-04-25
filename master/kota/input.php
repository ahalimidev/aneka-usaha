<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_kota = anti_injection($con, $_POST['id_kota']);
    $nama = anti_injection($con, $_POST['nama']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO kota (nama) values ('$nama')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_kota'] = mysqli_insert_id($con);
            $hasil['nama'] = $nama;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $like = mysqli_query($con, "SELECT * FROM kota where id_kota = '$id_kota'");
        if (mysqli_num_rows($like) > 0) {
            $edit = "UPDATE kota set nama = '$nama' where id_kota ='$id_kota'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_kota'] = $id_kota;
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
        $like = mysqli_query($con, "SELECT * FROM kota where id_kota = '$id_kota'");
        if (mysqli_num_rows($like) > 0) {
            $hapus = "DELETE FROM kota WHERE id_kota = '$id_kota'";
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
