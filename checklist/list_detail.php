<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tipe = anti_injection($con, $_GET['tipe']);
    $id_checklist_transaksi = anti_injection($con, $_GET['id_checklist_transaksi']);
    $id_kendaraan = anti_injection($con, $_GET['id_kendaraan']);

    if ($tipe == "all") {
     
        
        $tampil = mysqli_query($con, "SELECT 
                        a.id_checklist_transaksi_detail,
                        a.id_checklist_transaksi,
                        c.id_kendaraan,
                        d.no_pol,
                        e.id_pengiriman_jenis,
                        e.jenis,
                        f.id_kendaraan_sopir,
                        f.nama as nama_sopir,
                        a.id_checklist_master,
                        b.nama,
                        a.kondisi,
                        a.keterangan
                        FROM checklist_transaksi_detail a 
                        LEFT OUTER JOIN checklist_master b on a.id_checklist_master = b.id_checklist_master
                        LEFT OUTER JOIN checklist_transaksi c on a.id_checklist_transaksi = c.id_checklist_transaksi
                        LEFT OUTER JOIN kendaraan d on c.id_kendaraan = d.id_kendaraan
                        LEFT OUTER JOIN pengiriman_jenis e on e.id_pengiriman_jenis = d.id_pengiriman_jenis
                        LEFT OUTER JOIN kendaraan_sopir f on d.id_kendaraan_sopir = f.id_kendaraan_sopir
                        where a.id_checklist_transaksi = '$id_checklist_transaksi' ");
        if (mysqli_num_rows($tampil) > 0) {
            $response["success"] = 1;
            $response['data'] = array();
            while ($row = mysqli_fetch_array($tampil)) {
                $hasil = array();
                $hasil['id_checklist_transaksi_detail'] = $row['id_checklist_transaksi_detail'];
                $hasil['id_checklist_transaksi'] = $row['id_checklist_transaksi'];

                $kendaraan = array();
                $kendaraan['id'] = $row['id_kendaraan'];
                $kendaraan['no_pol'] = $row['no_pol'];
                $hasil['kendaraan'] = $kendaraan;

                $jenis = array();
                $jenis['id'] = $row['id_pengiriman_jenis'];
                $jenis['nama'] = $row['jenis'];
                $hasil['jenis'] = $jenis;

                $sopir = array();
                $sopir['id'] = $row['id_pengiriman_jenis'];
                $sopir['nama'] = $row['nama_sopir'];
                $hasil['sopir'] = $sopir;

                $checklist_master = array();
                $checklist_master['id'] = $row['id_checklist_master'];
                $checklist_master['nama'] = $row['nama'];
                $hasil['checklist_master'] = $checklist_master;


                $hasil['kondisi'] = $row['kondisi'];
                $hasil['keterangan'] = $row['keterangan'];

                $total = mysqli_query($con, "SELECT  id_checklist_transaksi, SUM(if(kondisi=0,0,1)) AS tidak_baik, SUM(if(kondisi=1,1,0)) AS baik FROM checklist_transaksi_detail WHERE id_checklist_transaksi = '$id_checklist_transaksi' GROUP BY id_checklist_transaksi");

                while( $row1 = mysqli_fetch_array($total)){
                    $hasil['tidak_baik'] = $row1['tidak_baik'];
                    $hasil['baik'] = $row1['baik'];
                }
             
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else  if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT 
        a.id_checklist_transaksi_detail,
        a.id_checklist_transaksi,
        c.id_kendaraan,
        d.no_pol,
        e.id_pengiriman_jenis,
        e.jenis,
        f.id_kendaraan_sopir,
        f.nama as nama_sopir,
        a.id_checklist_master,
        b.nama,
        a.kondisi,
        a.keterangan
        FROM checklist_transaksi_detail a 
        LEFT OUTER JOIN checklist_master b on a.id_checklist_master = b.id_checklist_master
        LEFT OUTER JOIN checklist_transaksi c on a.id_checklist_transaksi = c.id_checklist_transaksi
        LEFT OUTER JOIN kendaraan d on c.id_kendaraan = d.id_kendaraan
        LEFT OUTER JOIN pengiriman_jenis e on e.id_pengiriman_jenis = d.id_pengiriman_jenis
        LEFT OUTER JOIN kendaraan_sopir f on d.id_kendaraan_sopir = f.id_kendaraan_sopir
        where c.id_kendaraan = '$id_kendaraan'");

        if (mysqli_num_rows($like) > 0) {
            $response["success"] = 1;
            $response['data'] = array();
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_checklist_transaksi_detail'] = $row['id_checklist_transaksi_detail'];
                $hasil['id_checklist_transaksi'] = $row['id_checklist_transaksi'];

                $kendaraan = array();
                $kendaraan['id'] = $row['id_kendaraan'];
                $kendaraan['no_pol'] = $row['no_pol'];
                $hasil['kendaraan'] = $kendaraan;

                $jenis = array();
                $jenis['id'] = $row['id_pengiriman_jenis'];
                $jenis['nama'] = $row['jenis'];
                $hasil['jenis'] = $jenis;

                $sopir = array();
                $sopir['id'] = $row['id_pengiriman_jenis'];
                $sopir['nama'] = $row['nama_sopir'];
                $hasil['sopir'] = $sopir;

                $checklist_master = array();
                $checklist_master['id'] = $row['id_checklist_master'];
                $checklist_master['nama'] = $row['nama'];
                $hasil['checklist_master'] = $checklist_master;

                $total = mysqli_query($con, "SELECT  b.id_kendaraan, SUM(if(a.kondisi=0,1,0)) AS tidak_baik, SUM(if(a.kondisi=1,1,0)) AS baik FROM checklist_transaksi_detail a LEFT OUTER JOIN checklist_transaksi b on a.id_checklist_transaksi = b.id_checklist_transaksi WHERE b.id_kendaraan = '$id_kendaraan' GROUP BY id_kendaraan");

                while( $row1 = mysqli_fetch_array($total)){
                    $hasil['tidak_baik'] = $row1['tidak_baik'];
                    $hasil['baik'] = $row1['baik'];
                }

                $hasil['kondisi'] = $row['kondisi'];
                $hasil['keterangan'] = $row['keterangan'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else  {

        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
