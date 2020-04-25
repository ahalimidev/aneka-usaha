<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_sub_client_tipe = anti_injection($con, $_POST['id_sub_client_tipe']);
    $nama = anti_injection($con, $_POST['nama']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO sub_client_tipe (nama) values ('$nama')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_sub_client_tipe'] = mysqli_insert_id($con);
            $hasil['nama'] = $nama;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $edit = "UPDATE sub_client_tipe set nama = '$nama' where id_sub_client_tipe ='$id_sub_client_tipe'";
        if (mysqli_query($con, $edit)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_sub_client_tipe'] = $id_sub_client_tipe;
            $hasil['nama'] = $nama;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        $hapus = "DELETE FROM sub_client_tipe WHERE id_sub_client_tipe = '$id_sub_client_tipe'";
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
