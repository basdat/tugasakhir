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

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="row">
                    <div class="row">
                        <h2 class="display-4 text-xs-center">Data Sidang</h2>
                        <hr/>
                    </div>
                    <div class="row">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#filterJadwal" role="tab" data-toggle="tab">Jadwal
                                    Sidang</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#filterMahasiswa" role="tab" data-toggle="tab">Mahasiswa</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" role="tabpanel" id="filterJadwal">
                                <div class="form-group row">
                                    <label for="inputTerm" class="col-sm-2 form-control-label">Term</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="inputTerm">
                                            <option selected value="-1,-1">Semua Waktu</option>
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
                                        <select id="inputJenis" class="form-control">
                                            <option value="0" selected>Semua Jenis</option>
                                            <?php
                                            foreach ($jenisMKS as $mks) {
                                                printf("<option value=\"%s\">%s</option>", $mks['id'], $mks['namamks']);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row text-xs-right">
                                    <input type="submit" id="btnSubmit" name="submit" class="btn btn-primary btn-login"
                                           value="Pilih">
                                </div>
                            </div>
                            <div class="tab-pane fade in form-group" role="tabpanel" id="filterMahasiswa">
                                <label for="inputFilterMahasiswa" class="col-sm-2 form-control-label">Cari
                                    Mahasiswa</label>
                                <div class="col-sm-10">
                                    <input type="search" id="inputFilterMahasiswa"
                                           class="form-control" placeholder="Nama Mahasiswa">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="table_admin">
                        <div class="alert alert-warning">Sedang mengunduh data...</div>
                    </div>
                </div>
                <div class="row">

                </div>
                <script>
                    $(document).ready(function () {
                        function getTable() {
                            var dataTable = $('.table').DataTable({
                                "paging": true,
                                "ordering": false,
                                "info": false,
                                "initComplete": function (nRow, aData, iDataIndex) {
                                    var html = '&nbsp&nbsp&nbsp<label for="sort">Sort by: &nbsp</label><select id="sort"><option value="waktu" class="input-large" ">Waktu Sidang</option><option value="mahasiswa">Nama Mahasiswa</option><option value="jenis_sidang">Jenis Sidang</option></select></div>';

                                    $('.dataTables_length').append(html);
                                    $('div.dataTables_wrapper div.dataTables_length select').css('width', '150px');
                                    $('#table_admin select').addClass("form-control");
                                    $('#table_admin input').addClass("form-control");

                                    $(".edit").click(function() {
                                        console.log("Edit");
                                        $.post("jadwal_sidang.php", {edit: ($(this).attr("id"))}, function () {
                                            window.location.href = "edit_jadwal_sidang_MKS.php";
                                        });

                                    });
                                }
                            });

                            return dataTable;
                        }

                        var currentTable = getTable();
                        var inputTerm = $('#inputTerm').val();
                        var inputJenis = $('#inputJenis').val();

                        $(".edit").click(function () {
                            console.log("Edit");
                            $.post("jadwal_sidang.php", {edit: ($(this).attr("id"))}, function () {
                                window.location.href = "edit_jadwal_sidang_MKS.php";
                            });

                        });
                        $("#btntambah").click(function () {
                            console.log("Tambah");
                            window.location.href = "membuat_jadwal_sidang_MKS.php"
                        });

                        $('#inputTerm').change(function () {
                            inputTerm = $('#inputTerm').val();
                            inputJenis = $('#inputJenis').val();
                        });
                        $('#inputJenis').change(function () {
                            inputTerm = $('#inputTerm').val();
                            inputJenis = $('#inputJenis').val();
                        });

                        $(document).on("keyup change", "#inputFilterMahasiswa", function () {
                            if ($("#table_admin tbody tr").children('td').hasClass("dataTables_empty")) {
                                $("#inputFilterMahasiswa").parent().addClass("has-warning");
                                $("#inputFilterMahasiswa").focus().addClass("form-control-warning");
                            } else {
                                $("#inputFilterMahasiswa").parent().removeClass("has-warning");
                                $("#inputFilterMahasiswa").focus().removeClass("form-control-warning");
                            }
                        });

                        $('#btnSubmit').click(function () {
                            $("#table_admin tbody").html("<td colspan='7' class=\"alert alert-warning\">Sedang mengunduh data...</td>");
                            $.post("server/server_jadwal_sidang_admin.php", {
                                admin_order: 'js.tanggal ASC, js.jammulai ASC',
                                input_term: inputTerm,
                                input_jenis: inputJenis
                            }, function (response) {
                                $("#table_admin").html(response);
                                getTable();
                            });
                        });

                        $.post("server/server_jadwal_sidang_admin.php", {
                            admin_order: 'js.tanggal ASC, js.jammulai ASC',
                            input_term: inputTerm,
                            input_jenis: inputJenis
                        }, function (response) {
                            $("#table_admin").html(response);
                            currentTable = getTable();
                        });

                        $(document).on("keyup change", "#table_admin input[type='search']", function () {
                            if ($("#table_admin tbody tr").children('td').hasClass("dataTables_empty")) {
                                $("#table_admin input[type='search']").parent().addClass("has-warning");
                                $("#table_admin input[type='search']").focus().addClass("form-control-warning");
                            } else {
                                $("#table_admin input[type='search']").parent().removeClass("has-warning");
                                $("#table_admin input[type='search']").focus().removeClass("form-control-warning");
                            }
                        });

                        $("#inputFilterMahasiswa").on('keyup change', function () {
                            currentTable.columns(0).search(this.value).draw();
                        });

                        $(document).on("change", "#sort", function () {
                            var val = $("#sort").val();
                            var order = "";

                            if (val == 'mahasiswa') {
                                order = "mh.nama";
                            } else if (val == 'jenis_sidang') {
                                order = "jm.namamks";
                            } else if (val == 'waktu') {
                                order = 'js.tanggal ASC, js.jammulai ASC';
                            } else {
                                order = 'js.tanggal ASC, js.jammulai ASC';
                            }
                            $("#table_admin tbody").html("<td colspan='7' class=\"alert alert-warning\">Sedang mengunduh data...</td>");
                            $.post("server/server_jadwal_sidang_admin.php", {
                                admin_order: order,
                                input_term: inputTerm,
                                input_jenis: inputJenis
                            }, function (response) {
                                $("#table_admin").html(response);
                                currentTable = getTable();
                                $(".edit").click(function() {
                                    console.log("Edit");
                                    $.post("jadwal_sidang.php", {edit: ($(this).attr("id"))}, function () {
                                        window.location.href = "edit_jadwal_sidang_MKS.php";
                                    });

                                });
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</section>
