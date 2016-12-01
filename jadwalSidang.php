<?php
require_once "database.php";
if (! $_POST) {echo "400 Bad Request"; die();}




$db = new database();
$conn = $db->connectDB();

$query = "INSERT INTO jadwal_sidang (tanggal, jammulai, jamselesai, idruangan) VALUES ( :tangga;, :jammulai, :jamselesai, :idruangan);";
$stmt = $conn->prepare($query);
$stmt->execute(array(':tanggal'));
