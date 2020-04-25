<?php
require_once('../../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tipe = anti_injection($con, $_GET['tipe']);
    $id_kendaraan = anti_injection($con, $_GET['id_kendaraan']);
    $id_perusahaan = anti_injection($con, $_GET['id_perusahaan']);
    $like = anti_injection($con, $_GET['like']);

    if ($tipe == "all") {
        if (empty($id_perusahaan)) {
            $all = mysqli_query($con, "SELECT 
                    a.id_kendaraan,
                    a.no_pol,
                    a.mulai_operasi,
                    a.aktif,
                    a.ket,
                    a.id_pengiriman_jenis,b.jenis as nama_jenis, a.id_perusahaan,c.nama as nama_perusahaan, a.id_kendaraan_sopir , d.nama as nama_kendaraan
                    FROM kendaraan a
                    LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
                    LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
                    LEFT OUTER JOIN kendaraan_sopir d on a.id_kendaraan_sopir = d.id_kendaraan_sopir");
            if (mysqli_num_rows($all) > 0) {
                //jika ada
                $response["success"] = 1;
                $response['data'] = array();
                //maka dibikin perulangan tampilkan data
                while ($row = mysqli_fetch_array($all)) {
                    $hasil = array();
                    $hasil['id_kendaraan'] = $row['id_kendaraan'];

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

                    $hasil['no_pol'] = $row['no_pol'];
                    $hasil['mulai_operasi'] = $row['mulai_operasi'];
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
        } else {
            $all = mysqli_query($con, "SELECT 
            a.id_kendaraan,
            a.no_pol,
            a.mulai_operasi,
            a.aktif,
            a.ket,
            a.id_pengiriman_jenis,b.jenis as nama_jenis, a.id_perusahaan,c.nama as nama_perusahaan, a.id_kendaraan_sopir , d.nama as nama_kendaraan
                        FROM kendaraan a
                        LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
                        LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
                        LEFT OUTER JOIN kendaraan_sopir d on a.id_kendaraan_sopir = d.id_kendaraan_sopir
                        WHERE a.id_perusahaan = '$id_perusahaan'");
            if (mysqli_num_rows($all) > 0) {
                //jika ada
                $response["success"] = 1;
                $response['data'] = array();
                //maka dibikin perulangan tampilkan data
                while ($row = mysqli_fetch_array($all)) {
                    $hasil = array();
                    $hasil['id_kendaraan'] = $row['id_kendaraan'];

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

                    $hasil['no_pol'] = $row['no_pol'];
                    $hasil['mulai_operasi'] = $row['mulai_operasi'];
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
        }
    } else if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT 
        a.id_kendaraan,
        a.no_pol,
        a.mulai_operasi,
        a.aktif,
        a.ket,
        a.id_pengiriman_jenis,b.jenis as nama_jenis, a.id_perusahaan,c.nama as nama_perusahaan, a.id_kendaraan_sopir , d.nama as nama_kendaraan
                    FROM kendaraan a
                    LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
                    LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
                    LEFT OUTER JOIN kendaraan_sopir d on a.id_kendaraan_sopir = d.id_kendaraan_sopir
                    where a.id_perusahaan = '$id_perusahaan' and a.no_pol  LIKE '%$like%'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_kendaraan'] = $row['id_kendaraan'];

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

                $hasil['no_pol'] = $row['no_pol'];
                $hasil['mulai_operasi'] = $row['mulai_operasi'];
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
        $like = mysqli_query($con, "SELECT 
        a.id_kendaraan,
        a.no_pol,
        a.mulai_operasi,
        a.aktif,
        a.ket,
        a.id_pengiriman_jenis,b.jenis as nama_jenis, a.id_perusahaan,c.nama as nama_perusahaan, a.id_kendaraan_sopir , d.nama as nama_kendaraan
                    FROM kendaraan a
                    LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
                    LEFT OUTER JOIN perusahaan c on a.id_perusahaan = c.id_perusahaan
                    LEFT OUTER JOIN kendaraan_sopir d on a.id_kendaraan_sopir = d.id_kendaraan_sopir
                    where a.id_kendaraan ='$id_kendaraan'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($like);
            $hasil = array();
            $hasil['id_kendaraan'] = $row['id_kendaraan'];

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

            $hasil['no_pol'] = $row['no_pol'];
            $hasil['mulai_operasi'] = $row['mulai_operasi'];
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
