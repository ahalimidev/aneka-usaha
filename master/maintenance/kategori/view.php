<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_jenis_maintenance_kategori = anti_injection($con, $_GET['id_jenis_maintenance_kategori']);
    $like = anti_injection($con, $_GET['like']);
    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT * FROM jenis_maintenance_kategori");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_jenis_maintenance_kategori'] = $row['id_jenis_maintenance_kategori'];
                $hasil['kategori'] = $row['kategori'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT * FROM jenis_maintenance_kategori where kategori  LIKE '%$like%' ");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_jenis_maintenance_kategori'] = $row['id_jenis_maintenance_kategori'];
                $hasil['kategori'] = $row['kategori'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        //tampilkan data 1 
        $one = mysqli_query($con, "SELECT * FROM jenis_maintenance_kategori where id_jenis_maintenance_kategori = '$id_jenis_maintenance_kategori'");
        if (mysqli_num_rows($one) > 0) {
            $response["success"] = 1;
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_jenis_maintenance_kategori'] = $row['id_jenis_maintenance_kategori'];
            $hasil['kategori'] = $row['kategori'];
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
