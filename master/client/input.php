<?php

require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_client = anti_injection($con, $_POST['id_client']);
    $nama = anti_injection($con, $_POST['nama']);
    $alias = anti_injection($con, $_POST['alias']);
    $alamat = anti_injection($con, $_POST['alamat']);
    $no_handphone = anti_injection($con, $_POST['no_handphone']);
    $fax = anti_injection($con, $_POST['fax']);
    $ekstensi_diperbolehkan    = array('png', 'jpg');
    $nama_logo = $_FILES['logo']['name'];
    $x = explode('.', $nama_logo);
    $ekstensi = strtolower(end($x));
    $nama_baru = acakhuruf(32) . '.' . $ekstensi;
    $file_tmp = $_FILES['logo']['tmp_name'];

    if ($tipe == "tambah") {
        //tambah
        $tambah = "INSERT INTO client (nama,alias,alamat,no_handphone,fax) VALUES ('$nama','$alias','$alamat','$no_handphone','$fax')";
        if (mysqli_query($con, $tambah)) {
            //tampilkan data sudah tersimpan
            $response['data'] = array();
            $hasil = array();
            $hasil['id_client'] = mysqli_insert_id($con);
            $hasil['nama'] = $nama;
            $hasil['alias'] = $alias;
            $hasil['alamat'] = $alamat;
            $hasil['no_handphone'] = $no_handphone;
            $hasil['fax'] = $fax;
            $hasil['logo'] = "";
            $response["success"] = 1;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            //jika gagal tersimpan
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "logo") {
        //edit logo       
        $cari_foto = mysqli_query($con, "SELECT logo FROM client where id_client = '$id_client'");
        if (mysqli_num_rows($cari_foto) > 0) {
            //tampil gambar pada database
            $row = mysqli_fetch_array($cari_foto);
            $gambar = $row['logo'];
            //query edit logo
            $edit = "UPDATE client SET logo = '$nama_baru' where id_client = '$id_client'";
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
                    $hasil['id_client'] = $id_client;
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
        $cari = mysqli_query($con, "SELECT * FROM client where id_client = '$id_client'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE client SET nama ='$nama', alias ='$alias', alamat ='$alamat', no_handphone ='$no_handphone', fax ='$fax' where id_client = '$id_client'";
            if (mysqli_query($con, $edit)) {
                //tampilkan data sudah ubah
                $hasil = array();
                $hasil['id_client'] = $id_client;
                $hasil['nama'] = $nama;
                $hasil['alias'] = $alias;
                $hasil['alamat'] = $alamat;
                $hasil['no_handphone'] = $no_handphone;
                $hasil['fax'] = $fax;
                $hasil['logo'] = "";
                $response["success"] = 1;
                $response['data'] = $hasil;
                echo json_encode($response);
            } else {
                //jika gagal tersimpan
                $response["success"] = 0;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        //hapus data
        $cari_foto = mysqli_query($con, "SELECT logo FROM client where id_client = '$id_client'");
        if (mysqli_num_rows($cari_foto) > 0) {
            $row = mysqli_fetch_array($cari_foto);
            $hapus = "DELETE FROM client WHERE id_client = '$id_client'";
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
