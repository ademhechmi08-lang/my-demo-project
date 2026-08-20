<?php
include 'db.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM trips WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$trip = $result->fetch_assoc();
$stmt->close();

if (!$trip) {
    echo "الرحلة غير موجودة!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $destination = $_POST['destination'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $start_date = $_POST['start_date'];
    $image = $_POST['image'];

    $update_stmt = $conn->prepare("UPDATE trips SET title = ?, destination = ?, description = ?, price = ?, start_date = ?, image = ? WHERE id = ?");
    $update_stmt->bind_param("sssdssi", $title, $destination, $description, $price, $start_date, $image, $id);
    
    if ($update_stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $error = "حدث خطأ أثناء التعديل.";
    }
    $update_stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الرحلة</title>
    <style>
        body { font-family: Tahoma, sans-serif; background-color: #f4f7f6; padding: 30px; }
        .form-box { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #3498db; color: white; padding: 12px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>تعديل الرحلة السياحية</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>عنوان الرحلة:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($trip['title']); ?>" required>

        <label>الوجهة:</label>
        <input type="text" name="destination" value="<?php echo htmlspecialchars($trip['destination']); ?>" required>

        <label>الوصف:</label>
        <textarea name="description" rows="3" required><?php echo htmlspecialchars($trip['description']); ?></textarea>

        <label>السعر (د.ت):</label>
        <input type="number" step="0.01" name="price" value="<?php echo $trip['price']; ?>" required>

        <label>تاريخ الانطلاق:</label>
        <input type="date" name="start_date" value="<?php echo $trip['start_date']; ?>" required>

        <label>رابط أو مسار الصورة:</label>
        <input type="text" name="image" value="<?php echo htmlspecialchars($trip['image']); ?>" required>

        <button type="submit">حفظ التعديلات ✏️</button>
    </form>
    <br>
    <a href="index.php">العودة للرحلات</a>
</div>
<footer style="background-color: #2c3e50; color: #ecf0f1; text-align: center; padding: 15px; margin-top: 40px; font-size: 14px; border-top: 3px solid #3498db;">
    <p style="margin: 0;">
        جميع الحقوق محفوظة &copy; <?php echo date("Y"); ?> | تم التطوير بواسطة 
        <span style="color: #3498db; font-weight: bold;">Adem Hechmi</span>
    </p>
</body>
</html>