<?php
require "config.php"; cek_login();
$title="Riwayat"; require "header.php";
$where = $_SESSION['role']==='admin' ? "" : "WHERE t.id_user=".(int)$_SESSION['id_user'];
$sql="SELECT t.*, u.nama, s.jenis_sampah FROM transaksi t JOIN user u ON t.id_user=u.id_user JOIN sampah s ON t.id_sampah=s.id_sampah $where ORDER BY t.tanggal DESC";
$data=$conn->query($sql);
?>
<h2>Riwayat Transaksi</h2>
<div class="card p-3">
<div class="table-responsive"><table class="table table-bordered">
<thead><tr><th>Tanggal</th><th>Nama</th><th>Sampah</th><th>Tipe</th><th>Berat</th><th>Total</th><th>Status</th></tr></thead>
<tbody>
<?php while($r=$data->fetch_assoc()): ?>
<tr><td><?= $r['tanggal'] ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['jenis_sampah']) ?></td><td><?= $r['tipe'] ?></td><td><?= $r['berat_kg'] ?> kg</td><td><?= rupiah($r['total_rp']) ?></td><td><span class="badge bg-secondary"><?= $r['status'] ?></span></td></tr>
<?php endwhile; ?>
</tbody></table></div></div>
<?php require "footer.php"; ?>