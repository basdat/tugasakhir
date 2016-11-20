<?php
session_start();
if (!isset($_SESSION["isLogin"])) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sistem Informasi Sidang</title>
    <meta name="description" content="A informatic system for final presentation of paper which is created by college student."/>
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
    <script src="js/main.js"></script>
</head>
<body>
<section class="login-form">

    <div class="header">
        <div class="container">
            <div class="row">
                <div class="row text-xs-center">
                    <span class="display-3">Login</span>
                </div>
                <div class="col-xs-2 offset-xs-5">
                    <hr/>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>


