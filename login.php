<?php include "_layout/_header.php" ?>

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

<?php include "_layout/_footer.php" ?>
