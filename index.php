<?php 
session_start(); 
include 'db.php'; 

// التحقق مما إذا كان هناك بحث جارٍ
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
if (!empty($search)) {
    $sql = "SELECT * FROM trips WHERE destination LIKE '%$search%' OR title LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM trips";
}
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>وكالة السفر - الرحلات المتاحة</title>
    <style>
        body { font-family: Tahoma, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .header { background-color: #2c3e50; color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header h1 { margin: 0; font-size: 24px; }
        .admin-nav a { background-color: #27ae60; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; margin-left: 10px; font-size: 14px; font-weight: bold; }
        .admin-nav a.logout { background-color: #e74c3c; }
        .admin-nav a.bookings { background-color: #e67e22; }
        
        .search-container { text-align: center; margin: 25px 0; }
        .search-container input { padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px; font-size: 15px; }
        .search-container button { padding: 10px 20px; background-color: #2980b9; color: white; border: none; border-radius: 4px; font-size: 15px; cursor: pointer; }

        .trips-container { max-width: 1100px; margin: 0 auto 30px auto; padding: 0 20px; }
        .trips-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .card-content { padding: 15px; flex-grow: 1; }
        .card-content h3 { margin-top: 0; color: #2980b9; }
        .price { font-weight: bold; color: #e74c3c; font-size: 18px; margin: 10px 0; }
        .btn-book { display: block; text-align: center; background-color: #2980b9; color: white; padding: 10px; text-decoration: none; border-radius: 4px; font-weight: bold; margin: 15px; }
        .card-actions { text-align: center; padding: 0 15px 15px 15px; }
        .btn-edit { background-color: #f39c12; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 13px; margin-left: 5px; }
        .btn-delete { background-color: #e74c3c; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>

<div class="header">
    <h1>✈️ وكالة السفر والسياحة</h1>
    <div class="admin-nav">
        <?php if(isset($_SESSION['is_admin'])): ?>
            <a href="add_trip.php">➕ إضافة رحلة</a>
            <a href="admin_bookings.php" class="bookings">📋 الحجوزات</a>
            <a href="logout.php" class="logout">خروج 🚪</a>
        <?php else: ?>
            <a href="login.php" style="background-color: #3498db;">دخول المدير 🔐</a>
        <?php endif; ?>
    </div>
</div>

<!-- صندوق البحث -->
<!-- صندوق البحث المعدل -->
<div class="search-container">
    <form method="GET" style="display: flex; justify-content: center; gap: 10px; max-width: 500px; margin: 0 auto;">
        <input type="text" name="search" placeholder="ابحث عن وجهة أو رحلة..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 15px;">
        <button type="submit" style="padding: 10px 20px; background-color: #2980b9; color: white; border: none; border-radius: 4px; font-size: 15px; cursor: pointer;">بحث 🔍</button>
        <?php if(!empty($_GET['search'])): ?>
            <a href="index.php" style="padding: 10px; background-color: #e74c3c; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">إلغاء ❌</a>
        <?php endif; ?>
    </form>
</div>

<div class="trips-container">
    <div class="trips-grid">
    <?php 
    if ($res && $res->num_rows > 0) {
        while($row = $res->fetch_assoc()): 
    ?>
        <div class="card">
            <img src="<?=$row['image']?>" alt="صورة الرحلة">
            <div class="card-content">
                <h3><?=$row['title']?></h3>
                <p><strong>الوجهة:</strong> <?=$row['destination']?></p>
                <p><strong>التاريخ:</strong> <?=$row['start_date']?></p>
                <div class="price"><?=$row['price']?> د.ت</div>
            </div>
            <a href="book.php?trip_id=<?=$row['id']?>" class="btn-book">احجز تذكرتك الآن 🎟️</a>
            
            <?php if(isset($_SESSION['is_admin'])): ?>
                <div class="card-actions">
                    <a href="edit_trip.php?id=<?=$row['id']?>" class="btn-edit">تعديل ✏️</a>
                    <a href="delete_trip.php?id=<?=$row['id']?>" class="btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف 🗑️</a>
                </div>
            <?php endif; ?>
        </div>
    <?php 
        endwhile; 
    } else {
        echo "<p style='text-align:center; grid-column:1/-1; color:#7f8c8d;'>عذراً، لمار نتطابق أي رحلة مع بحثك.</p>";
    }
    ?>
    </div>
</div>
<footer style="background-color: #2c3e50; color: #ecf0f1; text-align: center; padding: 15px; margin-top: 40px; font-size: 14px; border-top: 3px solid #3498db;">
    <p style="margin: 0;">
        جميع الحقوق محفوظة &copy; <?php echo date("Y"); ?> | تم التطوير بواسطة 
        <span style="color: #3498db; font-weight: bold;">Adem Hechmi</span>
    </p>
</footer>
</body>

</html>