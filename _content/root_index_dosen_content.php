<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();

$stmt = $conn->prepare("SELECT mks.ijinmajusidang,mks.pengumpulanhardcopy,mh.nama,jm.namamks,mks.judul,js.tanggal,js.jammulai,js.jamselesai,dp.nipdosenpenguji,dpem.nipdosenpembimbing,mks.idmks,r.namaruangan
FROM jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mh JOIN jenis_mks jm ON mks.idjenismks = jm.id  NATURAL LEFT OUTER JOIN dosen_pembimbing dpem NATURAL LEFT OUTER JOIN dosen_penguji dp NATURAL JOIN ruangan r
WHERE dp.nipdosenpenguji=:nip
ORDER BY js.tanggal, js.jammulai ASC;");
$stmt->execute(array(':nip' => $_SESSION['userdata']['nip']));
$datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($datas);
?>
<section>
    <style>
        th {
            text-align: center;
            vertical-align: middle;
        }

        tr {
            height: 100px;
        }

        td {
            color: #2b2d2f;
        }
    </style>
    <div class="container">
        <div class="row text-xs-center">
            <div class="display-4">
                November 2016
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead class="thead-inverse">
                <tr>
                    <th>Senin</th>
                    <th>Selasa</th>
                    <th>Rabu</th>
                    <th>Kamis</th>
                    <th>Jumat</th>
                    <th>Sabtu</th>
                    <th>Minggu</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $tanggal = 1;
                for ($i = 1; $i <= 4; $i++) {
                    echo "<tr>";
                    for (; $tanggal <= 7 * $i; $tanggal++) {
                        printf("<td>
    %d<br/>
    %s
</td>", $tanggal, "<div class=\"tag tag-success\">Skripsi</div><br/>
<div class=\"tag tag-success\">Tugas Akhir</div>");
                    }

                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</section>