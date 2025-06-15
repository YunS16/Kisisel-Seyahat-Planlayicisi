<?php
session_start();
include "database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $destination_id = $_POST["destination_id"];
    $start = $_POST["start_date"];
    $end = $_POST["end_date"];
    $notes = $_POST["notes"];

    $sql = "INSERT INTO plans (user_id, destination_id, title, start_date, end_date, notes)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iissss", $user_id, $destination_id, $title, $start, $end, $notes);
    mysqli_stmt_execute($stmt);
}

$sql = "SELECT p.*, d.name AS destination_name FROM plans p 
        JOIN destinations d ON p.destination_id = d.id 
        WHERE p.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Hoş geldin, <?= $_SESSION["username"] ?>!</h2>
        <a class="btn btn-outline-danger" href="logout.php">Çıkış Yap</a>
    </div>

    <div class="card p-4 mb-4 shadow">
        <h4 class="mb-3">Yeni Seyahat Planı Ekle</h4>
        <form method="POST">
            <div class="mb-3">
                <input class="form-control" type="text" name="title" placeholder="Başlık" required>
            </div>
            <div class="mb-3">
                <select class="form-select" name="destination_id" required>
                    <option value="">Şehir Seç</option>
                    <?php
                    $dest_q = mysqli_query($conn, "SELECT * FROM destinations");
                    while ($dest = mysqli_fetch_assoc($dest_q)) {
                        echo "<option value='{$dest['id']}'>{$dest['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <input class="form-control" type="date" name="start_date" required>
                </div>
                <div class="col">
                    <input class="form-control" type="date" name="end_date" required>
                </div>
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="notes" placeholder="Notlar (isteğe bağlı)"></textarea>
            </div>
            <button class="btn btn-success w-100" type="submit">Ekle</button>
        </form>
    </div>

    <div class="card p-4 shadow">
        <h4 class="mb-3">Seyahat Planların</h4>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Şehir</th>
                    <th>Başlangıç</th>
                    <th>Bitiş</th>
                    <th>Not</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($plan = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $plan["title"] ?></td>
                    <td><?= $plan["destination_name"] ?></td>
                    <td><?= $plan["start_date"] ?></td>
                    <td><?= $plan["end_date"] ?></td>
                    <td><?= $plan["notes"] ?></td>
                    <td>
                        <a class="btn btn-sm btn-info" href="activity/activities.php?plan_id=<?= $plan['id'] ?>">Aktiviteler</a>
                        <a class="btn btn-sm btn-warning" href="plans/update.php?id=<?= $plan['id'] ?>">Güncelle</a>
                        <a class="btn btn-sm btn-danger" href="plans/delete.php?id=<?= $plan['id'] ?>" onclick="return confirm('Silinsin mi?')">Sil</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
