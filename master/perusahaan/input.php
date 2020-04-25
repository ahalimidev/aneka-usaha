<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $nama = anti_injection($con, $_POST['nama']);
    $alamat = anti_injection($con, $_POST['alamat']);
    $id_kota = anti_injection($con, $_POST['id_kota']);
    $no_handphone = anti_injection($con, $_POST['no_handphone']);
    $fax = anti_injection($con, $_POST['fax']);
    $status = anti_injection($con, $_POST['status']);
    $id_perusahaan = anti_injection($con, $_POST['id_perusahaan']);
    $ekstensi_diperbolehkan    = array('png', 'jpg');
    $nama_logo = $_FILES['logo']['name'];
    $x = explode('.', $nama_logo);
    $ekstensi = strtolower(end($x));
    $nama_baru = acakhuruf(32) . '.' . $ekstensi;
    $file_tmp = $_FILES['logo']['tmp_name'];
    if ($tipe == "tambah") {
        $tambah  =  "INSERT INTO perusahaan (nama,alamat,id_kota,no_handphone,fax,status) 
        values ('$nama','$alamat','$id_kota','$no_handphone','$fax','$status')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $id_perusahaan = mysqli_insert_id($con);
            $hasil['id_perusahaan'] = $id_perusahaan;
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
            $hasil['logo'] = "";
            $hasil['status'] = $status;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "logo") {
        $cari_foto = mysqli_query($con, "SELECT logo FROM perusahaan where id_perusahaan = '$id_perusahaan'");
        if (mysqli_num_rows($cari_foto) > 0) {
            //tampil gambar pada database
            $row = mysqli_fetch_array($cari_foto);
            $gambar = $row['logo'];
            //query edit logo
            $edit = "UPDATE perusahaan SET logo = '$nama_baru' where id_perusahaan = '$id_perusahaan'";
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
                    $hasil['id_perusahaan'] = $id_perusahaan;
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
        $cari = mysqli_query($con, "SELECT * FROM perusahaan where id_perusahaan = '$id_perusahaan'");
        if (mysqli_num_rows($cari) > 0) {

            $edit  =  "UPDATE perusahaan SET nama='$nama',
            alamat='$alamat',
            id_kota='$id_kota',
            no_handphone='$no_handphone',
            fax='$fax',
            status='$status'
            where id_perusahaan = '$id_perusahaan'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_perusahaan'] = $id_perusahaan;
                $hasil['nama'] = $nama;
                $hasil['alamat'] = $alamat;
                $red_join_edit = mysqli_query($con, "SELECT id_kota, nama as nama_kota FROM kota WHERE id_kota ='$id_kota'");
                $row = mysqli_fetch_array($red_join_edit);
                $tampil_data = array();
                $tampil_data['id'] =  $row['id_kota'];
                $tampil_data['nama'] = $row['nama_kota'];
                $hasil['kota'] = $tampil_data;
                $hasil['no_handphone'] = $no_handphone;
                $hasil['fax'] = $fax;
                $hasil['logo'] = "";
                $hasil['status'] = $status;
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
        $cari_foto = mysqli_query($con, "SELECT logo FROM perusahaan where id_perusahaan = '$id_perusahaan'");
        if (mysqli_num_rows($cari_foto) > 0) {
            $row = mysqli_fetch_array($cari_foto);
            $hapus = "DELETE FROM perusahaan WHERE id_perusahaan = '$id_perusahaan'";
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
