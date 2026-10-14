<?php
$title = "Dashboard";
require "header.php";

$id = (int)$_SESSION['id_user'];
$role = $_SESSION['role'];

$totalSampah = $conn->query("SELECT COUNT(*) jml FROM sampah")->fetch_assoc()['jml'];
$totalTransaksi = $conn->query("SELECT COUNT(*) jml FROM transaksi")->fetch_assoc()['jml'];
$saldo = $conn->query("SELECT saldo FROM user WHERE id_user=$id")->fetch_assoc()['saldo'];

$stats = [
    ['🗑️', 'Jenis Sampah', $totalSampah],
    ['🔄', 'Total Transaksi', $totalTransaksi],
];
if ($role === 'admin') {
    $totalUser = $conn->query("SELECT COUNT(*) jml FROM user")->fetch_assoc()['jml'];
    $stats[] = ['👥', 'Total User', $totalUser];
}
$colStat = count($stats) === 3 ? 'col-md-4' : 'col-md-6';

$menus = [];
if (in_array($role, ['siswa', 'guru'])) {
    $menus[] = ['setor.php', '♻️', 'Setor Sampah', 'Ajukan setoran sampah.'];
    $menus[] = ['tarik.php', '💰', 'Tarik Saldo', 'Ajukan penarikan saldo.'];
}
$menus[] = ['riwayat.php', '📋', 'Riwayat', 'Lihat transaksi dan aktivitas.'];
if ($role === 'admin') {
    $menus[] = ['users.php', '👥', 'Kelola User', 'Kelola siswa, guru, admin, pengepul.'];
    $menus[] = ['sampah.php', '🗑️', 'Kelola Sampah', 'Kelola jenis, harga, dan stok.'];
    $menus[] = ['verifikasi.php', '✅', 'Verifikasi', 'Verifikasi transaksi dan penarikan.'];
}
if ($role === 'pengepul') {
    $menus[] = ['pengepul.php', '🚛', 'Setoran Pengepul', 'Kelola pembelian sampah.'];
}
?>
<div class="hero mb-4">
  <div>
    <span class="hero-role"><?= htmlspecialchars(ucfirst($role)) ?></span>
    <h1 class="hero-title">Halo, <?= htmlspecialchars($_SESSION['nama']) ?> 👋</h1>
    <p class="mb-0">Selamat datang di Bank Sampah Avin. Yuk kelola sampah jadi berkah.</p>
  </div>
  <div class="hero-saldo">
    <small>Saldo Saya</small>
    <strong><?= rupiah($saldo) ?></strong>
  </div>
</div>

<div class="row g-3 mb-4">
<?php foreach ($stats as [$icon, $label, $val]): ?>
  <div class="<?= $colStat ?>">
    <div class="card stat p-3 flex-row align-items-center gap-3">
      <div class="stat-icon"><?= $icon ?></div>
      <div><small><?= $label ?></small><h3><?= $val ?></h3></div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<h5 class="section-title">Menu Utama</h5>
<div class="row g-3">
<?php foreach ($menus as [$href, $icon, $judul, $desk]): ?>
  <div class="col-md-4">
    <a href="<?= $href ?>" class="text-decoration-none">
      <div class="card menu-card p-4 h-100">
        <div class="menu-icon"><?= $icon ?></div>
        <h5><?= $judul ?></h5>
        <p class="text-muted mb-0"><?= $desk ?></p>
      </div>
    </a>
  </div>
<?php endforeach; ?>
</div>
<?php require "footer.php"; ?>