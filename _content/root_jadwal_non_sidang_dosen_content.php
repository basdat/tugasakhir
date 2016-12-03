<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();

if(isset($_SESSION['userdata']['nip'])){
	$nipDosen = $_SESSION['userdata']['nip'];
	$sql = "Select nip, nama from Dosen where nip = '$nipDosen';";
	$stmt_get_all_nama_dosen = $conn->prepare($sql);
	$stmt_get_all_nama_dosen->execute(array());

	$allNamaDosen = $stmt_get_all_nama_dosen->fetchAll(PDO::FETCH_ASSOC);


	$stmt_get_all_jadwal = $conn->prepare("Select idjadwal, tanggalmulai, tanggalselesai, alasan, repetisi, nama, nipdosen from jadwal_non_sidang jns, dosen d where jns.nipdosen = d.nip and d.nip = '$nipDosen' order by tanggalmulai ;");

	$stmt_get_all_jadwal->execute(array());
	$allJadwal = $stmt_get_all_jadwal->fetchAll(PDO::FETCH_ASSOC);
} else {
	$stmt_get_all_nama_dosen = $conn->prepare("Select nip, nama from Dosen;");
	$stmt_get_all_nama_dosen->execute(array());
	$allNamaDosen = $stmt_get_all_nama_dosen->fetchAll(PDO::FETCH_ASSOC);


	$stmt_get_all_jadwal = $conn->prepare("Select idjadwal, tanggalmulai, tanggalselesai, alasan, repetisi, nama, nipdosen from jadwal_non_sidang jns, dosen d where jns.nipdosen = d.nip order by tanggalmulai;");

	$stmt_get_all_jadwal->execute(array());
	$allJadwal = $stmt_get_all_jadwal->fetchAll(PDO::FETCH_ASSOC);
}

//TODO: Handling dia admin atau dosen

?>
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
			<?php echo "<h4> Welcome, " . $_SESSION['userdata']['nama'] . "! </h4> <br><br>"; ?>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalTambah">Tambah Jadwal Non Sidang</button>
		</div>
		<br>
		<div class="row">
			<table>
				<table id="jadwaltable" class="table table-striped">
					<thead class="thead-inverse">
						<tr>
							<th>No.</th>
							<th>Nama</th>
							<th>Tanggal</th>
							<th>Repetisi</th>
							<th>Keterangan</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody class="table-condensed">
						<?php 
							$counter = 1;
							foreach ($allJadwal as $key => $jadwal) {
								
								$id = $jadwal['idjadwal'];
								$nama = $jadwal['nama'];
								$tglMulai = $jadwal['tanggalmulai'];
								$tglSelesai = $jadwal['tanggalselesai'];
								$repetisi = $jadwal['repetisi'];
								$alasan = $jadwal['alasan'];
								$nip = $jadwal['nipdosen'];

								echo "<tr>";
								echo "<td>" . $counter . "</td>";
								echo "<td>" . $jadwal['nama'] . "</td>";
								echo "<td>" . $jadwal['tanggalmulai'] . " - " . $jadwal['tanggalselesai'] . "</td>";
								echo "<td>" . ucwords($jadwal['repetisi']) . "</td>";
								echo "<td>" . ucwords($jadwal['alasan']) . "</td>";
								echo '<td><button type="button" data-toggle="modal" data-target="#modalEdit" class="btn btn-warning" id="' . $jadwal['idjadwal'] . '" onclick="updateData(\''.$id.'\',\''.$nama.'\',\''.$tglMulai.'\',\''.$tglSelesai.'\',\''.$repetisi.'\',\''.$alasan.'\',\''.$nip.'\');">Edit</button></td>';
								echo "</tr>";
								$counter++;
							}
						?>
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
						<form action="helper_jadwal_non_sidang.php" method="post">
							<div class="form-group">
								<label for="namaDosen">Nama Dosen: </label> <br>
								<select class="form-control" id="sel1" name="nipDosen">
								    <?php 
								    foreach ($allNamaDosen as $key => $dosen) {
								    	echo "<option value='". $dosen['nip'] ."'>";
								    	echo $dosen['nama'] . " - " . $dosen['nip'];
								    	echo "</option>";
								    }
								    ?>
								</select>
							</div>
							<div class="form-group col-xs-6">
								<label for="tanggalMulai">Tanggal Mulai: </label>
								<input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai">
							</div>
							<div class="form-group col-xs-6">
								<label for="tanggalSelesai">Tanggal Selesai: </label>
								<input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai">
							</div>
							<div class="col-xs-12">
								Repetisi Kegiatan:
								<div class="form-control">
									<label class="form-control radio-inline"><input type="radio" name="repetisi" value="harian"> Harian&nbsp</label>
									<label class="form-control radio-inline"><input type="radio" name="repetisi" value="mingguan"> Mingguan&nbsp</label>
									<label class="form-control radio-inline"><input type="radio" name="repetisi" value="bulanan"> Bulanan&nbsp</label>
								</div>
							</div>
							<div class="form-group">
								<label for="keterangan">Keterangan: </label>
								<textarea class="form-control" id="keterangan" name="keterangan"></textarea>
							</div>
						
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-success" name="simpan" value="Simpan">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
					</form>
				</div>
			</div>
		</div>

		<div id="modalEdit" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title" id="editTitle">Edit Jadwal Non Sidang &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</h4>
					</div>
					<div class="modal-body">
						<form action="helper_jadwal_non_sidang.php" method="post">
							<div class="form-group">
								<label for="namaDosen">Nama Dosen: </label> <br>
								<select class="form-control" id="editNama" name="nipDosen">
								</select>
							</div>
							<div class="form-group col-xs-6">
								<label for="tanggalMulai">Tanggal Mulai: </label>
								<input type="date" class="form-control" id="editTanggalMulai" name="tanggalMulai">
							</div>
							<div class="form-group col-xs-6">
								<label for="tanggalSelesai">Tanggal Selesai: </label>
								<input type="date" class="form-control" id="editTanggalSelesai" name="tanggalSelesai">
							</div>
							<div class="col-xs-12">
								Repetisi Kegiatan:
								<div class="form-control">
									<label class="form-control radio-inline"><input type="radio" name="repetisi" value="harian"> Harian&nbsp</label>
									<label class="form-control radio-inline"><input type="radio" name="repetisi" value="mingguan"> Mingguan&nbsp</label>
									<label class="form-control radio-inline"><input type="radio" name="repetisi" value="bulanan"> Bulanan&nbsp</label>
								</div>
							</div>
							<div class="form-group">
								<label for="keterangan">Keterangan: </label>
								<textarea class="form-control" id="editKeterangan" name="keterangan"></textarea>
							</div>
						
					</div>
					<div class="modal-footer">
						<input type="hidden" name="idJadwal" id="idJadwal">
						<input type="submit" class="btn btn-success" name="simpan" value="Update">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
					</form>
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
	<script>
		$(document).ready(function() {
	    	$('#jadwaltable').DataTable( {
	        "paging":   true,
	        "ordering": false,
	        "info":     false
    		} );
		} );

		function updateData(id, nama, tglMulai, tglSelesai, repetisi, alasan, nip){
			$("#idJadwal").val(id);
			$("#editNama").append('<option name="nipDosen" value=' + nip + '>'+ nama +'</option>');
			$("#editTanggalMulai").val(tglMulai);
			$("#editTanggalSelesai").val(tglSelesai);
			$("#editKeterangan").val(alasan);

		}

		function test(){
			alert("asdad");
		}
	</script>
</div>
</div>
</section>