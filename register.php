<?php
session_start();
require_once 'includes/config.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST['fname']);
    $sname = trim($_POST['sname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($fname) || empty($sname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
        //Only uni emails can register
    } elseif (!preg_match('/@.*\.ac\.uk$/', $email)) {
        $error = "You must register with a university email address (ending in .ac.uk).";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Users (fName, sName, Email, Password_Hash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fname, $sname, $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $success = "Registration successful! Redirecting to homepage...";
            header("refresh:3;url=index.php");
        } else {
            $error = "That email may already be registered.";
        }

        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h1>Register</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (empty($success)): ?>
    <form method="POST" action="register.php">
    <div class="name-row">
    <input type="text" name="fname" placeholder="First Name" required>
    <input type="text" name="sname" placeholder="Surname" required></div>
        <div><input type="email" name="email" placeholder="Email" required></div>
        <div><select name="university" required>
            <option value="">Select your university</option>
            <<option value="University of Brighton">University of Brighton</option>
            <option value="University of Sussex">University of Sussex</option>
            <option value="BIMM">BIMM</option>
            <option value="BSMS">BSMS</option>
    </select></div>
        <div><input type="password" name="password" placeholder="Password" required></div>
        <div><input type="password" name="confirm_password" placeholder="Confirm Password" required></div>
        <button type="submit">Sign Up</button>

        <p class="login-link">
            Already have an account? <a href="login.php">Log in here</a>.
        </p>
    </form>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
