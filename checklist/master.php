<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tipe = anti_injection($con, $_GET['tipe']);
    $id_pengiriman_jenis = anti_injection($con, $_GET['id_pengiriman_jenis']);
    $page = anti_injection($con, $_GET['page']);

    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT a.id_checklist_master,a.id_pengiriman_jenis,b.jenis, a.nama  FROM checklist_master a 
        LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis where a.id_pengiriman_jenis = '$id_pengiriman_jenis'");
        if (mysqli_num_rows($all) > 0) {
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_checklist_master'] = $row['id_checklist_master'];

                $jenis_pengiriman = array();
                $jenis_pengiriman['id'] = $row['id_pengiriman_jenis'];
                $jenis_pengiriman['nama'] = $row['jenis'];
                $hasil['jenis_pengiriman'] = $jenis_pengiriman;

                $hasil['nama'] = $row['nama'];
                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $batas = 1;
        $halaman = $page;
        if (empty($halaman)) {
            $posisi = 0;
            $halaman = 1;
        } else {
            $posisi = ($halaman - 1) * $batas;
        }
        $one = mysqli_query($con, "SELECT * FROM checklist_master where id_pengiriman_jenis = '$id_pengiriman_jenis' ORDER BY  id_checklist_master ASC limit $posisi,$batas ");

        $paging = mysqli_query($con, "SELECT * FROM checklist_master  where id_pengiriman_jenis = '$id_pengiriman_jenis' ");
        $jmldata = mysqli_num_rows($paging);
        $jmlhalaman = ceil($jmldata / $batas);

        if (mysqli_num_rows($one) > 0) {

            $response["success"] = 1;
            $response["page"] = $page;
            $response['data'] = array();
            for ($i = 1; $i <= $jmlhalaman; $i++) {
                if ($i != $halaman) {
                    $response["page_total"] = $i;
                } else {
                    $response["page_total"] = $i;
                }
            }
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_checklist_master'] = $row['id_checklist_master'];
            $hasil['nama'] = $row['nama'];
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
