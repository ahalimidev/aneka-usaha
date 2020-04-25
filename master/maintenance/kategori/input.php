<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $kategori = anti_injection($con, $_POST['kategori']);
    $id_jenis_maintenance_kategori = anti_injection($con, $_POST['id_jenis_maintenance_kategori']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO  jenis_maintenance_kategori (kategori) values ('$kategori')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_jenis_maintenance_kategori'] = mysqli_insert_id($con);
            $hasil['kategori'] = $kategori;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $edit = "UPDATE jenis_maintenance_kategori set kategori = '$kategori' where id_jenis_maintenance_kategori ='$id_jenis_maintenance_kategori'";
        if (mysqli_query($con, $edit)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_jenis_maintenance_kategori'] = $id_jenis_maintenance_kategori;
            $hasil['kategori'] = $kategori;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        $hapus = "DELETE FROM jenis_maintenance_kategori WHERE id_jenis_maintenance_kategori = '$id_jenis_maintenance_kategori'";
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
