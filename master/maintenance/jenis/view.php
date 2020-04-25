<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tipe = anti_injection($con, $_GET['tipe']);
    $id_jenis_maintenance = anti_injection($con, $_GET['id_jenis_maintenance']);
    $like = anti_injection($con, $_GET['like']);
    if ($tipe == "all") {
        $all = mysqli_query($con, "SELECT * FROM jenis_maintenance a LEFT OUTER JOIN jenis_maintenance_kategori b on b.id_jenis_maintenance_kategori = a.id_kategori");
        if (mysqli_num_rows($all) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($all)) {
                $hasil = array();
                $hasil['id_jenis_maintenance'] = $row['id_jenis_maintenance'];
                $hasil['jenis'] = $row['jenis'];
                $kategori = array();
                $kategori['id'] =  $row['id_jenis_maintenance_kategori'];
                $kategori['nama'] = $row['kategori'];
                $hasil['kategori'] = $kategori;

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "like") {
        $like = mysqli_query($con, "SELECT * FROM jenis_maintenance a LEFT OUTER JOIN jenis_maintenance_kategori b on b.id_jenis_maintenance_kategori = a.id_kategori where a.jenis LIKE '%$like%'");
        if (mysqli_num_rows($like) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            while ($row = mysqli_fetch_array($like)) {
                $hasil = array();
                $hasil['id_jenis_maintenance'] = $row['id_jenis_maintenance'];
                $hasil['jenis'] = $row['jenis'];
                $kategori = array();
                $kategori['id'] =  $row['id_jenis_maintenance_kategori'];
                $kategori['nama'] = $row['kategori'];
                $hasil['kategori'] = $kategori;

                array_push($response['data'], $hasil);
            }
            echo json_encode($response);
        } else {
            //tidak ada data
            $response["success"] = 0;
            echo json_encode($response);
        }
    } else if ($tipe == "one") {
        $one = mysqli_query($con, "SELECT * FROM jenis_maintenance a LEFT OUTER JOIN jenis_maintenance_kategori b on b.id_jenis_maintenance_kategori = a.id_kategori where id_jenis_maintenance = '$id_jenis_maintenance'");
        if (mysqli_num_rows($one) > 0) {
            //jika ada
            $response["success"] = 1;
            $response['data'] = array();
            //maka dibikin perulangan tampilkan data
            $row = mysqli_fetch_array($one);
            $hasil = array();
            $hasil['id_jenis_maintenance'] = $row['id_jenis_maintenance'];
            $hasil['jenis'] = $row['jenis'];
            $kategori = array();
            $kategori['id'] =  $row['id_jenis_maintenance_kategori'];
            $kategori['nama'] = $row['kategori'];
            $hasil['kategori'] = $kategori;
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
