<?php
require_once "database.php";
if (! $_POST) {echo "400 Bad Request"; die();}

if(isset($_POST['npmmks'])){

    $db = new database();
    $conn = $db->connectDB();

    $query = "SELECT * from mata_kuliah_spesial mks WHERE mks.npm =:npm ";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(':npm'=> $_POST['npmmks']));
    $mksrows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    unset($_POST['npmmks']);
    echo json_encode($mksrows,JSON_FORCE_OBJECT);
}

if(isset($_POST["pengujiIdmks"])){
    $db = new database();
    $conn = $db->connectDB();

    $query = "SELECT * FROM dosen d JOIN dosen_penguji dp ON d.nip = dp.nipdosenpenguji WHERE dp.idmks=:idmks";
    $stmt = $conn->prepare($query);
    if(!$stmt){
        print_r($conn->errorInfo());
    }
    $stmt->execute(array(':idmks'=> $_POST["pengujiIdmks"]));
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($datas,JSON_FORCE_OBJECT);
}