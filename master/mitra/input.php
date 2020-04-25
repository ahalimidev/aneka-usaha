<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $nama = anti_injection($con, $_POST['nama']);
    $alamat = anti_injection($con, $_POST['alamat']);
    $id_kota = anti_injection($con, $_POST['id_kota']);
    $no_handphone = anti_injection($con, $_POST['no_handphone']);
    $fax = anti_injection($con, $_POST['fax']);
    $id_mitra = anti_injection($con, $_POST['id_mitra']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO mitra (nama,alamat,id_kota,no_handphone,fax) values ('$nama','$alamat','$id_kota','$no_handphone','$fax')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_mitra'] = mysqli_insert_id($con);
            $hasil['nama'] = $nama;
            $hasil['alamat'] = $alamat;
            $red_join_tambah = mysqli_query($con, "SELECT id_kota, nama as nama_kota FROM kota WHERE id_kota ='$id_kota'");
            $row = mysqli_fetch_array($red_join_tambah);
            $tampil_data = array();
            $tampil_data['id'] =  $row['id_kota'];
            $tampil_data['nama'] = $row['nama_kota'];
            $hasil['kota'] = $tampil_data;
            $hasil['no_handphone'] = $no_handphone;
            $hasil['fax'] = $fax;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $cari = mysqli_query($con, "SELECT * FROM mitra WHERE id_mitra ='$id_mitra'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE mitra SET id_kota ='$id_kota', nama ='$nama', alamat ='$alamat', no_handphone ='$no_handphone', fax ='$fax' where id_mitra ='$id_mitra'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_mitra'] = $id_mitra;
                $hasil['nama'] = $nama;
                $hasil['alamat'] = $alamat;
                $red_join_tedit = mysqli_query($con, "SELECT id_kota, nama as nama_kota FROM kota WHERE id_kota ='$id_kota'");
                $row = mysqli_fetch_array($red_join_tedit);
                $tampil_data = array();
                $tampil_data['id'] =  $row['id_kota'];
                $tampil_data['nama'] = $row['nama_kota'];
                $hasil['kota'] = $tampil_data;
                $hasil['no_handphone'] = $no_handphone;
                $hasil['fax'] = $fax;
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
        $cari = mysqli_query($con, "SELECT * FROM mitra WHERE id_mitra ='$id_mitra'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus = "DELETE FROM mitra WHERE id_mitra = '$id_mitra'";
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
