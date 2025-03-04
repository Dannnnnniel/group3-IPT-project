<?php
session_start();
include_once 'database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate the ID
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']); 

        
        if ($stmt = $conn->prepare("DELETE FROM barangay_official WHERE id = ?")) {
            $stmt->bind_param("i", $id); // Bind the ID parameter

         
            if ($stmt->execute()) {
                // Set session status for success
                $_SESSION['status'] = 'deleted';
            } else {
            
                $_SESSION['status'] = 'error';
                error_log("Delete Error: " . $stmt->error); 
            }

            // Close the statement
            $stmt->close();
        } else {
        
            $_SESSION['status'] = 'error';
            error_log("Prepare Error: " . $conn->error); 
        }
    } else {
        // Set session status for invalid ID
        $_SESSION['status'] = 'invalid_id';
    }

    // Redirect back to the dashboard after deletion
    header('Location: ../dashboard.php');
    exit();
} else {
    //redirect to the dashboard
    header('Location: ../dashboard.php');
    exit();
}
?>