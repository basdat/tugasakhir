<?php 
session_start(); 
require_once "database.php"; 
class jadwal_non_sidang
{ 
    private $conn; 

    public function __construct() 
    { 
        $db = new database(); 
        $conn = $db->connectDB(); 
    } 
    public function tambah_jadwal(){ 
        $db = new database(); 
        $conn = $db->connectDB(); 
        
        $nipDosen = $_POST['nipDosen'];
        $tglMulai = $_POST['tanggalMulai'];
        $tglSelesai = $_POST['tanggalSelesai'];
        $repetisi = $_POST['repetisi'];
        $keterangan = $_POST['keterangan'];

        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        $sql = "INSERT INTO jadwal_non_sidang (tanggalmulai, tanggalselesai, alasan, repetisi, nipdosen)
        VALUES ('$tglMulai', '$tglSelesai', '$keterangan', '$repetisi', '$nipDosen');";

        $stmt_insert_new_jadwal_non_sidang = $conn->prepare($sql);
        $stmt_insert_new_jadwal_non_sidang->execute();

        echo $sql;

        header("Location: jadwal_non_sidang_dosen.php");
    } 
} 

$jns = new jadwal_non_sidang(); 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            $jns->tambah_jadwal();
    }