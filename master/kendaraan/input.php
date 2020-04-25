<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_perusahaan = anti_injection($con, $_POST['id_perusahaan']);
    $id_pengiriman_jenis = anti_injection($con, $_POST['id_pengiriman_jenis']);
    $id_kendaraan_sopir = anti_injection($con, $_POST['id_kendaraan_sopir']);
    $no_pol = anti_injection($con, $_POST['no_pol']);
    $mulai_operasi = anti_injection($con, $_POST['mulai_operasi']);
    $aktif = anti_injection($con, $_POST['aktif']);
    $ket = anti_injection($con, $_POST['ket']);
    $id_kendaraan = anti_injection($con, $_POST['id_kendaraan']);

    if ($tipe == "tambah") {
        $tambah = "INSERT INTO kendaraan (id_perusahaan,id_pengiriman_jenis,id_kendaraan_sopir,no_pol,mulai_operasi,aktif,ket) 
        VALUES
        ('$id_perusahaan','$id_pengiriman_jenis','$id_kendaraan_sopir','$no_pol','$mulai_operasi','$aktif','$ket')";
        if (mysqli_query($con, $tambah)) {
            $response["success"] = 1;
            $hasil = array();
            $hasil['id_kendaraan'] = mysqli_insert_id($con);
            $id = mysqli_insert_id($con);
            $red_join_tambah = mysqli_query($con, "SELECT a.id_pengiriman_jenis,b.jenis as nama_jenis, a.id_perusahaan,c.nama as nama_perusahaan, a.id_kendaraan_sopir , d.nama as nama_kendaraan
            FROM kendaraan a
            LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
            LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
            LEFT OUTER JOIN kendaraan_sopir d on a.id_kendaraan_sopir = d.id_kendaraan_sopir
            where a.id_kendaraan = '$id'");
            $row = mysqli_fetch_array($red_join_tambah);

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
            $hasil['no_pol'] = $no_pol;
            $hasil['mulai_operasi'] = $mulai_operasi;
            $hasil['aktif'] = $aktif;
            $hasil['ket'] = $ket;
            $response['data'] = $hasil;
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "edit") {
        $cari = mysqli_query($con, "SELECT * FROM  kendaraan WHERE id_kendaraan ='$id_kendaraan'");
        if (mysqli_num_rows($cari) > 0) {
            $edit = "UPDATE kendaraan SET id_perusahaan ='$id_perusahaan',
            id_pengiriman_jenis ='$id_pengiriman_jenis',
            id_kendaraan_sopir ='$id_kendaraan_sopir',
            no_pol ='$no_pol',
            mulai_operasi ='$mulai_operasi',
            aktif ='$aktif',
            ket ='$ket'
            WHERE id_kendaraan ='$id_kendaraan'";
            if (mysqli_query($con, $edit)) {
                $response["success"] = 1;
                $hasil = array();
                $hasil['id_kendaraan'] = $id_kendaraan;
                $red_join_edit = mysqli_query($con, "SELECT a.id_pengiriman_jenis,b.jenis as nama_jenis, a.id_perusahaan,c.nama as nama_perusahaan, a.id_kendaraan_sopir , d.nama as nama_kendaraan
        FROM kendaraan a
        LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
        LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
        LEFT OUTER JOIN kendaraan_sopir d on a.id_kendaraan_sopir = d.id_kendaraan_sopir
        where a.id_kendaraan = '$id_kendaraan'");
                $row = mysqli_fetch_array($red_join_edit);

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
                $hasil['no_pol'] = $no_pol;
                $hasil['mulai_operasi'] = $mulai_operasi;
                $hasil['aktif'] = $aktif;
                $hasil['ket'] = $ket;
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
        $cari = mysqli_query($con, "SELECT * FROM  kendaraan WHERE id_kendaraan ='$id_kendaraan'");
        if (mysqli_num_rows($cari) > 0) {
            $hapus = "DELETE FROM kendaraan WHERE id_kendaraan = '$id_kendaraan'";
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
