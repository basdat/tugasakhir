<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();
$userRows = null;
$query = "SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks
          WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id
          ORDER BY mahasiswa.nama, namamks";
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
              ORDER BY mahasiswa.nama,namamks";
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
            <form class="form-inline">
                <div class="form-group">
                    <label for="sort">Urutkan:</label>
                    <select id="sort" class="pick">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="jenis">Jenis MKS</option>
                        <option value="term">Term</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter">&nbsp Filter term:</label>
                    <select id="filter" class="pick">
                      <option value="all">All</option>
                      <?php

                          $conn = $db->connectDB();
                          $query = "SELECT * FROM TERM";
                          $stmt = $conn->prepare($query);
                          $stmt->execute(array());
                          $termRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          foreach ($termRows as $key => $value){
                            $semester = "";
                            if($value['semester'] % 2 == 0){
                                $semester =  "Genap";
                            }
                            else {
                                $semester = "Gasal";
                            }
                              echo '<option value="'.$value['semester'].' '.$value['tahun'].'">'.$semester.' '.$value['tahun'].'</option>';
                          }
                       ?>
                   </select>
                   </div>
                    <?php
                if($_SESSION['userdata']['role'] == 'admin') {
                    echo "<div class='form-group'><a href=\"tambah_peserta.php\" class=\"btn btn-success\">Tambah</a></div>";
                }
            ?>
            </form>
            <br>
        </div>
        <div class='row'>
            <div id="tableArea">
            <table id="example" class="table">
            <colgroup>
                <col style='width:1%'>
                <col style='width:20%'>
                <col style='width:10%'>
                <col style='width:10%'>
                <col style='width:10%'>
                <col style='width:10%'>
            </colgroup>
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
            </div>
        </div>
            <div class="row">
    </div>
</section>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            "paging":   true,
            "ordering": false,
            "info":     false
        } );   

        $('select').addClass("form-control");
        $('select').css("width", "150px");

        $('input').addClass("form-control");
        
        $(".pick").change(function () {
            var val = $("#sort").val();
            var order = "";

            if(val=='mahasiswa'){
                order = "nama";
            }else if(val=='jenis'){
                order = "namamks";
            }else{
                order = 'mata_kuliah_spesial.tahun, mata_kuliah_spesial.semester';
            }

            
            var val2 = $("#filter").val();

            var semester = -1;
            var tahun = -1;
            if(val2 != "all"){
              var arrTerm = val2.split(" ");
              semester = arrTerm[0];
              tahun = arrTerm[1];
            }


            $.post("server/server_mata_kuliah_spesial.php",{order: order, semester: semester, tahun: tahun},function(response){
                $("#tableArea").html(response);
                $('.table').DataTable( {
                    "paging":   true,
                    "ordering": false,
                    "info":false,
                } );

                $('select').addClass("form-control");
                $('input').addClass("form-control");
        
            });
        });






    });


</script>
