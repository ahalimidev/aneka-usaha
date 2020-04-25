<?php

define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DATABASE', 'aneka_core');

$con = mysqli_connect(HOST, USER, PASS, DATABASE) or die('Unable to Connect');
$link = "http://localhost/cv_aneka_usaha/api/";

