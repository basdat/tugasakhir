<?php
require_once "database.php";
if (! $_POST) {echo "400 Bad Request"; die();}



$db = new database();
$conn = $db->connectDB();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$query = "UPDATE jadwal_sidang SET (tanggal, jammulai, jamselesai, idruangan,idmks) = ( :tanggal, :jammulai, :jamselesai, :idruangan,:idmks) WHERE idjadwal = :id;";
$stmt = $conn->prepare($query);
if(!$stmt){
    print_r($conn->errorInfo());
}


$stmt->execute(array(':tanggal' => $_POST["tanggal"],':jammulai'=>$_POST["jam_mulai"],':jamselesai'=>$_POST["jam_selesai"],':idruangan'=>$_POST["idruangan"],':idmks'=> $_POST["mks"],':id'=> $_SESSION["edit_idjs"]));
$hc=false;

if($hc=="hardcopy"){
    $hc = True;
}else{
    $hc = false;
}



$query = "UPDATE mata_kuliah_spesial SET pengumpulanhardcopy = :hc WHERE idmks =:idmks;";
$stmt = $conn->prepare($query);
if(!$stmt){
    print_r($conn->errorInfo());
}
$stmt->bindParam(':hc',$hc,PDO::PARAM_BOOL);
$stmt->bindParam('idmks',$_POST["mks"]);
$stmt->execute();



$penguji = $_POST['Penguji'];

$q = "DELETE FROM dosen_penguji WHERE idmks =:idmks";

$stmt = $conn->prepare($q);
if(!$stmt){
    print_r( $conn->errorInfo());
}
$stmt->execute(array('idmks'=>$_POST["mks"]));

foreach ($penguji as $key => $data){
    echo $data."<br>";
    echo "id mks :".$_POST["mks"]."<br>";
    $q = "INSERT INTO dosen_penguji (nipdosenpenguji,idmks) VALUES (:nip,:id)";

    $stmt = $conn->prepare($q);
    if(!$stmt){
        print_r( $conn->errorInfo());
    }
    $stmt->execute(array(':nip'=>$data,':id'=>$_POST["mks"]));
};

unset($_SESSION["edit_idjs"]);



