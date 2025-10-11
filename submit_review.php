<?php
require 'db.php'; // Make sure db.php is in the same directory

// Get POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';
$rating = $_POST['rating'] ?? 0;

if ($name && $email && $message && $rating) {
    $stmt = $pdo->prepare("INSERT INTO reviews (name, email, message, rating) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$name, $email, $message, $rating]);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit review.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Please fill out all fields.']);
}
?>