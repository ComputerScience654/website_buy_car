<?php
include("db.php");

$errors = [];
$success_message = "";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long.";
    }

    if (empty($confirmPassword)) {
        $errors['confirm_password'] = "Confirm Password is required.";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // ตรวจสอบอีเมลซ้ำ
    $stmt_check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt_check_email->execute([$email]);
    $existing_user = $stmt_check_email->fetch();

    if ($existing_user) {
        $errors['email'] = "Email is already in use";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        $success_message = "Registration successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ColumbinaCar | Sign Up</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form-container login active">
                <div class="form">
                    <img src="img/ColumbinaCar.png" alt="Sign Up Image">
                </div>
                <form method="POST" action="register.php">
                    <h1>Sign up to start access</h1>
                    <div class="input-box">
                        <input name="username" type="text" placeholder="Username" required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="error"><?php echo $errors['username']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <input name="email" type="email" placeholder="Email" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="error"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <input name="password" type="password" placeholder="Password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="error"><?php echo $errors['password']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <input name="confirmPassword" type="password" placeholder="Confirm Password" required>
                        <?php if (isset($errors['confirm_password'])): ?>
                            <div class="error"><?php echo $errors['confirm_password']; ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="submit" class="btn">Sign Up</button>
                    <?php if ($success_message): ?>
                        <div class="success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    <div class="back-button">
                        <a href="index.html" class="btn">Back to Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>