<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipe = anti_injection($con, $_POST['tipe']);
    $id_pengguna = anti_injection($con, $_POST['id_pengguna']);
    $ekstensi_diperbolehkan    = array('png', 'jpg');
    $nama_ttd = $_FILES['ttd']['name'];
    $x = explode('.', $nama_ttd);
    $ekstensi = strtolower(end($x));
    $nama_baru = acakhuruf(32) . '.' . $ekstensi;
    $file_tmp = $_FILES['ttd']['tmp_name'];

    if ($tipe == "tambah") {
        $cari_foto_tambah = mysqli_query($con, "SELECT ttd FROM pengguna where id_pengguna = '$id_pengguna'");
        if (mysqli_num_rows($cari_foto_tambah) > 0) {
            $row = mysqli_fetch_array($cari_foto_tambah);
            $gambar = $row['ttd'];
            $tambah = "UPDATE pengguna SET ttd = '$nama_baru' where id_pengguna = '$id_pengguna'";
            $query_tambah;
            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                //gambar kosong
                if ($gambar == null) {
                    move_uploaded_file($file_tmp, '../../photo/ttd/' . $nama_baru);
                    $query_tambah = mysqli_query($con, $tambah);
                } else {
                    move_uploaded_file($file_tmp, '../../photo/ttd/' . $nama_baru);
                    $query_tambah = mysqli_query($con, $tambah);
                    //hapus gambar lalu upload
                    if (unlink('../../photo/ttd/' . $gambar)) {
                        move_uploaded_file($file_tmp, '../../photo/ttd/' . $nama_baru);
                        $query_tambah = mysqli_query($con, $tambah);
                    } else {
                        move_uploaded_file($file_tmp, '../../photo/ttd/' . $nama_baru);
                        $query_tambah = mysqli_query($con, $tambah);
                    }
                }
                if ($query_tambah === true) {
                    $response['ttd'] = URL_TTD . $nama_baru;
                    $response["success"] = 1;
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
                    echo json_encode($response);
                }
            } else {
                $response["success"] = 2;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 5;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        $cari_foto_hapus = mysqli_query($con, "SELECT ttd FROM pengguna where id_pengguna = '$id_pengguna'");
        if (mysqli_num_rows($cari_foto_hapus) > 0) {
            $row = mysqli_fetch_array($cari_foto_hapus);
            unlink('../../photo/ttd/' . $row['ttd']);
            $hapus = "UPDATE pengguna SET ttd = null where id_pengguna = '$id_pengguna'";
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
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
