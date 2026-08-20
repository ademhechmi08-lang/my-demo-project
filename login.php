<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && $_POST['username'] === 'admin' && $_POST['password'] === '12345') {
        $_SESSION['is_admin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة!";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل دخول المدير</title>
    <style>
        body { font-family: Tahoma, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        h2 { text-align: center; color: #2c3e50; margin-top: 0; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #2980b9; color: white; padding: 12px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background-color: #2471a3; }
        .error { color: #e74c3c; text-align: center; font-size: 14px; margin-top: 10px; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>دخول المدير</h2>
    <form method="POST">
        <label>اسم المستخدم:</label>
        <input type="text" name="username" required>
        
        <label>كلمة المرور:</label>
        <input type="password" name="password" required>
        
        <button type="submit">تسجيل الدخول</button>
    </form>
    
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    
    <a href="index.php" class="back-link">العودة إلى الموقع الرئيسي</a>
</div>

</body>
</html>