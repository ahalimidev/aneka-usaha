<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $asal = anti_injection($con, $_POST['asal']);
    $tujuan = anti_injection($con, $_POST['tujuan']);
    $jarak = anti_injection($con, $_POST['jarak']);
    $biaya_jasa = anti_injection($con, $_POST['biaya_jasa']);
    $biaya_satuan = anti_injection($con, $_POST['biaya_satuan']);
    $biaya_operasional = anti_injection($con, $_POST['biaya_operasional']);
    $biaya_gaji = anti_injection($con, $_POST['biaya_gaji']);
    $biaya_gendongan = anti_injection($con, $_POST['biaya_gendongan']);
    $id_daftar_biaya = anti_injection($con, $_POST['id_daftar_biaya']);
    $id_pengiriman_jenis = anti_injection($con, $_POST['id_pengiriman_jenis']);
    $id_kendaraan_jenis = anti_injection($con, $_POST['id_kendaraan_jenis']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO daftar_biaya (asal,tujuan,jarak,biaya_jasa,biaya_satuan,biaya_operasional,biaya_gaji,biaya_gendongan,id_pengiriman_jenis, id_kendaraan_jenis) 
        values ('$asal','$tujuan','$jarak','$biaya_jasa','$biaya_satuan','$biaya_operasional','$biaya_gaji','$biaya_gendongan','$id_pengiriman_jenis','$id_kendaraan_jenis')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_daftar_biaya'] = mysqli_insert_id($con);
            $id = mysqli_insert_id($con);
            $red_join_tambah = mysqli_query($con, "SELECT a.asal,b.nama as nama_asal, a.tujuan, c.nama as nama_tujuan,a.id_pengiriman_jenis,d.jenis as nama_jenis, a.id_kendaraan_jenis, k.nama as nama_kendaraan_jenis
            FROM daftar_biaya a 
            LEFT OUTER JOIN sub_client b on a.asal = b.id_sub_client
            LEFT OUTER JOIN sub_client c on a.tujuan = c.id_sub_client
            LEFT OUTER JOIN pengiriman_jenis d on a.id_pengiriman_jenis = d.id_pengiriman_jenis
            LEFT OUTER JOIN kendaraan_jenis k on a.id_kendaraan_jenis = k.id_kendaraan_jenis
            where a.id_daftar_biaya ='$id'");
            $row = mysqli_fetch_array($red_join_tambah);

            $asal_ = array();
            $asal_['id'] = $row['asal'];
            $asal_['nama'] = $row['nama_asal'];
            $hasil['asal'] = $asal_;

            $tujuan_ = array();
            $tujuan_['id'] = $row['tujuan'];
            $tujuan_['nama'] = $row['nama_tujuan'];
            $hasil['tujuan'] = $tujuan_;

            $jenis_pengiriman = array();
            $jenis_pengiriman['id'] = $row['id_pengiriman_jenis'];
            $jenis_pengiriman['nama'] = $row['nama_jenis'];
            $hasil['jenis_pengiriman'] = $jenis_pengiriman;

            $jenis_kendaraan = array();
            $jenis_kendaraan['id'] = $row['id_kendaraan_jenis'];
            $jenis_kendaraan['nama'] = $row['nama_kendaraan_jenis'];
            $hasil['jenis_kendaraan'] = $jenis_kendaraan;

            $hasil['jarak'] = $jarak;
            $hasil['biaya_jasa'] = $biaya_jasa;
            $hasil['biaya_satuan'] = $biaya_satuan;
            $hasil['biaya_operasional'] = $biaya_operasional;
            $hasil['biaya_gaji'] = $biaya_gaji;
            $hasil['biaya_gendongan'] = $biaya_gendongan;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $sql = mysqli_query($con, "SELECT * FROM daftar_biaya WHERE id_daftar_biaya = '$id_daftar_biaya'");
        if (mysqli_num_rows($sql) > 0) {
            $edit = "UPDATE daftar_biaya SET asal='$asal',
        tujuan='$tujuan',
        jarak='$jarak',
        biaya_jasa='$biaya_jasa',
        biaya_satuan='$biaya_satuan',
        biaya_operasional='$biaya_operasional',
        id_pengiriman_jenis='$id_pengiriman_jenis',
        id_kendaraan_jenis='$id_kendaraan_jenis',
        biaya_gaji='$biaya_gaji',
        biaya_gendongan='$biaya_gendongan'
        WHERE id_daftar_biaya='$id_daftar_biaya'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_daftar_biaya'] = $id_daftar_biaya;
                $red_join_tambah = mysqli_query($con, "SELECT a.asal,b.nama as nama_asal, a.tujuan, c.nama as nama_tujuan,a.id_pengiriman_jenis,d.jenis as nama_jenis, a.id_kendaraan_jenis, k.nama as nama_kendaraan_jenis
                FROM daftar_biaya a 
                LEFT OUTER JOIN sub_client b on a.asal = b.id_sub_client
                LEFT OUTER JOIN sub_client c on a.tujuan = c.id_sub_client
                LEFT OUTER JOIN pengiriman_jenis d on a.id_pengiriman_jenis = d.id_pengiriman_jenis
                LEFT OUTER JOIN kendaraan_jenis k on a.id_kendaraan_jenis = k.id_kendaraan_jenis where a.id_daftar_biaya ='$id_daftar_biaya'");

                $row = mysqli_fetch_array($red_join_tambah);

                $asal_ = array();
                $asal_['id'] = $row['asal'];
                $asal_['nama'] = $row['nama_asal'];
                $hasil['asal'] = $asal_;

                $tujuan_ = array();
                $tujuan_['id'] = $row['tujuan'];
                $tujuan_['nama'] = $row['nama_tujuan'];
                $hasil['tujuan'] = $tujuan_;

                $jenis_pengiriman = array();
                $jenis_pengiriman['id'] = $row['id_pengiriman_jenis'];
                $jenis_pengiriman['nama'] = $row['nama_jenis'];
                $hasil['jenis_pengiriman'] = $jenis_pengiriman;

                $jenis_kendaraan = array();
                $jenis_kendaraan['id'] = $row['id_kendaraan_jenis'];
                $jenis_kendaraan['nama'] = $row['nama_kendaraan_jenis'];
                $hasil['jenis_kendaraan'] = $jenis_kendaraan;

                $hasil['jarak'] = $jarak;
                $hasil['biaya_jasa'] = $biaya_jasa;
                $hasil['biaya_satuan'] = $biaya_satuan;
                $hasil['biaya_operasional'] = $biaya_operasional;
                $hasil['biaya_gaji'] = $biaya_gaji;
                $hasil['biaya_gendongan'] = $biaya_gendongan;
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
        $sql = mysqli_query($con, "SELECT * FROM  daftar_biaya WHERE id_daftar_biaya = '$id_daftar_biaya'");
        if (mysqli_num_rows($sql) > 0) {
            $hapus = "DELETE FROM daftar_biaya WHERE id_daftar_biaya = '$id_daftar_biaya'";
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
