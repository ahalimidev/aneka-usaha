<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_sub_client = anti_injection($con, $_POST['id_sub_client']);
    $nama = anti_injection($con, $_POST['nama']);
    $alias = anti_injection($con, $_POST['alias']);
    $alamat = anti_injection($con, $_POST['alamat']);
    $no_handphone = anti_injection($con, $_POST['no_handphone']);
    $fax = anti_injection($con, $_POST['fax']);
    $id_client = anti_injection($con, $_POST['id_client']);
    $id_kota = anti_injection($con, $_POST['id_kota']);
    $id_tipe = anti_injection($con, $_POST['id_tipe']);
    $ekstensi_diperbolehkan    = array('png', 'jpg');
    $nama_logo = $_FILES['logo']['name'];
    $x = explode('.', $nama_logo);
    $ekstensi = strtolower(end($x));
    $nama_baru = acakhuruf(32) . '.' . $ekstensi;
    $file_tmp = $_FILES['logo']['tmp_name'];

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO sub_client (nama,alias,alamat,no_handphone,fax,id_client,id_kota,id_sub_client_tipe) 
        VALUES ('$nama','$alias','$alamat','$no_handphone','$fax','$id_client','$id_kota','$id_tipe')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;

            $hasil = array();
            $id_sub_client = mysqli_insert_id($con);
            $hasil['id_sub_client'] = $id_sub_client;
            $hasil['nama'] = $nama;
            $hasil['alias'] = $alias;
            $hasil['alamat'] = $alamat;
            $hasil['no_handphone'] = $no_handphone;
            $hasil['fax'] = $fax;
            $hasil['logo'] = URL_LOGO . "";

            $red_join_tambah = mysqli_query($con, "select a.id_kota, b.nama as nama_kota,a.id_sub_client_tipe,c.nama as nama_sub_client, a.id_client ,d.nama as nama_client
             FROM sub_client a
             LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
             LEFT OUTER JOIN sub_client_tipe c on a.id_sub_client_tipe = c.id_sub_client_tipe
             LEFT OUTER JOIN client d on a.id_client = d.id_client
             WHERE id_sub_client = '$id_sub_client' ");
            $row = mysqli_fetch_array($red_join_tambah);
            $client = array();
            $client['id'] = $row['id_client'];
            $client['nama'] = $row['nama_client'];
            $hasil['client'] = $client;

            $kota = array();
            $kota['id'] = $row['id_kota'];
            $kota['nama'] = $row['nama_kota'];
            $hasil['kota'] = $kota;

            $tipe = array();
            $tipe['id'] = $row['id_sub_client_tipe'];
            $tipe['nama'] = $row['nama_sub_client'];
            $hasil['tipe'] = $tipe;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "logo") {
        $cari_foto = mysqli_query($con, "SELECT logo FROM sub_client where id_sub_client = '$id_sub_client'");
        if (mysqli_num_rows($cari_foto) > 0) {
            //tampil gambar pada database
            $row = mysqli_fetch_array($cari_foto);
            $gambar = $row['logo'];
            //query edit logo
            $edit = "UPDATE sub_client SET logo = '$nama_baru' where id_sub_client = '$id_sub_client'";
            //cek gambar apa sudah sesuai dengan ketentuan PNG / JPG
            $query_edit;
            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                //gambar kosong
                if ($gambar == null) {
                    move_uploaded_file($file_tmp, '../../photo/logo/' . $nama_baru);
                    $query_edit = mysqli_query($con, $edit);
                } else {
                    //hapus gambar lalu upload
                    if (unlink('../../photo/logo/' . $gambar)) {
                        move_uploaded_file($file_tmp, '../../photo/logo/' . $nama_baru);
                        $query_edit = mysqli_query($con, $edit);
                    } else {
                        move_uploaded_file($file_tmp, '../../photo/logo/' . $nama_baru);
                        $query_edit = mysqli_query($con, $edit);
                    }
                }

                if ($query_edit === true) {
                    $hasil = array();
                    $hasil['id_sub_client'] = $id_sub_client;
                    $hasil['logo'] = URL_LOGO . $nama_baru;
                    $response["success"] = 1;
                    $response['data'] = $hasil;
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
                    echo json_encode($response);
                }
            } else {
                //apabila tidak sesuai dengan ketentuan
                $response["success"] = 2;
                echo json_encode($response);
            }
        } else {
            //jika id tidak di temukan
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $edit = "UPDATE sub_client set nama = '$nama', 
        alias = '$alias', alamat='$alamat', 
        no_handphone = '$no_handphone', fax = '$fax', 
        id_client ='$id_client',  id_kota = '$id_kota' , 
        id_sub_client_tipe = '$id_tipe' where id_sub_client = '$id_sub_client'";
        if (mysqli_query($con, $edit)) {
            $response["success"] = 1;

            $hasil = array();
            $hasil['id_sub_client'] = $id_sub_client;
            $hasil['nama'] = $nama;
            $hasil['alias'] = $alias;
            $hasil['alamat'] = $alamat;
            $hasil['no_handphone'] = $no_handphone;
            $hasil['fax'] = $fax;
            $hasil['logo'] = "";
            $red_join_tambah = mysqli_query($con, "select a.id_kota, b.nama as nama_kota,a.id_sub_client_tipe,c.nama as nama_sub_client, a.id_client ,d.nama as nama_client
            FROM sub_client a
            LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
            LEFT OUTER JOIN sub_client_tipe c on a.id_sub_client_tipe = c.id_sub_client_tipe
            LEFT OUTER JOIN client d on a.id_client = d.id_client
            WHERE id_sub_client = '$id_sub_client' ");
            $row = mysqli_fetch_array($red_join_tambah);
            $client = array();
            $client['id'] = $row['id_client'];
            $client['nama'] = $row['nama_client'];
            $hasil['client'] = $client;

            $kota = array();
            $kota['id'] = $row['id_kota'];
            $kota['nama'] = $row['nama_kota'];
            $hasil['kota'] = $kota;

            $tipe = array();
            $tipe['id'] = $row['id_sub_client_tipe'];
            $tipe['nama'] = $row['nama_sub_client'];
            $hasil['tipe'] = $tipe;
            $response['data'] = $hasil;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        //hapus data
        $cari_foto = mysqli_query($con, "SELECT logo FROM sub_client where id_sub_client = '$id_sub_client'");
        if (mysqli_num_rows($cari_foto) > 0) {
            $row = mysqli_fetch_array($cari_foto);
            $hapus = "DELETE FROM sub_client WHERE id_sub_client = '$id_sub_client'";
            if (mysqli_query($con, $hapus)) {

                $response["success"] = 1;
                $gambar = $row['logo'];
                unlink('../../photo/logo/' . $gambar);
                echo json_encode($response);
            } else {
                $response["success"] = 0;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 1;
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
