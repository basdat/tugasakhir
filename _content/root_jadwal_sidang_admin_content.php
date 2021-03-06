<?php
require_once "database.php";
if(!isset($_SESSION['userdata']['role']) ||$_SESSION['userdata']['role'] !="admin") {echo "400 Bad Request"; die();}

if(isset($_POST{"edit"})){
    $_SESSION["edit_idjs"] = $_POST["edit"];
}

function generateTable($order){

    $db = new database();
    $conn = $db->connectDB();
    $stmt = $conn->prepare("SELECT js.idjadwal,mks.ijinmajusidang,mks.pengumpulanhardcopy,mh.nama,jm.namamks,mks.judul,js.tanggal,js.jammulai,js.jamselesai,mks.idmks,r.namaruangan
FROM jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mh JOIN jenis_mks jm ON mks.idjenismks = jm.id NATURAL JOIN ruangan r
WHERE mks.issiapsidang = true
ORDER BY ".$order);


    $stmt->execute(array());
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $html = "<table class='table dataTable'>";
     $html .= "<colgroup>
                <col style='width:10%'>
                <col style='width:10%'>
                <col style='width:10%'>
                <col style='width:10%'>
                <col style='width:10%'>
                <col style='width:10%'>
                <col style='width:1%'>
            </colgroup>";
    $html .= "<thead><tr>";

    $columnName = array('Mahasiswa','Judul','Jenis Sidang','Waktu dan Lokasi','Penguji','Pembimbing','Action');
    foreach ($columnName as $th){
        $html = $html."<th>".$th." </th>";
    }
    $html = $html."</thead></tr>";

    foreach ($datas as $key => $dataRow){
        $html = $html."<tr>";

        $html = $html."<td>".$dataRow['nama']."</td>".
            "<td>".$dataRow['judul']."</td>".
            "<td>".$dataRow['namamks']
        ;


        $waktudanlokasi = "<td>";
        $waktudanlokasi = $waktudanlokasi.$dataRow['tanggal']."\n".$dataRow['jammulai']."-".$dataRow['jamselesai']."\n".$dataRow['namaruangan']."</td>";
        $html=$html.$waktudanlokasi;

        $dospenghtml="<td>";

        $stmt = $conn->prepare("SELECT d.nama FROM dosen_penguji dpem JOIN dosen d ON d.nip = dpem.nipdosenpenguji WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospeng = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospeng as $key => $dataR){
            $dospenghtml=$dospenghtml.$dataR['nama']."\n";
        }

        $dospenghtml = $dospenghtml."</td>";

        $html=$html.$dospenghtml;

        $dospemhtml ="<td>";
        $stmt = $conn->prepare("SELECT d.nama FROM dosen_pembimbing dpem JOIN dosen d ON d.nip = dpem.nipdosenpembimbing WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospem = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospem as $key => $dataR2){
            $dospemhtml=$dospemhtml.$dataR2['nama']."\n";
        }

        $dospemhtml=$dospemhtml."</td>";

        $html=$html.$dospemhtml;

        $html=$html."<td>.<button id='".$dataRow['idjadwal']."' class='btn btn-primary edit'>Edit</button>.</td>";

        $html = $html."</tr>";
    }
    $html = $html."</table>";
    return $html;
}

?>

<section>
    <div class="container">
        <div class="row">
            <button class="btn btn-primary" id="btntambah" style="float: left">Tambah Jadwal Sidang</button>
            <br>
            <br>
            <div id="table_admin">
                <?php
                echo generateTable('js.tanggal ASC, js.jammulai ASC');
                ?>
            </div>
            <div id="load_alert"></div>
            <br>
            <br>
        </div>
        <script>
            $(document).ready(function(){



                $('.table').DataTable( {
                    "paging":   true,
                    "ordering": false,
                    "info":     false,
                } );


                var html = '&nbsp&nbsp&nbsp<label for="sort">Sort by: &nbsp</label><select id="sort"><option value="waktu" class="input-large" ">Waktu Sidang</option><option value="mahasiswa">Nama Mahasiswa</option><option value="jenis_sidang">Jenis Sidang</option></select></div>';

                $('.dataTables_length').append(html);
                $('div.dataTables_wrapper div.dataTables_length select').css('width', '150px');
                $('select').addClass("form-control");
                $('input').addClass("form-control");

                $(".edit").click(function() {
                    console.log("Edit");
                    $.post("jadwal_sidang.php", {edit: ($(this).attr("id"))}, function () {
                        window.location.href = "edit_jadwal_sidang_MKS.php";
                    });

                });
                $("#btntambah").click(function() {
                    console.log("Tambah");
                    window.location.href="membuat_jadwal_sidang_MKS.php"
                });



                $("#sort").change(function () {
                    var val = $("#sort").val();
                    var order = "";

                    if(val=='mahasiswa'){
                        order = "mh.nama";
                    }else if(val=='jenis_sidang'){
                        order = "jm.namamks";
                    }else if(val=='waktu'){
                        order = 'js.tanggal ASC, js.jammulai ASC';
                    }else{
                        order = 'js.tanggal ASC, js.jammulai ASC';
                    }

                  /*  $("tbody").remove();
                    $("#load_alert").html("<div style='width: 100%;' class='alert alert-warning' role='alert'> Sedang mengunduh data</div>");*/

                    $.post("server/server_jadwal_sidang_admin.php",{admin_order: order},function(response){
                        $("#table_admin").html(response);
                        $('.table').DataTable( {
                            "paging":   true,
                            "ordering": false,
                            "info":false,
                        } );

                        var html = '&nbsp&nbsp&nbsp<label for="sort">Sort by: &nbsp</label><select id="sort"><option value="waktu" class="input-large" ">Waktu Sidang</option><option value="mahasiswa">Nama Mahasiswa</option><option value="jenis_sidang">Jenis Sidang</option></select></div>';

                        $('.dataTables_length').append(html);
                        $('div.dataTables_wrapper div.dataTables_length select').css('width', '150px');
                        $('select').addClass("form-control");
                        $('input').addClass("form-control");

                    });
                });
            });
        </script>
</section>