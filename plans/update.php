<?php
session_start();
include "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$plan_id = $_GET["id"] ?? null;

if (!$plan_id) {
    echo "Geçersiz ID.";
    exit;
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $destination_id = $_POST["destination_id"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $notes = $_POST["notes"];

    $sql = "UPDATE plans SET title=?, destination_id=?, start_date=?, end_date=?, notes=? 
            WHERE id=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sisssii", $title, $destination_id, $start_date, $end_date, $notes, $plan_id, $user_id);
    mysqli_stmt_execute($stmt);

    header("Location: ../dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Plan Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4 w-50 mx-auto">
        <h4 class="mb-4">Planı Güncelle</h4>
        <form method="POST">
            <div class="mb-3">
                <input class="form-control" type="text" name="title" value="<?= $plan['title'] ?>" required>
            </div>

            <div class="mb-3">
                <select class="form-select" name="destination_id" required>
                    <?php
                    $dest_q = mysqli_query($conn, "SELECT * FROM destinations");
                    while ($dest = mysqli_fetch_assoc($dest_q)) {
                        $selected = $dest['id'] == $plan['destination_id'] ? "selected" : "";
                        echo "<option value='{$dest['id']}' $selected>{$dest['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <input class="form-control" type="date" name="start_date" value="<?= $plan['start_date'] ?>" required>
                </div>
                <div class="col">
                    <input class="form-control" type="date" name="end_date" value="<?= $plan['end_date'] ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <textarea class="form-control" name="notes"><?= $plan['notes'] ?></textarea>
            </div>

            <button class="btn btn-primary w-100" type="submit">Güncelle</button>
        </form>
    </div>
</div>
</body>
</html>
