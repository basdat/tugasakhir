<?php
require_once "database.php";
session_start();
if (! $_POST) {echo "400 Bad Request"; die();}

$validated = true;
unset($_SESSION["edit_js_error"]);
$_SESSION["edit_js_error"] = array();

$hc="";
$db = new database();
$conn = $db->connectDB();
$query = "SELECT * FROM jadwal_sidang NATURAL JOIN ruangan r WHERE idjadwal = :id;";
$stmt = $conn->prepare($query);
$stmt->execute(array(':id'=> $_SESSION["edit_idjs"]));

$oldjs = $stmt->fetchAll(PDO::FETCH_ASSOC);
$oldjs = $oldjs['0'];

$db = new database();
$conn = $db->connectDB();
$query = "SELECT dp.nipdosenpenguji FROM dosen_penguji dp NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN jadwal_sidang js WHERE js.idjadwal = :id;";
$stmt = $conn->prepare($query);
$stmt->execute(array(':id'=> $_SESSION["edit_idjs"]));
$oldpenguji = $stmt->fetchAll(PDO::FETCH_ASSOC);
$op = array();

foreach ($oldpenguji as $key=>$datas){
    $op[]=$datas['nipdosenpenguji'];
}


if(isset($_POST["Penguji"] )) {
    foreach ($_POST["Penguji"] as $key => $datas) {

        if(!in_array($datas,$op)) {
            $db = new database();
            $conn = $db->connectDB();
            $query = "SELECT js.tanggalmulai,js.tanggalselesai,d.nama FROM jadwal_non_sidang js JOIN dosen d ON d.nip = js.nipdosen WHERE js.nipdosen = :nip ;";
            $stmt = $conn->prepare($query);
            $stmt->execute(array(':nip' => $datas));
            $time = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($time as $key => $data) {
                if (check_in_range($data['tanggalmulai'], $data['tanggalselesai'], $_POST['tanggal'])) {
                    $validated = false;
                    $_SESSION["edit_js_error"][] = "Tanggal overlap pada penguji " . $data["nama"] . "terhadap jadwal non sidang " . $data["tanggalmulai"] . "-" . $data["tanggalselesai"];
                };
            }

            $db = new database();
            $conn = $db->connectDB();
            $query = "SELECT js.jammulai,js.jamselesai,d.nama,js.tanggal FROM mata_kuliah_spesial mks NATURAL JOIN jadwal_sidang js JOIN dosen_penguji dp ON mks.idmks = dp.idmks  JOIN dosen d ON d.nip= dp.nipdosenpenguji WHERE dp.nipdosenpenguji = :nip;";
            $stmt = $conn->prepare($query);
            $stmt->execute(array(':nip' => $datas));
            $time2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($time2 as $key => $data) {
                /*print_r($data);*/
                /* echo checkTimeOverlap($_POST["jam_mulai"], $_POST["jam_selesai"], $data["jammulai"], $data['jamselesai']);*/
                if (strtotime($data['tanggal']) == strtotime($_POST["tanggal"])) {
                    if (checkTimeOverlap($_POST["jam_mulai"], $_POST["jam_selesai"], $data["jammulai"], $data['jamselesai'])) {
                        $validated = false;
                        $_SESSION["edit_js_error"][] = "Waktu Overlap pada penguji " . $data["nama"] . " terhadap waktu " . $data["jammulai"] . "-" . $data["jamselesai"];
                        /*print_r($_SESSION["edit_js_error"]);*/
                    }
                }
            }
        }
    }
}

if($oldjs['idruangan'] != $_POST["idruangan"]){
    $db = new database();
    $conn = $db->connectDB();
    $query = "SELECT js.jammulai,js.jamselesai,r.namaruangan,js.tanggal FROM jadwal_sidang js NATURAL JOIN ruangan r WHERE idruangan =:idruangan;";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(':idruangan' => $_POST["idruangan"]));
    $time = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($time as $key => $data) {
        if (strtotime($data['tanggal']) == strtotime($_POST["tanggal"])) {
            if (checkTimeOverlap($_POST["jam_mulai"], $_POST["jam_selesai"], $data["jammulai"], $data['jamselesai'])) {
                $validated = false;
                $_SESSION["edit_js_error"][] = "Waktu Overlap pada ruangan " . $data['namaruangan'] . " terhadap waktu " . $data["jammulai"] . "-" . $data["jamselesai"];
            }
        }
    }
}


if(!isset($_POST["tanggal"]) || $_POST["tanggal"]=="" || strtotime($_POST['tanggal'])=='00-00-0000' || empty($_POST["tanggal"]) ){
    $validated=false;
    $_SESSION["edit_js_error"][] = "Tanggal harus diisi";
}

if(!isset($_POST["jam_mulai"]) || $_POST["jam_mulai"]=="" || empty($_POST["jam_mulai"])  ){
    $validated=false;
    $_SESSION["edit_js_error"][] = "Jam mulai harus diisi";
}

if(!isset($_POST["jam_selesai"]) || $_POST["jam_selesai"]=="" || empty($_POST["jam_selesai"])){
    $validated=false;
    $_SESSION["edit_js_error"][] = "Jam selesai harus diisi";
}

if ($validated == true){
    if(strtotime($_POST["jam_mulai"]) > strtotime($_POST["jam_selesai"])){
        $validated=false;
        $_SESSION["edit_js_error"][] = "Jam mulai harus sebelum jam selesai";
    }
}

$existOnce = array();

foreach ($_POST["Penguji"] as $key => $data){
    if(in_array($data,$existOnce)){
        $validated=false;
        $_SESSION["edit_js_error"][] = "Ada penguji duplikat (bernilai sama) pada saat pengisian form";
    }else{
        $existOnce[] = $data;
    }
}

if($validated){

$db = new database();
$conn = $db->connectDB();

    try{
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $query = "UPDATE jadwal_sidang SET (tanggal, jammulai, jamselesai, idruangan,idmks) = ( :tanggal, :jammulai, :jamselesai, :idruangan,:idmks) WHERE idjadwal = :id;";
        $stmt = $conn->prepare($query);

        $stmt->execute(array(':tanggal' => $_POST["tanggal"],':jammulai'=>$_POST["jam_mulai"],':jamselesai'=>$_POST["jam_selesai"],':idruangan'=>$_POST["idruangan"],':idmks'=> $_POST["mks"],':id'=> $_SESSION["edit_idjs"]));

        if(isset($_POST["hc"])){
            $hc = $_POST["hc"];
        }

        if ($hc == "hardcopy") {
            $hc = 'TRUE';
        } else {
            $hc = 'FALSE';
        }

        echo $hc;

        $query = "UPDATE mata_kuliah_spesial SET pengumpulanhardcopy =".$hc." WHERE idmks =:idmks;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam('idmks',$_POST["mks"]);

        $stmt->execute();

        if(isset($_POST['Penguji'])) {
        $q = "DELETE FROM dosen_penguji WHERE idmks =:idmks";

        $stmt = $conn->prepare($q);

        $stmt->execute(array('idmks'=>$_POST["mks"]));

        $penguji = $_POST['Penguji'];


            foreach ($penguji as $key => $data) {
                /*echo $data."<br>";
                echo "id mks :".$_POST["mks"]."<br>";*/
                $q = "INSERT INTO dosen_penguji (nipdosenpenguji,idmks) VALUES (:nip,:id)";

                $stmt = $conn->prepare($q);
                /*if(!$stmt){
                    print_r( $conn->errorInfo());
                }*/
                $stmt->execute(array(':nip' => $data, ':id' => $_POST["mks"]));
            };

            unset($_SESSION["edit_idjs"]);
            unset($_SESSION["edit_js_error"]);
            unset($_SESSION["edit_prev_data"]);
            header('Location: index.php');
        }
    }catch (Exception $e){

        $_SESSION["edit_js_error"][] = $e->getMessage();
        try{
            $_SESSION["edit_prev_data"]= array("hc"=> $hc,'nama'=>$name,'npm'=>$_POST["Mahasiswa"],'tanggal' => $_POST["tanggal"], 'jammulai' => $_POST["jam_mulai"], 'jamselesai' => $_POST["jam_selesai"], 'idruangan' => $_POST["idruangan"], 'idmks' => $_POST["mks"]);

            if(isset($_POST["Penguji"])){
                $_SESSION["edit_prev_data"]["penguji"] = $_POST["Penguji"];
            }
        }catch (Exception $e){

        }
        header('Location: edit_jadwal_sidang_MKS.php');
        /*echo $e->getMessage();*/
    }

}else{
    $db = new database();
    $conn = $db->connectDB();
    $query = "SELECT nama FROM mahasiswa m WHERE m.npm=:npm ;";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(':npm'=>$_POST["Mahasiswa"]));
    $name= $stmt->fetchAll(PDO::FETCH_ASSOC);
    $name=$name['0']["nama"];

    $_SESSION["edit_prev_data"]= array("hc"=> $hc,'nama'=>$name,'npm'=>$_POST["Mahasiswa"],'tanggal' => $_POST["tanggal"], 'jammulai' => $_POST["jam_mulai"], 'jamselesai' => $_POST["jam_selesai"], 'idruangan' => $_POST["idruangan"], 'idmks' => $_POST["mks"]);
    if(isset($_POST["Penguji"])){
        $_SESSION["edit_prev_data"]["penguji"] = $_POST["Penguji"];
    }

    header('Location: edit_jadwal_sidang_MKS.php');
}


function checkTimeOverlap ($st1,$et1,$st2,$et2){
    $startTime = strtotime($st1);
    $endTime   = strtotime($et1);
    $chkStartTime = strtotime($st2);
    $chkEndTime   = strtotime($et2);

    $overlap = false;

    if($chkStartTime > $startTime && $chkEndTime < $endTime)
    {
        $overlap=true;
    }
    elseif(($chkStartTime > $startTime && $chkStartTime < $endTime) || ($chkEndTime > $startTime && $chkEndTime < $endTime))
    {
        $overlap=true;
    }
    elseif($chkStartTime==$startTime || $chkEndTime==$endTime)
    {
        $overlap=true;
    }
    elseif($startTime > $chkStartTime && $endTime < $chkEndTime)
    {
        $overlap=true;
    }

    return $overlap;
}


function check_in_range($start_date, $end_date, $date)
{
    // Convert to timestamp
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $date_ts = strtotime($date);

    // Check that user date is between start & end
    return (($date_ts >= $start_ts) && ($date_ts <= $end_ts));
}


