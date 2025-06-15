<?php
session_start();
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["user_id"] = $user['id'];
        $_SESSION["username"] = $user['username'];
        header("Location: dashboard.php");
    } else {
        $error = "Hatalı giriş.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow w-50 mx-auto p-4">
        <h3 class="mb-4 text-center">Giriş Yap</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input class="form-control" type="text" name="username" placeholder="Kullanıcı Adı" required>
            </div>
            <div class="mb-3">
                <input class="form-control" type="password" name="password" placeholder="Şifre" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Giriş Yap</button>
        </form>

        <div class="text-center mt-3">
            <a href="register.php">Hesabın yok mu? Kayıt ol</a>
        </div>
    </div>
</div>
</body>
</html>
