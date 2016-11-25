<section id="hero" class="header">
    <div class="container">
        <div class="row">
            <div class="row text-xs-center">
                <span class="display-3">Jadwal Non-Sidang</span>
            </div>
            <div class="col-xs-2 offset-xs-5">
                <hr/>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalTambah">Tambah Jadwal Non
                Sidang
            </button>
        </div>
        <br>
        <div class="row">
            <table>
                <table class="table table-striped">
                    <thead class="thead-inverse">
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody class="table-condensed">
                    <tr>
                        <th>1</th>
                        <td>Sen, 29 Aug 2016 - Jum, 6 Jan 2017</td>
                        <td>08.00 - 17.00</td>
                        <td>Kunjungan Riset ke Australia</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Rabu, 15 Jun 2016 - Jum, 17 Jun 2016</td>
                        <td>08.00 - 10.00</td>
                        <td>Visitasi BAN PT</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Jum, 12 Aug 2016 - Jum, 1 Des 2016</td>
                        <td>08.00 - 17.00</td>
                        <td>Konferensi ke Thailand</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Sen, 29 Aug 2016 - Jum, 6 Jan 2017</td>
                        <td>08.00 - 17.00</td>
                        <td>Kunjungan Riset ke Australia</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Rabu, 15 Jun 2016 - Jum, 17 Jun 2016</td>
                        <td>08.00 - 10.00</td>
                        <td>Visitasi BAN PT</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Jum, 12 Aug 2016 - Jum, 1 Des 2016</td>
                        <td>08.00 - 17.00</td>
                        <td>Konferensi ke Thailand</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Sen, 29 Aug 2016 - Jum, 6 Jan 2017</td>
                        <td>08.00 - 17.00</td>
                        <td>Kunjungan Riset ke Australia</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Rabu, 15 Jun 2016 - Jum, 17 Jun 2016</td>
                        <td>08.00 - 10.00</td>
                        <td>Visitasi BAN PT</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Jum, 12 Aug 2016 - Jum, 1 Des 2016</td>
                        <td>08.00 - 17.00</td>
                        <td>Konferensi ke Thailand</td>
                        <td>
                            <button type="button" class="btn btn-warning">Edit</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </table>
        </div>
        <div id="modalTambah" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tambah Jadwal Non Sidang</h4>
                    </div>
                    <div class="modal-body">
                        <form action="_content\root_jadwal_non_sidang_dosen_content.php" method="post">
                            <div class="form-group">
                                <label for="namaDosen">Nama Dosen: </label>
                                <input type="text" class="form-control" id="namaDosen" name="namaDosen">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="tanggalMulai">Tanggal Mulai: </label>
                                <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="tanggalSelesai">Tanggal Mulai: </label>
                                <input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="jamMulai">Jam Mulai: </label>
                                <input type="time" class="form-control" id="jamMulai" name="jamMulai">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="jamSelesai">Jam Selesai: </label>
                                <input type="time" class="form-control" id="jamSelesai" name="jamSelesai">
                            </div>
                            <div class="col-xs-12">
                                Repetisi Kegiatan: <br>
                                <label class="radio-inline"><input type="radio" name="optradio"> Harian&nbsp</label>
                                <label class="radio-inline"><input type="radio" name="optradio"> Mingguan&nbsp</label>
                                <label class="radio-inline"><input type="radio" name="optradio"> Bulanan&nbsp</label>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan: </label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" name="simpan" value="Simpan">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @font-face {
            font-family: 'Glyphicons Halflings';
            src: url('../fonts/glyphicons-halflings-regular.eot');
            src: url('../fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), url('../fonts/glyphicons-halflings-regular.woff') format('woff'), url('../fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('../fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
        }
    </style>
</section>