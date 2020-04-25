
 <?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");


  include_once 'database.php';

  define('URL_LOGO', $link . 'apisys/photo/logo/');
  define('URL_TTD', $link . 'apisys/photo/ttd/');
  define('URL_DOKUMEN', $link . 'apisys/photo/dokumen/');
  define('API_ACCESS_KEY', 'AIzaSyBqVOkkKAvy1L30xevINSpr5pxEPv7VQho');

  function anti_injection($con, $data)
  {
    $isset = (isset($data)) ? $data : '';
    $data_1 = mysqli_real_escape_string($con, $isset);
    $data_2 = trim($data_1);
    $data_3 = stripcslashes($data_2);
    $data_4 = htmlspecialchars($data_3);
    $data_5 = strip_tags($data_4);
    return $data_5;
  }

  function acakhuruf($data)
  {
    $karakter = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $string = "";
    for ($i = 0; $i <= $data; $i++) {
      $pos = rand(0, strlen($karakter) - 1);
      $string .= $karakter{
        $pos};
    }
    return $string;
  }


  ?>