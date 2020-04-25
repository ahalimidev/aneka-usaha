<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_jenis_maintenance_kategori = anti_injection($con, $_POST['id_jenis_maintenance_kategori']);
    $jenis = anti_injection($con, $_POST['jenis']);
    $id_jenis_maintenance = anti_injection($con, $_POST['id_jenis_maintenance']);
    if ($tipe == "tambah") {
        $tambah = "INSERT INTO  jenis_maintenance (jenis,id_kategori) values ('$jenis','$id_jenis_maintenance_kategori')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_jenis_maintenance'] = mysqli_insert_id($con);
            $hasil['jenis'] = $jenis;
            $tampil = mysqli_query($con, "SELECT * FROM jenis_maintenance_kategori where id_jenis_maintenance_kategori = '$id_jenis_maintenance_kategori'");
            $row = mysqli_fetch_array($tampil);
            $kategori = array();
            $kategori['id'] =  $row['id_jenis_maintenance_kategori'];
            $kategori['nama'] = $row['kategori'];
            $hasil['kategori'] = $kategori;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $edit = "UPDATE jenis_maintenance set jenis = '$jenis' where id_jenis_maintenance ='$id_jenis_maintenance'";
        if (mysqli_query($con, $edit)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_jenis_maintenance'] = $id_jenis_maintenance;
            $hasil['jenis'] = $jenis;
            $tampil = mysqli_query($con, "SELECT a.id_jenis_maintenance_kategori,a.kategori FROM jenis_maintenance_kategori  a LEFT OUTER JOIN jenis_maintenance b on b.id_kategori = a.id_jenis_maintenance_kategori where b.id_jenis_maintenance = '$id_jenis_maintenance'");
            $row = mysqli_fetch_array($tampil);
            $kategori = array();
            $kategori['id'] =  $row['id_jenis_maintenance_kategori'];
            $kategori['nama'] = $row['kategori'];
            $hasil['kategori'] = $kategori;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        $hapus = "DELETE FROM jenis_maintenance WHERE id_jenis_maintenance = '$id_jenis_maintenance'";
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
