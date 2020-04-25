<?php

require_once('../koneksi.php');
require_once('../notif.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_pengguna = anti_injection($con, $_POST['id_pengguna']);
    $id_perusahaan = anti_injection($con, $_POST['id_perusahaan']);
    $id_kendaraan = anti_injection($con, $_POST['id_kendaraan']);
    $id_kendaraan_sopir = anti_injection($con, $_POST['id_kendaraan_sopir']);
    $id_maintenance = anti_injection($con, $_POST['id_maintenance']);
    $no_memo = anti_injection($con, $_POST['no_memo']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO maintenance (id_pengguna,id_perusahaan,id_kendaraan,id_kendaraan_sopir,status) VALUES ('$id_pengguna','$id_perusahaan','$id_kendaraan','$id_kendaraan_sopir','1')";
        if (mysqli_query($con, $tambah)) {
            $id = mysqli_insert_id($con);
            $tampil = mysqli_query($con, "SELECT 
            a.id_maintenance,a.tgl_pengajuan, b.id_pengguna,b.nama as nama_penguna,c.id_perusahaan,c.nama as nama_perusahaan ,d.id_kendaraan,d.no_pol,e.id_kendaraan_sopir,e.nama as nama_sopir,a.status
            FROM maintenance a 
            LEFT OUTER JOIN pengguna b on a.id_pengguna = b.id_pengguna 
            LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
            LEFT OUTER JOIN kendaraan d on a.id_kendaraan = d.id_kendaraan
            LEFT OUTER JOIN kendaraan_sopir e on a.id_kendaraan_sopir = e.id_kendaraan_sopir
            where a.id_maintenance = '$id'");
            if (mysqli_num_fields($tampil) > 0) {
                $row = mysqli_fetch_array($tampil);
                $hasil = array();
                $hasil['id_maintenance'] = $id;
                $hasil['tgl_pengajuan'] = $row['tgl_pengajuan'];

                $pengguna = array();
                $pengguna['id'] =  $row['id_pengguna'];
                $pengguna['nama'] = $row['nama_penguna'];
                $hasil['pengguna'] = $pengguna;

                $perusahaan = array();
                $perusahaan['id'] =  $row['id_perusahaan'];
                $perusahaan['nama'] = $row['nama_perusahaan'];
                $hasil['perusahaan'] = $perusahaan;

                $kendaraan = array();
                $kendaraan['id'] =  $row['id_kendaraan'];
                $kendaraan['no_pol'] = $row['no_pol'];
                $hasil['kendaraan'] = $kendaraan;

                $sopir = array();
                $sopir['id'] =  $row['id_kendaraan_sopir'];
                $sopir['nama'] = $row['nama_sopir'];
                $hasil['sopir'] = $sopir;

                $hasil['status'] = $row['status'];
                $response["success"] = 1;
                $response['data'] = $hasil;
                echo json_encode($response);
                $token = mysqli_fetch_array(mysqli_query($con, "SELECT *  FROM notif_token WHERE id_perusahaan = '$id_perusahaan' AND hak_akses ='4'"));
                notif(
                    "Pengajuan Maintenance " . $row['no_pol'],
                    "Membutuhkan persetujuan anda untuk melanjutkan",
                    "Maintenance",
                    "maintenance_pengajuan",
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
    } else if ($tipe == "tambah_memo") {
        $update = "UPDATE maintenance set no_memo = '$no_memo' where id_maintenance='$id_maintenance'";
        if (mysqli_query($con, $update)) {
            $one = mysqli_query($con, "SELECT a.id_maintenance,a.tgl_pengajuan, b.id_pengguna,b.nama as nama_penguna,c.id_perusahaan,c.nama as nama_perusahaan ,d.id_kendaraan,d.no_pol,e.id_kendaraan_sopir,e.nama as nama_sopir,a.status,b.ttd,a.no_memo
            FROM maintenance a 
            LEFT OUTER JOIN pengguna b on a.id_pengguna = b.id_pengguna 
            LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
            LEFT OUTER JOIN kendaraan d on a.id_kendaraan = d.id_kendaraan
            LEFT OUTER JOIN kendaraan_sopir e on a.id_kendaraan_sopir = e.id_kendaraan_sopir
            where a.id_maintenance = '$id_maintenance'");
            if (mysqli_num_rows($one) > 0) {
                $row = mysqli_fetch_array($one);
                $response["success"] = 1;
                $response['data'] = array();
                $hasil = array();
                $id = $row['id_maintenance'];
                $hasil['id_maintenance'] = $row['id_maintenance'];
                $hasil['tgl_pengajuan'] = $row['tgl_pengajuan'];

                $pengguna = array();
                $pengguna['id'] =  $row['id_pengguna'];
                $pengguna['nama'] = $row['nama_penguna'];
                $hasil['pengguna'] = $pengguna;

                $perusahaan = array();
                $perusahaan['id'] =  $row['id_perusahaan'];
                $perusahaan['nama'] = $row['nama_perusahaan'];
                $hasil['perusahaan'] = $perusahaan;

                $kendaraan = array();
                $kendaraan['id'] =  $row['id_kendaraan'];
                $kendaraan['no_pol'] = $row['no_pol'];
                $hasil['kendaraan'] = $kendaraan;

                $sopir = array();
                $sopir['id'] =  $row['id_kendaraan_sopir'];
                $sopir['nama'] = $row['nama_sopir'];
                $hasil['sopir'] = $sopir;

                $status = array();
                $status['kode'] =  $row['status'];
                if ($row['status'] == 1) {
                    $status['keterangan'] =  "Proses pengajuan maintenance";
                } else if ($row['status'] == 2) {
                    $status['keterangan'] =  "Pengajuan maintenance telah disetujui";
                } else if ($row['status'] == 3) {
                    $status['keterangan'] =  "Proses pengajuan biaya maintenance";
                } else if ($row['status'] == 4) {
                    $status['keterangan'] =  "Pengajuan biaya maintenance telah disetujui";
                } else if ($row['status'] == 5) {
                    $status['keterangan'] =  "Pengajuan maintenance telah selesai";
                }
                $hasil['status'] = $status;

                $maintenance_sql = mysqli_query($con, "SELECT a.id_maintenance_detail,a.id_maintenance,c.kategori,b.jenis,a.biaya,a.keterangan,a.nama_memo FROM maintenance_detail a 
                LEFT OUTER JOIN jenis_maintenance b on a.id_jenis_maintenance = b.id_jenis_maintenance
                LEFT OUTER JOIN jenis_maintenance_kategori c on b.id_kategori = c.id_jenis_maintenance_kategori
                where a.id_maintenance = '$id'");
                $maintenance = array();
                while ($row_1 = mysqli_fetch_array($maintenance_sql)) {
                    $hasil_1 = array();
                    $hasil_1['id_maintenance_detail'] = $row_1['id_maintenance_detail'];
                    $hasil_1['id_maintenance'] = $row_1['id_maintenance'];

                    $jenis = array();
                    $jenis['kategori'] =  $row_1['kategori'];
                    $jenis['nama'] = $row_1['jenis'];
                    $hasil_1['jenis'] = $jenis;

                    $hasil_1['biaya'] = $row_1['biaya'];
                    $hasil_1['keterangan'] = $row_1['keterangan'];
                    array_push($maintenance, $hasil_1);
                }
                $hasil['maintenance'] = $maintenance;

                $dokumen_sql = mysqli_query($con, "SELECT * FROM maintenance_dokumen where id_maintenance = '$id'");
                $dokumen = array();
                while ($row_2 = mysqli_fetch_array($dokumen_sql)) {
                    $hasil_2 = array();
                    $hasil_2['id_maintenance_dokumen'] = $row_2['id_maintenance_dokumen'];
                    $hasil_2['judul'] = $row_2['judul'];
                    $hasil_2['file'] = URL_DOKUMEN . $row_2['file'];
                    array_push($dokumen, $hasil_2);
                }
                $hasil['dokumen'] = $dokumen;
                $hasil['nomor_memo'] = $row['no_memo'];
                if ($row['status'] == 5) {
                    $hasil['ttd_direktur'] = URL_TTD . $row['ttd'];
                }
                array_push($response['data'], $hasil);
                echo json_encode($response);
            } else {
                $response["success"] = 0;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else   if ($tipe == "hapus") {
        $cari = mysqli_query($con, "SELECT status FROM maintenance where id_maintenance = '$id_maintenance'");
        if (mysqli_num_rows($cari) > 0) {
            $row = mysqli_fetch_array($cari);
            if ($row['status'] >= 0 && $row['status']  <= 3) {
                $hapus = "DELETE FROM  maintenance where id_maintenance = '$id_maintenance'";
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
