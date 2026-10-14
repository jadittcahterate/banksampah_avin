<?php require_once "config.php"; cek_login(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? 'Bank Sampah Avin') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
<div class="container">
<a class="navbar-brand fw-bold" href="dashboard.php">♻️ Bank Sampah Avin</a>
<div class="d-flex align-items-center gap-2 text-white flex-wrap">
<?php if (($title ?? '') !== 'Dashboard'): ?>
<a href="dashboard.php" class="btn btn-outline-light btn-sm">← Kembali</a>
<?php endif; ?>
<span><?= htmlspecialchars($_SESSION['nama']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
<a href="logout.php" class="btn btn-light btn-sm">Logout</a>
</div>
</div>
</nav>
<div class="container py-4">