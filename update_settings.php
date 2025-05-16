<?php
session_start();
require_once 'includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

$notifications = isset($_POST['notifications']) ? 1 : 0;

$stmt = $conn->prepare("UPDATE Users SET notifications = ? WHERE User_ID = ?");
$stmt->bind_param("sii", $notifications, $user_id);
$stmt->execute();
$stmt->close();

header("Location: profile.php");
exit();


