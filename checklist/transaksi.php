<?php

require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_kendaraan = anti_injection($con, $_POST['id_kendaraan']);

    $simpan = "INSERT INTO  checklist_transaksi (id_kendaraan) values ('$id_kendaraan')";
    if (mysqli_query($con, $simpan)) {
        $id = mysqli_insert_id($con);
        $tampil = mysqli_query($con, "SELECT 
                    a.id_kendaraan,
                    a.no_pol,
                    a.mulai_operasi,
                    a.aktif,
                    a.ket,
                    a.id_pengiriman_jenis,b.jenis as nama_jenis, 
                    a.id_perusahaan,c.nama as nama_perusahaan, 
                    a.id_kendaraan_sopir , d.nama as nama_kendaraan,
                    e.tgl as checklist_kendaraan
                    FROM kendaraan a
                    LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
                    LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
                    LEFT OUTER JOIN kendaraan_sopir d on a.id_kendaraan_sopir = d.id_kendaraan_sopir
                    LEFT OUTER JOIN checklist_transaksi e on e.id_kendaraan = a.id_kendaraan
                    where a.id_kendaraan ='$id_kendaraan'");

        $response["success"] = 1;
        $row = mysqli_fetch_array($tampil);
        $hasil = array();
        $hasil['id_checklist_transaksi'] = $id;

        $hasil['id_kendaraan'] = $row['id_kendaraan'];

        $perusahaan = array();
        $perusahaan['id'] = $row['id_perusahaan'];
        $perusahaan['nama'] = $row['nama_perusahaan'];
        $hasil['perusahaan'] = $perusahaan;

        $jenis = array();
        $jenis['id'] = $row['id_pengiriman_jenis'];
        $jenis['nama'] = $row['nama_jenis'];
        $hasil['jenis'] = $jenis;

        $sopir = array();
        $sopir['id'] = $row['id_kendaraan_sopir'];
        $sopir['nama'] = $row['nama_kendaraan'];
        $hasil['sopir'] = $sopir;

        $hasil['no_pol'] = $row['no_pol'];
        $hasil['mulai_operasi'] = $row['mulai_operasi'];
        $hasil['checklist_kendaraan'] = $row['checklist_kendaraan'];
        $hasil['aktif'] = $row['aktif'];
        $hasil['ket'] = $row['ket'];

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
