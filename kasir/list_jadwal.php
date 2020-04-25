<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id_perusahaan = anti_injection($con, $_GET['id_perusahaan']);
    $id_kendaraan = anti_injection($con, $_GET['id_kendaraan']);
    $page = anti_injection($con, $_GET['page']);

    $sintax;
    $batas = 10;
    $halaman = $page;
    if (empty($halaman)) {
        $posisi = 0;
        $halaman = 1;
    } else {
        $posisi = ($halaman - 1) * $batas;
    }

    if (empty($id_perusahaan)) {
        if (empty($id_kendaraan)) {
            $sintax = "  ";
        } else {
            $sintax = "WHERE d.id_kendaraan = '$id_kendaraan'";
        }
    } else {
        if (empty($id_kendaraan)) {
            $sintax = "WHERE a.id_perusahaan = '$id_perusahaan'";
        } else {
            $sintax = "WHERE a.id_perusahaan = '$id_perusahaan' AND d.id_kendaraan = '$id_kendaraan'";
        }
    }



    $all_1 = mysqli_query(
        $con,
        "SELECT
        c.id_pengiriman_kendaraan,
        a.id_pengiriman,
        a.tgl as tgl_pengiriman,
        a.id_daftar_biaya,
        e.nama as asal_pengiriman,
        f.nama as tujuan_pengiriman,
        b.jenis as jenis_pengiriman,
        c.id_kendaraan,
        d.no_pol,
        a.status AS status_pengiriman,
        c.status status_kendaraan,
        a.biaya_gaji,
        a.biaya_operasional,
        c.status as status_gaji,
        c.pembayaran_tgl,
        a.pembayaran_operasional_tgl,
        a.pembayaran_operasional
        FROM pengiriman a
        LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
        LEFT OUTER JOIN pengiriman_kendaraan c on c.id_pengiriman = a.id_pengiriman
        LEFT OUTER JOIN kendaraan d on c.id_kendaraan = d.id_kendaraan
        LEFT OUTER JOIN sub_client e on a.asal = e.id_sub_client
        LEFT OUTER JOIN sub_client f on a.tujuan = f.id_sub_client
        $sintax
        ORDER BY  a.id_pengiriman ASC limit $posisi,$batas"
    );

    $paging2 = mysqli_query($con, "select * from pengiriman");
    $jmldata = mysqli_num_rows($paging2);
    $jmlhalaman = ceil($jmldata / $batas);

    if (mysqli_num_rows($all_1) > 0) {
        $response["success"] = 1;
        $response["page"] = $page;
        for ($i = 1; $i <= $jmlhalaman; $i++) {
            if ($i != $halaman) {
                $response["page_total"] = $i;
            } else {
                $response["page_total"] = $i;
            }
        }
        $response['data'] = array();
        while ($row = mysqli_fetch_array($all_1)) {
            $hasil = array();

            $sopir_kendaraan = mysqli_query($con, " SELECT
            c.id_kendaraan_sopir,
            c.nama
            FROM pengiriman_kendaraan a 
            LEFT OUTER join kendaraan_sopir c on a.id_kendaraan_sopir = c.id_kendaraan_sopir
            where a.id_pengiriman = '$row[id_pengiriman]' 
            ORDER BY a.id_pengiriman_kendaraan DESC  LIMIT 2");

            $hasil['id_pengiriman_kendaraan'] = $row['id_pengiriman_kendaraan'];
            $hasil['id_pengiriman'] = $row['id_pengiriman'];
            $hasil['tgl_pengiriman'] = $row['tgl_pengiriman'];
            $hasil['id_daftar_biaya'] = $row['id_daftar_biaya'];
            $hasil['asal_pengiriman'] = $row['asal_pengiriman'];
            $hasil['tujuan_pengiriman'] = $row['tujuan_pengiriman'];
            $hasil['jenis_pengiriman'] = $row['jenis_pengiriman'];
            $kendaraan = array();
            $kendaraan['id'] =  $row['id_kendaraan'];
            $kendaraan['no_pol'] = $row['no_pol'];
            $hasil['kendaraan'] = $kendaraan;

            $sopir = array();
            while ($row_1 = mysqli_fetch_array($sopir_kendaraan)) {
                $hasil_1 = array();
                $hasil_1['id'] = $row_1['id_kendaraan_sopir'];
                $hasil_1['nama'] = $row_1['nama'];
                array_push($sopir, $hasil_1);
            }
            $hasil['sopir'] = $sopir;
            $hasil['status_pengiriman'] = $row['status_pengiriman'];
            $hasil['status_kendaraan'] = $row['status_kendaraan'];

            $pembayaran_operasional = array();
            $pembayaran_operasional['biaya'] =  $row['biaya_operasional'];
            $pembayaran_operasional['status'] = $row['pembayaran_operasional'];
            $pembayaran_operasional['tgl'] = $row['pembayaran_operasional_tgl'];
            $hasil['pembayaran_operasional'] = $pembayaran_operasional;


            $pembayaran_gaji = array();
            $pembayaran_gaji['biaya'] =  $row['biaya_gaji'];
            $pembayaran_gaji['status'] = $row['status_gaji'];
            $pembayaran_gaji['tgl'] = $row['pembayaran_tgl'];
            $hasil['pembayaran_gaji'] = $pembayaran_gaji;

            array_push($response['data'], $hasil);
        }
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    echo json_encode($response);
}
