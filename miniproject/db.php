<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_membership";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน

            // ตรวจสอบว่าอีเมลมีอยู่ในฐานข้อมูลแล้วหรือไม่
            $checkEmail = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($checkEmail);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<script>alert('❌ อีเมลนี้ถูกใช้ไปแล้ว!');</script>";
            } else {
                // คำสั่ง SQL สำหรับเพิ่มข้อมูล
                $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);

                if ($stmt->execute()) {
                    // แจ้งเตือนสำเร็จ
                    echo "<script>alert('✅ สมัครสมาชิกสำเร็จ!'); window.location.href='login.php';</script>";
                } else {
                    // แจ้งเตือนไม่สำเร็จ
                    echo "<script>alert('❌ สมัครสมาชิกไม่สำเร็จ!');</script>";
                }
            }
        } else {
            // แจ้งเตือนกรอกข้อมูลไม่ครบ
            echo "<script>alert('❌ กรุณากรอกข้อมูลให้ครบ!');</script>";
        }
    }
} catch (PDOException $e) {
    echo "<div class='error-message'>❌ Error: " . $e->getMessage() . "</div>";
}
