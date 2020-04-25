<?php
require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_pengiriman = anti_injection($con, $_POST['id_pengiriman']);
    $id_pengiriman_dokumen = anti_injection($con, $_POST['id_pengiriman_dokumen']);
    $judul = anti_injection($con, $_POST['judul']);
    $ekstensi_diperbolehkan    = array('png', 'jpg', 'pdf');
    $nama_dokumen = $_FILES['file']['name'];
    $x_dokumen = explode('.', $nama_dokumen);
    $ekstensi_dokumen = pathinfo($nama_dokumen, PATHINFO_EXTENSION);
    $nama_baru_dokumen = acakhuruf(32) . '.' . $ekstensi_dokumen;
    $file_tmp_dokumen = $_FILES['file']['tmp_name'];

    if ($tipe == "tambah") {
        $aksi = mysqli_query($con, "SELECT status FROM pengiriman where id_pengiriman ='$id_pengiriman'");
        if (mysqli_num_rows($aksi) > 0) {
            $row = mysqli_fetch_array($aksi);
            $status = $row['status'];
            if ($status != 4) {
                if (in_array($ekstensi_dokumen, $ekstensi_diperbolehkan) === true) {
                    move_uploaded_file($file_tmp_dokumen, '../photo/dokumen/' . $nama_baru_dokumen);
                    $simpan = "INSERT INTO pengiriman_dokumen (id_pengiriman,judul,file) values ('$id_pengiriman','$judul','$nama_baru_dokumen')";
                    if (mysqli_query($con, $simpan)) {
                        $response["success"] = 1;
                        $hasil = array();
                        $hasil['id_pengiriman_dokumen'] = mysqli_insert_id($con);
                        $hasil['id_pengiriman'] = $id_pengiriman;
                        $hasil['judul'] = $judul;
                        $hasil['file'] = URL_DOKUMEN . $nama_baru_dokumen;
                        $response['data'] = $hasil;
                        echo json_encode($response);
                    } else {
                        $response["success"] = 0;
                        echo json_encode($response);
                    }
                } else {
                    $response["success"] = 2;
                    $response["test"] = $file_tmp_dokumen;
                    echo json_encode($response);
                }
            } else {
                $response["success"] = 2;
                echo json_encode($response);
            }
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "hapus") {
        $cari = mysqli_query($con, "SELECT * FROM pengiriman_dokumen where id_pengiriman_dokumen  ='$id_pengiriman_dokumen '");
        if (mysqli_num_rows($cari) > 0) {
            $row = mysqli_fetch_array($cari);
            unlink('../photo/dokumen/' . $row['file']);
            $hapus = "DELETE FROM pengiriman_dokumen where id_pengiriman_dokumen  ='$id_pengiriman_dokumen '";
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
