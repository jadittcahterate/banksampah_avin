<?php
require "config.php"; cek_login();
if (!in_array($_SESSION['role'], ['siswa','guru'])) { header("Location: dashboard.php"); exit; }
$msg="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $nominal=(float)$_POST['nominal'];
    $saldo=$conn->query("SELECT saldo FROM user WHERE id_user=".(int)$_SESSION['id_user'])->fetch_assoc()['saldo'];
    if($nominal>0 && $nominal <= $saldo){
        $stmt=$conn->prepare("INSERT INTO tarik_saldo(id_user,nominal) VALUES(?,?)");
        $stmt->bind_param("id",$_SESSION['id_user'],$nominal); $stmt->execute();
        $msg="Permintaan penarikan berhasil diajukan.";
    } else $msg="Nominal tidak boleh melebihi saldo.";
}
$title="Tarik Saldo"; require "header.php";
$saldo=$conn->query("SELECT saldo FROM user WHERE id_user=".(int)$_SESSION['id_user'])->fetch_assoc()['saldo'];
?>
<h2>Tarik Saldo</h2>
<div class="card p-4">
<p>Saldo tersedia: <strong><?= rupiah($saldo) ?></strong></p>
<?php if($msg): ?><div class="alert alert-info"><?= $msg ?></div><?php endif; ?>
<form method="post">
<input type="number" step="1000" min="1000" name="nominal" class="form-control mb-3" placeholder="Nominal penarikan" required>
<button class="btn btn-primary">Ajukan Penarikan</button>
</form>
</div>
<?php require "footer.php"; ?>