<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_perusahaan = anti_injection($con, $_GET['id_perusahaan']);
    $id_maintenance = anti_injection($con, $_GET['id_maintenance']);
    $id_kendaraan = anti_injection($con, $_GET['id_kendaraan']);
    $last = anti_injection($con, $_GET['last']);
    $page = anti_injection($con, $_GET['page']);

    // TODO : @halimi paginasinya ga bisa jalan, ga ada logicnya

    if ($tipe == "all") {

        $batas = 10;
        $halaman = $page;
        if (empty($page)) {
            $posisi = 0;
            $halaman = 1;
        } else {
            $posisi = ($halaman - 1) * $batas;
        }
        $all = mysqli_query($con, "SELECT a.id_maintenance,a.tgl_pengajuan, b.id_pengguna,b.nama as nama_penguna,c.id_perusahaan,c.nama as nama_perusahaan ,d.id_kendaraan,d.no_pol,e.id_kendaraan_sopir,e.nama as nama_sopir,a.status
        FROM maintenance a 
        LEFT OUTER JOIN pengguna b on a.id_pengguna = b.id_pengguna 
        LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
        LEFT OUTER JOIN kendaraan d on a.id_kendaraan = d.id_kendaraan
        LEFT OUTER JOIN kendaraan_sopir e on a.id_kendaraan_sopir = e.id_kendaraan_sopir
        WHERE a.id_perusahaan = '$id_perusahaan'
        ORDER BY a.id_perusahaan ASC limit $posisi,$batas");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            while ($row = mysqli_fetch_array($all)) {
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
                    $status['keterangan'] =  "Pengajuan maintenance telah selesai   ";
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

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else  if ($tipe == "no_pol") {
        $batas = 10;
        if (empty($page)) {
            $posisi = 0;
            $halaman = 1;
        } else {
            $posisi = ($halaman - 1) * $batas;
        }
        if ($last == 1) {
            $urutan = "ASC";
        } else {
            $urutan = "DESC";
        }
        ///ini revisi
        $no_pol = mysqli_query($con, "SELECT a.id_maintenance,a.tgl_pengajuan, b.id_pengguna,b.nama as nama_penguna,c.id_perusahaan,c.nama as nama_perusahaan ,d.id_kendaraan,d.no_pol,e.id_kendaraan_sopir,e.nama as nama_sopir,a.status
        FROM maintenance a 
        LEFT OUTER JOIN pengguna b on a.id_pengguna = b.id_pengguna 
        LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
        LEFT OUTER JOIN kendaraan d on a.id_kendaraan = d.id_kendaraan
        LEFT OUTER JOIN kendaraan_sopir e on a.id_kendaraan_sopir = e.id_kendaraan_sopir
        WHERE d.id_kendaraan is NOT NULL AND a.id_kendaraan = '$id_kendaraan'
        ORDER BY a.id_maintenance $urutan limit $page,$batas");
        if (mysqli_num_rows($no_pol) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($no_pol)) {
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
                    $status['keterangan'] =  "Pengajuan maintenance telah selesai   ";
                }
                $hasil['status'] = $status;

                $maintenance_sql_no_pol = mysqli_query($con, "SELECT a.id_maintenance_detail,a.id_maintenance,c.kategori,b.jenis,a.biaya,a.keterangan,a.nama_memo FROM maintenance_detail a 
                LEFT OUTER JOIN jenis_maintenance b on a.id_jenis_maintenance = b.id_jenis_maintenance
                LEFT OUTER JOIN jenis_maintenance_kategori c on b.id_kategori = c.id_jenis_maintenance_kategori
                where a.id_maintenance = '$id'");
                $maintenance_no_pol = array();
                while ($row_1 = mysqli_fetch_array($maintenance_sql_no_pol)) {
                    $hasil_1 = array();
                    $hasil_1['id_maintenance_detail'] = $row_1['id_maintenance_detail'];
                    $hasil_1['id_maintenance'] = $row_1['id_maintenance'];

                    $jenis = array();
                    $jenis['kategori'] =  $row_1['kategori'];
                    $jenis['nama'] = $row_1['jenis'];
                    $hasil_1['jenis'] = $jenis;

                    $hasil_1['biaya'] = $row_1['biaya'];
                    $hasil_1['keterangan'] = $row_1['keterangan'];
                    array_push($maintenance_no_pol, $hasil_1);
                }
                $hasil['maintenance'] = $maintenance_no_pol;

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else  if ($tipe == "one") {
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
