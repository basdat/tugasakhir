<?php
session_start();
require_once "../database.php";
if (! $_POST) {echo "400 Bad Request"; die();}

function generateTable($order){

    $db = new database();
    $conn = $db->connectDB();
    $sql = "Select jsidang.idjadwal as IDSidang, Mhs.Nama as Mahasiswa, jenis_MKS.NamaMKS as Jenis, MKS.Judul as Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, MKS.ijinmajusidang, ruangan.NamaRuangan, mhs.npm, jsidang.idMKS, string_agg(dosen.nama, '|')
From jadwal_sidang jsidang, mata_kuliah_spesial MKS, Mahasiswa Mhs, dosen_pembimbing dospem, jenis_mks, dosen, ruangan
where jsidang.idMKS = MKS.idMKS AND
MKS.NPM = Mhs.NPM AND
MKS.idjenisMKS = jenis_MKS.id AND
jsidang.idMKS = dospem.IDMKS AND
dospem.NIPdosenpembimbing = dosen.NIP AND
jsidang.Idruangan = ruangan.idruangan
Group By jsidang.idjadwal, IDSidang, Mahasiswa, Jenis, Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, ruangan.NamaRuangan, mhs.npm, jsidang.idMKS, MKS.ijinmajusidang order by " . $order . ";";

    $stmt = $conn->prepare($sql);

    $stmt->execute(array());

    $jadwalSidangRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<table class="table table-striped" id="izinSidang"> <colgroup> <col style="width:2%"> <col style="width:10%"> <col style="width:10%"> <col style="width:10%"> <col style="width:10%"> <col style="width:10%"> <col style="width:5%"> </colgroup> <thead class="thead-inverse"> <tr> <th style="text-align:center">No</th> <th style="text-align:center">Mahasiswa</th> <th style="text-align:center">Jenis Sidang</th> <th style="text-align:center">Judul</th> <th style="text-align:center">Waktu dan Lokasi</th> <th style="text-align:center">Dosen pembimbing</th> <th style="text-align:center">Izin sidang</th> </tr> </thead>';
    $counter = 1;
    foreach ($jadwalSidangRows as $key => $value) {
        $html .= "<tr>";
        $html .= "<td>";
            $html .= $counter;
        $html .= "</td>";
        $html .= "<td>";
            $html .= $value['mahasiswa'];
        $html .= "</td>";
        $html .= "<td>";
            $html .= $value['jenis'];
        $html .= "</td>";
        $html .= "<td>";
            $html .= $value['judul'];
        $html .= "</td>";
        $html .= "<td>";
            $html .= $value['tanggal'] . "<br>";
            $html .= $value['jammulai'] . " - " . $value['jamselesai'] . "<br>";
            $html .= $value['namaruangan'];
        $html .= "</td>";
        $html .= "<td>";
            $dosen = explode("|", $value['string_agg']);
            $html .= "<ul>";
            foreach ($dosen as $key => $d) {
                $html .= "<li>" . $d . "</li>" ;
            }
            $html .= "</ul>";
        $html .= "</td>";
        $html .= "<td>";
            if($value['ijinmajusidang'] == 'true'){
                $html .= "<button type='button' class='btn btn-warning disabled'>Diizinkan</button>";
            } else {

            $html .= "<form action=helper_izinkan.php method='post'>    <input type='hidden' name='npm' value='". $value['npm'] . "'><input type='hidden' name='idmks' value='".$value['idmks']."'><input type='submit' name='izin' value='Izinkan' class='btn btn-warning'></form>";
            }
        $html .= "</td>";   

        $html .= "</tr>";
        $counter++;
    }

    return $html;
}

if(isset($_POST["order"])){
    echo generateTable($_POST["order"]);
}
?>