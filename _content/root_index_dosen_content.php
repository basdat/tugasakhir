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
                $lastDay = new DateTime('1999-03-25');
                $lastDay->modify('last day of');
                echo $lastDay->format('M jS, Y');
                echo $lastDay->format('D');
                $dowMap = array('Mon' => 1, 'Tue' => 2, 'Wed' => 3, 'Thu' => 4, 'Fri' => 5, 'Sat' => 6, 'Sun' => 7);
                $maxDay = (int)($lastDay->format('j'));
                $firstDay = $dowMap[$lastDay->modify('first day of')->format('D')];

                $counter = 1;
                echo $maxDay . " ====== > " . ((int)(($maxDay + $firstDay) / 7));
                $totalDay = $maxDay + $firstDay - 1;
                $max7MulDay = 7 * ((int)(($totalDay) / 7) + (($totalDay % 7 == 0) ? 0 : 1));
                echo "max7FUllDay" . $max7MulDay;

                for ($i = 2 - $firstDay; $i <= $max7MulDay - $firstDay + 1; $i++, $counter++) {
                    if ($counter % 7 == 1) {
                        echo "<tr>";
                    }
                    if ($i < 1 || $i > $maxDay) {
                        printf("<td></td>");
                        continue;
                    }

                    printf("<td>
    %d<br/>
    %s
</td>", $i, "<div class=\"tag tag-success\">Skripsi</div><br/>
<div class=\"tag tag-success\">Tugas Akhir</div>");
                    if ($counter % 7 == 0) {
                        echo "</tr>";
                    }
                }

                ?>
                </tbody>
            </table>
        </div>

    </div>
</section>