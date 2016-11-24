<?php

$db = new database();
$conn = $db->connectDB();

$query = "SELECT nama from dosen";
$stmt = $conn->prepare($query);
$stmt->execute(array());
$pengujiRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                <label for='" . $label . "'>$label</label>
                <select id='" . $label . "' class='form-control' name='" . $postname . "' required>
                <option value=''>Pilih " . $default . "</option>";
    foreach ($arr as $key => $value) {
        $select .= '<option value="' . $value[$val] . '">' . $value[$name] . '</option>';
    }
    $select .= "</select></div>";
    return $select;
}
?>


