<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_daftar_biaya = anti_injection($con, $_GET['id_daftar_biaya']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT 
        a.id_daftar_biaya,a.jarak,a.biaya_jasa,a.biaya_satuan,a.biaya_operasional,a.biaya_gaji,a.biaya_gendongan,
        a.asal,b.nama as nama_asal, a.tujuan, c.nama as nama_tujuan,a.id_pengiriman_jenis,d.jenis as nama_jenis, a.id_kendaraan_jenis, kj.nama as nama_kendaraan_jenis 
        FROM daftar_biaya a
        LEFT OUTER JOIN sub_client b on a.asal = b.id_sub_client
        LEFT OUTER JOIN sub_client c on a.tujuan = c.id_sub_client
        LEFT OUTER JOIN pengiriman_jenis d on a.id_pengiriman_jenis = d.id_pengiriman_jenis
        LEFT OUTER JOIN kendaraan_jenis kj on a.id_kendaraan_jenis = kj.id_kendaraan_jenis");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_daftar_biaya'] = $row['id_daftar_biaya'];
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

                $hasil['jarak'] =  $row['jarak'] . 'km';
                $hasil['biaya_jasa'] =  $row['biaya_jasa'];
                $hasil['biaya_satuan'] =  $row['biaya_satuan'];
                $hasil['biaya_operasional'] =  $row['biaya_operasional'];
                $hasil['biaya_gaji'] =  $row['biaya_gaji'];
                $hasil['biaya_gendongan'] =  $row['biaya_gendongan'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT 
        a.id_daftar_biaya,a.jarak,a.biaya_jasa,a.biaya_satuan,a.biaya_operasional,a.biaya_gaji,a.biaya_gendongan,
        a.asal,b.nama as nama_asal, a.tujuan, c.nama as nama_tujuan,a.id_pengiriman_jenis,d.jenis as nama_jenis, a.id_kendaraan_jenis, kj.nama as nama_kendaraan_jenis 
        FROM daftar_biaya a
        LEFT OUTER JOIN sub_client b on a.asal = b.id_sub_client
        LEFT OUTER JOIN sub_client c on a.tujuan = c.id_sub_client
        LEFT OUTER JOIN pengiriman_jenis d on a.id_pengiriman_jenis = d.id_pengiriman_jenis
        LEFT OUTER JOIN kendaraan_jenis kj on a.id_kendaraan_jenis = kj.id_kendaraan_jenis
        where b.nama LIKE '%$like%' OR c.nama LIKE '%$like%'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_daftar_biaya'] = $row['id_daftar_biaya'];
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

                $hasil['jarak'] =  $row['jarak'] . 'km';
                $hasil['biaya_jasa'] =  $row['biaya_jasa'];
                $hasil['biaya_satuan'] =  $row['biaya_satuan'];
                $hasil['biaya_operasional'] =  $row['biaya_operasional'];
                $hasil['biaya_gaji'] =  $row['biaya_gaji'];
                $hasil['biaya_gendongan'] =  $row['biaya_gendongan'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $one = mysqli_query($con, "SELECT 
        a.id_daftar_biaya,a.jarak,a.biaya_jasa,a.biaya_satuan,a.biaya_operasional,a.biaya_gaji,a.biaya_gendongan,
        a.asal,b.nama as nama_asal, a.tujuan, c.nama as nama_tujuan,a.id_pengiriman_jenis,d.jenis as nama_jenis, a.id_kendaraan_jenis, kj.nama as nama_kendaraan_jenis 
        FROM daftar_biaya a
        LEFT OUTER JOIN sub_client b on a.asal = b.id_sub_client
        LEFT OUTER JOIN sub_client c on a.tujuan = c.id_sub_client
        LEFT OUTER JOIN pengiriman_jenis d on a.id_pengiriman_jenis = d.id_pengiriman_jenis
        LEFT OUTER JOIN kendaraan_jenis kj on a.id_kendaraan_jenis = kj.id_kendaraan_jenis
        WHERE a.id_daftar_biaya = '$id_daftar_biaya'");
        if (mysqli_num_rows($one) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_daftar_biaya'] = $row['id_daftar_biaya'];
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

            $hasil['jarak'] =  $row['jarak'] . 'km';
            $hasil['biaya_jasa'] =  $row['biaya_jasa'];
            $hasil['biaya_satuan'] =  $row['biaya_satuan'];
            $hasil['biaya_operasional'] =  $row['biaya_operasional'];
            $hasil['biaya_gaji'] =  $row['biaya_gaji'];
            $hasil['biaya_gendongan'] =  $row['biaya_gendongan'];
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
