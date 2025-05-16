<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");  // Redirect to login or homepage
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Logged Out</title>
  <link rel="stylesheet" href="css/style2.css" />
</head>
<body>
  <main class="container">
    <h1>You have successfully logged out.</h1>
    <p><a href="login.php">Click here to log back in.</a></p>
  </main>
</body>
</html>