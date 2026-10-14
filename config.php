<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "banksampah_avin";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

function cek_login() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }
}

function rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}
?>