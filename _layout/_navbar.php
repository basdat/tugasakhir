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