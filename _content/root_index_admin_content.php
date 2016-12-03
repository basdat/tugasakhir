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
                <div class="row">
                    <h2 class="display-4 text-xs-center">Filter</h2>
                    <hr/>
                </div>
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
                <div class="row">
                    <h2 class="display-4 text-xs-center">Data Sidang</h2>
                    <hr/>
                </div>
                <div class="row">
                    <label for="sort">Sort by</label>
                    <select style="" id="sort">
                        <option value="waktu">Waktu</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="jenis_sidang">Jenis Sidang</option>
                    </select></div>
                <div id="table_admin">
                        qwertyuio
                </div>
            </div>
            <div class="row">

            </div>

            <!--<div class="row text-xs-center">
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

            </div>-->
            <script>
                $(document).ready(function(){
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

                    $('.table').DataTable( {
                        "paging":   true,
                        "ordering": false,
                        "info":     false,
                    } );

                    $.post("server/server_jadwal_sidang_admin.php",{admin_order: 'js.tanggal ASC, js.jammulai ASC'},function(response){
                        $("#table_admin").html(response);
                        $('.table').DataTable( {
                            "paging":   true,
                            "ordering": false,
                            "info":false,
                        } );
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

                        $.post("server/server_jadwal_sidang_admin.php",{admin_order: order},function(response){
                            $("#table_admin").html(response);
                            $('.table').DataTable( {
                                "paging":   true,
                                "ordering": false,
                                "info":false,
                            } );
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>
