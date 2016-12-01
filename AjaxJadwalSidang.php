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
    echo 
}
