<?php
$host = "localhost";
$user = "root";
$pass = ""; // XAMPP'ta şifre yok!
$dbname = "seyahat";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}
?>
