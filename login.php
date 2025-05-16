<?php
// Start a session to track user login state
session_start();

// Connect to the database
require_once 'includes/config.php';

// Initialize an empty error message
$error = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the form values
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Only continue if both fields are filled in
    if (!empty($email) && !empty($password)) {
        // Prepare a SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT User_ID, Password_Hash FROM Users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if a user with that email exists
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            // Check if the password matches
            if (password_verify($password, $hashed_password)) {
                // Save user ID in the session and redirect to homepage
                $_SESSION['user_id'] = $user_id;

            
                header("Location: index.php");
                exit();
            } else {
                $error = "Incorrect password. Try again.";
            }
        } else {
            $error = "No account found with that email.";
        }

        $stmt->close(); 
    } else {
        $error = "Please fill in both fields.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<?php
session_start();

if (isset($_SESSION['user_id'])) {
    echo "<p style='color: red; font-weight: bold;'>You are already logged in!</p>";
    exit(); 
}
?>

<html>
<main class="container">
    <h1>Log In</h1>

    <!-- Show error message if there is one -->
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Login form -->
    <form method="POST" action="login.php">
        <div>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Email"
                required
            >
        </div>

        <div>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Password"
                required
            >
        </div>

        <button type="submit">Sign In</button>

        <p class="login-link">
            Don’t have an account? <a href="register.php">Sign up here</a>.
        </p>
        <p class="login-link">
            <a href="forgot_password.php">Forgot password?</a>
        </p>
    </form>
</main>
</html>

<?php include 'includes/footer.php'; ?>
