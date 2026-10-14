<?php
require "config.php";

if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id_user, nama, username, password, role, saldo FROM user WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['saldo'] = $user['saldo'];
        header("Location: dashboard.php");
        exit;
    }
    $error = "Username atau password salah.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Bank Sampah Avin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
body{min-height:100vh;background:linear-gradient(135deg,#1b5e20,#66bb6a 55%,#ffd400);display:flex;align-items:center;justify-content:center}
.card{border:0;border-radius:20px;box-shadow:0 15px 40px rgba(0,0,0,.2)}
.logo{font-size:55px}
</style>
</head>
<body>
<div class="card p-4" style="width:390px">
<div class="text-center">
<div class="logo">♻️</div>
<h2 class="fw-bold">Bank Sampah Avin</h2>
<p class="text-muted">Silakan login untuk melanjutkan</p>
</div>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
<button class="btn btn-primary w-100">Login</button>
</form>
<hr>
<small class="text-muted d-block text-center">Admin: admin/admin123 · Siswa: siswa/siswa123 · Guru: guru/guru123 · Pengepul: pengepul/pengepul123</small>
</div>
</body>
</html>