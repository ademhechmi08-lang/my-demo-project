<?php
include 'db.php';

if (!isset($_GET['trip_id'])) {
    header("Location: index.php");
    exit;
}

$trip_id = intval($_GET['trip_id']);
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $seats = intval($_POST['seats']);
    $payment_method = $_POST['payment_method'];
    
    // تحديد حالة الدفع بناءً على اختياره
    $payment_status = ($payment_method === 'online') ? 'مدفوع إلكترونياً 💳' : 'قيد الدفع (عند الوصول) 💵';

    $stmt = $conn->prepare("INSERT INTO bookings (trip_id, client_name, client_email, client_phone, seats, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $trip_id, $name, $email, $phone, $seats, $payment_status);
    
    if ($stmt->execute()) {
        $booking_id = $stmt->insert_id;
        $success_message = "🎉 تم الحجز بنجاح! رقم التذكرة المرجعي: <strong>#TICKET-$booking_id</strong> | حالة الدفع: <strong>$payment_status</strong>";
    } else {
        $error_message = "حدث خطأ أثناء إتمام الحجز.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تأكيد الحجز والدفع</title>
    <style>
        body { font-family: Tahoma, sans-serif; background-color: #f4f7f6; padding: 40px; }
        .box { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #27ae60; color: white; padding: 12px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background-color: #219653; }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="box">
    <h2>حجز وتأكيد الدفع ✈️</h2>
    
    <?php if(!empty($success_message)): ?>
        <div class="alert-success"><?= $success_message ?></div>
    <?php endif; ?>
    
    <?php if(!empty($error_message)): ?>
        <div class="alert-error"><?= $error_message ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>الاسم الكامل:</label>
        <input type="text" name="name" required>
        
        <label>البريد الإلكتروني:</label>
        <input type="email" name="email" required>
        
        <label>رقم الهاتف:</label>
        <input type="text" name="phone" required>
        
        <label>عدد المقاعد:</label>
        <input type="number" name="seats" min="1" value="1" required>

        <label>طريقة الدفع:</label>
        <select name="payment_method" required>
            <option value="online">دفع إلكتروني بالبطاقة البنكية 💳</option>
            <option value="cash">الدفع النقدي عند الوصول 💵</option>
        </select>
        
        <button type="submit">إتمام الحجز والدفع 🎟️</button>
    </form>
    <br>
    <a href="index.php" style="color: #2980b9; text-decoration: none;">🏠 العودة للرئيسية</a>
</div>
<footer style="background-color: #2c3e50; color: #ecf0f1; text-align: center; padding: 15px; margin-top: 40px; font-size: 14px; border-top: 3px solid #3498db;">
    <p style="margin: 0;">
        جميع الحقوق محفوظة &copy; <?php echo date("Y"); ?> | تم التطوير بواسطة 
        <span style="color: #3498db; font-weight: bold;">Adem Hechmi</span>
    </p>
</body>
</html>