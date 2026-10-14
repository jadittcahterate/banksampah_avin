<?php
require "config.php"; cek_login();
if($_SESSION['role']!=='admin'){header("Location: dashboard.php");exit;}
if(isset($_POST['tambah'])){
    $stmt=$conn->prepare("INSERT INTO sampah(jenis_sampah,harga_per_kg,stok_kg) VALUES(?,?,0)");
    $stmt->bind_param("sd",$_POST['jenis_sampah'],$_POST['harga_per_kg']); $stmt->execute();
}
if(isset($_GET['hapus'])) $conn->query("DELETE FROM sampah WHERE id_sampah=".(int)$_GET['hapus']);
$title="Kelola Sampah"; require "header.php";
?>
<h2>Kelola Sampah</h2>
<div class="card p-4 mb-4"><form method="post" class="row g-2">
<input type="hidden" name="tambah" value="1">
<div class="col-md-5"><input name="jenis_sampah" class="form-control" placeholder="Jenis sampah" required></div>
<div class="col-md-4"><input type="number" step="0.01" name="harga_per_kg" class="form-control" placeholder="Harga/kg" required></div>
<div class="col-md-2"><button class="btn btn-primary">Tambah</button></div>
</form></div>
<div class="card p-3"><table class="table table-bordered"><tr><th>Jenis</th><th>Harga/kg</th><th>Stok</th><th>Aksi</th></tr>
<?php $data=$conn->query("SELECT * FROM sampah ORDER BY id_sampah"); while($r=$data->fetch_assoc()): ?>
<tr><td><?= htmlspecialchars($r['jenis_sampah']) ?></td><td><?= rupiah($r['harga_per_kg']) ?></td><td><?= $r['stok_kg'] ?> kg</td><td><a class="btn btn-sm btn-danger" href="?hapus=<?= $r['id_sampah'] ?>" onclick="return confirm('Hapus sampah?')">Hapus</a></td></tr>
<?php endwhile; ?></table></div>
<?php require "footer.php"; ?>