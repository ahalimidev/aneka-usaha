<?php

require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_perusahaan_anak_usaha = anti_injection($con, $_POST['id_perusahaan_anak_usaha']);
    $nama = anti_injection($con, $_POST['nama']);
    $alamat = anti_injection($con, $_POST['alamat']);
    $id_kota = anti_injection($con, $_POST['id_kota']);
    $no_handphone = anti_injection($con, $_POST['no_handphone']);
    $fax = anti_injection($con, $_POST['fax']);
    $nama_direktur = anti_injection($con, $_POST['nama_direktur']);
    $ekstensi_diperbolehkan    = array('png', 'jpg');

    //nama_logo
    $nama_logo = $_FILES['logo']['name'];
    $x_logo = explode('.', $nama_logo);
    $ekstensi_logo = strtolower(end($x_logo));
    $nama_baru_logo = acakhuruf(32) . '.' . $ekstensi_logo;
    $file_tmp_logo = $_FILES['logo']['tmp_name'];
    //ttd
    $nama_ttd = $_FILES['ttd_direktur']['name'];
    $x_ttd = explode('.', $nama_ttd);
    $ekstensi_ttd = strtolower(end($x_ttd));
    $nama_baru_ttd = acakhuruf(32) . '.' . $ekstensi_ttd;
    $file_tmp_ttd = $_FILES['ttd_direktur']['tmp_name'];

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO perusahaan_anak_usaha (nama,alamat,id_kota,no_handphone,fax,direktur_nama) VALUES
       ('$nama','$alamat','$id_kota','$no_handphone','$fax','$nama_direktur')";

        if (mysqli_query($con, $tambah)) {
            $hasil['id_perusahaan_anak_usaha'] = mysqli_insert_id($con);
            $id = mysqli_insert_id($con);
            $join = mysqli_query($con, "SELECT a.id_perusahaan_anak_usaha,a.nama,a.alamat,a.id_kota,b.nama as nama_kota, a.no_handphone,a.fax,a.logo,a.direktur_nama,a.direktur_ttd
            FROM perusahaan_anak_usaha a 
            LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
            where a.id_perusahaan_anak_usaha = '$id'");
            $row = mysqli_fetch_array($join);
            $tampil_data = array();
            $tampil_data['id'] =  $row['id_kota'];
            $tampil_data['nama'] = $row['nama_kota'];
            $hasil['kota'] = $tampil_data;

            $hasil['nama'] = $nama;
            $hasil['alamat'] = $alamat;

            $hasil['no_handphone'] = $no_handphone;
            $hasil['fax'] = $fax;
            $hasil['logo'] = URL_LOGO."";
            $direktur = array();
            $direktur['nama'] =  $nama;
            $direktur['ttd'] = URL_TTD . $row['direktur_ttd'];
            $hasil['direktur'] = $direktur;
            $response['data'] = $hasil;
            $response["success"] = 1;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "logo") {
        //edit logo       
        $cari_foto = mysqli_query($con, "SELECT logo FROM perusahaan_anak_usaha where id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'");
        if (mysqli_num_rows($cari_foto) > 0) {
            //tampil gambar pada database
            $row = mysqli_fetch_array($cari_foto);
            $gambar = $row['logo'];
            //query edit logo
            $edit = "UPDATE perusahaan_anak_usaha SET logo = '$nama_baru_logo' where id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'";
            //cek gambar apa sudah sesuai dengan ketentuan PNG / JPG
            $query_edit;
            if (in_array($ekstensi_logo, $ekstensi_diperbolehkan) === true) {
                //gambar kosong
                if ($gambar == null) {
                    move_uploaded_file($file_tmp_logo, '../../photo/logo/' . $nama_baru_logo);
                    $query_edit = mysqli_query($con, $edit);
                } else {
                    //hapus gambar lalu upload
                    if (unlink('../../photo/logo/' . $gambar)) {
                        move_uploaded_file($file_tmp_logo, '../../photo/logo/' . $nama_baru_logo);
                        $query_edit = mysqli_query($con, $edit);
                    } else {
                        move_uploaded_file($file_tmp_logo, '../../photo/logo/' . $nama_baru_logo);
                        $query_edit = mysqli_query($con, $edit);
                    }
                }

                if ($query_edit === true) {
                    $hasil = array();
                    $hasil['id_perusahaan_anak_usaha'] = $id_perusahaan_anak_usaha;
                    $hasil['logo'] = URL_LOGO . $nama_baru_logo;
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
    } else if ($tipe == "ttd") {
        //edit logo       
        $cari_foto = mysqli_query($con, "SELECT direktur_ttd FROM perusahaan_anak_usaha where id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'");
        if (mysqli_num_rows($cari_foto) > 0) {
            //tampil gambar pada database
            $row = mysqli_fetch_array($cari_foto);
            $gambar = $row['direktur_ttd'];
            //query edit logo
            $edit = "UPDATE perusahaan_anak_usaha SET direktur_ttd = '$nama_baru_ttd' where id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'";
            //cek gambar apa sudah sesuai dengan ketentuan PNG / JPG
            $query_edit;
            if (in_array($ekstensi_ttd, $ekstensi_diperbolehkan) === true) {
                //gambar kosong
                if ($gambar == null) {
                    move_uploaded_file($file_tmp_ttd, '../../photo/ttd/' . $nama_baru_ttd);
                    $query_edit = mysqli_query($con, $edit);
                } else {
                    //hapus gambar lalu upload
                    if (unlink('../../photo/ttd/' . $gambar)) {
                        move_uploaded_file($file_tmp_ttd, '../../photo/ttd/' . $nama_baru_ttd);
                        $query_edit = mysqli_query($con, $edit);
                    } else {
                        move_uploaded_file($file_tmp_ttd, '../../photo/ttd/' . $nama_baru_ttd);
                        $query_edit = mysqli_query($con, $edit);
                    }
                }

                if ($query_edit === true) {
                    $hasil = array();
                    $hasil['id_perusahaan_anak_usaha'] = $id_perusahaan_anak_usaha;
                    $hasil['ttd'] = URL_TTD . $nama_baru_ttd;
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
        $cari = mysqli_query($con, "SELECT * FROM perusahaan_anak_usaha where id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE perusahaan_anak_usaha set nama='$nama',
            alamat='$alamat',
            id_kota='$id_kota',
            no_handphone='$no_handphone',
            fax='$fax',
            direktur_nama='$nama_direktur'
            where id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'";

            if (mysqli_query($con, $edit)) {

                $hasil['id_perusahaan_anak_usaha'] = $id_perusahaan_anak_usaha;

                $join = mysqli_query($con, "SELECT a.id_perusahaan_anak_usaha,a.nama,a.alamat,a.id_kota,b.nama as nama_kota, a.no_handphone,a.fax,a.logo,a.direktur_nama,a.direktur_ttd
         FROM perusahaan_anak_usaha a 
         LEFT OUTER JOIN kota b on a.id_kota = b.id_kota
         where a.id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'");

                $row = mysqli_fetch_array($join);
                $tampil_data = array();
                $tampil_data['id'] =  $row['id_kota'];
                $tampil_data['nama'] = $row['nama_kota'];
                $hasil['kota'] = $tampil_data;

                $hasil['nama'] = $nama;
                $hasil['alamat'] = $alamat;

                $hasil['no_handphone'] = $no_handphone;
                $hasil['fax'] = $fax;
                $hasil['logo'] = URL_LOGO."";
                $direktur = array();
                $direktur['nama'] =  $nama;
                $direktur['ttd'] = URL_TTD . $row['direktur_ttd'];
                $hasil['direktur'] = $direktur;
                $response['data'] = $hasil;
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
    } else if ($tipe == "hapus") {
        $cari_foto = mysqli_query($con, "SELECT direktur_ttd,logo FROM perusahaan_anak_usaha where id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'");
        if (mysqli_num_rows($cari_foto) > 0) {
            $row = mysqli_fetch_array($cari_foto);
            $hapus = "DELETE FROM perusahaan_anak_usaha WHERE id_perusahaan_anak_usaha = '$id_perusahaan_anak_usaha'";
            if (mysqli_query($con, $hapus)) {

                $response["success"] = 1;
                $gambar1 = $row['logo'];
                $gambar2 = $row['direktur_ttd'];
                unlink('../../photo/logo/' . $gambar1);
                unlink('../../photo/ttd/' . $gambar2);
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
