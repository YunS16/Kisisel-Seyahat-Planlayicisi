<?php
session_start();
include "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$plan_id = $_GET["id"] ?? null;

if ($plan_id) {
    $sql = "DELETE FROM plans WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $plan_id, $user_id);
    mysqli_stmt_execute($stmt);
}

header("Location: ../dashboard.php");
exit;
?>
