<?php
session_start();
include "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$plan_id = $_GET["plan_id"] ?? null;

$sql = "SELECT * FROM plans WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $plan_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$plan = mysqli_fetch_assoc($result);

if (!$plan) {
    echo "Plan bulunamadı.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aktiviteler - <?= $plan['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>"<?= $plan['title'] ?>" Planına Ait Aktiviteler</h3>
        <a class="btn btn-outline-secondary" href="../dashboard.php">← Geri</a>
    </div>

    <div class="card p-4 mb-4 shadow">
        <h5 class="mb-3">Yeni Aktivite Ekle</h5>
        <form method="POST" action="add.php">
            <input type="hidden" name="plan_id" value="<?= $plan_id ?>">

            <div class="mb-3">
                <input class="form-control" type="text" name="name" placeholder="Aktivite Adı" required>
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="description" placeholder="Açıklama (isteğe bağlı)"></textarea>
            </div>
            <div class="mb-3">
                <input class="form-control" type="date" name="date">
            </div>

            <button class="btn btn-success w-100" type="submit">Ekle</button>
        </form>
    </div>

    <div class="card p-4 shadow">
        <h5 class="mb-3">Aktivite Listesi</h5>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Adı</th>
                    <th>Açıklama</th>
                    <th>Tarih</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM activities WHERE plan_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $plan_id);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            while ($activity = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $activity["name"] ?></td>
                    <td><?= $activity["description"] ?></td>
                    <td><?= $activity["date"] ?></td>
                    <td>
                        <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $activity["id"] ?>&plan_id=<?= $plan_id ?>" onclick="return confirm('Silinsin mi?')">Sil</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
