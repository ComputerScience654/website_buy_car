<?php
// เปิดการแสดงข้อผิดพลาดเพื่อดีบัก
error_reporting(E_ALL);
ini_set('display_errors', 1);

// เริ่มเซสชัน
session_start();

// ตรวจสอบว่าได้กดปุ่ม login หรือยัง
if (isset($_POST['login'])) {
    // เชื่อมต่อฐานข้อมูล
    include('db.php');

    // กำหนดตัวแปรจากฟอร์ม
    $email = trim(strtolower($_POST['email'] ?? '')); // ตัดช่องว่างและแปลงเป็นตัวพิมพ์เล็ก
    $password = $_POST['password'] ?? '';

    // Debug ตรวจสอบค่าที่ได้รับจากฟอร์ม
    // var_dump($_POST); exit();

    // ตรวจสอบว่าอีเมลและรหัสผ่านไม่ว่างเปล่า
    if (empty($email) || empty($password)) {
        $loginError = "❌ กรุณากรอกข้อมูลให้ครบ!";
    } else {
        // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลผู้ใช้จากฐานข้อมูล
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug ตรวจสอบข้อมูลที่ดึงมา
        // var_dump($user); exit();

        // ตรวจสอบว่าผู้ใช้มีอยู่ในฐานข้อมูลหรือไม่
        if ($user) {
            // ตรวจสอบรหัสผ่านว่าเหมือนกับที่เก็บในฐานข้อมูลหรือไม่
            if (!empty($user['password']) && password_verify($password, $user['password'])) {
                // สร้างเซสชันให้ผู้ใช้
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                $loginError = "❌ รหัสผ่านไม่ถูกต้อง!";
            }
        } else {
            $loginError = "❌ ไม่พบบัญชีนี้!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ColumbinaCar | Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .alert-danger {
            color: white;
            background-color: #777777;
            border: 1px solid #777777;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- แสดงข้อความผิดพลาดหากมี -->
        <?php if (isset($loginError)) : ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($loginError); ?>
            </div>
        <?php endif; ?>

        <div class="forms">
            <div class="form-container login active" id="loginForm">
                <div class="form">
                    <img src="img/ColumbinaCar.png" alt="Login Image">
                </div>
                <form action="login.php" method="POST">
                    <h1>Log in to ColumbinaCar</h1>
                    <div class="input-box">
                        <input name="email" type="email" placeholder="Email" required>
                    </div>
                    <div class="input-box">
                        <input name="password" type="password" placeholder="Password" required>
                    </div>
                    <div class="forget-link">
                        <button type="submit" name="login" class="btn">Log In</button>
                        <a href="#">Forgot your password?</a>
                    </div>
                    <div class="back-button">
                        <a href="register.php" class="btn">Sign up</a>
                    </div>
                    <div class="back-button">
                        <a href="index.html" class="btn">Back to Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>