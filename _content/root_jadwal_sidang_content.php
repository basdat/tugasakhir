<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();
$stmt = $conn->prepare(" SELECT * FROM mahasiswa m NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN jadwal_sidang jd, jenis_mks j WHERE j.id=mks.idjenismks AND m.npm=:npm");
$stmt->execute(array(':npm' => $_SESSION['userdata']['npm']));
$userRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

print_r($_SESSION['userdata']);

foreach ($userRows as $row) {
    print_r($row);
}

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
