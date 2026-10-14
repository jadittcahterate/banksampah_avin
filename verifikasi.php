<?php
require "config.php"; cek_login();
if($_SESSION['role']!=='admin'){header("Location: dashboard.php");exit;}

if(isset($_GET['transaksi'])){
    $id=(int)$_GET['transaksi']; $aksi=$_GET['aksi'] ?? '';
    if($aksi==='diverifikasi'){
        $r=$conn->query("SELECT * FROM transaksi WHERE id_transaksi=$id")->fetch_assoc();
        if($r && $r['status']==='pending'){
            $conn->query("UPDATE transaksi SET status='diverifikasi' WHERE id_transaksi=$id");
            if($r['tipe']==='setor'){
                $conn->query("UPDATE user SET saldo=saldo+{$r['total_rp']} WHERE id_user={$r['id_user']}");
                $conn->query("UPDATE sampah SET stok_kg=stok_kg+{$r['berat_kg']} WHERE id_sampah={$r['id_sampah']}");
            }
        }
    } elseif($aksi==='ditolak') $conn->query("UPDATE transaksi SET status='ditolak' WHERE id_transaksi=$id");
}
if(isset($_GET['tarik'])){
    $id=(int)$_GET['tarik']; $aksi=$_GET['aksi'] ?? '';
    $r=$conn->query("SELECT * FROM tarik_saldo WHERE id_tarik=$id")->fetch_assoc();
    if($r && $r['status']==='pending'){
        if($aksi==='disetujui'){
            $saldo=$conn->query("SELECT saldo FROM user WHERE id_user={$r['id_user']}")->fetch_assoc()['saldo'];
            if($saldo >= $r['nominal']){
                $conn->query("UPDATE user SET saldo=saldo-{$r['nominal']} WHERE id_user={$r['id_user']}");
                $conn->query("UPDATE tarik_saldo SET status='disetujui' WHERE id_tarik=$id");
            }
        } elseif($aksi==='ditolak') $conn->query("UPDATE tarik_saldo SET status='ditolak' WHERE id_tarik=$id");
    }
}
$title="Verifikasi"; require "header.php";
?>
<h2>Verifikasi Setoran</h2>
<div class="card p-3 mb-4"><table class="table table-bordered"><tr><th>Nama</th><th>Sampah</th><th>Berat</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
<?php $d=$conn->query("SELECT t.*,u.nama,s.jenis_sampah FROM transaksi t JOIN user u ON t.id_user=u.id_user JOIN sampah s ON t.id_sampah=s.id_sampah ORDER BY t.tanggal DESC"); while($r=$d->fetch_assoc()): ?>
<tr><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['jenis_sampah']) ?></td><td><?= $r['berat_kg'] ?> kg</td><td><?= rupiah($r['total_rp']) ?></td><td><?= $r['status'] ?></td><td><?php if($r['status']==='pending'): ?><a class="btn btn-sm btn-success" href="?transaksi=<?= $r['id_transaksi'] ?>&aksi=diverifikasi">Terima</a> <a class="btn btn-sm btn-danger" href="?transaksi=<?= $r['id_transaksi'] ?>&aksi=ditolak">Tolak</a><?php endif; ?></td></tr>
<?php endwhile; ?></table></div>

<h2>Verifikasi Tarik Saldo</h2>
<div class="card p-3"><table class="table table-bordered"><tr><th>Nama</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr>
<?php $d=$conn->query("SELECT t.*,u.nama FROM tarik_saldo t JOIN user u ON t.id_user=u.id_user ORDER BY t.tanggal DESC"); while($r=$d->fetch_assoc()): ?>
<tr><td><?= htmlspecialchars($r['nama']) ?></td><td><?= rupiah($r['nominal']) ?></td><td><?= $r['status'] ?></td><td><?php if($r['status']==='pending'): ?><a class="btn btn-sm btn-success" href="?tarik=<?= $r['id_tarik'] ?>&aksi=disetujui">Setujui</a> <a class="btn btn-sm btn-danger" href="?tarik=<?= $r['id_tarik'] ?>&aksi=ditolak">Tolak</a><?php endif; ?></td></tr>
<?php endwhile; ?></table></div>
<?php require "footer.php"; ?>