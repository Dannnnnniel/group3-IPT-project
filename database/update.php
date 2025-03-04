<?php
session_start();
include('database.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];
    $fullName = $_POST['full_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $age = $_POST['age'];
    $position = $_POST['position'];
    $sex = $_POST['sex'];

   
    $sql = "UPDATE barangay_official 
            SET full_name = ?, middle_name = ?, last_name = ?, age = ?, position = ?, sex = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssissi', $fullName, $middleName, $lastName, $age, $position, $sex, $id);
        if ($stmt->execute()) {
            $_SESSION['status'] = 'updated'; 
        } else {
            $_SESSION['status'] = 'error'; 
            error_log("Update Error: " . $stmt->error); 
        }
        $stmt->close();
    } else {
        $_SESSION['status'] = 'error'; 
        error_log("Prepare Error: " . $conn->error); 
    }


    header('Location: ../dashboard.php');
    exit();
}
?>