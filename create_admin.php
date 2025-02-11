<?php
include 'includes/db_connect.php';

$username = 'admin';
$password = 'Jihed**25122558';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL statement
$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");

// Execute the statement
try {
    $stmt->execute([$username, $hashed_password]);
    echo "Admin user created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

