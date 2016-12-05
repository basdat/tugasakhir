<?php
require_once "database.php";
$db = new database();
$conn = $db->connectDB();
$role = $_SESSION['userdata']['role'];
$sql;
if ($role == 'admin'){
	$sql = "Select jsidang.idjadwal as IDSidang, Mhs.Nama as Mahasiswa, jenis_MKS.NamaMKS as Jenis, MKS.Judul as Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, MKS.ijinmajusidang, ruangan.NamaRuangan, mhs.npm, jsidang.idMKS, string_agg(dosen.nama, '|')
From jadwal_sidang jsidang, mata_kuliah_spesial MKS, Mahasiswa Mhs, dosen_pembimbing dospem, jenis_mks, dosen, ruangan
where jsidang.idMKS = MKS.idMKS AND
MKS.NPM = Mhs.NPM AND
MKS.idjenisMKS = jenis_MKS.id AND
jsidang.idMKS = dospem.IDMKS AND
dospem.NIPdosenpembimbing = dosen.NIP AND
jsidang.Idruangan = ruangan.idruangan AND MKS.ijinmajusidang = false
Group By jsidang.idjadwal, IDSidang, Mahasiswa, Jenis, Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, ruangan.NamaRuangan, mhs.npm, jsidang.idMKS, MKS.ijinmajusidang
order by idjadwal asc;";
}

if($role == 'dosen'){
	$nip = $_SESSION['userdata']['nip'];
	$sql = "Select jsidang.idjadwal as IDSidang, Mhs.Nama as Mahasiswa, jenis_MKS.NamaMKS as Jenis, MKS.Judul as Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, MKS.ijinmajusidang, ruangan.NamaRuangan, mhs.npm, jsidang.idMKS, string_agg(dosen.nama, '|')
From jadwal_sidang jsidang, mata_kuliah_spesial MKS, Mahasiswa Mhs, dosen_pembimbing dospem, jenis_mks, dosen, ruangan
where jsidang.idMKS = MKS.idMKS AND
MKS.NPM = Mhs.NPM AND
MKS.idjenisMKS = jenis_MKS.id AND
jsidang.idMKS = dospem.IDMKS AND
dospem.NIPdosenpembimbing = dosen.NIP AND
dosen.NIP = '$nip' AND
jsidang.Idruangan = ruangan.idruangan AND MKS.ijinmajusidang = false
Group By jsidang.idjadwal, IDSidang, Mahasiswa, Jenis, Judul, jsidang.tanggal, jsidang.jammulai, jsidang.jamselesai, ruangan.NamaRuangan, mhs.npm, jsidang.idMKS, MKS.ijinmajusidang
order by idjadwal asc;";
}
$stmt = $conn->prepare($sql);
$stmt->execute(array());
$jadwalSidangRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalRow = count($jadwalSidangRows);
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
    <?php
    	if($totalRow == '0'){
    		echo "<h3 style='text-align: center; text-decoration: underline;'>Tidak ada jadwal sidang yang perlu diizinkan </h3>";
    	} else {
    		echo '<div id="tableArea">
			<table class="table table-striped" id="izinSidang">
			<colgroup>
			    <col style="width:2%">
			    <col style="width:10%">
			    <col style="width:10%">
			    <col style="width:10%">
			    <col style="width:10%">
			    <col style="width:10%">
			    <col style="width:5%">
  			</colgroup> 
			<thead>
				<tr>
					<th style="text-align:center">No</th>
					<th style="text-align:center">Mahasiswa</th>
					<th style="text-align:center">Jenis Sidang</th>
					<th style="text-align:center">Judul</th>
					<th style="text-align:center">Waktu dan Lokasi</th>
					<th style="text-align:center">Dosen pembimbing</th>
					<th style="text-align:center">Izin sidang</th>
				</tr>
			</thead>';      
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
						if($value['ijinmajusidang'] == 'true'){
							echo "<button type='button' class='btn btn-warning disabled'>Diizinkan</button>";
						} else {

						echo "<form action=helper_izinkan.php method='post'>	<input type='hidden' name='npm' value='". $value['npm'] . "'><input type='hidden' name='idmks' value='".$value['idmks']."'><button type='submit' name='izin' class='btn btn-warning'>Izinkan</button></form>";
						}
					echo "</td>";	
				
					echo "</tr>";
					$counter++;
				}
			}
				?>
		</table>
	</div>
            <div class="row">
    </div>
    <script>
		$(document).ready(function() {
	    	$('#izinSidang').DataTable( {
	        "paging":   true,
	        "ordering": false,
	        "info":     false,
	        "order": [[ 2, "desc" ]]
    		} );

	    	 var html = '&nbsp&nbsp&nbsp<label for="sort">Sort by: &nbsp</label><select id="sort"><option value="waktu" clas="input-large" ">Waktu Sidang</option><option value="mahasiswa">Nama Mahasiswa</option><option value="jenis_sidang">Jenis Sidang</option></select></div>';

            $('.dataTables_length').append(html);
            $('div.dataTables_wrapper div.dataTables_length select').css('width', '150px');
            $('select').addClass("form-control");
            $('input').addClass("form-control");


    		$("#sort").change(function () {
                    var val = $("#sort").val();
                    var order = "";

                    if(val=='mahasiswa'){
                        order = "Mhs.nama";
                    }else if(val=='jenis_sidang'){
                        order = "jenis_mks.namamks";
                    }else if(val=='waktu'){
                        order = 'jsidang.tanggal ASC, jsidang.jammulai ASC';
                    }else{
                        order = 'jsidang.tanggal ASC, jsidang.jammulai ASC';
                    }

                    $.post("server/server_izin_sidang.php",{order: order},function(response){
                        $("#tableArea").html(response);
                        $('.table').DataTable( {
                            "paging":   true,
                            "ordering": false,
                            "info":false,
                        } );

                         var html = '&nbsp&nbsp&nbsp<label for="sort">Sort by: &nbsp</label><select id="sort"><option value="waktu" clas="input-large" ">Waktu Sidang</option><option value="mahasiswa">Nama Mahasiswa</option><option value="jenis_sidang">Jenis Sidang</option></select></div>';

		                $('.dataTables_length').append(html);
		                $('div.dataTables_wrapper div.dataTables_length select').css('width', '150px');
		                $('select').addClass("form-control");
		                $('input').addClass("form-control");

                    });
                });
		} );
	</script>
</section>