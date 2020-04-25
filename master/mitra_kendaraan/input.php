<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $tipe = anti_injection($con, $_POST['tipe']);
  $id_mitra = anti_injection($con, $_POST['id_mitra']);
  $id_pengiriman_jenis = anti_injection($con, $_POST['id_pengiriman_jenis']);
  $no_pol = anti_injection($con, $_POST['no_pol']);
  $aktif = anti_injection($con, $_POST['aktif']);
  $ket = anti_injection($con, $_POST['ket']);
  $id_mitra_kendaraan = anti_injection($con, $_POST['id_mitra_kendaraan']);

  if ($tipe == "tambah") {
    $tambah = "INSERT INTO mitra_kendaraan (id_mitra,id_pengiriman_jenis,no_pol,aktif,ket) VALUES
        ('$id_mitra','$id_pengiriman_jenis','$no_pol','$aktif','$ket')";
    if (mysqli_query($con, $tambah)) {
      $response["success"] = 1;
      $hasil = array();
      $hasil['id_mitra_kendaraan'] = mysqli_insert_id($con);
      $id = mysqli_insert_id($con);
      $read = mysqli_query($con, "SELECT a.id_mitra_kendaraan,a.id_mitra,b.nama as nama_mitra,a.id_pengiriman_jenis,c.jenis as nama_jenis

            FROM mitra_kendaraan a
            LEFT OUTER JOIN mitra b on a.id_mitra = b.id_mitra
            LEFT OUTER JOIN pengiriman_jenis c on a.id_pengiriman_jenis = c.id_pengiriman_jenis
            where a.id_mitra_kendaraan = '$id'");
      $row = mysqli_fetch_array($read);

      $mitra = array();
      $mitra['id'] = $row['id_mitra'];
      $mitra['nama'] = $row['nama_mitra'];
      $hasil['mitra'] = $mitra;

      $jenis = array();
      $jenis['id'] = $row['id_pengiriman_jenis'];
      $jenis['nama'] = $row['nama_jenis'];
      $hasil['jenis'] = $jenis;

      $hasil['no_pol'] = $no_pol;
      $hasil['aktif'] = $aktif;
      $hasil['ket'] = $ket;
      $response['data'] = $hasil;
      echo json_encode($response);
    } else {
      $response["success"] = 0;
      echo json_encode($response);
    }
  } else if ($tipe == "edit") {
    $cari = mysqli_query($con, "SELECT * FROM mitra_kendaraan where id_mitra_kendaraan = '$id_mitra_kendaraan'");
    if (mysqli_num_rows($cari) > 0) {
      $edit = "UPDATE mitra_kendaraan SET id_mitra ='$id_mitra',
      id_pengiriman_jenis = '$id_pengiriman_jenis',
      no_pol = '$no_pol',
      aktif = '$aktif',
      ket = '$ket'
  where id_mitra_kendaraan = '$id_mitra_kendaraan'";
      if (mysqli_query($con, $edit)) {
        $response["success"] = 1;
        $hasil = array();
        $hasil['id_mitra_kendaraan'] = $id_mitra_kendaraan;

        $read = mysqli_query($con, "SELECT a.id_mitra_kendaraan,a.id_mitra,b.nama as nama_mitra,a.id_pengiriman_jenis,c.jenis as nama_jenis

      FROM mitra_kendaraan a
      LEFT OUTER JOIN mitra b on a.id_mitra = b.id_mitra
      LEFT OUTER JOIN pengiriman_jenis c on a.id_pengiriman_jenis = c.id_pengiriman_jenis
      where a.id_mitra_kendaraan = '$id_mitra_kendaraan'");
        $row = mysqli_fetch_array($read);

        $mitra = array();
        $mitra['id'] = $row['id_mitra'];
        $mitra['nama'] = $row['nama_mitra'];
        $hasil['mitra'] = $mitra;

        $jenis = array();
        $jenis['id'] = $row['id_pengiriman_jenis'];
        $jenis['nama'] = $row['nama_jenis'];
        $hasil['jenis'] = $jenis;

        $hasil['no_pol'] = $no_pol;
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
    $cari = mysqli_query($con, "SELECT * FROM mitra_kendaraan where id_mitra_kendaraan = '$id_mitra_kendaraan'");
    if (mysqli_num_rows($cari) > 0) {
      $hapus = "DELETE FROM mitra_kendaraan WHERE id_mitra_kendaraan = '$id_mitra_kendaraan'";
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
