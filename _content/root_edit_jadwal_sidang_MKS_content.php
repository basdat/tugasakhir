<?php
require_once "database.php";
if(!isset($_SESSION['userdata']['role']) ||$_SESSION['userdata']['role'] !="admin") {echo "400 Bad Request"; die();}


$db = new database();
$conn = $db->connectDB();

try {
    $query = "SELECT * from jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mhs NATURAL JOIN ruangan r WHERE js.idjadwal =:id";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(':id' => $_SESSION["edit_idjs"]));
    $mks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $mks = $mks[0];
}catch (Exception $e){
    echo $e;
}


$query = "SELECT * from dosen";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$dosenRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * from ruangan";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$ruanganRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT nama,npm from mahasiswa ORDER BY nama";
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

function getDropDownDV($arr, $val, $name, $default,$defaultVal,$label, $postname)
{
    $select = "<div class='form-group'>
                <label id='label_.$label' for='" . $label . "'>$label</label>
                <select id='" . $label . "' class='form-control' name='" . $postname . "' required>
                <option value='".$defaultVal."'>$default</option>";

    foreach ($arr as $key => $value) {
        $select .= '<option value="' . $value[$val] . '">' . $value[$name] . '</option>';
    }

    $select .= "</select></div>";
    return $select;
}

function getStaticForm($default,$defaultVal,$label, $postname)
{
$static = "<div class='form-group'>
    <label id='label_.$label' for='" . $label . "'>$label</label><br>
    <p class='form-control-static'>$default</p>
    <input id='" . $label . "' type='hidden' name='" . $postname ."' value='".$defaultVal."' placeholder='".$default."' required></div>";
return $static;
}

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
            <div>
                <form method="post" action="editJadwalSidang.php">
                    <?php
                    echo getStaticForm($mks["nama"],$mks["npm"],"Mahasiswa","Mahasiswa")."<br/>";
                    ?>
                    <div id="selectMKS">
                        <?php echo getStaticForm($mks["judul"],$mks["idmks"],"Mata Kuliah Spesial","mks") ?>
                    </div>
                    <label for="tanggal">Tanggal</label><br>
                    <input class="form-control id="tanggal" type="date" name="tanggal" value='<?php if(isset($_SESSION["edit_prev_data"])) {echo $_SESSION["edit_prev_data"]["tanggal"];} else echo $mks["tanggal"];?>'><br>
                    <label for="jam_mulai">Jam Mulai</label><br>
                    <input class="form-control id="jam_mulai" type="time" name="jam_mulai" value='<?php if(isset($_SESSION["edit_prev_data"])) {echo $_SESSION["edit_prev_data"]["jammulai"]; } else echo $mks["jammulai"];?>'><br>
                    <label for="jam_selesai">Jam Selesai</label><br>
                    <input class="form-control  id="jam_selesai" type="time" name="jam_selesai" value='<?php if(isset($_SESSION["edit_prev_data"])) {echo $_SESSION["edit_prev_data"]["jamselesai"];} else echo $mks["jamselesai"]?>'><br>
                    <?php
                    echo getDropDownDV($ruanganRows,"idruangan","namaruangan",$mks["namaruangan"],$mks["idruangan"],"Ruangan","idruangan")."<br/>";
                    ?>
                    <div id="penguji">
                        <button id="tambahPenguji" class="btn btn-primary"> Tambah Penguji</button>
                        <?php
                        if(isset($_SESSION["edit_prev_data"])){
                            $count = 0;

                            try {
                                if(isset($_SESSION["edit_prev_data"]["penguji"])) {
                                    foreach ($_SESSION["edit_prev_data"]["penguji"] as $key => $data) {

                                        $count = $count + 1;

                                        $db = new database();
                                        $conn = $db->connectDB();
                                        $stmt = $conn->prepare("SELECT d.nama FROM dosen d WHERE d.nip = :nip");
                                        $stmt->execute(array(':nip' => $data));
                                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        echo getDropDownDV($dosenRows, "nip", "nama", $row['0']['nama'], $data, "Penguji " . $count, "Penguji[]") . "<br/>";
                                    }
                                }
                            }catch (Exception $exception){

                            }
                        }
                        ?>
                    </div>
                    <label class="radio-inline">
                        <input type="radio" name="hc" value="hardcopy"  <?php if(isset($_SESSION["edit_prev_data"]["hc"])){echo "checked='".$_SESSION["edit_prev_data"]["hc"]."''";} else echo $mks["pengumpulanhardcopy"]?>>Sudah Mengumpulkan Hardcopy
                    </label><br>

                    <input class="btn btn-primary" type="submit" name="submit" value="Buat Jadwal MKS"/>
                    <input class="btn btn-primary" type="submit" name="" value="Batal"/>
                </form>

                <?php if(isset($_SESSION["edit_js_error"])){
                    echo "<br>";
                    foreach ($_SESSION["edit_js_error"] as $key=>$data){
                        echo "<div class='alert alert-danger' role='alert'>".$data."</div>";
                    }
                    unset($_SESSION["edit_js_error"]);
                }?>

            </div>
        </div>

        <?php if(isset($count)){
            echo "<script>var counter=".$count.";</script>";
        }else{
            echo "<script> var counter =0;</script>";
        }
        ?>

        <?php if(isset($_SESSION["edit_prev_data"])){
            echo "<script>var load=false;</script>";
        }else{
            echo "<script> var load =true;</script>";
        }
        ?>

        <script>

            $(document).ready(function(){
                console.log("Script on!")

                if(load){
                    $.post("ajaxJadwalSidang.php",{pengujiIdmks:<?php echo $mks["idmks"]?>},function(response){
                        var pengujiJSON = response;
                        var dosenJSON = <?php  echo json_encode($dosenRows)?>;
                        var result = "";
                        $.each(JSON.parse(pengujiJSON),function (key,value) {
                            counter++;
                            var temp = '<div class="form-group">';
                            temp += '<label for="Penguji'+counter+'">Penguji '+ counter+'</label>';
                            temp+=  '<select id="Penguji'+counter+'" class="form-control" name="Penguji[]" required>';
                            temp+='<option value="'+value.nip+'">'+value.nama+'</option>';

                            for(var i=0;i<dosenJSON.length;i++)
                            {
                                temp += '<option value="'+dosenJSON[i].nip+'">'+dosenJSON[i].nama+'</option>';
                            }

                            $("#penguji").append(temp);
                        });

                    });
                }
                
                
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


                $.post("AjaxJadwalSidang.php",{npmmks: ($("#Mahasiswa").val())},function(data){
                    var mksJSON = data;
                    console.log(data);
                    var tres = '<div class="form-group">';
                    tres += '<label for="mks">Pilih MKS</label>';
                    tres+=  '<select id="mks" class="form-control" name="mks" required>';
                    tres+='<option value="<?php echo $mks["idmks"]?>"><?php echo $mks["judul"] ?></option>';

                    $.each(JSON.parse(mksJSON),function (key,value) {
                        tres += '<option value="'+value.idmks+'">'+value.judul+'</option>';
                    });

                });

            });


        </script>

        <?php unset($_SESSION["edit_prev_data"])?>
</section>

sssss
