<?php
// Ensure an ID parameter is passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Include database connection
    require('../connection/connection.php');
    $con = crud::connect();

    if ($con !== null) {
        try {
            // Prepare SQL statement to delete record
            $sql = "DELETE FROM crudphp WHERE id = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect back to user management page after successful deletion
                header("Location: user.php");
                exit();
            } else {
                echo "Error deleting record";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Database connection not established";
    }
} else {
    echo "Invalid request";
}

