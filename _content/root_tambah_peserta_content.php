<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();

$query = "SELECT * from term";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$termRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * from jenis_mks";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$jenisRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT npm, nama from mahasiswa";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$mahasiswaRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT nip,nama from dosen";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$dosenRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getDropDown($arr, $val, $name, $default,$label, $postname){
    $select = "<div class='form-group'>
                <label for='".$label."'>$label</label>
                <select id='".$label."' class='form-control' name='".$postname."' required>
                <option value=''>Pilih ".$default."</option>";
    foreach ($arr as $key => $value) {
        $select .= '<option value="'.$value[$val].'">'.$value[$name].'</option>';
    }
    $select .= "</select></div>";
    return $select;
}
?>
<section id="hero" class="header">
    <div class="container">
        <div class="row">
            <div class="row text-xs-center">
                <span class="display-3">Buat Mata Kuliah Spesial</span>
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
            <div>
            <form method="post" action="peserta.php" style="width: 30%;margin: auto;">
                <?php
                     echo "<div class='form-group'>
                                <label for='term'>Term</label>
                                <select id='term' class='form-control' name='term' required>
                                    <option value=''>Pilih Term</option>";
                                     foreach ($termRows as $key => $value) {
                                         $semester = $value['semester'] % 2 == 0 ? "Genap" : "Gasal";
                                        echo "<option value='".$value['tahun']." ".$value['semester']."'>".$semester." ".$value['tahun']."</option>";
                                     }
                             echo" </select>
                         </div>";
                    echo getDropDown($jenisRows,"id","namamks", "Nama Mks","Jenis MKS","Jenis")."<br/>";
                    echo getDropDown($mahasiswaRows,"npm","nama","Nama Mahasiswa","Mahasiswa","Mahasiswa")."<br/>";
                    echo '<div class="form-group">
                                Judul MKS
                                <input type="text" class="form-control" name="judul" placeholder="Judul"/>
                            </div><br/>';
                    echo getDropDown($dosenRows,"nip","nama","Nama Dosen Pembimbing 1","Pembimbing 1","Pembimbing1")."<br/>";
//                    echo getDropDown($dosenRows,"nip","nama", "Nama Dosen Pembimbing 2","Pembimbing 2")."<br/>";
//                    echo getDropDown($dosenRows,"nip","nama", "Nama Dosen Pembimbing 3","Pembimbing 3")."<br/>";
                    echo getDropDown($dosenRows,"nip","nama", "Nama Dosen Penguji 1", "Penguji 1","Penguji1")."<br/>";

                ?>
                <input class="btn btn-primary" type="submit" name="submit" value="Tambah Peserta"/>
                <a class="btn btn-danger" href="mata_kuliah_spesial.php">Cancel</a>
            </form>
        </div>
    </div>
</section>