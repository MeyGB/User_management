<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2>User Management</h2>
        <a class="btn btn-primary" href="add_user.php">New User</a>
    
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Address</th>
                    <th scope="col">Password</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require '../connection/connection.php';
                
                // Check if $con is set and not null
                $con = crud::connect(); // Call the static method to get database connection
                
                if ($con !== null) {
                    // Fetch data from database
                    $sql = "SELECT * FROM crudphp";
                    $stmt = $con->query($sql); // Execute SQL query
                    
                    if ($stmt !== false) {
                        // Check if there are rows returned
                        if ($stmt->rowCount() > 0) {
                            // Output data of each row
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<th scope='row'>" . $row['id'] . "</th>";
                echo "<td>" . $row['firstname'] . "</td>";
                echo "<td>" . $row['lastname'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['password'] . "</td>";
                echo "<td>
                    <a class='btn btn-success' href='view_user.php?id=" . $row['id'] . "'>View</a>
                    <a class='btn btn-primary' href='edit_user.php?id=" . $row['id'] . "'>Edit</a>
                    <a class='btn btn-danger' href='delete_user.php?id=" . $row['id'] . "' onclick='return confirmDelete()'>Delete</a>
                </td>";
                echo "</tr>";
            }
                        } else {
                            echo "<tr><td colspan='6'>No records found</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Error executing query</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Database connection not established</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- JavaScript for confirmation dialog -->
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this record?");
        }
    </script>
</body>
</html>
