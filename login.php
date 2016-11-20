<?php
session_start();
if (isset($_SESSION["isLogin"])) {
    header("Location: index.php");
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
    <script src="js/main.js"></script>
</head>
<body>
<section class="login-form">

    <div class="header">
        <div class="container">
            <div class="row">
                <div class="row text-xs-center">
                    <span class="display-3">Sign In</span>
                </div>
                <div class="col-xs-2 offset-xs-5">
                    <hr/>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form method="post" action="auth.php">
                    <?php
                    if (isset($_SESSION["errorMsg"])) {
                        echo "<div class=\"alert alert-danger\">";
                        echo "<div class='alert alert-danger'>" . $_SESSION["errorMsg"] . "</div>";
                        echo "</div>";
                        unset($_SESSION["errorMsg"]);
                    }
                    ?>
                    <div class="form-group row">
                        <label for="inputUsername" class="col-sm-2 form-control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="username"
                                   onkeydown="if(event.keyCode == 13){$('.login-form .btn-login').click();}"
                                   class="form-control" id="inputUsername" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 form-control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="password"
                                   onkeydown="if(event.keyCode == 13){$('.login-form .btn-login').click();}"
                                   class="form-control" id="inputPassword" placeholder="Password">
                        </div>
                    </div>
                    <div class="row text-xs-right">
                        <input type="submit" name="submit" class="btn btn-primary btn-login" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</body>
</html>


