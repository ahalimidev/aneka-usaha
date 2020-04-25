<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_pengguna = anti_injection($con, $_GET['id_pengguna']);
    $id_perusahaan = anti_injection($con, $_GET['id_perusahaan']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT 
        a.id_pengguna,a.id_perusahaan,b.nama as nama_perusahaan , a.nama,a.email,a.password,a.hak_akses
        FROM pengguna a
        LEFT OUTER join perusahaan b on a.id_perusahaan = b.id_perusahaan");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_pengguna'] = $row['id_pengguna'];

                $perusahaan = array();
                $perusahaan['id'] = $row['id_perusahaan'];
                $perusahaan['nama'] = $row['nama_perusahaan'];
                $hasil['perusahaan'] = $perusahaan;

                $hasil['nama'] = $row['nama'];
                $hasil['email'] = $row['email'];
                $hasil['password'] = $row['password'];
                $hasil['hak_akses'] = $row['hak_akses'];

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {

        $like = mysqli_query($con, "SELECT 
        a.id_pengguna,a.id_perusahaan,b.nama as nama_perusahaan , a.nama,a.email,a.password,a.hak_akses
        FROM pengguna a
        LEFT OUTER join perusahaan b on a.id_perusahaan = b.id_perusahaan
        where a.id_perusahaan = '$id_perusahaan' and a.nama like  '%$like%'");

        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_pengguna'] = $row['id_pengguna'];

                $perusahaan = array();
                $perusahaan['id'] = $row['id_perusahaan'];
                $perusahaan['nama'] = $row['nama_perusahaan'];
                $hasil['perusahaan'] = $perusahaan;

                $hasil['nama'] = $row['nama'];
                $hasil['email'] = $row['email'];
                $hasil['password'] = $row['password'];
                $hasil['hak_akses'] = $row['hak_akses'];

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $one = mysqli_query($con, "SELECT 
        a.id_pengguna,a.id_perusahaan,b.nama as nama_perusahaan , a.nama,a.email,a.password,a.hak_akses
        FROM pengguna a
        LEFT OUTER join perusahaan b on a.id_perusahaan = b.id_perusahaan
        where a.id_pengguna = '$id_pengguna'");
        if (mysqli_num_rows($one) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_pengguna'] = $row['id_pengguna'];

            $perusahaan = array();
            $perusahaan['id'] = $row['id_perusahaan'];
            $perusahaan['nama'] = $row['nama_perusahaan'];
            $hasil['perusahaan'] = $perusahaan;

            $hasil['nama'] = $row['nama'];
            $hasil['email'] = $row['email'];
            $hasil['password'] = $row['password'];
            $hasil['hak_akses'] = $row['hak_akses'];

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
