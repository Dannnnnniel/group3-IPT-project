<?php
session_start();
include('database.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $position = $_POST['position'];
    $sex = $_POST['sex'];

    // Insert into database
    $sql = "INSERT INTO barangay_official (full_name, age, position, sex) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $full_name, $age, $position, $sex);

    if ($stmt->execute()) {
        $_SESSION['status'] = 'created';
    } else {
        $_SESSION['status'] = 'error';
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the dashboard page
    header('Location: ../dashboard.php');
    exit();
} else {
    // If the form is not submitted, redirect to the dashboard
    header('Location: ../dashboard.php');
    exit();
}
?>