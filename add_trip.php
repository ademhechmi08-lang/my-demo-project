<?php
session_start();
if (!isset($_SESSION['is_admin'])) { header("Location: login.php"); exit; }
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $destination = $_POST['destination'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $start_date = $_POST['start_date'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("INSERT INTO trips (title, destination, description, price, start_date, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdss", $title, $destination, $description, $price, $start_date, $image);
    
    if ($stmt->execute()) {
        $msg = "<p style='color:green;'>تمت إضافة الرحلة بنجاح!</p>";
    } else {
        $msg = "<p style='color:red;'>حدث خطأ أثناء الإضافة.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة رحلة جديدة</title>
    <style>
        body { font-family: Tahoma, sans-serif; background-color: #f4f7f6; padding: 30px; }
        .form-box { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #2980b9; color: white; padding: 12px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #2471a3; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>إضافة رحلة سياحية جديدة</h2>
    <?php if (isset($msg)) echo $msg; ?>
    <form method="POST">
        <label>عنوان الرحلة:</label>
        <input type="text" name="title" required>

        <label>الوجهة:</label>
        <input type="text" name="destination" required>

        <label>الوصف:</label>
        <textarea name="description" rows="3" required></textarea>

        <label>السعر (د.ت):</label>
        <input type="number" step="0.01" name="price" required>

        <label>تاريخ الانطلاق:</label>
        <input type="date" name="start_date" required>

        <label>رابط أو مسار الصورة:</label>
        <input type="text" name="image" placeholder="مثال: images/trip1.jpg أو رابط صورة من النت" required>

        <button type="submit">إضافة الرحلة ✈️</button>
    </form>
    <br>
    <a href="index.php">عرض الموقع والرحلات</a>
</div>
<footer style="background-color: #2c3e50; color: #ecf0f1; text-align: center; padding: 15px; margin-top: 40px; font-size: 14px; border-top: 3px solid #3498db;">
    <p style="margin: 0;">
        جميع الحقوق محفوظة &copy; <?php echo date("Y"); ?> | تم التطوير بواسطة 
        <span style="color: #3498db; font-weight: bold;">Adem Hechmi</span>
    </p>
</body>
</html>