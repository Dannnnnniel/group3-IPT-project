<?php
    session_start();
    include_once 'database.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];

        $sql = "DELETE FROM employees WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'deleted';
            header('Location: ../dashboard.php');
        } else {
            $_SESSION['status'] = 'error';
            header('Location: ../dashboard.php');
        }
    }

?>