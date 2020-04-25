<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_karyawan = anti_injection($con, $_GET['id_karyawan']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        //memcari data semua
        $all = mysqli_query($con, "select
        a.nama,a.jklm,a.email,a.jabatan,a.no_handphone,a.rekening,
        a.id_karyawan,a.id_perusahaan,b.nama as nama_perusahan
                  FROM karyawan a
                  LEFT OUTER JOIN perusahaan b on a.id_perusahaan = b.id_perusahaan");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_karyawan'] = $row['id_karyawan'];

                $perusahaan = array();
                $perusahaan['id'] = $row['id_perusahaan'];
                $perusahaan['nama'] = $row['nama_perusahan'];
                $hasil['perusahaan'] = $perusahaan;

                $hasil['nama'] = $row['nama'];
                $hasil['jklm'] = $row['jklm'];
                $hasil['email'] = $row['email'];
                $hasil['jabatan'] = $row['jabatan'];
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['rekening'] = $row['rekening'];

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
        $like = mysqli_query($con, "select
        a.nama,a.jklm,a.email,a.jabatan,a.no_handphone,a.rekening,
        a.id_karyawan,a.id_perusahaan,b.nama as nama_perusahan
        FROM karyawan a
         LEFT OUTER JOIN perusahaan b on a.id_perusahaan = b.id_perusahaan
         where a.nama like '%$like%'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_karyawan'] = $row['id_karyawan'];

                $perusahaan = array();
                $perusahaan['id'] = $row['id_perusahaan'];
                $perusahaan['nama'] = $row['nama_perusahan'];
                $hasil['perusahaan'] = $perusahaan;

                $hasil['nama'] = $row['nama'];
                $hasil['jklm'] = $row['jklm'];
                $hasil['email'] = $row['email'];
                $hasil['jabatan'] = $row['jabatan'];
                $hasil['no_handphone'] = $row['no_handphone'];
                $hasil['rekening'] =  $row['rekening'];

                array_push($response['data'], $hasil);
            }
            $response["success"] = 1;
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        //tampilkan data 1 
        $one = mysqli_query($con, "select
        a.nama,a.jklm,a.email,a.jabatan,a.no_handphone,a.rekening,
        a.id_karyawan,a.id_perusahaan,b.nama as nama_perusahan
        FROM karyawan a
         LEFT OUTER JOIN perusahaan b on a.id_perusahaan = b.id_perusahaan
         where a.id_karyawan = '$id_karyawan'");
        if (mysqli_num_rows($one) > 0) {
            //jika ada
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_karyawan'] = $row['id_karyawan'];

            $perusahaan = array();
            $perusahaan['id'] = $row['id_perusahaan'];
            $perusahaan['nama'] = $row['nama_perusahan'];
            $hasil['perusahaan'] = $perusahaan;

            $hasil['nama'] = $row['nama'];
            $hasil['jklm'] = $row['jklm'];
            $hasil['email'] = $row['email'];
            $hasil['jabatan'] = $row['jabatan'];
            $hasil['no_handphone'] = $row['no_handphone'];
            $hasil['rekening'] = $row['rekening'];

            $response["success"] = 1;
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
