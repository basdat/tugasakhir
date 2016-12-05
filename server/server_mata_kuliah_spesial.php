<?php
session_start();
require_once "../database.php";
if (! $_POST) {echo "400 Bad Request"; die();}

function generateTable($order, $semester, $tahun){

    $db = new database();
    $conn = $db->connectDB();
    $userRows = null;

    $query = "SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks
          WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id
          AND semester = ".$semester." AND tahun = ".$tahun."
          ORDER BY ".$order;
    if($semester === "-1"){

      $query = "SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks
            WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id
            ORDER BY ".$order;
    }
    if($_SESSION['userdata']['role'] == 'dosen') {
      if($semester === "-1"){

        $query = "SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks
              WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id
              AND mata_kuliah_spesial.idmks IN(
              Select dpem.idmks from dosen_pembimbing dpem, dosen d where nipdosenpembimbing = nip
              AND d.username = ?
              UNION ALL
              Select dpen.idmks from dosen_penguji dpen, dosen d where nipdosenpenguji = nip
              AND d.username = ?)
              ORDER BY ".$order;
      } else {
        $query = "SELECT * FROM mata_kuliah_spesial, mahasiswa, jenis_mks
              WHERE mata_kuliah_spesial.NPM = mahasiswa.NPM AND idjenismks = id
              AND semester = ".$semester." AND tahun = ".$tahun."
              AND mata_kuliah_spesial.idmks IN(
              Select dpem.idmks from dosen_pembimbing dpem, dosen d where nipdosenpembimbing = nip
              AND d.username = ?
              UNION ALL
              Select dpen.idmks from dosen_penguji dpen, dosen d where nipdosenpenguji = nip
              AND d.username = ?)
              ORDER BY ".$order;
        }
        $stmt = $conn->prepare($query);
        $stmt->execute(array($_SESSION['userdata']['username'], $_SESSION['userdata']['username']));
    }
    else {
        $stmt = $conn->prepare($query);
        $stmt->execute(array());
    }

    $userRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<table  id="example" class="table" 
                     <colgroup>
                        <col style=\'width:1%\'>
                        <col style=\'width:20%\'>
                        <col style=\'width:10%\'>
                        <col style=\'width:10%\'>
                        <col style=\'width:10%\'>
                        <col style=\'width:10%\'>
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
					</thead>';

    foreach ($userRows as $key => $value) {
        $html .= "<tr>";
        $html .= "<td>";
        $html .= $value['idmks'];
        $html .= "</td>";
        $html .= "<td>";
        $html .= $value['judul'];
        $html .= "</td>";
        $html .= "<td>";
        $html .= $value['nama'];
        $html .= "</td>";
        $html .= "<td>";
        if($value['semester'] % 2 == 0){
            $html .= "Genap ";
        }
        else {
            $html .= "Gasal ";
        }

        $html .= $value['tahun'];
        $html .= "</td>";
        $html .= "<td>";
        $html .= $value['namamks'];
        $html .= "</td>";
        $html .= "<td>";
        if($value['ijinmajusidang'] == 1){
            $html .= "- Izin Maju<br/>";
        }
        if($value['pengumpulanhardcopy'] == 1){
            $html .= "- Kumpul Hard Copy<br/>";
        }
        if($value['issiapsidang'] == 1){
            $html .= "- Siap Sidang<br/>";
        }
        $html .= "</td>";
        $html .= "</tr>";

    }

    return $html;
}

if(isset($_POST["order"]) && isset($_POST['semester']) && isset($_POST['tahun'])){
    echo generateTable($_POST["order"], $_POST['semester'], $_POST['tahun']);

}
?>
