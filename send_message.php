<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to send messages.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sender_id = $_SESSION['user_id'];
    $recipient_email = trim($_POST['recipient_email']);
    $message_text = trim($_POST['message']);

    // Optional: if you're messaging about a specific listing
    $listing_id = isset($_POST['listing_id']) ? intval($_POST['listing_id']) : NULL;

    // Look up recipient's user ID
    $stmt = $conn->prepare("SELECT User_ID FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $recipient_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($recipient = $result->fetch_assoc()) {
        $receiver_id = $recipient['User_ID'];

        $insert = $conn->prepare("INSERT INTO Messages (Sender_ID, Receiver_ID, Listing_ID, Message_Text) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiis", $sender_id, $receiver_id, $listing_id, $message_text);

        if ($insert->execute()) {
            header("Location: messages.php?sent=1");
            exit();
        } else {
            echo "Message failed to send.";
        }
    } else {
        echo "User with that email not found.";
    }
}
?>

