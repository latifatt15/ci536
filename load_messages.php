<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to view messages.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT m.Message_ID, m.Message_Text, m.Sent_At, u.fName, u.sName, u.User_ID
        FROM Messages m
        JOIN Users u ON m.Sender_ID = u.User_ID
        WHERE m.Receiver_ID = ?
        ORDER BY m.Sent_At DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No messages.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='message-item'>";
        echo "<p><strong>From:</strong> " . htmlspecialchars($row['fName'] . ' ' . $row['sName']) . "</p>";
        echo "<p>" . nl2br(htmlspecialchars($row['Message_Text'])) . "</p>";
        echo "<small><em>Sent at " . $row['Sent_At'] . "</em></small>";
        echo "<form class='reply-form'>
                <input type='hidden' name='recipient_id' value='" . $row['User_ID'] . "'>
                <textarea name='message' placeholder='Write a reply...'></textarea>
                <button type='submit'>Send</button>
              </form>";
        echo "</div>";
    }
}

$stmt->close();
?>
