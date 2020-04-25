<?php

require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $email = anti_injection($con, $_POST['email']);
    $password = anti_injection($con, $_POST['password']);
    $token = anti_injection($con, $_POST['token']);
    $id_pengguna = anti_injection($con, $_POST['id_pengguna']);


    if ($tipe == "login") {
        $login = mysqli_query($con, "SELECT a.id_pengguna,a.nama,a.email,a.password,a.id_perusahaan,b.nama as nama_perusahaan,a.hak_akses,c.token FROM pengguna a LEFT OUTER JOIN perusahaan b on b.id_perusahaan = a.id_perusahaan LEFT OUTER JOIN notif_token c on a.id_pengguna = c.id_pengguna where a.email = '$email' and a.password = '$password'");
        if (mysqli_num_rows($login) > 0) {
            $row = mysqli_fetch_array($login);
            $id_pengguna = $row['id_pengguna'];
            $id_perusahaan = $row['id_perusahaan'];
            $hak_akses = $row['hak_akses'];
            if ($row['token'] == null) {
                $simpan = "INSERT INTO notif_token (id_pengguna,id_perusahaan,hak_akses,token) VALUES ('$id_pengguna','$id_perusahaan','$hak_akses','$token')";
                if (mysqli_query($con, $simpan)) {
                    $response["success"] = 1;
                    $hasil = array();
                    $hasil['id_pengguna'] = $id_pengguna;
                    $perusahaan = array();
                    $perusahaan['id'] =  $row['id_perusahaan'];
                    $perusahaan['nama'] = $row['nama_perusahaan'];
                    $hasil['perusahaan'] = $perusahaan;
                    $hasil['nama'] = $row['nama'];;
                    $hasil['email'] = $email;
                    $hasil['password'] = $password;
                    $hasil['hak_akses'] = $hak_akses;
                    $hasil['token'] = $token;
                    $response['data'] = $hasil;
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
                    echo json_encode($response);
                }
            } else {
                $update = "UPDATE notif_token SET id_perusahaan = '$id_perusahaan' , hak_akses = '$hak_akses', token = '$token' where  id_pengguna = '$id_pengguna'";
                if (mysqli_query($con, $update)) {
                    $response["success"] = 1;
                    $hasil = array();
                    $hasil['id_pengguna'] = $id_pengguna;
                    $perusahaan = array();
                    $perusahaan['id'] =  $row['id_perusahaan'];
                    $perusahaan['nama'] = $row['nama_perusahaan'];
                    $hasil['perusahaan'] = $perusahaan;
                    $hasil['nama'] = $row['nama'];;
                    $hasil['email'] = $email;
                    $hasil['password'] = $password;
                    $hasil['hak_akses'] = $hak_akses;
                    $hasil['token'] = $token;
                    $response['data'] = $hasil;
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
                    echo json_encode($response);
                }
            }
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "logout") {
        $cari = mysqli_query($con, "SELECT * FROM notif_token where id_pengguna = '$id_pengguna'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus =  "DELETE  FROM notif_token where id_pengguna = '$id_pengguna'";
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
    } else if ($tipe == "update_token") {
        $cari_token = mysqli_query($con, "SELECT * FROM notif_token where id_pengguna = '$id_pengguna'");
        if (mysqli_num_rows($cari_token) > 0) {
            $update_token =  "UPDATE notif_token set token = '$token' where id_pengguna = '$id_pengguna'";
            if (mysqli_query($con, $update_token)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['token'] = $token;
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
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
