<?php
session_start();
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php");
    } else {
        $error = "Kayıt sırasında hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow w-50 mx-auto p-4">
        <h3 class="mb-4 text-center">Kayıt Ol</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input class="form-control" type="text" name="username" placeholder="Kullanıcı Adı" required>
            </div>
            <div class="mb-3">
                <input class="form-control" type="email" name="email" placeholder="E-posta" required>
            </div>
            <div class="mb-3">
                <input class="form-control" type="password" name="password" placeholder="Şifre" required>
            </div>
            <button class="btn btn-success w-100" type="submit">Kayıt Ol</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">Zaten hesabın var mı? Giriş yap</a>
        </div>
    </div>
</div>
</body>
</html>
