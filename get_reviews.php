<?php
require 'db.php';
$stmt = $pdo->query("SELECT name, email, message, rating FROM reviews ORDER BY created_at DESC LIMIT 30");
echo json_encode($stmt->fetchAll());
?>