<?php
require_once "database.php";
//TODO check logged in == admin

$db = new database();
$conn = $db->connectDB();

$query = "SELECT * from dosen";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$dosenRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * from ruangan";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$ruanganRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT nama from mahasiswa";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$mahasiswaRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getDropDown($arr, $val, $name, $default,$label, $postname)
{
    $select = "<div class='form-group'>
                <label id='label_.$label' for='" . $label . "'>$label</label>
                <select id='" . $label . "' class='form-control' name='" . $postname . "' required>
                <option value=''>Pilih " . $default . "</option>";
    foreach ($arr as $key => $value) {
        $select .= '<option value="' . $value[$val] . '">' . $value[$name] . '</option>';
    }
    $select .= "</select></div>";
    return $select;
}


?>



<script
    src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
    crossorigin="anonymous"></script>

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
            <div>
                <form method="post" action="jadwalSidang.php">
                    <?php
                    echo getDropDown($mahasiswaRows,"nama","nama","Nama Mahasiswa","Mahasiswa","Mahasiswa")."<br/>";
                    ?>
                    <label for="tanggal">Tanggal</label><br>
                    <input class="form-control id="tanggal" type="date" name="tanggal"><br>
                    <label for="jam_mulai">Jam Mulai</label><br>
                    <input class="form-control id="jam_mulai" type="time" name="jam_mulai"><br>
                    <label for="jam_selesai">Jam Selesai</label><br>
                    <input class="form-control  id="jam_selesai" type="time" name="jam_selesai"><br>
                    <?php
                       echo getDropDown($ruanganRows,"namaruangan","namaruangan","Ruangan","Ruangan","Ruangan")."<br/>";
                    ?>
                    <div id="penguji">
                        <button id="tambahPenguji" class="btn btn-primary"> Tambah Penguji</button>
                    </div>
                    <label class="radio-inline">
                        <input type="radio" name="hardcopy" value="hardcopy">Sudah Mengumpulkan Hardcopy
                    </label><br>

                    <input class="btn btn-primary" type="submit" name="submit" value="Buat Jadwal MKS"/>
                </form>

            </div>
        </div>
        <script>
            console.log("Script on!")
            var counter = 0;
        $("#tambahPenguji").click(function(e){
            e.preventDefault();
            counter++;
            var dosenJSON = <?php  echo json_encode($dosenRows)?>;
            var result = '<div class="form-group">';
            result += '<label for="Penguji'+counter+'">Penguji '+ counter+'</label>';
            result+=  '<select id="Penguji'+counter+'" class="form-control" name="Penguji[]" required>';
            result+='<option value="">Pilih Dosen</option>';

            for(var i=0;i<dosenJSON.length;i++)
            {
                result += '<option value="'+dosenJSON[i].nip+'">'+dosenJSON[i].nama+'</option>';
            }
            $("#penguji").append(result);
        });
        </script>
</section>


