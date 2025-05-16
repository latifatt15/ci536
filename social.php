<?php
session_start();
require_once 'includes/config.php';  

include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Social Page</title>
</head>
<body>

<div class="page-container">

<form method="GET" action="social.php" class="search-form">
  <input type="text" name="search" placeholder="Search for friends..." class="search-bar" />
  <button type="submit">Search for other users</button>
</form>

<?php
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = "%" . trim($_GET['search']) . "%";

    $stmt = $conn->prepare("SELECT User_ID, fName, sName FROM Users WHERE fName LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='search-results'><h3>Search Results:</h3>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fullName = htmlspecialchars($row['fName'] . ' ' . $row['sName']);
            $userId = $row['User_ID'];
            echo "<p><a href='user_listings.php?user_id=$userId'>$fullName</a></p>";
        }
    } else {
        echo "<p>Could not find user.</p>";
    }
    echo "</div>";

    $stmt->close();
}
?>

 
<section class="user-info">
  <?php
  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT fName, sName, University, Profile_Picture FROM Users WHERE User_ID = ?");
    $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($user = $result->fetch_assoc()) {
          $fullName = htmlspecialchars($user['fName'] . ' ' . $user['sName']);
          $university = htmlspecialchars($user['University']);
          $profilePic = !empty($user['Profile_Picture']) ? $user['Profile_Picture'] : 'images/default.jpg';

          echo "<img src='" . htmlspecialchars($profilePic) . "' alt='Profile Picture' class='profile-pic' />";
          echo "<div class='user-details'>";
          echo "<p><strong>Name:</strong> $fullName</p>";
          echo "<p><strong>University:</strong> $university</p>";
          echo "</div>";
      } else {
          echo "<p>User not found.</p>";
      }

      $stmt->close();
  } else {
      echo "<p>You are not logged in.</p>";
  }
  ?>
</section>


  <!-- Main Feed Area -->
  <main class="main-content">
      

    <section class="main-feed">
        
        <section class="message-user-card">
  <div class="card-header">
    <h2>📨 Message a User</h2>
  </div>
  <form id="messageUserForm" method="POST" action="send_message.php" class="message-user-form">
    <div class="form-group">
      <label for="receiver">Recipient Email</label>
      <input type="email" id="recipient_email" name="recipient_email" placeholder="e.g. user@example.com" required />
    </div>
    <div class="form-group">
      <label for="message">Your Message</label>
      <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
    </div>
    <div class="form-footer">
      <button type="submit" class="send-button">Send</button>
    </div>
  </form>
  
  <!-- Inbox Button -->
<button id="inboxBtn" class="inbox-btn">📥 Inbox</button>

<!-- Inbox Modal -->
<div id="inboxModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h3>Your Inbox</h3>
    <div id="messageList">
      <!-- Messages will be loaded here -->
    </div>
  </div>
</div>

  
</section>


      <h3>What's going on?</h3>
      <div class="post">
        🗣 Debate tonight at 7PM - Cockcroft 101
      </div>
      <div class="post">
        🎉 Quiz Night at Student Union - Friday (6pm)
      </div>
       <div class="post">
    🍕 Free Pizza at the Basement Central Cafe - Wednesday at 1PM
  </div>
  <div class="post">
    🎬 Outdoor Movie Night - Saturday 8PM at Falmer Pitch
  </div>
  <div class="post">
    🧠 Mental Health Workshop - Thursday 2PM, Elm House
  </div>
    </section>

    <aside class="sidebar-feed">
      <div class="events">
        <h4>Events</h4>
        <ul>
          <li>Pride Parade</li>
          <li>Skateboards Comp</li>
          <li>Live Music in Pavilion Gardens</li>
        </ul>
      </div>
      <div class="clubs">
        <h4>Clubs</h4>
        <ul>
          <li>Rugby</li>
          <li>Football</li>
          <li>Cooking</li>
          <li>Drama</li>
          <li>Photography</li>
        </ul>
      </div>
      <div class="must-visits">
        <h4>Must Visits</h4>
        <ul>
          <li>Brighton Palace Pier</li>
          <li>Pryzm</li>
          <li>The Lanes</li>
          <li>Seven Sisters Cliffs</li>
        </ul>
      </div>
    </aside>
    

  </main>

</div>
<script src="js/social.js"></script>
</body>
</html>
<?php include 'includes/footer.php'; ?>