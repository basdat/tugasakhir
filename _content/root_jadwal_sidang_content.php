<?php
    require_once "database.php";
    $db = new database();
    $conn = $db->connectDB();
$stmt = $conn->prepare(" SELECT * FROM mahasiswa m NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN jadwal_sidang jd NATURAL JOIN ruangan r, jenis_mks j WHERE j.id=mks.idjenismks AND m.npm=:npm");
$stmt->execute(array(':npm' => $_SESSION['userdata']['npm']));
$userRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="hero" class="header">
    <div class="container">
        <div class="row">
            <div class="row text-xs-center">
                <span class="display-3">Jadwal Sidang</span>
            </div>
            <div class="col-xs-2 offset-xs-5">
                <hr/>
            </div>
        </div>
    </div>
</section>
<section id="jadwal">
    <div class="container">
        <div class="row">
            <?php
            print_r($userRows[0]);
            /*foreach ($userRows as $row) {
                printf("<div class=\"card\">
<div class=\"card-header\">
%s
</div>
            <div class=\"card-block\">
                <p class=\"card-text\">Some more card content</p>
            </div>
        </div>", $row['namamks']);
        }*/
            ?>
            <div class="card-deck-wrapper">
                <div class="card-deck">
                    <div class="card">
                        <div class="card-header">
                            Skripsi
                        </div>
                        <div class="card-block">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-3">
                                        Judul
                                    </div>
                                    <div class="col-md-9" scope="row">Aplikasi “Web Traffic Engineering”</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Jadwal Sidang
                                    </div>
                                    <div class="col-md-9">2016-01-26</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Waktu Sidang
                                    </div>
                                    <div class="col-md-9"><p>09:00 – 10:30 WIB @ 2.2301</p></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Dosen Penguji
                                    </div>
                                    <div class="col-md-9">
                                        <ul>
                                            <li>dosen</li>
                                            <li>dosen</li>
                                            <li>dosen</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card"></div>
                    <div class="card"></div>
                </div>
            </div>
        </div>
    </div>
</section>