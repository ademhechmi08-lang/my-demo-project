<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// معالجة حذف الحجز إذا تم الضغط على زر الحذف
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $del_stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $del_stmt->bind_param("i", $del_id);
    $del_stmt->execute();
    $del_stmt->close();
    header("Location: admin_bookings.php");
    exit;
}

$sql = "SELECT bookings.*, trips.title AS trip_title, trips.destination 
        FROM bookings 
        JOIN trips ON bookings.trip_id = trips.id 
        ORDER BY bookings.booking_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة حجوزات العملاء</title>
    <style>
        body { font-family: Tahoma, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #2c3e50; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .nav-links { margin-bottom: 20px; text-align: left; }
        .nav-links a { background-color: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .btn-del { background-color: #e74c3c; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-links">
        <a href="index.php">🏠 العودة للموقع</a>
    </div>

    <h2>📋 إدارة ومتابعة حجوزات العملاء</h2>

    <table>
        <thead>
            <tr>
                <th>رقم التذكرة</th>
                <th>اسم العميل</th>
                <th>البريد</th>
                <th>الهاتف</th>
                <th>الرحلة</th>
                <th>المقاعد</th>
                <th>التاريخ</th>
                <th>حالة الدفع</th>
                <th>إجراءات</th>
            
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
              echo "<tr>";
echo '<td><strong style="direction: ltr; unicode-bidi: bidi-override; white-space: nowrap; display: inline-block;">#TICKET-' . $row['id'] . '</strong></td>';
echo "<td>" . $row['client_name'] . "</td>";
echo "<td>" . $row['client_email'] . "</td>";
echo "<td>" . $row['client_phone'] . "</td>";
echo "<td>" . $row['trip_title'] . " (" . $row['destination'] . ")</td>";
echo "<td>" . $row['seats'] . "</td>";
echo "<td>" . $row['booking_date'] . "</td>";
echo "<td>" . $row['payment_status'] . "</td>";
echo "<td style='text-align: center;'><a href='admin_bookings.php?delete_id=" . $row['id'] . "' style='background-color: #e74c3c; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; display: inline-block;' onclick=\"return confirm('هل أنت متأكد من حذف هذا الحجز؟');\">حذف 🗑️</a></td>";
echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align: center; color: #7f8c8d;'>لا توجد حجوزات مسجلة حالياً.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
<footer style="background-color: #2c3e50; color: #ecf0f1; text-align: center; padding: 15px; margin-top: 40px; font-size: 14px; border-top: 3px solid #3498db;">
    <p style="margin: 0;">
        جميع الحقوق محفوظة &copy; <?php echo date("Y"); ?> | تم التطوير بواسطة 
        <span style="color: #3498db; font-weight: bold;">Adem Hechmi</span>
    </p>
</body>
</html>