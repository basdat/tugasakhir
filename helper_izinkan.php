<?php 
session_start(); 
require_once "database.php"; 
class izinkan
{ 
    private $conn; 

    public function __construct() 
    { 
        $db = new database(); 
        $conn = $db->connectDB(); 
    } 
    public function mengizinkan(){ 
        $db = new database(); 
        $conn = $db->connectDB(); 
        
        $npm = $_POST['npm'];
        $idmks = $_POST['idmks'];
    
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        $sql = "UPDATE mata_kuliah_spesial SET ijinmajusidang = true WHERE idmks = '$idmks' and npm = '$npm';";

        $stmt_izinkan = $conn->prepare($sql);
        $stmt_izinkan->execute();

        echo $sql;

        header("Location: jadwal_sidang.php");
    } 
} 

$izin = new izinkan(); 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            $izin->mengizinkan();
    }