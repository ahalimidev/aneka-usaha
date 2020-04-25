<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_pengiriman_jenis = anti_injection($con, $_GET['id_pengiriman_jenis']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT * FROM pengiriman_jenis");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_pengiriman_jenis'] = $row['id_pengiriman_jenis'];
                $hasil['nama'] = $row['jenis'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT * FROM pengiriman_jenis where jenis  LIKE '%$like%' ");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_pengiriman_jenis'] = $row['id_pengiriman_jenis'];
                $hasil['nama'] = $row['jenis'];
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
        $one = mysqli_query($con, "SELECT * FROM pengiriman_jenis where id_pengiriman_jenis = '$id_pengiriman_jenis'");
        if (mysqli_num_rows($one) > 0) {
            $response["success"] = 1;
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_pengiriman_jenis'] = $row['id_pengiriman_jenis'];
            $hasil['nama'] = $row['jenis'];
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
