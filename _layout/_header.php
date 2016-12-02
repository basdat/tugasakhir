<?php
session_start();
require_once 'navigation.php';
if (navigation::is_in_page('login.php')) {
    if (isset($_SESSION["username"])) {
        navigation::go_to_url('index.php');
    }
} else {
    if (!isset($_SESSION["username"])) {
        ;
        navigation::go_to_url('login.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sistem Informasi Sidang</title>
    <meta name="description"
          content="A informatic system for final presentation of paper which is created by college student."/>
    <meta name="keywords" content=""/>
    <meta name="rating" content="general"/>
    <meta name="copyright" content="Copyright ©2016"/>
    <meta name="expires" content="never"/>
    <meta name="distribution" content="global"/>
    <meta name="robots" content="index,follow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <link href="https://fonts.googleapis.com/css?family=Oswald|Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/tether.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/vendor/jquery-3.1.1.js"></script>
    <script src="js/vendor/tether.js"></script>
    <script src="js/vendor/bootstrap.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
</head>
<body>
<header>
    <?php
    if (!navigation::is_in_page('login.php')) {
        include "_navbar.php";
    }
    ?>
</header>