<?php
if (! $_POST) {echo "400 Bad Request"; die();}




$query = "INSERT INTO jadwal_sidang (idmks, tanggal, jammulai, jamselesai, idruangan) VALUES (, '2016-01-20', '09:46:00', '11:46:36', 8);"