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

            </div>
            <div class="row">
                <table class="table">
                    <thead class="thead-default">
                    <tr>
                        <th>#</th>
                        <th>NPM</th>
                        <th>Nama Lengkap</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 1; $i <= 10; $i++) {
                        printf("<tr>
                <th scope=\"row\">%d
                </th>
                <td>15066892" . (($i < 10) ? "2" : "") . "%d</td>
                <td>Joe Koe</td>
            </tr>", $i, $i);
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
