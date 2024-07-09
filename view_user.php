<?php
require '../connection/connection.php';

// Check if ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Initialize variables to store user data
    $firstname = '';
    $lastname = '';
    $email = '';
    $password = '';

    // Fetch user data from database
    $con = crud::connect();
    if ($con !== null) {
        try {
            // Prepare SQL statement to fetch user details
            $sql = "SELECT * FROM crudphp WHERE id = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Fetch the user data
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $firstname = $row['firstname'];
                    $lastname = $row['lastname'];
                    $email = $row['email'];
                    $password = $row['password'];
                } else {
                    $error = "User not found";
                }
            } else {
                $error = "Error fetching user data";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Database connection not established";
    }
} else {
    $error = "Invalid request";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2>User Profile</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Name:</strong> <?php echo $firstname . ' ' . $lastname; ?></h5>
                    <p class="card-text"><strong>Email:</strong> <?php echo $email; ?></p>
                    <p class="card-text"><strong>Password:</strong> <?php echo $password; ?></p>
                    <a href="user.php" class="btn btn-primary">Back to User List</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
