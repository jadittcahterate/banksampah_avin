<?php
require "config.php"; cek_login();
if($_SESSION['role']!=='admin'){header("Location: dashboard.php");exit;}
if(isset($_POST['tambah'])){
    $stmt=$conn->prepare("INSERT INTO user(nama,username,password,role,saldo) VALUES(?,?,?,?,0)");
    $stmt->bind_param("ssss",$_POST['nama'],$_POST['username'],$_POST['password'],$_POST['role']);
    $stmt->execute();
}
if(isset($_GET['hapus'])){
    $id=(int)$_GET['hapus'];
    if($id !== (int)$_SESSION['id_user']) $conn->query("DELETE FROM user WHERE id_user=$id");
}
$title="Kelola User"; require "header.php";
?>
<h2>Kelola User</h2>
<div class="card p-4 mb-4">
<h5>Tambah User</h5>
<form method="post" class="row g-2">
<input type="hidden" name="tambah" value="1">
<div class="col-md-3"><input name="nama" class="form-control" placeholder="Nama" required></div>
<div class="col-md-2"><input name="username" class="form-control" placeholder="Username" required></div>
<div class="col-md-2"><input name="password" class="form-control" placeholder="Password" required></div>
<div class="col-md-2"><select name="role" class="form-select"><option>siswa</option><option>guru</option><option>admin</option><option>pengepul</option></select></div>
<div class="col-md-2"><button class="btn btn-primary">Tambah</button></div>
</form>
</div>
<div class="card p-3"><table class="table table-bordered"><tr><th>ID</th><th>Nama</th><th>Username</th><th>Role</th><th>Saldo</th><th>Aksi</th></tr>
<?php $data=$conn->query("SELECT * FROM user ORDER BY id_user"); while($r=$data->fetch_assoc()): ?>
<tr><td><?= $r['id_user'] ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['username']) ?></td><td><?= $r['role'] ?></td><td><?= rupiah($r['saldo']) ?></td><td><?php if($r['id_user']!=$_SESSION['id_user']): ?><a class="btn btn-sm btn-danger" href="?hapus=<?= $r['id_user'] ?>" onclick="return confirm('Hapus user?')">Hapus</a><?php endif; ?></td></tr>
<?php endwhile; ?></table></div>
<?php require "footer.php"; ?>