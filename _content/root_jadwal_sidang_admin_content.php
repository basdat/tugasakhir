<?php
require_once "database.php";

function generateTable($order){

    /*$bottom = ($page-1)*$datasperPage+ 1;
    $top = $page*$datasperPage;*/

    $db = new database();
    $conn = $db->connectDB();
    $stmt = $conn->prepare("SELECT mks.ijinmajusidang,mks.pengumpulanhardcopy,mh.nama,jm.namamks,mks.judul,js.tanggal,js.jammulai,js.jamselesai,dp.nipdosenpenguji,dpem.nipdosenpembimbing,mks.idmks,r.namaruangan
FROM jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mh JOIN jenis_mks jm ON mks.idjenismks = jm.id  NATURAL LEFT OUTER JOIN dosen_pembimbing dpem NATURAL LEFT OUTER JOIN dosen_penguji dp NATURAL JOIN ruangan r
WHERE mks.issiapsidang = true;
ORDER BY :order;");
    $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':order'=>$order));
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "<table class='table'><thead><tr>";

    $columnName = array('Mahasiswa','Jenis Sidang','Judul','Waktu dan Lokasi','Pembimbing','Penguji','Action');
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


        $waktudanlokasi = "<td>";
        $waktudanlokasi = $waktudanlokasi.$dataRow['tanggal']."\n".$dataRow['jammulai']."-".$dataRow['jamselesai']."\n".$dataRow['namaruangan']."</td>";
        $html=$html.$waktudanlokasi;

        $dospenghtml="<td>";

        $stmt = $conn->prepare("SELECT d.nama FROM dosen_penguji dpem JOIN dosen d ON d.nip = dpem.nipdosenpenguji WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospeng = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospeng as $key => $dataR){
            $dospenghtml=$dospenghtml.$dataR['nama']."\n";
        }

        $dospenghtml = $dospenghtml."</td>";

        $html=$html.$dospenghtml;

        $dospemhtml ="<td>";
        $stmt = $conn->prepare("SELECT d.nama FROM dosen_pembimbing dpem JOIN dosen d ON d.nip = dpem.nipdosenpembimbing WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospem = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospem as $key => $dataR2){
            $dospemlainhtml=$dospemhtml.$dataR2['nama']."\n";
        }
        $dospemtml=$dospemhtml."</td>";

        $html=$html.$dospemhtml;

        $html=$html."<td>.<form></form>.</td>";

        $html = $html."</tr>";
    }
    $html = $html."</table>";
    return $html;
}

?>

<form action>

</form>

<section>
    <div class="container">
        <div class="row">
            <div>
                <?php
                /*     echo generateTable(1,100,10);*/
                ?>
                <!--Mockup-->
                <table class="table">
                    <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Juduk</th>
                        <th>Waktu & Lokasi</th>
                        <th>Dosen Pembimbing</th>
                        <th>Dosen Penguji</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php
                        for ($i = 0; $i < 10; $i++) {
                            echo "<tr>
                        <td>Andi</td>
                        <td>Skripsi<br>Sebagai:<br>Pembimbing</td>
                        <td>Green ICT</td>
                        <td>17 November 2016<br>09.00-10.30<br>2.2301</td>
                        <td>Alief</td>
                        <td>Izin Masuk Sidang</td>
                        <td><a class=\"btn btn-primary\" href=\"#\">Edit</a></td>

                    </tr>";
                        }
                        ?>
                    </tbody>

                </table>

            </div>
            <div class="row">
                <div class="row text-xs-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">«</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">»</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
</section>