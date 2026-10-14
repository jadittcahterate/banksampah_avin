<?php
require "config.php"; cek_login();
if($_SESSION['role']!=='pengepul'){header("Location: dashboard.php");exit;}
$msg="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $id_sampah=(int)$_POST['id_sampah']; $berat=(float)$_POST['berat_kg']; $harga=(float)$_POST['harga_jual_per_kg'];
    if($berat>0 && $harga>0){
        $total=$berat*$harga;
        $stmt=$conn->prepare("INSERT INTO setoran_pengepul(id_sampah,id_admin,berat_kg,harga_jual_per_kg,total_rp) VALUES(?,?,?, ?,?)");
        $admin=$conn->query("SELECT id_user FROM user WHERE role='admin' ORDER BY id_user LIMIT 1")->fetch_assoc();
        $id_admin=$admin['id_user'];
        $stmt->bind_param("iiddd",$id_sampah,$id_admin,$berat,$harga,$total);
        $stmt->execute();
        $conn->query("UPDATE sampah SET stok_kg=GREATEST(stok_kg-$berat,0) WHERE id_sampah=$id_sampah");
        $msg="Setoran pengepul berhasil disimpan.";
    }
}
$title="Setoran Pengepul"; require "header.php";
?>
<h2>Setoran Pengepul</h2>
<?php if($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
<div class="card p-4 mb-4"><form method="post">
<select name="id_sampah" class="form-select mb-3" required><option value="">Pilih sampah</option>
<?php $d=$conn->query("SELECT * FROM sampah ORDER BY jenis_sampah"); while($r=$d->fetch_assoc()): ?><option value="<?= $r['id_sampah'] ?>"><?= htmlspecialchars($r['jenis_sampah']) ?></option><?php endwhile; ?>
</select>
<input type="number" step="0.01" min="0.01" name="berat_kg" class="form-control mb-3" placeholder="Berat (kg)" required>
<input type="number" step="0.01" min="0" name="harga_jual_per_kg" class="form-control mb-3" placeholder="Harga jual/kg" required>
<button class="btn btn-primary">Simpan Setoran</button>
</form></div>
<div class="card p-3"><table class="table table-bordered"><tr><th>Tanggal</th><th>Sampah</th><th>Berat</th><th>Harga/kg</th><th>Total</th></tr>
<?php $d=$conn->query("SELECT sp.*,s.jenis_sampah FROM setoran_pengepul sp JOIN sampah s ON sp.id_sampah=s.id_sampah ORDER BY sp.tanggal DESC"); while($r=$d->fetch_assoc()): ?><tr><td><?= $r['tanggal'] ?></td><td><?= htmlspecialchars($r['jenis_sampah']) ?></td><td><?= $r['berat_kg'] ?> kg</td><td><?= rupiah($r['harga_jual_per_kg']) ?></td><td><?= rupiah($r['total_rp']) ?></td></tr><?php endwhile; ?></table></div>
<?php require "footer.php"; ?>