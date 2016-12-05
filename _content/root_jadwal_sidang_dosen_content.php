<?php
require_once "database.php";
if(!isset($_SESSION['userdata']['role']) ||$_SESSION['userdata']['role'] !="dosen") {echo "400 Bad Request"; die();}

function generateTable($order){

    $db = new database();
    $conn = $db->connectDB();
    $stmt = $conn->prepare("SELECT mks.ijinmajusidang,mks.pengumpulanhardcopy,mh.nama,jm.namamks,mks.judul,js.tanggal,js.jammulai,js.jamselesai,dp.nipdosenpenguji,dpem.nipdosenpembimbing,mks.idmks,r.namaruangan
FROM jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mh JOIN jenis_mks jm ON mks.idjenismks = jm.id  NATURAL LEFT OUTER JOIN dosen_pembimbing dpem NATURAL LEFT OUTER JOIN dosen_penguji dp NATURAL JOIN ruangan r
WHERE dp.nipdosenpenguji=:nip OR dpem.nipdosenpembimbing =:nip 
ORDER BY ".$order);

    $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip']));
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

    $columnName = array('Mahasiswa','Jenis Sidang','Judul','Waktu dan Lokasi','Penguji','Pembimbing','Status');
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

        $sebagai = "\nSebagai :";

        $stmt = $conn->prepare("SELECT COUNT(*) As Jumlah FROM dosen_pembimbing dpem WHERE dpem.nipdosenpembimbing=:nip AND dpem.idmks=:idmks");
        $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':idmks'=>$dataRow['idmks']));
        $dospem = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($dospem[0]['jumlah'] > 0){
            $sebagai = $sebagai."\n Dosen Pembimbing";
        }


        $stmt = $conn->prepare("SELECT COUNT(*) As Jumlah FROM dosen_penguji dpem WHERE dpem.nipdosenpenguji=:nip AND dpem.idmks=:idmks");
        $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':idmks'=>$dataRow['idmks']));
        $dospeng = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($dospeng[0]['jumlah'] > 0){
            $sebagai = $sebagai."\n Dosen Penguji";
        }

        $sebagai = $sebagai."</td>";

        $html=$html.$sebagai;

        $waktudanlokasi = "<td>";

        $waktudanlokasi = $waktudanlokasi.$dataRow['tanggal']."\n".$dataRow['jammulai']."-".$dataRow['jamselesai']."\n".$dataRow['namaruangan']."</td>";
        $html=$html.$waktudanlokasi;

        $dospenglainhtml="<td>";

        $stmt = $conn->prepare("SELECT d.nama FROM dosen_penguji dpem JOIN dosen d ON d.nip = dpem.nipdosenpenguji WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospenglain = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospenglain as $key => $dataR){
            $dospenglainhtml=$dospenglainhtml.$dataR['nama']."\n";
        }

        $dospenglainhtml = $dospenglainhtml."</td>";

        $html=$html.$dospenglainhtml;

        $dospemlainhtml ="<td>";
        $stmt = $conn->prepare("SELECT d.nama FROM dosen_pembimbing dpem JOIN dosen d ON d.nip = dpem.nipdosenpembimbing WHERE dpem.idmks=:idmks");
        $stmt->execute(array(':idmks'=>$dataRow['idmks']));
        $dospemlain = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dospemlain as $key => $dataR2){
            $dospemlainhtml=$dospemlainhtml.$dataR2['nama']."\n";
        }
        $dospemlainhtml=$dospemlainhtml."</td>";

        $html=$html.$dospemlainhtml;

        $res ="";
        if($dataRow['ijinmajusidang'] == true){
            $res=$res."Izin Masuk Sidang";
        }
        if($dataRow['pengumpulanhardcopy'] == true){
            $res=$res."Kumpul Hard Copy";
        }

        $html = $html."<td>".$res."</td>";

        $html = $html."</tr>";
    }
    $html = $html."</table>";
    return $html;
}

?>

<section>
    <div class="container">
        <div class="row">
           <div class="row" style="padding: 20px;">
            <!-- <label for="sort">Urut Berdasarkan:</label>
            <select id="sort">
                    <option value="waktu">Waktu Sidang</option>
                    <option value="mahasiswa">Nama Mahasiswa</option>
                    <option value="jenis_sidang">Jenis Sidang</option>
            </select> -->
            </div>
            <div id="table_dosen">
                <?php
                   echo generateTable('js.tanggal ASC, js.jammulai ASC');
                ?>

            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('.table').DataTable( {
                    "paging":   true,
                    "ordering": false,
                    "info":false,
                } );

                var html = '&nbsp&nbsp&nbsp<label for="sort">Sort by: &nbsp</label><select id="sort"><option value="waktu" clas="input-large" ">Waktu Sidang</option><option value="mahasiswa">Nama Mahasiswa</option><option value="jenis_sidang">Jenis Sidang</option></select></div>';

                $('.dataTables_length').append(html);
                $('div.dataTables_wrapper div.dataTables_length select').css('width', '150px');
                $('select').addClass("form-control");
                $('input').addClass("form-control");

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

                    $.post("server/server_jadwal_sidang_dosen.php",{dosen_order: order},function(response){
                        $("#table_dosen").html(response);
                        $('.table').DataTable( {
                            "paging":   true,
                            "ordering": false,
                            "info":false,
                        } );
                         var html = '&nbsp&nbsp&nbsp<label for="sort">Sort by: &nbsp</label><select id="sort"><option value="waktu" clas="input-large" ">Waktu Sidang</option><option value="mahasiswa">Nama Mahasiswa</option><option value="jenis_sidang">Jenis Sidang</option></select></div>';

                        $('.dataTables_length').append(html);
                        $('div.dataTables_wrapper div.dataTables_length select').css('width', '150px');
                        $('select').addClass("form-control");
                        $('input').addClass("form-control");

                    });
                });

            } );
        </script>
</section>