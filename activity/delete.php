<?php
session_start();
include "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET["id"] ?? null;
$plan_id = $_GET["plan_id"] ?? null;

if ($id && $plan_id) {
    $sql = "DELETE FROM activities WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: activities.php?plan_id=$plan_id");
exit;
?>
