<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_client_tanda_tangan = anti_injection($con, $_GET['id_client_tanda_tangan']);
    $like = anti_injection($con, $_GET['like']);
    $id_client = anti_injection($con, $_GET['id_client']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT
         a.id_client_tanda_tangan,a.id_client,b.nama as nama_client,a.nama,a.area,a.unit
        FROM client_tanda_tangan a 
        LEFT OUTER JOIN client b on a.id_client = b.id_client");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_client_tanda_tangan'] = $row['id_client_tanda_tangan'];
                $client = array();
                $client['id'] = $row['id_client'];
                $client['nama'] = $row['nama_client'];
                $hasil['client'] = $client;
                $hasil['nama'] = $row['nama'];
                $hasil['area'] = $row['area'];
                $hasil['unit'] = $row['unit'];

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "client") {
        $all = mysqli_query($con, "SELECT
        a.id_client_tanda_tangan,a.id_client,b.nama as nama_client,a.nama,a.area,a.unit
       FROM client_tanda_tangan a 
       LEFT OUTER JOIN client b on a.id_client = b.id_client
       where a.id_client = '$id_client'");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_client_tanda_tangan'] = $row['id_client_tanda_tangan'];
                $client = array();
                $client['id'] = $row['id_client'];
                $client['nama'] = $row['nama_client'];
                $hasil['client'] = $client;
                $hasil['nama'] = $row['nama'];
                $hasil['area'] = $row['area'];
                $hasil['unit'] = $row['unit'];

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $client = mysqli_query($con, "SELECT
        a.id_client_tanda_tangan,a.id_client,b.nama as nama_client,a.nama,a.area,a.unit
       FROM client_tanda_tangan a 
       LEFT OUTER JOIN client b on a.id_client = b.id_client
       where a.id_client_tanda_tangan ='$id_client_tanda_tangan'");
        if (mysqli_num_rows($client) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($client);
            $hasil = array();
            $hasil['id_client_tanda_tangan'] = $row['id_client_tanda_tangan'];
            $client = array();
            $client['id'] = $row['id_client'];
            $client['nama'] = $row['nama_client'];
            $hasil['client'] = $client;
            $hasil['nama'] = $row['nama'];
            $hasil['area'] = $row['area'];
            $hasil['unit'] = $row['unit'];

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
