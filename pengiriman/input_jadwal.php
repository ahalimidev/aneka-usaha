<?php
require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tipe = anti_injection($con, $_POST['tipe']);
    $id_perusahaan = anti_injection($con, $_POST['id_perusahaan']);
    $id_daftar_biaya = anti_injection($con, $_POST['id_daftar_biaya']);
    $tgl_pengiriman = anti_injection($con, $_POST['tgl_pengiriman']);
    $proses_pengiriman = anti_injection($con, $_POST['proses_pengiriman']);
    $biaya_lain = anti_injection($con, $_POST['biaya_lain']);
    $tipe_harga = anti_injection($con,$_POST['tipe_harga']);
    $biaya_satuan = anti_injection($con, $_POST['biaya_satuan']);
    $id_pengiriman = anti_injection($con, $_POST['id_pengiriman']);
    $jumlah_yang_diangkut = anti_injection($con, $_POST['jumlah_yang_diangkut']);
    $satuan_jumlah_yang_diangkut = anti_injection($con, $_POST['satuan_jumlah_yang_diangkut']);

    if ($tipe == "tambah") {
        preg_match_all("/([^;= ]+):([^;= ]+)/", $biaya_lain, $r);
        $biaya_lain = array_combine($r[1], $r[2]);
        $total = "";
        foreach ($biaya_lain as $key => $value) {
            $total += $value;
        }
        $aksi = mysqli_query($con, "SELECT id_perusahaan FROM perusahaan where id_perusahaan ='$id_perusahaan'");
        if (mysqli_num_rows($aksi) > 0) {
            $bayar = mysqli_query($con, "SELECT 
            a.id_daftar_biaya,
            a.asal,
            a.tujuan,
            a.id_pengiriman_jenis,
            a.id_kendaraan_jenis,
            b.nama as asal_pengiriman,
            c.nama as tujuan_pengiriman,
            d.jenis as jenis_pengiriman, 
            a.jarak,
            a.biaya_jasa,
            a.biaya_operasional,
            a.biaya_gaji,
            a.biaya_gendongan
            FROM daftar_biaya a
            LEFT OUTER JOIN sub_client b on a.asal = b.id_sub_client
            LEFT OUTER JOIN sub_client c on a.tujuan = c.id_sub_client
            LEFT OUTER JOIN pengiriman_jenis d on a.id_pengiriman_jenis = d.id_pengiriman_jenis
            where a.id_daftar_biaya ='$id_daftar_biaya'");
            if (mysqli_num_rows($bayar) > 0) {
                $row = mysqli_fetch_array($bayar);
                $asal = $row['asal'];
                $tujuan = $row['tujuan'];
                $id_pengiriman_jenis = $row['id_pengiriman_jenis'];
                $jarak = $row['jarak'];
                $biaya_jasa = $row['biaya_jasa'];
                $biaya_gaji = $row['biaya_gaji'];
                $biaya_operasional = $row['biaya_operasional'];
                $biaya_gendongan = $row['biaya_gendongan'];
                $id_kendaraan_jenis = $row['id_kendaraan_jenis'];

            if($tipe_harga == "satuan"){
                $simpan = "INSERT INTO pengiriman
                (
                id_perusahaan,
                id_daftar_biaya,
                tgl,
                asal,
                tujuan,
                id_pengiriman_jenis,
                jarak,
                proses_pengiriman,
                biaya_jasa,
                biaya_operasional,
                biaya_gaji,
                biaya_gendongan,
                total_biaya_lain,
                id_kendaraan_jenis,
                jumlah_yang_diangkut,
                satuan_jumlah_yang_diangkut,
                biaya_satuan)
                VALUES 
                (
                '$id_perusahaan',
                '$id_daftar_biaya',
                '$tgl_pengiriman',
                '$asal',
                '$tujuan',
                '$id_pengiriman_jenis',
                '$jarak',
                '$proses_pengiriman',
                '$biaya_jasa',
                '$biaya_operasional',
                '$biaya_gaji',
                '$biaya_gendongan',
                '$total',
                '$id_kendaraan_jenis',
                '$jumlah_yang_diangkut',
                '$satuan_jumlah_yang_diangkut',
                '$biaya_satuan'
                )";
                if (mysqli_query($con, $simpan)) {
                    $response["success"] = 1;
                    $hasil = array();
                    $hasil['id_pengiriman'] = mysqli_insert_id($con);
                    $id =  mysqli_insert_id($con);
                    $hasil['tgl_pengiriman'] = $tgl_pengiriman;
                    $hasil['id_daftar_biaya'] = $id_daftar_biaya;
                    $hasil['asal_pengiriman'] = $row['asal_pengiriman'];
                    $hasil['tujuan_pengiriman'] = $row['tujuan_pengiriman'];
                    $hasil['jenis_pengiriman'] = $row['jenis_pengiriman'];
                    $hasil['jarak'] = $row['jarak'] . 'km';
                    $hasil['biaya_jasa'] = $row['biaya_jasa'];
                    $hasil['biaya_operasional'] = $row['biaya_operasional'];
                    $hasil['biaya_gaji'] = $row['biaya_gaji'];
                    $hasil['biaya_gendongan'] = $row['biaya_gendongan'];
                    $hasil['proses_pengiriman'] = $proses_pengiriman;
                    $hasil['biaya_lain'] = array();
                    foreach ($biaya_lain as $key => $value) {
                        $menu = array();
                        $menu["keterangan"] = $key;
                        $menu["biaya"] = $value;
                        mysqli_query($con, "INSERT INTO pengiriman_biaya_lain (id_pengiriman,biaya,keterangan) VALUES ('$id','$value','$key')");

                        array_push($hasil["biaya_lain"], $menu);
                    }
                    $hasil['jumlah_yang_diangkut'] = $jumlah_yang_diangkut;
                    $hasil['satuan_jumlah_yang_diangkut'] = $satuan_jumlah_yang_diangkut;
                    $hasil['biaya_satuan'] = $biaya_satuan;
                    $response['data'] = $hasil;
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
                    echo json_encode($response);
                }
            }else if($tipe_harga =="borongan"){
                $simpan = "INSERT INTO pengiriman
                (
                id_perusahaan,
                id_daftar_biaya,
                tgl,
                asal,
                tujuan,
                id_pengiriman_jenis,
                jarak,
                proses_pengiriman,
                biaya_jasa,
                biaya_operasional,
                biaya_gaji,
                biaya_gendongan,
                total_biaya_lain,
                id_kendaraan_jenis)
                VALUES 
                (
                '$id_perusahaan',
                '$id_daftar_biaya',
                '$tgl_pengiriman',
                '$asal',
                '$tujuan',
                '$id_pengiriman_jenis',
                '$jarak',
                '$proses_pengiriman',
                '$biaya_jasa',
                '$biaya_operasional',
                '$biaya_gaji',
                '$biaya_gendongan',
                '$total',
                '$id_kendaraan_jenis'
                )";
                if (mysqli_query($con, $simpan)) {
                    $response["success"] = 1;
                    $hasil = array();
                    $hasil['id_pengiriman'] = mysqli_insert_id($con);
                    $id =  mysqli_insert_id($con);
                    $hasil['tgl_pengiriman'] = $tgl_pengiriman;
                    $hasil['id_daftar_biaya'] = $id_daftar_biaya;
                    $hasil['asal_pengiriman'] = $row['asal_pengiriman'];
                    $hasil['tujuan_pengiriman'] = $row['tujuan_pengiriman'];
                    $hasil['jenis_pengiriman'] = $row['jenis_pengiriman'];
                    $hasil['jarak'] = $row['jarak'] . 'km';
                    $hasil['biaya_jasa'] = $row['biaya_jasa'];
                    $hasil['biaya_operasional'] = $row['biaya_operasional'];
                    $hasil['biaya_gaji'] = $row['biaya_gaji'];
                    $hasil['biaya_gendongan'] = $row['biaya_gendongan'];
                    $hasil['proses_pengiriman'] = $proses_pengiriman;
                    $hasil['biaya_lain'] = array();
                    foreach ($biaya_lain as $key => $value) {
                        $menu = array();
                        $menu["keterangan"] = $key;
                        $menu["biaya"] = $value;
                        mysqli_query($con, "INSERT INTO pengiriman_biaya_lain (id_pengiriman,biaya,keterangan) VALUES ('$id','$value','$key')");

                        array_push($hasil["biaya_lain"], $menu);
                    }
                    $response['data'] = $hasil;
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
                    echo json_encode($response);
                }
            }else{
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
    } else if ($tipe == "edit") {
        $aksi = mysqli_query($con, "SELECT status FROM pengiriman where id_pengiriman ='$id_pengiriman'");
        if (mysqli_num_rows($aksi) > 0) {
            $row = mysqli_fetch_array($aksi);
            $status = $row['status'];
            if ($status != 4) {
                $edit = "UPDATE pengiriman SET jumlah_yang_diangkut='$jumlah_yang_diangkut', satuan_jumlah_yang_diangkut='$satuan_jumlah_yang_diangkut' where id_pengiriman = '$id_pengiriman' ";
                if (mysqli_query($con, $edit)) {
                    $response["success"] = 1;
                    $hasil = array();
                    $hasil['id_pengiriman'] = $id_pengiriman;
                    $hasil['jumlah_yang_diangkut'] = $jumlah_yang_diangkut;
                    $hasil['satuan_jumlah_yang_diangkut'] = $satuan_jumlah_yang_diangkut;
                    $response['data'] = $hasil;
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
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
    } else {
        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
