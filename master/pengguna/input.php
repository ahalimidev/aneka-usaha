<?php

require_once('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $tipe = anti_injection($con, $_POST['tipe']);
  $id_perusahaan = anti_injection($con, $_POST['id_perusahaan']);
  $nama = anti_injection($con, $_POST['nama']);
  $email = anti_injection($con, $_POST['email']);
  $password = anti_injection($con, $_POST['password']);
  $hak_akses = anti_injection($con, $_POST['hak_akses']);
  $id_pengguna = anti_injection($con, $_POST['id_pengguna']);

  if ($tipe == "tambah") {
    $tambah = "INSERT INTO pengguna (id_perusahaan,nama,email,password,hak_akses) values
       ('$id_perusahaan','$nama','$email','$password','$hak_akses')";
    if (mysqli_query($con, $tambah)) {
      $response["success"] = 1;
      $hasil = array();
      $hasil['id_pengguna'] = mysqli_insert_id($con);
      $id = mysqli_insert_id($con);
      $read = mysqli_query($con, "SELECT a.id_pengguna,a.id_perusahaan,b.nama as nama_perusahaan 
            FROM pengguna a
            LEFT OUTER join perusahaan b on a.id_perusahaan = b.id_perusahaan
            where a.id_pengguna ='$id'");
      $row = mysqli_fetch_array($read);

      $perusahaan = array();
      $perusahaan['id'] = $row['id_perusahaan'];
      $perusahaan['nama'] = $row['nama_perusahaan'];
      $hasil['perusahaan'] = $perusahaan;

      $hasil['nama'] = $nama;
      $hasil['email'] = $email;
      $hasil['password'] = $password;
      $hasil['hak_akses'] = $hak_akses;

      $response['data'] = $hasil;
      echo json_encode($response);
    } else {
      $response["success"] = 0;
      echo json_encode($response);
    }
  } else if ($tipe == "edit") {
    $edit = "UPDATE pengguna set id_perusahaan='$id_perusahaan', 
                nama='$nama',
                email='$email',
                password='$password',
                hak_akses='$hak_akses'
                where id_pengguna ='$id_pengguna'";
    if (mysqli_query($con, $edit)) {
      $response["success"] = 1;
      $hasil = array();
      $hasil['id_pengguna'] = $id_pengguna;

      $read = mysqli_query($con, "SELECT a.id_pengguna,a.id_perusahaan,b.nama as nama_perusahaan 
           FROM pengguna a
           LEFT OUTER join perusahaan b on a.id_perusahaan = b.id_perusahaan
           where a.id_pengguna ='$id_pengguna'");
      $row = mysqli_fetch_array($read);

      $perusahaan = array();
      $perusahaan['id'] = $row['id_perusahaan'];
      $perusahaan['nama'] = $row['nama_perusahaan'];
      $hasil['perusahaan'] = $perusahaan;

      $hasil['nama'] = $nama;
      $hasil['email'] = $email;
      $hasil['password'] = $password;
      $hasil['hak_akses'] = $hak_akses;

      $response['data'] = $hasil;
      echo json_encode($response);
    } else {
      $response["success"] = 0;
      echo json_encode($response);
    }
  } else if ($tipe == "hapus") {
    $hapus = "DELETE FROM pengguna WHERE id_pengguna = '$id_pengguna'";
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
