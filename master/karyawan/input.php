<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_karyawan = anti_injection($con, $_POST['id_karyawan']);
    $id_perusahaan = anti_injection($con, $_POST['id_perusahaan']);
    $nama = anti_injection($con, $_POST['nama']);
    $jklm = anti_injection($con, $_POST['jklm']);
    $email = anti_injection($con, $_POST['email']);
    $jabatan = anti_injection($con, $_POST['jabatan']);
    $no_handphone = anti_injection($con, $_POST['no_handphone']);
    $rekening = anti_injection($con, $_POST['rekening']);

    if ($tipe == "tambah") {

        $tambah = "INSERT INTO karyawan (id_perusahaan,nama,jklm,email,jabatan,no_handphone,rekening) values
      ('$id_perusahaan','$nama','$jklm','$email','$jabatan','$no_handphone','$rekening')";

        if (mysqli_query($con, $tambah)) {
            $hasil['id_karyawan'] = mysqli_insert_id($con);
            $id = mysqli_insert_id($con);
            $read = mysqli_query($con, "select a.id_karyawan,a.id_perusahaan,b.nama as nama_perusahan
          FROM karyawan a
          LEFT OUTER JOIN perusahaan b on a.id_perusahaan = b.id_perusahaan
          where a.id_karyawan = '$id'");
            $row = mysqli_fetch_array($read);
            $response["success"] = 1;
            $perusahaan = array();
            $perusahaan['id'] = $row['id_perusahaan'];
            $perusahaan['nama'] = $row['nama_perusahan'];
            $hasil['perusahaan'] = $perusahaan;

            $hasil['nama'] = $nama;
            $hasil['jklm'] = $jklm;
            $hasil['email'] = $email;
            $hasil['jabatan'] = $jabatan;
            $hasil['no_handphone'] = $no_handphone;
            $hasil['rekening'] = $rekening;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $cari = mysqli_query($con, "SELECT * FROM karyawan where id_karyawan = '$id_karyawan'");

        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE karyawan SET id_perusahaan = '$id_perusahaan', nama ='$nama', jklm= '$jklm', email= '$email', jabatan = '$jabatan', no_handphone = '$no_handphone', rekening = '$rekening' where id_karyawan = '$id_karyawan'";
            if (mysqli_query($con, $edit)) {
                $hasil['id_karyawan'] = $id_karyawan;
                $read = mysqli_query($con, "select a.id_karyawan,a.id_perusahaan,b.nama as nama_perusahan
              FROM karyawan a
              LEFT OUTER JOIN perusahaan b on a.id_perusahaan = b.id_perusahaan
              where a.id_karyawan = '$id_karyawan'");
                $row = mysqli_fetch_array($read);
                $response["success"] = 1;
                $perusahaan = array();
                $perusahaan['id'] = $row['id_perusahaan'];
                $perusahaan['nama'] = $row['nama_perusahan'];
                $hasil['perusahaan'] = $perusahaan;

                $hasil['nama'] = $nama;
                $hasil['jklm'] = $jklm;
                $hasil['email'] = $email;
                $hasil['jabatan'] = $jabatan;
                $hasil['no_handphone'] = $no_handphone;
                $hasil['rekening'] = $rekening;
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
    } else if ($tipe == "hapus") {
        $cari = mysqli_query($con, "SELECT * FROM karyawan where id_karyawan = '$id_karyawan'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus = "DELETE FROM karyawan WHERE id_karyawan = '$id_karyawan'";
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
