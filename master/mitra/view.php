<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_mitra = anti_injection($con, $_GET['id_mitra']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT a.id_mitra,a.id_kota, b.nama as nama_kota, a.nama,a.alamat,a.no_handphone,a.fax
        FROM mitra a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_mitra'] = $row['id_mitra'];
                $hasil['nama'] = $row['nama'];
                $hasil['alamat'] = $row['alamat'];
                $tampil_data = array();
                $tampil_data['id'] =  $row['id_kota'];
                $tampil_data['nama'] = $row['nama_kota'];
                $hasil['kota'] = $tampil_data;
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT a.id_mitra,a.id_kota, b.nama as nama_kota, a.nama,a.alamat,a.no_handphone,a.fax
        FROM mitra a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
        where a.nama  LIKE '%$like%'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_mitra'] = $row['id_mitra'];
                $hasil['nama'] = $row['nama'];
                $hasil['alamat'] = $row['alamat'];
                $tampil_data = array();
                $tampil_data['id'] =  $row['id_kota'];
                $tampil_data['nama'] = $row['nama_kota'];
                $hasil['kota'] = $tampil_data;
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['fax'] = $row['fax'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $one = mysqli_query($con, "SELECT a.id_mitra,a.id_kota, b.nama as nama_kota, a.nama,a.alamat,a.no_handphone,a.fax
        FROM mitra a
        LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
        WHERE a.id_mitra ='$id_mitra'");
        if (mysqli_num_rows($one) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_mitra'] = $row['id_mitra'];
            $hasil['nama'] = $row['nama'];
            $hasil['alamat'] = $row['alamat'];
            $tampil_data = array();
            $tampil_data['id'] =  $row['id_kota'];
            $tampil_data['nama'] = $row['nama_kota'];
            $hasil['kota'] = $tampil_data;
            $hasil['no_handphone'] = $row['no_handphone'];
            $hasil['fax'] = $row['fax'];
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
