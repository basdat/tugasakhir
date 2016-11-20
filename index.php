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
    <header>
        <nav class="navbar navbar-light navbar-fixed-top bg-faded">
            <a href="" class="navbar-brand"><span class="text-highlight">Sistem Informasi Sidang</span></a>
            <button class="navbar-toggler hidden-sm-up float-xs-right" type="button" data-toggle="collapse"
                    data-target="#navbar-collapse">
            </button>
            <div class="collapse navbar-toggleable-xs" id="navbar-collapse">
                <ul class="nav navbar-nav float-xs-right">
                    <li class="nav-item nav-link">
                        Hello,
                        <?php
                            echo $_SESSION['username'];
                        ?>
                    </li>
                    <li class="nav-item">
                        <form method="post" action="auth.php">
                            <div class="row text-xs-right">
                                <input type="submit" name="submit" class="btn btn-outline-danger btn-login" value="Logout">
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="row text-xs-center">
                    <span class="display-3">Hello</span>
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


