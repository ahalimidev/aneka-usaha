<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_client = anti_injection($con, $_POST['id_client']);
    $nama = anti_injection($con, $_POST['nama']);
    $area = anti_injection($con, $_POST['area']);
    $unit = anti_injection($con, $_POST['unit']);
    $id_client_tanda_tangan = anti_injection($con, $_POST['id_client_tanda_tangan']);
    if ($tipe == "tambah") {
        $tambah = "INSERT INTO client_tanda_tangan (id_client,nama,area,unit) values ('$id_client','$nama','$area','$unit')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_client_tanda_tangan'] = mysqli_insert_id($con);
            $red_join_tambah = mysqli_query($con, "SELECT id_client, nama as nama_client FROM client WHERE id_client='$id_client'");
            $row = mysqli_fetch_array($red_join_tambah);
            $client = array();
            $client['id'] = $row['id_client'];
            $client['nama'] = $row['nama_client'];
            $hasil['client'] = $client;
            $hasil['nama'] = $nama;
            $hasil['area'] = $area;
            $hasil['unit'] = $unit;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $cari = mysqli_query($con, "SELECT * FROM client_tanda_tangan WHERE id_client_tanda_tangan= '$id_client_tanda_tangan'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE client_tanda_tangan SET id_client='$id_client',
            nama='$nama',
            area='$area',
            unit='$unit'
            where id_client_tanda_tangan ='$id_client_tanda_tangan'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_client_tanda_tangan'] = $id_client_tanda_tangan;
                $red_join_edit = mysqli_query($con, "SELECT id_client, nama as nama_client FROM client WHERE id_client='$id_client'");
                $row = mysqli_fetch_array($red_join_edit);
                $client = array();
                $client['id'] = $row['id_client'];
                $client['nama'] = $row['nama_client'];
                $hasil['client'] = $client;
                $hasil['nama'] = $nama;
                $hasil['area'] = $area;
                $hasil['unit'] = $unit;
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
        $cari = mysqli_query($con, "SELECT * FROM client_tanda_tangan WHERE id_client_tanda_tangan= '$id_client_tanda_tangan'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus = "DELETE FROM client_tanda_tangan WHERE id_client_tanda_tangan = '$id_client_tanda_tangan'";
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
