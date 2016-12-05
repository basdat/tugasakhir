<?php
session_start();
require_once "database.php";
/**
 * Created by PhpStorm.
 * User: Janno
 * Date: 11/21/2016
 * Time: 11:58 PM
 */
class peserta
{
    private $conn;

    public function __construct()
    {
        $db = new database();
        $this->conn = $db->connectDB();
    }
    public function tambah_peserta(){
        $jenismks =  stripslashes($_POST['Jenis']);
        $npm =  stripslashes($_POST['Mahasiswa']);
        $judul = stripslashes($_POST['judul']);

        $termarr = explode(" ", stripslashes($_POST['term']));
        $tahun = $termarr[0];
        $semester = $termarr[1];
        
        $query = "INSERT INTO Mata_Kuliah_Spesial(npm,tahun,semester,judul,idjenismks) VALUES(?,?,?,?,?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($npm,$tahun,$semester,$judul,$jenismks));

        foreach ($_POST['pembimbing'] as $key => $value) {
            $query = "INSERT INTO dosen_pembimbing VALUES((SELECT max(idmks) from mata_kuliah_spesial),?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array($value));
        }
        foreach ($_POST['penguji'] as $key => $value) {

            $query = "INSERT INTO dosen_penguji VALUES((SELECT max(idmks) from mata_kuliah_spesial),?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array($value));
        }

        header("Location: index.php");




    }


}

$peserta = new peserta();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "hai";
        if ($_POST['submit'] === 'tambahpeserta') {

            $peserta->tambah_peserta();

        }
    }
