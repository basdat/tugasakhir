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
                    echo getDropDown($jenisRows,"id","namamks", "Nama Mks","Jenis MKS","Jenis");
                    echo getDropDown($mahasiswaRows,"npm","nama","Nama Mahasiswa","Mahasiswa","Mahasiswa");
                    echo '<div class="form-group">
                                Judul MKS
                                <input type="text" class="form-control" name="judul" placeholder="Judul"/>
                            </div>';

                echo "<div id='TextBoxesGroupPembimbing'>
	                            <div id=\"TextBoxDivPembimbing1\">";
                    echo getDropDown($dosenRows,"nip","nama","Dosen","Pembimbing 1","pembimbing[]");
                echo "</div></div>";
                    echo "<div id='TextBoxesGroup'>
	                            <div id=\"TextBoxDiv1\">";
                    echo getDropDown($dosenRows,"nip","nama", "Dosen", "Penguji 1","penguji[]");
                     echo "</div></div>"

                ?>
                <input class="btn btn-primary" type="submit" name="submit" value="tambahpeserta"/>
                <a class="btn btn-danger" href="mata_kuliah_spesial.php">Cancel</a>
            <br/><br/>
            </form>
                <input type='button' class="btn btn-success" value='Add Pembimbing' id='addPembimbing'>
                <input type='button' class="btn btn-success" value='Remove Pembimbing' id='removePembimbing'>
                <input type='button' class="btn btn-success" value='Add Penguji' id='addButton'>
                <input type='button' class="btn btn-success" value='Remove Penguji' id='removeButton'>


        </div>
    </div>
</section>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.3.2.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(){

        var counter = 2;
        var counter2 = 2;

        $("#addButton").click(function () {

            if(counter>5){
                alert("Only 5 textboxes allow");
                return false;
            }

            var newTextBoxDiv = $(document.createElement('div'))
                .attr("id", 'TextBoxDiv' + counter);


//            foreach ($arr as $key => $value) {
//                $select .= '<option value="'.dosenJSON[i].nip.'">'.$value[$name].'</option>';
//            }
//            $select .= "</select></div>";
            var dosenJSON = <?php  echo json_encode($dosenRows)?>;
            var result = '<div class="form-group">';
            result += '<label for="Penguji'+counter+'">Penguji '+ counter+'</label>';
            result +=  '<select id="Penguji'+counter+'" class="form-control" name="penguji[]" required>';
            result += '<option value="">Pilih Dosen</option>';

            for(var i=0;i<dosenJSON.length;i++)
            {

                result += '<option value="'+dosenJSON[i].nip+'">'+dosenJSON[i].nama+'</option>';
            }
            result += "</select></div>";
            newTextBoxDiv.after().html(result);

            newTextBoxDiv.appendTo("#TextBoxesGroup");


            counter++;
        });

        $("#addPembimbing").click(function () {

            if(counter2>5){
                alert("Only 5 textboxes allow");
                return false;
            }

            var newTextBoxDiv = $(document.createElement('div'))
                .attr("id", 'TextBoxDivPembimbing' + counter2);


//            foreach ($arr as $key => $value) {
//                $select .= '<option value="'.dosenJSON[i].nip.'">'.$value[$name].'</option>';
//            }
//            $select .= "</select></div>";
            var dosenJSON = <?php  echo json_encode($dosenRows)?>;
            var result = '<div class="form-group">';
            result += '<label for="Pembimbing'+counter2+'">Pembimbing '+ counter2+'</label>';
            result +=  '<select id="Pembimbing'+counter2+'" class="form-control" name="pembimbing[]" required>';
            result += '<option value="">Pilih Dosen</option>';

            for(var i=0;i<dosenJSON.length;i++)
            {

                result += '<option value="'+dosenJSON[i].nip+'">'+dosenJSON[i].nama+'</option>';
            }
            result += "</select></div>";
            newTextBoxDiv.after().html(result);

            newTextBoxDiv.appendTo("#TextBoxesGroupPembimbing");


            counter2++;
        });


        $("#removeButton").click(function () {
            if(counter==1){
                alert("No more textbox to remove");
                return false;
            }
            counter--;
            $("#TextBoxDiv" + counter).remove();

        });

        $("#removePembimbing").click(function () {
            if(counter2==1){
                alert("No more textbox to remove");
                return false;
            }
            counter2--;
            $("#TextBoxDivPembimbing" + counter).remove();

        });
    });
</script>