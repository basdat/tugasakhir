<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();
$userRows = null;
$query = "SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks 
          WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id 
          ORDER BY mahasiswa.nama, namamks
          LIMIT 10";
if($_SESSION['userdata']['role'] == 'dosen') {
    echo "MANCAY";
    $query = "SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks 
              WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id
              AND mata_kuliah_spesial.idmks IN(
              Select dpem.idmks from dosen_pembimbing dpem, dosen d where nipdosenpembimbing = nip
              AND d.username = ?
              UNION ALL
              Select dpen.idmks from dosen_penguji dpen, dosen d where nipdosenpenguji = nip
              AND d.username = ?)
              ORDER BY mahasiswa.nama,namamks
              LIMIT 10";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($_SESSION['userdata']['username'], $_SESSION['userdata']['username']));
}
else {

    $stmt = $conn->prepare($query);
    $stmt->execute(array());
}

$userRows = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<section id="hero" class="header">
    <div class="container">
        <div class="row">
            <div class="row text-xs-center">
                <span class="display-3">Mata Kuliah Spesial</span>
            </div>
            <div class="col-xs-2 offset-xs-5">
                <hr/>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">

            <?php
                if($_SESSION['userdata']['role'] == 'admin') {
                    echo "<a href=\"tambah_peserta.php\" class=\"btn btn-success\">Tambah</a>";
                }

            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Judul</th>
                        <th>Mahasiswa</th>
                        <th>Term</th>
                        <th>Jenis MKS</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
        <?php
            $var = 0;
            foreach ($userRows as $key => $value) {
                if($var == 10) break;
                echo "<tr>";
                echo "<td>";
                    print_r($value['idmks']);
                echo "</td>";
                echo "<td>";
                    print_r($value['judul']);
                echo "</td>";
                echo "<td>";
                     print_r($value['nama']);
                echo "</td>";
                echo "<td>";
                    if($value['semester'] % 2 == 0){
                        echo "Genap";
                    }
                    else {
                        echo "Gasal";
                    }
                    echo "<br/>";
                    print_r($value['tahun']);
                echo "</td>";
                echo "<td>";
                    print_r($value['namamks']);
                echo "</td>";
                echo "<td>";
                    if($value['ijinmajusidang'] == 1){
                            echo "- Izin Maju<br/>";
                    }
                    if($value['pengumpulanhardcopy'] == 1){
                        echo "- Kumpul Hard Copy<br/>";
                    }
                    if($value['issiapsidang'] == 1){
                        echo "- Siap Sidang<br/>";
                    }

                echo "</td>";
                echo "</tr>";
                $var = $var + 1;
            }
        ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="active page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="row">
    </div>
</section>