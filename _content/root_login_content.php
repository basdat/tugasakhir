<!--<section id="hero" class="header">

</section>-->
<section class="login-form">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 offset-md-7 col-sm-6 offset-sm-6 login-sidebar">
                <div class="container bg-white">

                    <div class="row text-xs-center title">
                        <span class="display-3">Sign In</span>
                    </div>
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
                            <label for="inputUsername" class="col-md-2 form-control-label">Username</label>
                            <div class="col-md-10">
                                <input type="text" name="username"
                                       onkeydown="if(event.keyCode == 13){$('.login-form .btn-login').click();}"
                                       class="form-control" id="inputUsername" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-md-2 form-control-label">Password</label>
                            <div class="col-md-10">
                                <input type="password" name="password"
                                       onkeydown="if(event.keyCode == 13){$('.login-form .btn-login').click();}"
                                       class="form-control" id="inputPassword" placeholder="Password">
                            </div>
                        </div>
                        <div class="row text-xs-right">
                            <input type="submit" name="submit" class="btn btn-primary btn-login" value="Login">
                        </div>
                        <!--                    Debug Login-->
                        <div class="row text-xs-right">
                            <label for="buttonDebugLogin">Mahasiswa Login</label>
                            <input id="buttonDebugLogin" type="submit"
                                   onclick="$('#inputUsername').val('Nurhayati31');$('#inputPassword').val('p1506757964')"
                                   name="submit" class="btn btn-secondary btn-login" value="Login">
                        </div>
                        <div class="row text-xs-right">
                            <label for="buttonDebugLogin">Dosen Login</label>
                            <input id="buttonDebugLogin" type="submit"
                                   onclick="$('#inputUsername').val('novita51');$('#inputPassword').val('novita98')"
                                   name="submit" class="btn btn-secondary btn-login" value="Login">
                        </div>
                        <div class="row text-xs-right">
                            <label for="buttonDebugLogin">Admin Login</label>
                            <input id="buttonDebugLogin" type="submit"
                                   onclick="$('#inputUsername').val('admin');$('#inputPassword').val('admin')"
                                   name="submit" class="btn btn-secondary btn-login" value="Login">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>