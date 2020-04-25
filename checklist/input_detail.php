<?php

require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_checklist_transaksi = anti_injection($con, $_POST['id_checklist_transaksi']);
    $id_checklist_master = anti_injection($con, $_POST['id_checklist_master']);
    $kondisi = anti_injection($con, $_POST['kondisi']);
    $keterangan = anti_injection($con, $_POST['keterangan']);
    $simpan = "INSERT INTO checklist_transaksi_detail(id_checklist_transaksi,id_checklist_master,kondisi,keterangan) VALUES ('$id_checklist_transaksi','$id_checklist_master','$kondisi','$keterangan')";
    if (mysqli_query($con, $simpan)) {
        $id = mysqli_insert_id($con);
        $tampil = mysqli_query($con, "SELECT 
                        a.id_checklist_transaksi_detail,
                        a.id_checklist_transaksi,
                        a.id_checklist_master,
                        b.nama,
                        a.kondisi,
                        a.keterangan 
                        FROM checklist_transaksi_detail a 
                        LEFT OUTER JOIN checklist_master b on a.id_checklist_master = b.id_checklist_master
                        where a.id_checklist_transaksi_detail = '$id' ");

        $response["success"] = 1;
        $row = mysqli_fetch_array($tampil);
        $hasil = array();

        $hasil['id_checklist_transaksi_detail'] = $row['id_checklist_transaksi_detail'];
        $hasil['id_checklist_transaksi'] = $row['id_checklist_transaksi'];

        $checklist_master = array();
        $checklist_master['id'] = $row['id_checklist_master'];
        $checklist_master['nama'] = $row['nama'];
        $hasil['checklist_master'] = $checklist_master;


        $hasil['kondisi'] = $row['kondisi'];
        $hasil['keterangan'] = $row['keterangan'];

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
