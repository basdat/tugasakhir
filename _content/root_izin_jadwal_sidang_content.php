<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();
$stmt = $conn->prepare("Select jsidang.idjadwal as IDSidang, Mhs.Nama as Mahasiswa, jenis_MKS.NamaMKS as Jenis, MKS.Judul as Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, ruangan.NamaRuangan, string_agg(dosen.nama, '|')
From jadwal_sidang jsidang, mata_kuliah_spesial MKS, Mahasiswa Mhs, dosen_pembimbing dospem, jenis_mks, dosen, ruangan
where jsidang.idMKS = MKS.idMKS AND
MKS.NPM = Mhs.NPM AND
MKS.idjenisMKS = jenis_MKS.id AND
jsidang.idMKS = dospem.IDMKS AND
dospem.NIPdosenpembimbing = dosen.NIP AND
jsidang.Idruangan = ruangan.idruangan
Group By jsidang.idjadwal, IDSidang, Mahasiswa, Jenis, Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, ruangan.NamaRuangan
order by idjadwal asc;");
$stmt->execute(array());
$jadwalSidangRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section id="hero" class="header">
    <div class="container">
        <div class="row">
            <div class="row text-xs-center">
                <span class="display-3">Izin Jadwal Sidang</span>
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
	<table class="table table-striped">
	<thead class="thead-inverse">
		<tr>
			<th>No</th>
			<th>Mahasiswa</th>
			<th>Jenis Sidang</th>
			<th>Judul</th>
			<th>Waktu dan Lokasi</th>
			<th>Dosen pembimbing</th>
			<th>Izin sidang</th>
		</tr>
	</thead>
		<?php 
			$counter = 1;
			foreach ($jadwalSidangRows as $key => $value) {
			echo "<tr>";
			echo "<td>";
				echo $counter;
			echo "</td>";
			echo "<td>";
				echo $value['mahasiswa'];
			echo "</td>";
			echo "<td>";
				echo $value['jenis'];
			echo "</td>";
			echo "<td>";
				echo $value['judul'];
			echo "</td>";
			echo "<td>";
				echo $value['tanggal'] . "<br>";
				echo $value['jammulai'] . " - " . $value['jamselesai'] . "<br>";
				echo $value['namaruangan'];
			echo "</td>";
			echo "<td>";
				$dosen = explode("|", $value['string_agg']);
				echo "<ul>";
				foreach ($dosen as $key => $d) {
					echo "<li>" . $d . "</li>" ;
				}
				echo "</ul>";
			echo "</td>";
			echo "<td>";
				echo "<button type='button' class='btn btn-warning'>Izinkan</button>";
			echo "</td>";	
		
			echo "</tr>";
			$counter++;
		}

		?>
</table>
            <div class="row">
    </div>
</section>