<nav id="navbar" class="navbar navbar-light navbar-fixed-top bg-faded">
    <a href="" class="navbar-brand"><span class="text-highlight">Sistem Informasi Sidang</span></a>
    <button class="navbar-toggler hidden-md-up float-xs-right" type="button" data-toggle="collapse"
            data-target="#navbar-collapse">
    </button>
    <div class="collapse navbar-toggleable-sm" id="navbar-collapse">
        <ul class="nav navbar-nav float-md-right">
            <li class="nav-item nav-link">
                Hello,
                <strong>
                    <?php
                    echo $_SESSION['username'];
                    ?>
                </strong>
            </li>
            <li class="nav-item">
                <a href="jadwal_sidang.php" class="nav-link">Jadwal Sidang</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false">
                    Mata Kuliah Spesial
                </a>
                <div class="dropdown-menu" aria-labelledby="Preview">
                    <a class="dropdown-item" href="#">Tambah Peserta</a>
                    <a class="dropdown-item" href="#">Buat Jadwal Sidang</a>
                    <a class="dropdown-item" href="mata_kuliah_spesial.php">Lihat Daftar</a>
                </div>
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