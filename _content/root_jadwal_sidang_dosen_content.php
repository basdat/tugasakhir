<?php
require_once "database.php";

function generateTable($page,$totalData,$datasperPage){

    $bottom = ($page-1)*$datasperPage+ 1;
    $top = $page*$datasperPage;

    $db = new database();
    $conn = $db->connectDB();
    $stmt = $conn->prepare("SELECT mh.nama,jm.namamks,mks.judul,js.tanggal,js.jammulai,js.jamselesai,dp.nipdosenpenguji,dpem.nipdosenpembimbing
FROM jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mh JOIN jenis_mks jm ON mks.idjenismks = jm.id  NATURAL LEFT OUTER JOIN dosen_pembimbing dpem NATURAL LEFT OUTER JOIN dosen_penguji dp
WHERE dp.nipdosenpenguji=:nip OR dpem.nipdosenpembimbing =:nip AND RowNum >=:st AND RowNum <=:ed
ORDER BY js.tanggal DESC,js.jammulai;");
    $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':st' =>$bottom, ':ed'=>$top));
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "<table><thead><tr>";

    $columnName = array('aa','aaa','aaaa');
    foreach ($columnName as $th){
        $html = $html."<th>".$th." </th>";
    }
    $html = $html."</thead></tr>";

    foreach ($datas as $key => $dataRow){
        $html = $html."<tr>";

        echo "1";

        $html = $html."<td>".$dataRow["mahasiswa"]."</td>".
            "<td>".$dataRow['namamks']."</td>".
            "<td>".$dataRow['judul']."</td>"
        ;

        //TODO add waktu dan lokasi

        //TODO add Dospem lain


        $res ="";
        if($dataRow['izinmasuksidang'] == true){
            $res=$res."Izin Masuk Sidang";
        }
        if($dataRow['kumpulhardcopy'] == true){
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
                        <th>Pembimbing lain</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < 10; $i++) {
                        echo "<tr>
                        <td>Andi</td>
                        <td>Skripsi<br>Sebagai:<br>Pembimbing</td>
                        <td>Green ICT</td>
                        <td>17 November 2016<br>09.00-10.30<br>2.2301</td>
                        <td>Alief</td>
                        <td>Izin Masuk Sidang</td>
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