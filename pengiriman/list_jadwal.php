<?php
require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $tipe = anti_injection($con, $_GET['tipe']);
  $id_pengiriman = anti_injection($con, $_GET['id_pengiriman']);
  $id_perusahaan = anti_injection($con, $_GET['id_perusahaan']);
  $status_pengiriman = anti_injection($con, $_GET['status_pengiriman']);
  $page = anti_injection($con, $_GET['page']);
  $sort = anti_injection($con, $_GET['sort']);
  $filter_tagihan = anti_injection($con, $_GET['filter_tagihan']);
  $filter_tgl_dari = anti_injection($con, $_GET['filter_tgl_dari']);
  $filter_tgl_sampai = anti_injection($con, $_GET['filter_tgl_sampai']);
  $filter_id_jenis = anti_injection($con, $_GET['filter_id_jenis']);
  $filter_id_client = anti_injection($con, $_GET['filter_id_client']);
  $filter_id_perusahaan = anti_injection($con, $_GET['filter_id_perusahaan']);

  if ($tipe == "all") {
    if (empty($sort)) {
      $sort = 'DESC';
    }

    if ($status_pengiriman != 4) {
      if ($filter_tagihan == 1) {
        $status = "='4'";
        if (empty($filter_tgl_sampai) || empty($filter_tgl_dari)) {
        } else {
          $filter_tanggal = " BETWEEN $filter_tgl_dari AND $filter_tgl_sampai";
        }
        if (empty($filter_id_jenis)) {
        } else {
          $filter_jenis = " LIKE '%$filter_id_jenis%'";
        }
        if (empty($filter_id_client)) {
        } else {
          $filter_client = " LIKE '%$filter_id_client%'";
        }

        if (empty($filter_id_perusahaan)) {
        } else {
          $filter_perusahan = " LIKE '%$filter_id_perusahaan%'";
        }
      } else {
        $perusahaan = "='$id_perusahaan'";
        $status = "='$status_pengiriman'";
      }
    } else {
      if ($filter_tagihan == 1) {
        $status = "='4'";
        if (empty($filter_tgl_sampai) || empty($filter_tgl_dari)) {
        } else {
          $filter_tanggal = "BETWEEN $filter_tgl_dari AND $filter_tgl_sampai";
        }
        if (empty($filter_id_jenis)) {
        } else {
          $filter_jenis = " LIKE '%$filter_id_jenis%'";
        }
        if (empty($filter_id_client)) {
        } else {
          $filter_client = " LIKE '%$filter_id_client%'";
        }

        if (empty($filter_id_perusahaan)) {
        } else {
          $filter_perusahan = " LIKE '%$filter_id_perusahaan%'";
        }
        echo $status . $filter_tanggal . $filter_jenis . $filter_client . $filter_perusahan;
      }
    }

    $batas = 25;
    $halaman = $page;
    if (empty($halaman)) {
      $posisi = 0;
      $halaman = 1;
    } else {
      $posisi = ($halaman - 1) * $batas;
    }

    $all = mysqli_query(
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
      c.status status_kendaraan
      FROM pengiriman a
      LEFT OUTER JOIN pengiriman_jenis b on a.id_pengiriman_jenis = b.id_pengiriman_jenis
      LEFT OUTER JOIN pengiriman_kendaraan c on c.id_pengiriman = a.id_pengiriman
      LEFT OUTER JOIN kendaraan d on c.id_kendaraan = d.id_kendaraan
      LEFT OUTER JOIN sub_client e on e.id_sub_client = a.asal
      LEFT OUTER JOIN sub_client f on f.id_sub_client = a.tujuan
      WHERE a.status $status AND 
      a.id_perusahaan $perusahaan AND 
      a.tgl $filter_tanggal AND 
      a.id_pengiriman_jenis $filter_jenis AND
      a.asal $filter_client AND 
      a.id_perusahaan $filter_perusahan
      ORDER BY  a.id_pengiriman $sort limit $posisi,$batas"
    );

    $paging2 = mysqli_query($con, "select * from pengiriman");
    $jmldata = mysqli_num_rows($paging2);
    $jmlhalaman = ceil($jmldata / $batas);

    if (mysqli_num_rows($all) > 0) {
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
      while ($row = mysqli_fetch_array($all)) {
        $hasil = array();
        $sopir_kendaraan = mysqli_query($con, "SELECT
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
        array_push($response['data'], $hasil);
      }
      echo json_encode($response);
    } else {
      $response["success"] = 0;
      echo json_encode($response);
    }
  } else if ($tipe == "one") {

    $aksi = mysqli_query($con, "SELECT
    a.id_pengiriman,
    a.tgl as tgl_pengiriman,
    a.status as status_pengiriman,
    a.id_daftar_biaya,
    e.nama as asal_pengiriman,
    f.nama as tujuan_pengiriman,
    c.jenis as jenis_pengiriman,
    a.jarak,
    a.proses_pengiriman,
    d.status as status_kendaraan,
    a.biaya_jasa,
    a.biaya_operasional,
    a.biaya_gaji,
    a.biaya_gendongan,
    a.total_biaya_lain,
    a.id_perusahaan,
    b.nama as nama_perusahaan
    FROM pengiriman a
    LEFT OUTER JOIN perusahaan b on a.id_perusahaan = b.id_perusahaan
    LEFT OUTER JOIN pengiriman_jenis c on a.id_pengiriman_jenis = c.id_pengiriman_jenis
    LEFT OUTER JOIN pengiriman_kendaraan d on a.id_pengiriman  = d.id_pengiriman
    LEFT OUTER JOIN sub_client e on e.id_sub_client = a.asal
      LEFT OUTER JOIN sub_client f on f.id_sub_client = a.tujuan
    where a.id_pengiriman = '$id_pengiriman'
    GROUP BY a.id_pengiriman,d.status");
    //pengiriman_biaya_lain
    $aksi_1 = mysqli_query($con, "SELECT biaya,keterangan FROM pengiriman_biaya_lain where id_pengiriman = '$id_pengiriman'");
    //dokumen
    $aksi_2 = mysqli_query($con, "SELECT * FROM  pengiriman_dokumen where id_pengiriman = '$id_pengiriman'");
    //mitra 
    $aksi_3 = mysqli_query($con, "SELECT
        b.id_mitra_kendaraan,
        b.id_mitra,
        b.no_pol,
        c.nama
        FROM pengiriman_kendaraan a
        LEFT OUTER JOIN mitra_kendaraan b on a.id_mitra_kendaraan = b.id_mitra_kendaraan
        LEFT OUTER JOIN mitra c on b.id_mitra = c.id_mitra
        WHERE a.id_pengiriman = '$id_pengiriman' and b.aktif = 1");
    //kendaraan 
    $aksi_4 = mysqli_query($con, "SELECT
        a.id_kendaraan,
        b.no_pol
        FROM pengiriman_kendaraan a 
        LEFT OUTER JOIN kendaraan b on a.id_kendaraan = b.id_kendaraan
        where a.id_pengiriman = '$id_pengiriman'
        group by a.id_pengiriman");
    //sopir
    $aksi_5 = mysqli_query($con, "SELECT
        c.id_kendaraan_sopir,
        c.nama
        FROM pengiriman_kendaraan a 
        LEFT OUTER join kendaraan_sopir c on a.id_kendaraan_sopir = c.id_kendaraan_sopir
        where a.id_pengiriman = '$id_pengiriman'
        ORDER BY a.id_pengiriman_kendaraan DESC  LIMIT 2");

    if (mysqli_num_rows($aksi) > 0) {
      $row_1 = mysqli_fetch_array($aksi);
      $response["success"] = 1;
      $hasil = array();
      $hasil['id_pengiriman'] = $row_1['id_pengiriman'];
      $hasil['tgl_pengiriman'] = $row_1['tgl_pengiriman'];
      $hasil['status_pengiriman'] = $row_1['status_pengiriman'];
      $hasil['id_daftar_biaya'] = $row_1['id_daftar_biaya'];
      $hasil['asal_pengiriman'] = $row_1['asal_pengiriman'];
      $hasil['tujuan_pengiriman'] = $row_1['tujuan_pengiriman'];
      $hasil['jenis_pengiriman'] = $row_1['jenis_pengiriman'];
      $hasil['jarak'] = $row_1['jarak'] . 'km';
      $hasil['proses_pengiriman'] = $row_1['proses_pengiriman'];
      $hasil['status_kendaraan'] = $row_1['status_kendaraan'];
      $hasil['biaya_jasa'] = $row_1['biaya_jasa'];
      $hasil['biaya_operasional'] = $row_1['biaya_operasional'];
      $hasil['biaya_gaji'] = $row_1['biaya_gaji'];
      $hasil['biaya_gendongan'] = $row_1['biaya_gendongan'];

      $biaya_lain = array();
      while ($row_2 = mysqli_fetch_array($aksi_1)) {
        $hasil_1 = array();
        $hasil_1['keterangan'] = $row_2['keterangan'];
        $hasil_1['biaya'] = $row_2['biaya'];
        array_push($biaya_lain, $hasil_1);
      }
      $hasil['biaya_lain'] = $biaya_lain;

      $perusahaan = array();
      $perusahaan['id'] =  $row_1['id_perusahaan'];
      $perusahaan['nama'] = $row_1['nama_perusahaan'];
      $hasil['perusahaan'] = $perusahaan;

      $sopir = array();
      while ($row_6 = mysqli_fetch_array($aksi_5)) {
        $hasil_3 = array();
        $hasil_3['id'] = $row_6['id_kendaraan_sopir'];
        $hasil_3['nama'] = $row_6['nama'];
        array_push($sopir, $hasil_3);
      }
      $hasil['sopir'] = $sopir;


      $row_5 = mysqli_fetch_array($aksi_4);
      $kendaraan = array();
      $kendaraan['id'] =  $row_5['id_kendaraan'];
      $kendaraan['no_pol'] = $row_5['no_pol'];
      $hasil['kendaraan'] = $kendaraan;

      $row_4 = mysqli_fetch_array($aksi_3);
      $mitra = array();
      $mitra['id_mitra_kendaraan'] =  $row_4['id_mitra_kendaraan'];
      $mitra['id_mitra'] = $row_4['id_mitra'];
      $mitra['nama_mitra'] = $row_4['nama'];
      $mitra['no_pol'] = $row_4['no_pol'];
      $hasil['mitra'] = $mitra;

      $dokumen = array();
      while ($row_3 = mysqli_fetch_array($aksi_2)) {
        $hasil_2 = array();
        $hasil_2['id'] = $row_3['id_pengiriman_dokumen'];
        $hasil_2['judul'] = $row_3['judul'];
        $hasil_2['file'] = URL_DOKUMEN . $row_3['file'];
        array_push($dokumen, $hasil_2);
      }

      $hasil['dokumen'] = $dokumen;

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
