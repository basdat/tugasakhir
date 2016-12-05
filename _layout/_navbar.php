<nav id="navbar" class="navbar navbar-light navbar-fixed-top bg-faded">
    <a href="index.php" class="navbar-brand"><span class="text-highlight">Sistem Informasi Sidang</span></a>
    <button class="navbar-toggler hidden-md-up float-xs-right" type="button" data-toggle="collapse"
            data-target="#navbar-collapse">
    </button>
    <div class="collapse navbar-toggleable-sm" id="navbar-collapse">
        <ul class="nav navbar-nav float-md-right">
            <li class="nav-item nav-link">
                Hello,
                <strong>
                    <?php
                    echo $_SESSION['userdata']['nama'];
                    ?>
                </strong>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false">
                    Jadwal Sidang
                </a>
                <div class="dropdown-menu" aria-labelledby="Preview">
                    <a class="dropdown-item" href="jadwal_sidang.php">Lihat Jadwal Sidang</a>
                    <?php
                    if ($_SESSION['userdata']['role'] == 'admin') {
                        echo "<a class=\"dropdown-item\" href=\"membuat_jadwal_sidang_MKS.php\">Buat Jadwal Sidang</a>";
                    }
                    ?>
                    <?php
                    if ($_SESSION['userdata']['role'] != 'mahasiswa') {
                        echo "<a class=\"dropdown-item\" href=\"jadwal_non_sidang_dosen.php\">Buat Jadwal Non-Sidang</a>";
                        echo "<a class=\"dropdown-item\" href=\"izin_jadwal_sidang.php\">Mengizinkan Jadwal Sidang</a>";
                    }
                    ?>

                </div>
            </li>
            <?php if ($_SESSION['userdata']['role'] != 'mahasiswa'){ ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        Mata Kuliah Spesial
                    </a>
                    <div class="dropdown-menu" aria-labelledby="Preview">
                        <?php
                        if ($_SESSION['userdata']['role'] == 'admin') {
                            echo '<a class="dropdown-item" href="tambah_peserta.php">Tambah Peserta MKS</a>';
                        }
                        ?>
                        <a class="dropdown-item" href="mata_kuliah_spesial.php">Lihat Daftar MKS</a>
                    </div>
                </li>
            <?php } ?>
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