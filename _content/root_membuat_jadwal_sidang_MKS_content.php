<?php
require_once "database.php";
if(!isset($_SESSION['userdata']['role']) ||$_SESSION['userdata']['role'] !="admin") {echo "Bad Request: Must be logged in as role:admin"; die();}

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
                <form method="post" action="tambahJadwalSidang.php">
                    <?php
                    if(isset($_SESSION["tambah_prev_data"])){
                        echo getDropDownDV($mahasiswaRows,"npm","nama",$_SESSION["tambah_prev_data"]['nama'],$_SESSION["tambah_prev_data"]["npm"],"Mahasiswa","Mahasiswa")."<br/>";
                    }else echo getDropDown($mahasiswaRows,"npm","nama","Nama Mahasiswa","Mahasiswa","Mahasiswa")."<br/>";
                    ?>
                    <div id="selectMKS"></div>
                    <label for="tanggal">Tanggal</label><br>
                    <input class="form-control id="tanggal" type="date" name="tanggal" <?php if(isset($_SESSION["tambah_prev_data"])) echo "value='".$_SESSION["tambah_prev_data"]["tanggal"]."'" ?>><br>
                    <label for="jam_mulai">Jam Mulai</label><br>
                    <input class="form-control id="jam_mulai" type="time" name="jam_mulai" <?php if(isset($_SESSION["tambah_prev_data"])) echo "value='".$_SESSION["tambah_prev_data"]["jammulai"]."'" ?> ><br>
                    <label for="jam_selesai">Jam Selesai</label><br>
                    <input class="form-control  id="jam_selesai" type="time" name="jam_selesai" <?php if(isset($_SESSION["tambah_prev_data"])) echo "value='".$_SESSION["tambah_prev_data"]["jamselesai"]."'" ?>"><br>
                    <?php
                    if(isset($_SESSION["tambah_prev_data"])){

                        $db = new database();
                        $conn = $db->connectDB();
                        $query = "SELECT r.namaruangan FROM ruangan r WHERE r.idruangan=:id ;";
                        $stmt = $conn->prepare($query);
                        $stmt->execute(array(':id'=>$_SESSION["tambah_prev_data"]["idruangan"]));
                        $namaruangan= $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $namaruangan=$namaruangan['0']['namaruangan'];

                        echo getDropDownDV($ruanganRows,"idruangan","namaruangan",$namaruangan,$_SESSION["tambah_prev_data"]["idruangan"],"Ruangan","idruangan")."<br/>";
                    }else echo getDropDown($ruanganRows,"idruangan","namaruangan","Ruangan","Ruangan","idruangan")."<br/>";
                    ?>

                    <div id="penguji">
                        <br>
                        <h3>Penguji</h3>
                        <button style="float: right;" id="tambahPenguji" class="btn btn-primary"> Tambah Penguji</button>
                        <br><br>
                        <?php
                        if(isset($_SESSION["tambah_prev_data"])){

                            $count = 0;

                            try {
                                if(isset($_SESSION["tambah_prev_data"]["penguji"])) {
                                    foreach ($_SESSION["tambah_prev_data"]["penguji"] as $key => $data) {

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
                        <input type="checkbox" name="hc" value="hardcopy" <?php if(isset($_SESSION["tambah_prev_data"]["hc"])) echo "checked='".$_SESSION["tambah_prev_data"]["hc"]."'"?>>Sudah Mengumpulkan Hardcopy
                    </label><br>

                    <input class="btn btn-primary" type="submit" name="submit" value="Buat Jadwal MKS"/>
                    <a href="jadwal_sidang.php"  class="btn btn-danger">Batal</a>
                </form>
                <br>


                <?php if(isset($_SESSION["tambah_js_error"])){
                    echo "<br>";
                    foreach ($_SESSION["tambah_js_error"] as $key=>$data){
                        echo "<div class='alert alert-danger' role='alert'>".$data."</div>";
                    }
                    unset($_SESSION["tambah_js_error"]);

                }?>
            </div>
        </div>

        <?php if(isset($count)){
            echo "<script>var counter=".$count.";</script>";
        }else{
            echo "<script> var counter =0;</script>";
        }
        ?>

        <?php if(isset($_SESSION["tambah_prev_data"])){
            echo "<script>var def=".$_SESSION["tambah_prev_data"]["idmks"].";
            var judul ='".$_SESSION["tambah_prev_data"]["namamks"]."';
            </script>";
        }else{
            echo "<script> var def=''; var judul='Pilih MKS'</script>";
        }
        ?>



        <script>

            $(document).ready(function(){
                console.log("Script on!");

                <?php if(!isset($_SESSION["tambah_prev_data"])){
                echo "addOne();";}
                ?>

                $("#tambahPenguji").click(function(e){
                    e.preventDefault();
                    addOne();
                });
                function addOne() {
                    console.log("Click!");
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
                }

                $.post("AjaxJadwalSidang.php",{npmmks: ($("#Mahasiswa").val())},function(data){
                        var mksJSON = data;
                        console.log(data);
                        var tres = '<div class="form-group">';
                        tres += '<label for="mks">Pilih MKS</label>';
                        tres+=  '<select id="mks" class="form-control" name="mks" required>';
                        tres+='<option value="'+def+'">'+judul+'</option>';

                        $.each(JSON.parse(mksJSON),function (key,value) {
                            tres += '<option value="'+value.idmks+'">'+value.judul+'</option>';
                        });

                        $("#selectMKS").html(tres);
                });


                $("#Mahasiswa").change(function(){
                    $.post("AjaxJadwalSidang.php",{npmmks: ($("#Mahasiswa").val())},function(data){
                        var mksJSON = data;
                        console.log(data);
                        var res = '<div class="form-group">';
                        res += '<label for="mks">Pilih MKS</label>';
                        res+=  '<select id="mks" class="form-control" name="mks" required>';
                        res+='<option value="">Pilih MKS</option>';

                        $.each(JSON.parse(mksJSON),function (key,value) {
                            res += '<option value="'+value.idmks+'">'+value.judul+'</option>';
                        });

                        $("#selectMKS").html(res);
                    })

                });
            });

            <?php unset($_SESSION["tambah_prev_data"])?>

        </script>


</section>


