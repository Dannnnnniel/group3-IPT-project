<?php
    session_start();
    include_once 'database.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $position = $_POST['position'];
        $sex = $_POST['sex'];

        $sql = "UPDATE employees SET first_name = '$first_name', last_name = '$last_name', position = '$position', address = '$address' WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['status'] = 'updated';
            header('Location: ../dashboard.php');
        } else {
            $_SESSION['status'] = 'error';
        }

        mysqli_close($conn);
        header('Location: ../dashboard.php');
        exit();
    }
?>