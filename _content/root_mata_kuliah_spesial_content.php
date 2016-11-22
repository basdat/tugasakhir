<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();
$stmt = $conn->prepare("SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id ORDER BY mahasiswa.nama,namamks");
$stmt->execute(array());
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
            <table border="1">
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
            foreach ($userRows as $key => $value) {
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
            }
        ?>
                </tbody>
            </table>
            <div class="row">
    </div>
</section>