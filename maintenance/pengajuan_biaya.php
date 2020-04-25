<?php

require_once('../koneksi.php');
require_once('../notif.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_maintenance = anti_injection($con, $_POST['id_maintenance']);
    $id_jenis_maintenance = anti_injection($con, $_POST['id_jenis_maintenance']);
    $biaya = anti_injection($con, $_POST['biaya']);
    $keterangan = anti_injection($con, $_POST['keterangan']);
    $id_maintenance_detail = anti_injection($con, $_POST['id_maintenance_detail']);
    $nama_memo = anti_injection($con, $_POST['nama_memo']);


    if ($tipe == "tambah") {
        $tambah = "INSERT INTO maintenance_detail (id_maintenance,id_jenis_maintenance,biaya,keterangan,nama_memo) VALUES ('$id_maintenance','$id_jenis_maintenance','$biaya','$keterangan','$nama_memo')";
        if (mysqli_query($con, $tambah)) {
            $id = mysqli_insert_id($con);
            $tampil = mysqli_query($con, "SELECT a.id_maintenance_detail,a.id_maintenance,c.kategori,b.jenis,a.biaya,a.keterangan,a.nama_memo FROM maintenance_detail a 
            LEFT OUTER JOIN jenis_maintenance b on a.id_jenis_maintenance = b.id_jenis_maintenance
            LEFT OUTER JOIN jenis_maintenance_kategori c on b.id_kategori = c.id_jenis_maintenance_kategori
            where a.id_maintenance_detail = '$id'");
            if (mysqli_num_fields($tampil) > 0) {
                $row = mysqli_fetch_array($tampil);
                $hasil = array();
                $hasil['id_maintenance_detail'] = $id;
                $hasil['id_maintenance'] = $row['id_maintenance'];

                $jenis = array();
                $jenis['kategori'] =  $row['kategori'];
                $jenis['nama'] = $row['jenis'];
                $hasil['jenis'] = $jenis;

                $hasil['biaya'] = $row['biaya'];
                $hasil['keterangan'] = $row['keterangan'];
                $response["success"] = 1;
                $response['data'] = $hasil;
                echo json_encode($response);
                $token = mysqli_fetch_array(mysqli_query($con, "SELECT a.token,c.no_pol
                FROM notif_token a 
                LEFT OUTER JOIN maintenance b on a.id_perusahaan = b.id_perusahaan 
                LEFT OUTER JOIN kendaraan c on  b.id_kendaraan = c.id_kendaraan
                WHERE b.id_maintenance = '$id_maintenance' and a.hak_akses = '4' LIMIT  1"));
                notif(
                    "Pengajuan Biaya Maintenance " . $token['no_pol'],
                    "Membutuhkan persetujuan anda untuk melanjutkan",
                    "Maintenance",
                    "maintenance_biaya",
                    $token['token']
                );
            } else {
                $response["success"] = 0;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else   if ($tipe == "hapus") {
        $cari = mysqli_query($con, "SELECT b.status FROM maintenance_detail a LEFT OUTER JOIN maintenance b on a.id_maintenance = b.id_maintenance where a.id_maintenance_detail = '$id_maintenance_detail'");
        if (mysqli_num_rows($cari) > 0) {
            $row = mysqli_fetch_array($cari);
            if ($row['status'] >= 0 && $row['status']  <= 4) {
                $hapus = "DELETE FROM  maintenance_detail where id_maintenance_detail = '$id_maintenance_detail'";
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
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
