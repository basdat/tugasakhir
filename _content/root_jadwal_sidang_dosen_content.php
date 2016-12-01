<?php
require_once "database.php";

function generateTable($order){

    /*$bottom = ($page-1)*$datasperPage+ 1;
    $top = $page*$datasperPage;*/

    $db = new database();
    $conn = $db->connectDB();
    $stmt = $conn->prepare("SELECT mks.ijinmajusidang,mks.pengumpulanhardcopy,mh.nama,jm.namamks,mks.judul,js.tanggal,js.jammulai,js.jamselesai,dp.nipdosenpenguji,dpem.nipdosenpembimbing,mks.idmks,r.namaruangan
FROM jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mh JOIN jenis_mks jm ON mks.idjenismks = jm.id  NATURAL LEFT OUTER JOIN dosen_pembimbing dpem NATURAL LEFT OUTER JOIN dosen_penguji dp NATURAL JOIN ruangan r
WHERE dp.nipdosenpenguji=:nip OR dpem.nipdosenpembimbing =:nip 
ORDER BY :order;");
    $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':order'=>$order));
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "<table class='table'><thead><tr>";

    $columnName = array('Mahasiswa','Jenis Sidang','Judul','Waktu dan Lokasi','Pembimbing','Penguji','Status');
    foreach ($columnName as $th){
        $html = $html."<th>".$th." </th>";
    }
    $html = $html."</thead></tr>";

    foreach ($datas as $key => $dataRow){
        $html = $html."<tr>";

        $html = $html."<td>".$dataRow['nama']."</td>".
            "<td>".$dataRow['judul']."</td>".
            "<td>".$dataRow['namamks']
        ;

        $sebagai = "\nSebagai :";

        $stmt = $conn->prepare("SELECT COUNT(*) As Jumlah FROM dosen_pembimbing dpem WHERE dpem.nipdosenpembimbing=:nip AND dpem.idmks=:idmks");
        $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':idmks'=>$dataRow['idmks']));
        $dospem = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($dospem[0]['jumlah'] > 0){
            $sebagai = $sebagai."\n Dosen Pembimbing";
        }


        $stmt = $conn->prepare("SELECT COUNT(*) As Jumlah FROM dosen_penguji dpem WHERE dpem.nipdosenpenguji=:nip AND dpem.idmks=:idmks");
        $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':idmks'=>$dataRow['idmks']));
        $dospeng = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($dospeng[0]['jumlah'] > 0){
            $sebagai = $sebagai."\n Dosen Penguji";
        }

        $sebagai = $sebagai."</td>";

        $html=$html.$sebagai;

        $waktudanlokasi = "<td>";

        //TODO add waktu dan lokasi
        $waktudanlokasi = $waktudanlokasi.$dataRow['tanggal']."\n".$dataRow['jammulai']."-".$dataRow['jamselesai']."\n".$dataRow['namaruangan']."</td>";
        $html=$html.$waktudanlokasi;

        //TODO add Dospem lain
        $dospenglainhtml="<td>";

        $stmt = $conn->prepare("SELECT d.nama FROM dosen_penguji dpem JOIN dosen d ON d.nip = dpem.nipdosenpenguji WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospenglain = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospenglain as $key => $dataR){
            $dospenglainhtml=$dospenglainhtml.$dataR['nama']."\n";
        }

        $dospenglainhtml = $dospenglainhtml."</td>";

        $html=$html.$dospenglainhtml;

        $dospemlainhtml ="<td>";
        $stmt = $conn->prepare("SELECT d.nama FROM dosen_pembimbing dpem JOIN dosen d ON d.nip = dpem.nipdosenpembimbing WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospemlain = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospemlain as $key => $dataR2){
            $dospemlainhtml=$dospemlainhtml.$dataR2['nama']."\n";
        }
        $dospemlainhtml=$dospemlainhtml."</td>";

        $html=$html.$dospemlainhtml;

        $res ="";
        if($dataRow['ijinmajusidang'] == true){
            $res=$res."Izin Masuk Sidang";
        }
        if($dataRow['pengumpulanhardcopy'] == true){
            $res=$res."Kumpul Hard Copy";
        }

        $html = $html."<td>".$res."</td>";

        $html = $html."</tr>";
    }
    $html = $html."</table>";
    return $html;
}

?>

<section>
    <div class="container">
        <div class="row">
            <div>
                 <?php
                 echo generateTable('mh.nama');
                 ?>
                <!--Mockup-->
                <!--<table class="table">
                    <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Juduk</th>
                        <th>Waktu & Lokasi</th>
                        <th>Pembimbing lain</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
/*                    for ($i = 0; $i < 10; $i++) {
                        echo "<tr>
                        <td>Andi</td>
                        <td>Skripsi<br>Sebagai:<br>Pembimbing</td>
                        <td>Green ICT</td>
                        <td>17 November 2016<br>09.00-10.30<br>2.2301</td>
                        <td>Alief</td>
                        <td>Izin Masuk Sidang</td>
                    </tr>";
                    }
                    */?>
                    </tbody>

                </table>-->

            </div>
        </div>
</section>