<section>
    <style>
        th {
            text-align: center;
            vertical-align: middle;
        }
        tr {
            height: 100px;
        }
        td {
            color: #2b2d2f;
        }
    </style>
    <div class="container">
        <div class="row text-xs-center">
            <div class="display-4">
                November 2016
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead class="thead-inverse">
                <tr>
                    <th>Senin</th>
                    <th>Selasa</th>
                    <th>Rabu</th>
                    <th>Kamis</th>
                    <th>Jumat</th>
                    <th>Sabtu</th>
                    <th>Minggu</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $tanggal = 1;
                    for ($i = 1; $i <= 4; $i++) {
                        echo "<tr>";
                        for (;$tanggal <= 7*$i; $tanggal++) {
                            printf("<td>
    %d<br/>
    %s
</td>", $tanggal, "<div class=\"tag tag-success\">Skripsi</div><br/>
<div class=\"tag tag-success\">Tugas Akhir</div>");
                        }

                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</section>