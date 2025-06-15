<?php
session_start();
include "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$plan_id = $_POST["plan_id"];
$name = $_POST["name"];
$description = $_POST["description"];
$date = $_POST["date"];

$sql = "INSERT INTO activities (plan_id, name, description, date) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "isss", $plan_id, $name, $description, $date);
mysqli_stmt_execute($stmt);

header("Location: activities.php?plan_id=$plan_id");
exit;
?>
