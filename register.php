<?php
session_start();
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST['fname']);
    $sname = trim($_POST['sname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $university = $_POST['university'];

    // Validate required fields
    if (empty($fname) || empty($sname) || empty($email) || empty($password) || empty($confirm_password) || empty($university)) {
        $error = "Please fill in all fields.";
    } elseif (!preg_match('/@.*\.ac\.uk$/', $email)) {
        $error = "You must register with a university email address (ending in .ac.uk).";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
    $uploadFile = 'images/default.jpg'; // Default image path
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        // Handle optional image upload
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = uniqid('user_', true) . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $uploadFile = $uploadDir . basename($imageName);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $validTypes)) {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES['profile_picture']['size'] > 5000000) {
            $error = "File is too large. Max 5MB.";
        } elseif (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $error = "Failed to upload profile picture.";
        }
    }

    if (empty($error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO Users (fName, sName, Email, Password_Hash, Profile_Picture, University) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $sname, $email, $hashed_password, $uploadFile, $university);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_email'] = $email;
            $success = "Registration successful! Redirecting to homepage...";
            header("refresh:3;url=http://la843.brighton.domains/ci536/index.php");
            exit;
        } else {
            $error = "That email may already be registered.";
        }

        $stmt->close();
    }
}


        $imageName = uniqid('user_', true) . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $uploadFile = $uploadDir . basename($imageName);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $validTypes)) {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES['profile_picture']['size'] > 5000000) {
            $error = "File is too large. Max 5MB.";
        } elseif (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $error = "Failed to upload profile picture.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO Users (fName, sName, Email, Password_Hash, Profile_Picture, University) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $fname, $sname, $email, $hashed_password, $uploadFile, $university);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_email'] = $email;
                $success = "Registration successful! Redirecting to homepage...";
                header("refresh:3;url=http://la843.brighton.domains/ci536/index.php");
                exit;
            } else {
                $error = "That email may already be registered.";
            }

            $stmt->close();
        }
    }

?>

<?php include 'includes/header.php'; ?>

<?php
session_start();

if (isset($_SESSION['user_id'])) {
    echo "<p style='color: red; font-weight: bold;'>You are already logged in! Logout to register with a new account.</p>";
    exit(); 
}
?>


<main class="container">
    <h1>Register</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (empty($success)): ?>
    <form method="POST" action="register.php" enctype="multipart/form-data">
        <div class="name-row">
            <input type="text" name="fname" placeholder="First Name" required>
            <input type="text" name="sname" placeholder="Surname" required>
        </div>
        <div><input type="email" name="email" placeholder="Email" required></div>
        <div>
            <select name="university" required>
                <option value="">Select your university</option>
                <option value="University of Brighton">University of Brighton</option>
                <option value="University of Sussex">University of Sussex</option>
                <option value="BIMM">BIMM</option>
                <option value="BSMS">BSMS</option>
            </select>
        </div>
        <div><input type="password" name="password" placeholder="Password" required></div>
        <div><input type="password" name="confirm_password" placeholder="Confirm Password" required></div>
        <div>
            <label for="profile_picture">Upload Profile Picture:</label>
            <input type="file" name="profile_picture" accept="image/*">
        </div>
        <button type="submit">Sign Up</button>

        <p class="login-link">
            Already have an account? <a href="login.php">Log in here</a>.
        </p>
    </form>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
