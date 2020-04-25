<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_mitra = anti_injection($con, $_GET['id_mitra']);
    $id_mitra_kendaraan = anti_injection($con, $_GET['id_mitra_kendaraan']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT 
        a.no_pol,a.aktif,a.ket,
        a.id_mitra_kendaraan,a.id_mitra,b.nama as nama_mitra,a.id_pengiriman_jenis,c.jenis as nama_jenis
        
                    FROM mitra_kendaraan a
                    LEFT OUTER JOIN mitra b on a.id_mitra = b.id_mitra
                    LEFT OUTER JOIN pengiriman_jenis c on a.id_pengiriman_jenis = c.id_pengiriman_jenis
                    where a.id_mitra = '$id_mitra' ");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_mitra_kendaraan'] = $row['id_mitra_kendaraan'];

                $mitra = array();
                $mitra['id'] = $row['id_mitra'];
                $mitra['nama'] = $row['nama_mitra'];
                $hasil['mitra'] = $mitra;

                $jenis = array();
                $jenis['id'] = $row['id_pengiriman_jenis'];
                $jenis['nama'] = $row['nama_jenis'];
                $hasil['jenis'] = $jenis;

                $hasil['no_pol'] = $row['no_pol'];
                $hasil['aktif'] = $row['aktif'];
                $hasil['ket'] = $row['ket'];

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT 
        a.no_pol,a.aktif,a.ket,
        a.id_mitra_kendaraan,a.id_mitra,b.nama as nama_mitra,a.id_pengiriman_jenis,c.jenis as nama_jenis
        
                    FROM mitra_kendaraan a
                    LEFT OUTER JOIN mitra b on a.id_mitra = b.id_mitra
                    LEFT OUTER JOIN pengiriman_jenis c on a.id_pengiriman_jenis = c.id_pengiriman_jenis
                    where a.id_mitra = '$id_mitra' and no_pol like '%$like%'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_mitra_kendaraan'] = $row['id_mitra_kendaraan'];

                $mitra = array();
                $mitra['id'] = $row['id_mitra'];
                $mitra['nama'] = $row['nama_mitra'];
                $hasil['mitra'] = $mitra;

                $jenis = array();
                $jenis['id'] = $row['id_pengiriman_jenis'];
                $jenis['nama'] = $row['nama_jenis'];
                $hasil['jenis'] = $jenis;

                $hasil['no_pol'] = $row['no_pol'];
                $hasil['aktif'] = $row['aktif'];
                $hasil['ket'] = $row['ket'];

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $all = mysqli_query($con, "SELECT 
        a.no_pol,a.aktif,a.ket,
        a.id_mitra_kendaraan,a.id_mitra,b.nama as nama_mitra,a.id_pengiriman_jenis,c.jenis as nama_jenis
        
                    FROM mitra_kendaraan a
                    LEFT OUTER JOIN mitra b on a.id_mitra = b.id_mitra
                    LEFT OUTER JOIN pengiriman_jenis c on a.id_pengiriman_jenis = c.id_pengiriman_jenis
                    where a.id_mitra_kendaraan = '$id_mitra_kendaraan' ");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($all);
            $hasil = array();
            $hasil['id_mitra_kendaraan'] = $row['id_mitra_kendaraan'];

            $mitra = array();
            $mitra['id'] = $row['id_mitra'];
            $mitra['nama'] = $row['nama_mitra'];
            $hasil['mitra'] = $mitra;

            $jenis = array();
            $jenis['id'] = $row['id_pengiriman_jenis'];
            $jenis['nama'] = $row['nama_jenis'];
            $hasil['jenis'] = $jenis;

            $hasil['no_pol'] = $row['no_pol'];
            $hasil['aktif'] = $row['aktif'];
            $hasil['ket'] = $row['ket'];
            $response['data'] = $hasil;


            echo json_encode($response);
        } else {
            //tidak ada data
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
