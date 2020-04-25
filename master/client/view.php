<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_client = anti_injection($con, $_GET['id_client']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        //memcari data semua
        $all = mysqli_query($con, "SELECT * FROM client");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_client'] = $row['id_client'];
                $hasil['nama'] = $row['nama'];
                $hasil['alias'] = $row['alias'];
                $hasil['alamat'] = $row['alamat'];
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                $hasil['logo'] = URL_LOGO . $row['logo'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        //tampilkan dasarkan like pada nama 
        $like = mysqli_query($con, "SELECT * FROM client where nama  LIKE '%$like%'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_client'] = $row['id_client'];
                $hasil['nama'] = $row['nama'];
                $hasil['alias'] = $row['alias'];
                $hasil['alamat'] = $row['alamat'];
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                $hasil['logo'] = URL_LOGO . $row['logo'];
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
        $one = mysqli_query($con, "SELECT * FROM client where id_client = '$id_client'");
        if (mysqli_num_rows($one) > 0) {
            $response["success"] = 1;
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_client'] = $row['id_client'];
            $hasil['nama'] = $row['nama'];
            $hasil['alias'] = $row['alias'];
            $hasil['alamat'] = $row['alamat'];
            $hasil['no_handphone'] = $row['no_handphone'];
            $hasil['fax'] = $row['fax'];
            $hasil['logo'] = URL_LOGO . $row['logo'];
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
