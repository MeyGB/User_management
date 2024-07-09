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

    // Check if form is submitted for update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and validate inputs (you should add more validation as needed)
        $firstname = htmlspecialchars($_POST['firstname']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        // Validate input (you should add more validation as needed)
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            $error = "All fields are required.";
        } else {
            // Update user data in the database
            $con = crud::connect();
            if ($con !== null) {
                try {
                    // Prepare SQL statement to update record
                    $sql = "UPDATE crudphp SET firstname = :firstname, lastname = :lastname, email = :email, password = :password WHERE id = :id";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
                    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                    // Execute the statement
                    if ($stmt->execute()) {
                        // Redirect to user.php after successful update
                        header("Location: user.php");
                        exit();
                    } else {
                        $error = "Error updating record";
                    }
                } catch (PDOException $e) {
                    $error = "Error: " . $e->getMessage();
                }
            } else {
                $error = "Database connection not established";
            }
        }
    } else {
        // Fetch existing user data for the given ID
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
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2>Edit User</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>">
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="user.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
