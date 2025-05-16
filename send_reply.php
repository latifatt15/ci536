<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit("Not authorized");
}

$sender = $_SESSION['user_id'];
$recipient = $_POST['recipient_id'];
$message = trim($_POST['message']);

if ($recipient && $message !== "") {
    $stmt = $conn->prepare("INSERT INTO Messages (Sender_ID, Receiver_ID, Message_Text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender, $recipient, $message);
    $stmt->execute();
    $stmt->close();
    echo "Message sent.";
} else {
    http_response_code(400);
    echo "Missing data.";
}
?>
