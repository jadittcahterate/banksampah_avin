<?php
require "config.php"; cek_login();
if (!in_array($_SESSION['role'], ['siswa','guru'])) { header("Location: dashboard.php"); exit; }

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_sampah = (int)$_POST['id_sampah'];
    $berat = (float)$_POST['berat_kg'];
    $q = $conn->prepare("SELECT harga_per_kg FROM sampah WHERE id_sampah=?");
    $q->bind_param("i",$id_sampah); $q->execute();
    $s = $q->get_result()->fetch_assoc();
    if($s && $berat > 0){
        $total = $berat * $s['harga_per_kg'];
        $stmt=$conn->prepare("INSERT INTO transaksi(id_user,id_sampah,tipe,berat_kg,total_rp,status) VALUES(?,?, 'setor',?,?, 'pending')");
        $stmt->bind_param("iidd",$_SESSION['id_user'],$id_sampah,$berat,$total);
        $stmt->execute(); $msg="Setoran berhasil diajukan.";
    }
}
$title="Setor Sampah"; require "header.php";
?>
<h2>Setor Sampah</h2>
<?php if($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
<div class="card p-4">
<form method="post">
<label class="form-label">Jenis Sampah</label>
<select name="id_sampah" class="form-select mb-3" required>
<option value="">-- Pilih --</option>
<?php $data=$conn->query("SELECT * FROM sampah ORDER BY jenis_sampah"); while($r=$data->fetch_assoc()): ?>
<option value="<?= $r['id_sampah'] ?>"><?= htmlspecialchars($r['jenis_sampah']) ?> - <?= rupiah($r['harga_per_kg']) ?>/kg</option>
<?php endwhile; ?>
</select>
<label class="form-label">Berat (kg)</label>
<input type="number" step="0.01" min="0.01" name="berat_kg" class="form-control mb-3" required>
<button class="btn btn-primary">Ajukan Setoran</button>
<a href="dashboard.php" class="btn btn-secondary">Kembali</a>
</form>
</div>
<?php require "footer.php"; ?>