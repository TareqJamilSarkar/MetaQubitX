<?php
require 'db.php';

// Get POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

if ($name && $email && $phone && $subject && $message) {
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$name, $email, $phone, $subject, $message]);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Contact submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit contact.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Please fill out all fields.']);
}
?>