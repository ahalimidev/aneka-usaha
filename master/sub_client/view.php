<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_sub_client = anti_injection($con, $_GET['id_sub_client']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "select 
        a.id_sub_client,
        a.nama,
        a.alias,
        a.alamat,
        a.no_handphone,
        a.fax,
        a.logo,
        a.id_kota, 
        b.nama as nama_kota,
        a.id_sub_client_tipe,
        c.nama as nama_sub_client,
        a.id_client ,
        d.nama as nama_client
        FROM sub_client a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
        LEFT OUTER JOIN sub_client_tipe c on a.id_sub_client_tipe = c.id_sub_client_tipe
        LEFT OUTER JOIN client d on a.id_client = d.id_client");
        if (mysqli_num_rows($all) > 0) {
            $response['data'] = array();
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_sub_client'] = $row['id_sub_client'];
                $hasil['nama'] = $row['nama'];
                $hasil['alias'] = $row['alias'];
                $hasil['alamat'] = $row['alamat'];
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                $hasil['logo'] = URL_LOGO . $row['logo'];

                $client = array();
                $client['id'] = $row['id_client'];
                $client['nama'] = $row['nama_client'];
                $hasil['client'] = $client;

                $kota = array();
                $kota['id'] = $row['id_kota'];
                $kota['nama'] = $row['nama_kota'];
                $hasil['kota'] = $kota;

                $tipe = array();
                $tipe['id'] = $row['id_sub_client_tipe'];
                $tipe['nama'] = $row['nama_sub_client'];
                $hasil['tipe'] = $tipe;

                array_push($response['data'], $hasil);
            }
            $response["success"] = 1;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $all = mysqli_query($con, "select 
        a.id_sub_client,
        a.nama,
        a.alias,
        a.alamat,
        a.no_handphone,
        a.fax,
        a.logo,
        a.id_kota, 
        b.nama as nama_kota,
        a.id_sub_client_tipe,
        c.nama as nama_sub_client,
        a.id_client ,
        d.nama as nama_client
        FROM sub_client a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
        LEFT OUTER JOIN sub_client_tipe c on a.id_sub_client_tipe = c.id_sub_client_tipe
        LEFT OUTER JOIN client d on a.id_client = d.id_client
        where a.nama LIKE '%$like%'");
        if (mysqli_num_rows($all) > 0) {
            $response['data'] = array();
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_sub_client'] = $row['id_sub_client'];
                $hasil['nama'] = $row['nama'];
                $hasil['alias'] = $row['alias'];
                $hasil['alamat'] = $row['alamat'];
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                $hasil['logo'] = URL_LOGO . $row['logo'];

                $client = array();
                $client['id'] = $row['id_client'];
                $client['nama'] = $row['nama_client'];
                $hasil['client'] = $client;

                $kota = array();
                $kota['id'] = $row['id_kota'];
                $kota['nama'] = $row['nama_kota'];
                $hasil['kota'] = $kota;

                $tipe = array();
                $tipe['id'] = $row['id_sub_client_tipe'];
                $tipe['nama'] = $row['nama_sub_client'];
                $hasil['tipe'] = $tipe;

                array_push($response['data'], $hasil);
            }
            $response["success"] = 1;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $all = mysqli_query($con, "select 
        a.id_sub_client,
        a.nama,
        a.alias,
        a.alamat,
        a.no_handphone,
        a.fax,
        a.logo,
        a.id_kota, 
        b.nama as nama_kota,
        a.id_sub_client_tipe,
        c.nama as nama_sub_client,
        a.id_client ,
        d.nama as nama_client
        FROM sub_client a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
        LEFT OUTER JOIN sub_client_tipe c on a.id_sub_client_tipe = c.id_sub_client_tipe
        LEFT OUTER JOIN client d on a.id_client = d.id_client
        where  a.id_sub_client = '$id_sub_client'");
        if (mysqli_num_rows($all) > 0) {

            $row = mysqli_fetch_array($all);
            $hasil = array();
            $hasil['id_sub_client'] = $row['id_sub_client'];
            $hasil['nama'] = $row['nama'];
            $hasil['alias'] = $row['alias'];
            $hasil['alamat'] = $row['alamat'];
            $hasil['no_handphone'] = $row['no_handphone'];
            $hasil['fax'] = $row['fax'];
            $hasil['logo'] = URL_LOGO . $row['logo'];

            $client = array();
            $client['id'] = $row['id_client'];
            $client['nama'] = $row['nama_client'];
            $hasil['client'] = $client;

            $kota = array();
            $kota['id'] = $row['id_kota'];
            $kota['nama'] = $row['nama_kota'];
            $hasil['kota'] = $kota;

            $tipe = array();
            $tipe['id'] = $row['id_sub_client_tipe'];
            $tipe['nama'] = $row['nama_sub_client'];
            $hasil['tipe'] = $tipe;
            $response["success"] = 1;
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
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
