<?php

require_once('../koneksi.php');
require_once('../notif.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_maintenance = anti_injection($con, $_POST['id_maintenance']);
    $judul = anti_injection($con, $_POST['judul']);
    $id_maintenance_dokumen = anti_injection($con, $_POST['id_maintenance_dokumen']);

    $ekstensi_diperbolehkan    = array('png', 'jpg', 'pdf');
    $nama_dokumen = $_FILES['file']['name'];
    $x_dokumen = explode('.', $nama_dokumen);
    $ekstensi_dokumen = strtolower(end($x_dokumen));
    $nama_baru_dokumen = acakhuruf(32) . '.' . $ekstensi_dokumen;
    $file_tmp_dokumen = $_FILES['file']['tmp_name'];
    if ($tipe == "tambah") {
        if (in_array($ekstensi_dokumen, $ekstensi_diperbolehkan) === true) {
            move_uploaded_file($file_tmp_dokumen, '../photo/dokumen/' . $nama_baru_dokumen);
            $simpan = "INSERT INTO maintenance_dokumen (id_maintenance,judul,file) values ('$id_maintenance','$judul','$nama_baru_dokumen')";
            if (mysqli_query($con, $simpan)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_maintenance_dokumen'] = mysqli_insert_id($con);
                $hasil['id_maintenance'] = $id_maintenance;
                $hasil['judul'] = $judul;
                $hasil['file'] = URL_DOKUMEN . $nama_baru_dokumen;
                $response['data'] = $hasil;
                echo json_encode($response);
                $token = mysqli_fetch_array(mysqli_query($con, "SELECT a.token,c.no_pol
                FROM notif_token a 
                LEFT OUTER JOIN maintenance b on a.id_perusahaan = b.id_perusahaan 
                LEFT OUTER JOIN kendaraan c on  b.id_kendaraan = c.id_kendaraan
                WHERE b.id_maintenance = '$id_maintenance' and a.hak_akses = '4' LIMIT  1"));
                notif(
                    "Pengajuan Biaya Maintenance " . $token['no_pol'],
                    "Disetujui, anda dapat melanjutkan proses pengajuan",
                    "Maintenance",
                    "maintenance_acc_biaya",
                    $token['token']
                );
            } else {
                $response["success"] = 0;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 2;
            echo json_encode($response);
        }
    } else  if ($tipe == "hapus") {
        $cari = mysqli_query($con, "SELECT * FROM maintenance_dokumen where id_maintenance_dokumen  ='$id_maintenance_dokumen '");
        if (mysqli_num_rows($cari) > 0) {
            $row = mysqli_fetch_array($cari);
            unlink('../photo/dokumen/' . $row['file']);
            $hapus = "DELETE FROM maintenance_dokumen where id_maintenance_dokumen  ='$id_maintenance_dokumen '";
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
