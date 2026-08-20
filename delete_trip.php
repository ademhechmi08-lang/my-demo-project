<?php
session_start();
if (!isset($_SESSION['is_admin'])) { header("Location: login.php"); exit; }
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM trips WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit;
?>