<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();
$stmt = $conn->prepare(" SELECT * FROM mahasiswa m NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN jadwal_sidang jd NATURAL JOIN ruangan r, jenis_mks j WHERE j.id=mks.idjenismks AND m.npm=:npm");
$stmt->execute(array(':npm' => $_SESSION['userdata']['npm']));
$userRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section id="jadwal">
    <div class="container">
        <div class="row">
            <div class="card-deck-wrapper">
                <?php
                foreach ($userRows as $key => $row) {

                    if ((int)$key % 3 == 0) {
                        echo "<div class=\"card-deck\">";
                    }

                    $dospenglainhtml = "<td>";

                    $stmt = $conn->prepare("SELECT d.nama FROM dosen_penguji dpem JOIN dosen d ON d.nip = dpem.nipdosenpenguji WHERE dpem.idmks=:idmks");
                    $stmt->execute(array(':idmks' => $row['idmks']));
                    $dospenglain = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($dospenglain as $keyi => $dataR) {

                        $dospenglainhtml = $dospenglainhtml . sprintf("<li>%s</li>", $dataR['nama']) . "\n";
                    }

                    $dospenglainhtml = $dospenglainhtml . "</td>";

                    printf("
                    <div class=\"card\">
                        <div class=\"card-header\">
                            %s
                        </div>
                        <div class=\"card-block\">
                            <div class=\"container\">
                                <div class=\"row\">
                                    <div class=\"col-md-3\">
                                        Judul
                                    </div>
                                    <div class=\"col-md-9\" scope=\"row\">%s</div>
                                </div>
                                <div class=\"row\">
                                    <div class=\"col-md-3\">
                                        Jadwal Sidang
                                    </div>
                                    <div class=\"col-md-9\">%s</div>
                                </div>
                                <div class=\"row\">
                                    <div class=\"col-md-3\">
                                        Waktu Sidang
                                    </div>
                                    <div class=\"col-md-9\"><p>%s</p></div>
                                </div>
                                <div class=\"row\">
                                    <div class=\"col-md-3\">
                                        Dosen Penguji
                                    </div>
                                    <div class=\"col-md-9\">
                                        <ul>
                                            %s
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ",
                        $row['namamks'],
                        $row['judul'],
                        $row['tanggal'],
                        sprintf("%s – %s @ %s", $row['jammulai'], $row['jamselesai'], $row['namaruangan']),
                        $dospenglainhtml
                    );

                    if ((int)$key % 3 == 2) {
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>

    </div>
</section>