<?php
require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_pengiriman = anti_injection($con, $_POST['id_pengiriman']);
    $id_kendaraan = anti_injection($con, $_POST['id_kendaraan']);
    $sopir_1 = anti_injection($con, $_POST['sopir_1']);
    $sopir_2 = anti_injection($con, $_POST['sopir_2']);
    $id_mitra_kendaraan = anti_injection($con, $_POST['id_mitra_kendaraan']);
    $status = anti_injection($con, $_POST['status']);

    if ($status == 1) {
        $aksi = mysqli_query($con, "SELECT a.id_kendaraan,a.no_pol,b.id_kendaraan_sopir,b.nama
        FROM kendaraan a
        LEFT OUTER JOIN kendaraan_sopir b on a.id_kendaraan_sopir = b.id_kendaraan_sopir
        WHERE a.id_kendaraan = '$id_kendaraan'");


        if (mysqli_num_rows($aksi) > 0) {
            $simpan;
            if (empty($sopir_2)) {
                $simpan = "INSERT INTO pengiriman_kendaraan (id_pengiriman,id_kendaraan,id_kendaraan_sopir,status) 
                values ('$id_pengiriman','$id_kendaraan','$sopir_1','$status')";
            } else {
                $simpan = "INSERT INTO pengiriman_kendaraan (id_pengiriman,id_kendaraan,id_kendaraan_sopir,status) 
                 values ('$id_pengiriman','$id_kendaraan','$sopir_1','$status'),('$id_pengiriman','$id_kendaraan','$sopir_2','$status')";
            }
            if (mysqli_query($con, $simpan)) {
                $aksi_5 = mysqli_query($con, "SELECT
                c.id_kendaraan_sopir,
                c.nama
                FROM pengiriman_kendaraan a 
                LEFT OUTER join kendaraan_sopir c on a.id_kendaraan_sopir = c.id_kendaraan_sopir
                where a.id_pengiriman = '$id_pengiriman' and a.status = '$status'  
                ORDER BY a.id_pengiriman_kendaraan DESC  LIMIT 2");

                $row = mysqli_fetch_array($aksi);
                $hasil = array();
                $hasil['id_pengiriman'] = $id_pengiriman;
                $hasil['status_kendaraan'] = "Aneka Usaha";

                $kendaraan = array();
                $kendaraan['id'] =  $row['id_kendaraan'];
                $kendaraan['no_pol'] = $row['no_pol'];
                $hasil['kendaraan'] = $kendaraan;

                $sopir = array();
                while ($row_6 = mysqli_fetch_array($aksi_5)) {
                    $hasil_3 = array();
                    $hasil_3['id'] = $row_6['id_kendaraan_sopir'];
                    $hasil_3['nama'] = $row_6['nama'];
                    array_push($sopir, $hasil_3);
                }
                $hasil['sopir'] = $sopir;
                $response["success"] = 1;
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
    } else if ($status == 2) {

        $aksi = mysqli_query($con, "SELECT a.id_kendaraan,a.no_pol,b.id_kendaraan_sopir,b.nama
        FROM kendaraan a
        LEFT OUTER JOIN kendaraan_sopir b on a.id_kendaraan_sopir = b.id_kendaraan_sopir
        WHERE a.id_kendaraan = '$id_kendaraan'");

        $mitra = mysqli_fetch_array(mysqli_query($con, "SELECT a.*,b.id_mitra,b.nama
        FROM mitra_kendaraan a
        LEFT OUTER JOIN mitra b on a.id_mitra = b.id_mitra
        WHERE a.id_mitra_kendaraan = '$id_mitra_kendaraan'"));
        if (mysqli_num_rows($aksi) > 0) {
            $simpan;
            if (empty($sopir_2)) {
                $simpan = "INSERT INTO pengiriman_kendaraan (id_pengiriman,id_kendaraan,id_kendaraan_sopir,id_mitra_kendaraan,status) 
                values ('$id_pengiriman','$id_kendaraan','$sopir_1','$id_mitra_kendaraan','$status')";
            } else {
                $simpan = "INSERT INTO pengiriman_kendaraan (id_pengiriman,id_kendaraan,id_kendaraan_sopir,id_mitra_kendaraan,status) 
                 values ('$id_pengiriman','$id_kendaraan','$sopir_1',$id_mitra_kendaraan,'$status'),('$id_pengiriman','$id_kendaraan','$sopir_2',$id_mitra_kendaraan,'$status')";
            }
            if (mysqli_query($con, $simpan)) {
                $aksi_5 = mysqli_query($con, "SELECT
                c.id_kendaraan_sopir,
                c.nama
                FROM pengiriman_kendaraan a 
                LEFT OUTER join kendaraan_sopir c on a.id_kendaraan_sopir = c.id_kendaraan_sopir
                where a.id_pengiriman = '$id_pengiriman' and a.status = '$status'
                 ORDER BY a.id_pengiriman_kendaraan DESC  LIMIT 2");
                $row = mysqli_fetch_array($aksi);
                $hasil = array();
                $hasil['id_pengiriman'] = $id_pengiriman;
                $hasil['status_kendaraan'] = "Mitra";

                $kendaraan = array();
                $kendaraan['id'] =  $row['id_kendaraan'];
                $kendaraan['no_pol'] = $row['no_pol'];
                $hasil['kendaraan'] = $kendaraan;

                $sopir = array();
                while ($row_6 = mysqli_fetch_array($aksi_5)) {
                    $hasil_3 = array();
                    $hasil_3['id'] = $row_6['id_kendaraan_sopir'];
                    $hasil_3['nama'] = $row_6['nama'];
                    array_push($sopir, $hasil_3);
                }
                $hasil['sopir'] = $sopir;

                $mitra_array = array();
                $mitra_array['id_mitra_kendaraan'] =  $mitra['id_mitra_kendaraan'];
                $mitra_array['id_mitra'] = $mitra['id_mitra'];
                $mitra_array['nama_mitra'] = $mitra['nama'];
                $mitra_array['no_pol'] = $mitra['no_pol'];
                $hasil['mitra'] = $mitra_array;

                $response["success"] = 1;
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
    } else {
        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
