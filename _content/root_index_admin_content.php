<?php

require_once "database.php";
$db = new database();
$conn = $db->connectDB();

$stmt = $conn->prepare("SELECT * FROM sisidang.jenis_mks");
$stmt->execute();
$jenisMKS = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($jenisMKS);
$stmt = $conn->prepare("SELECT * FROM sisidang.term");
$stmt->execute();
$terms = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($terms);
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="row">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Jadwal Sidang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Mahasiswa</a>
                    </li>
                </ul>
                <div class="form-group row">
                    <label for="inputTerm" class="col-sm-2 form-control-label">Term</label>
                    <div class="col-sm-10">
                        <select class="custom-select" id="inputTerm">
                            <?php
                            foreach ($terms as $term) {
                                $semesterId = (int)($term['semester']);
                                $semester = "";
                                if ($semesterId == 3) {
                                    $semester = "Semester Pendek";
                                } elseif ($semesterId % 2 == 0) {
                                    $semester = "Genap";
                                } else
                                    $semester = "Ganjil";

                                printf("<option value=\"%s,%s\">%s %s</option>", $semesterId, $term['tahun'], $semester, $term['tahun']);
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputJenis" class="col-sm-2 form-control-label">Jenis Sidang</label>
                    <div class="col-sm-10">
                        <select id="inputJenis" class="custom-select">
                            <?php
                            foreach ($jenisMKS as $mks) {
                                printf("<option value=\"%s\">%s</option>", $mks['id'], $mks['namamks']);
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row text-xs-right">
                    <input type="submit" name="submit" class="btn btn-primary btn-login" value="Pilih">
                </div>
            </div>
            <div class="row">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Juduk</th>
                        <th>Waktu & Lokasi</th>
                        <th>Dosen Pembimbing</th>
                        <th>Dosen Penguji</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < 10; $i++) {
                        echo "<tr>
                        <td>Andi</td>
                        <td>Skripsi<br>Sebagai:<br>Pembimbing</td>
                        <td>Green ICT</td>
                        <td>17 November 2016<br>09.00-10.30<br>2.2301</td>
                        <td>Alni</td>
                        <td>Anto<br>Alif</td>
                        <td><a class=\"btn btn-primary\" href=\"#\">Edit</a></td>
                    </tr>";
                    }
                    ?>
                    </tbody>

                </table>
            </div>
            <div class="row text-xs-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">«</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">»</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
