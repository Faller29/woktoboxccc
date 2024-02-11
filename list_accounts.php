<!DOCTYPE html>
<html>

<head>
    <title>List of Accounts</title>
    <!-- Add the Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>List of Accounts</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
            <?php
        // Connect to the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "inventory_system";

        $con = mysqli_connect($servername, $username, $password, $database);

        // Retrieve the list of accounts from the database
        $query = "SELECT * FROM account";
        $result = mysqli_query($con, $query);

        // Display the accounts in a table
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['FirstName'] . ' ' . $row['lastName'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['role'] . '</td>';
            echo '</tr>';
        }
        ?>
            </tbody>
        </table>
    </div>

    <!-- Add the Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>
