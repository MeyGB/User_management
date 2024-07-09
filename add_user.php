<?php
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate firstname
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = 'First Name is required.';
    } else {
        $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    }

    // Validate lastname
    if (empty($_POST['lastname'])) {
        $errors['lastname'] = 'Last Name is required.';
    } else {
        $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    }

    // Validate email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    }

    // Validate password (you should hash the password before storing it in the database)
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required.';
    } else {
        $password = $_POST['password']; // Hash password securely before storing in DB
    }

    // If there are no errors, proceed to insert into database
    if (empty($errors)) {
        require('../connection/connection.php'); // Include your database connection script

        try {
            // Establish database connection
            $con = crud::connect();

            // Prepare SQL statement
            $sql = "INSERT INTO crudphp (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)";
            $stmt = $con->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // Remember to hash password

            // Execute query
            if ($stmt->execute()) {
                echo '<div class="alert alert-success mt-3" role="alert">New user added successfully.</div>';
            } else {
                echo '<div class="alert alert-danger mt-3" role="alert">Error adding new user.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger mt-3" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add New User</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        <div class="container my-5">
            <h2>Add New User</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
                    <?php if(isset($errors['firstname'])): ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['firstname']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                    <?php if(isset($errors['lastname'])): ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['lastname']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <?php if(isset($errors['email'])): ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <?php if(isset($errors['password'])): ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['password']; ?></div>
                    <?php endif; ?>
                </div>
                <a href="user.php" class="btn btn-primary">Back</a> 
                <button type="submit" class="btn btn-success" name="submit">Submit</button>
            </form>
        </div>

        <!-- Bootstrap JS bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
    </html>
