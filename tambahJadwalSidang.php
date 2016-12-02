<?php
require_once "database.php";
if (! $_POST) {echo "400 Bad Request"; die();}

$validated = true;
$_SESSION["tambah_js_error"] = array();

foreach($_POST["Penguji"] as $key => $datas){
    $db = new database();
    $conn = $db->connectDB();
    $query = "SELECT jammulai,jamselesai,d.nama FROM jadwal_non_sidang js JOIN dosen d ON d.nip = js.nipdosen WHERE js.nipdosen = :nip ;";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(':nip'=>$datas));
    $time= $stmt->fetchAll(PDO::FETCH_ASSOC);

    $db = new database();
    $conn = $db->connectDB();
    $query = "SELECT js.jammulai,js.jamselesai,d.nama FROM mata_kuliah_spesial mks NATURAL JOIN jadwal_sidang js JOIN dosen_pembimbing dp ON mks.idmks = dp.idmks JOIN dosen_pembimbing dp ON d.nip = dp.nipdosenpenguji WHERE dp.nipdosenpenguji = :nip  ;";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(':nip'=>$datas));
    $time2= $stmt->fetchAll(PDO::FETCH_ASSOC);

    $time = array_combine($time,$time2);

    foreach ($time as $key => $data){
        if(checkTimeOverlap($_POST["jam_mulai"],$_POST["jam_selesai"],$data["jammulai"],$data[jamselesai])){
            $validated = false;
            $_SESSION["edit_js_error"][] = "Waktu Overlap pada penguji ".$data["nama"]."terhadap waktu".$data["jammulai"]." ".$data["jamselesai"];
        }
    }
}

$db = new database();
$conn = $db->connectDB();
$query = "SELECT js.jammulai,js.jamselesai,r.nama FROM jadwal_sidang js NATURAL JOIN ruangan r WHERE idruangan =:idruangan;";
$stmt = $conn->prepare($query);
$stmt->execute(array(':idruangan'=>$_POST["idruangan"]));
$time= $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($time as $key=> $data){
    if(checkTimeOverlap($_POST["jam_mulai"],$_POST["jam_selesai"],$data["jammulai"],$data[jamselesai])){
        $validated = false;
        $_SESSION["edit_js_error"][] = "Waktu Overlap pada ruangan ".$data['nama']."terhadap waktu".$data["jammulai"]." ".$data["jamselesai"];
    }
}

if($validated){
try {
    $db = new database();
    $conn = $db->connectDB();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $query = "INSERT INTO jadwal_sidang (tanggal, jammulai, jamselesai, idruangan,idmks) VALUES ( :tanggal, :jammulai, :jamselesai, :idruangan,:idmks);";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        print_r($conn->errorInfo());
    }


    $stmt->execute(array(':tanggal' => $_POST["tanggal"], ':jammulai' => $_POST["jam_mulai"], ':jamselesai' => $_POST["jam_selesai"], ':idruangan' => $_POST["idruangan"], ':idmks' => $_POST["mks"]));
    $hc = false;

    if ($hc == "hardcopy") {
        $hc = True;
    } else {
        $hc = false;
    }


    $query = "UPDATE mata_kuliah_spesial SET pengumpulanhardcopy = :hc WHERE idmks =:idmks;";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        print_r($conn->errorInfo());
    }
    $stmt->bindParam(':hc', $hc, PDO::PARAM_BOOL);
    $stmt->bindParam('idmks', $_POST["mks"]);
    $stmt->execute();


    $penguji = $_POST['Penguji'];

    foreach ($penguji as $key => $data) {
        echo $data . "<br>";
        echo "id mks :" . $_POST["mks"] . "<br>";
        $q = "INSERT INTO dosen_penguji (nipdosenpenguji,idmks) VALUES ( :nip,:id)";

        $stmt = $conn->prepare($q);
        if (!$stmt) {
            print_r($conn->errorInfo());
        }
        $stmt->execute(array(':nip' => $data, ':id' => $_POST["mks"]));
    };

    unset($_SESSION["edit_idjs"]);
    unset($_SESSION["tambah_js_error"]);
    //header('Location: index.php');

}catch (Exception $e){
    $_SESSION["tambah_js_error"][] = $e->getMessage();
}

}else{
    header('Location: membuat_jadwal_sidang_MKS.php');
}

function checkTimeOverlap ($st1,$et1,$st2,$et2){
    $startTime = strtotime($st1);
    $endTime   = strtotime($st1);
    $chkStartTime = strtotime($st1);
    $chkEndTime   = strtotime($st2);

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




