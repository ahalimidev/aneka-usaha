<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_perusahaan = anti_injection($con, $_GET['id_perusahaan']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT a.id_perusahaan, a.nama,a.alamat,a.id_kota,b.nama as nama_kota, a.no_handphone,a.fax,a.logo,a.status
        FROM perusahaan a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_perusahaan'] = $row['id_perusahaan'];
                $hasil['nama'] = $row['nama'];
                $hasil['alamat'] = $row['alamat'];
                $tampil_data = array();
                $tampil_data['id'] =  $row['id_kota'];
                $tampil_data['nama'] = $row['nama_kota'];
                $hasil['kota'] = $tampil_data;
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                $hasil['logo'] = URL_LOGO . $row['logo'];
                $hasil['status'] = $row['status'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $all = mysqli_query($con, "SELECT a.id_perusahaan, a.nama,a.alamat,a.id_kota,b.nama as nama_kota, a.no_handphone,a.fax,a.logo,a.status
        FROM perusahaan a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
        where a.nama  LIKE '%$like%'");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_perusahaan'] = $row['id_perusahaan'];
                $hasil['nama'] = $row['nama'];
                $hasil['alamat'] = $row['alamat'];
                $tampil_data = array();
                $tampil_data['id'] =  $row['id_kota'];
                $tampil_data['nama'] = $row['nama_kota'];
                $hasil['kota'] = $tampil_data;
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                $hasil['logo'] = URL_LOGO . $row['logo'];
                $hasil['status'] = $row['status'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $one = mysqli_query($con, "SELECT a.id_perusahaan, a.nama,a.alamat,a.id_kota,b.nama as nama_kota, a.no_handphone,a.logo,a.fax,a.status
        FROM perusahaan a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
        where a.id_perusahaan  ='$id_perusahaan'");
        if (mysqli_num_rows($one) > 0) {
            //jika ada
            $response["success"] = 1;
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_perusahaan'] = $row['id_perusahaan'];
            $hasil['nama'] = $row['nama'];
            $hasil['alamat'] = $row['alamat'];
            $tampil_data = array();
            $tampil_data['id'] =  $row['id_kota'];
            $tampil_data['nama'] = $row['nama_kota'];
            $hasil['kota'] = $tampil_data;
            $hasil['no_handphone'] = $row['no_handphone'];
            $hasil['fax'] = $row['fax'];
            $hasil['logo'] = URL_LOGO . $row['logo'];
            $hasil['status'] = $row['status'];
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
