<?php
session_start();
$userRole = $_SESSION['userRole'];
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $_SESSION['firstName'] = $_POST['firstName'];
    $_SESSION['lastName'] = $_POST['lastName'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['role'] = $_POST['role'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['confirmPassword'] = $_POST['confirmPassword'];

    // Checks if the user has selected a role
    if ($_SESSION['role'] === '') {
        $error = "Please select a role.";
    } else {
        if ($_POST['password'] !== $_POST['confirmPassword']) {
            $error = "Passwords do not match.";
        } else {
            // Check if the username already exists in the database
            $username = $_SESSION['username'];
            $stmt = mysqli_prepare($con, "SELECT * FROM `accounts` WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = "Username is already taken.";
            } else {
                // Hash the password before inserting into the database
                $hashedPassword = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

                // Insert into the database using prepared statements
                $sql = "INSERT INTO `accounts` (firstName, username, role, lastName, password) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "sssss", $_SESSION['firstName'], $_SESSION['username'], $_SESSION['role'], $_SESSION['lastName'], $hashedPassword);

                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    // Clear the session variables after successful insertion
                    unset($_SESSION['firstName']);
                    unset($_SESSION['lastName']);
                    unset($_SESSION['username']);
                    unset($_SESSION['role']);
                    unset($_SESSION['password']);
                    unset($_SESSION['confirmPassword']);

                    $successMessage = urlencode("Account added successfully!");
                    header("Location: account.php?success=true&message=$successMessage");
                    exit;
                } else {
                    $warningMessage = urlencode("Account creation failed");
                    header("Location: display.php?success=false&warning=$warningMessage");
                    exit;
                }
            }
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>


    <title>Add Account</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <style>
        .logo-img {
            height: 50px;
            width: auto;
            /* This will scale the logo proportionally based on the height */
        }

        /* Add custom styles for the shadow effect */
        .shadow-container {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            /* Adjust the shadow properties as needed */
            border-radius: 10px;
            /* Add rounded corners to the container */
            padding: 20px;
            /* Add some padding to the container */
            background-color: #fff;
            /* Set the background color to white */
            width: 50%;
            margin: auto;
        }

        body {
            position: relative;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 100px;
        }


        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #C72225;
            color: #FFFFFF;
            text-align: center;
            padding: 0px 0px;
        }
    </style>
</head>

<body>
    <!-- HTML structure for the alert container -->
    <div id="alertContainer"></div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand navbar-logo" href="display.php"><img src="images/logo.png" alt="Logo"
                class="logo-img">Wox to
            Box</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar"
            aria-expanded="true" aria-controls="collapseOne">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="display.php">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="inventory.php">Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="revenue_graph.php">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="account_user.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>
    <br>
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger" style="position: fixed; top: 10%; right: 1%; z-index: 9999;">
            <?php echo $error; ?>
        </div>
    <?php } ?>
    <div class="container my-5">
        <div class="shadow-container">
            <!-- Add Account Heading -->
            <div class="container text-center mt-4">
                <h4 class="center-heading">Add Account</h4>
            </div>
            <form method="post">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <!-- here, instead of variables, we use session so that the user will not have to retype if the fill up has error like username taken -->
                    <input type="text" class="form-control" placeholder="Enter your first name" name="firstName"
                        autocomplete="off" required
                        value="<?php echo isset($_SESSION['firstName']) ? $_SESSION['firstName'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" placeholder="Enter your Last Name" name="lastName"
                        autocomplete="off" required
                        value="<?php echo isset($_SESSION['lastName']) ? $_SESSION['lastName'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" placeholder="Enter your name" name="username"
                        autocomplete="off" required
                        value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Enter your password" name="password"
                            autocomplete="off" required
                            value="<?php echo isset($_SESSION['password']) ? $_SESSION['password'] : ''; ?>">
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" style="cursor: pointer;"><i
                                    class="fa fa-eye-slash"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Confirm your password"
                            name="confirmPassword" autocomplete="off" required
                            value="<?php echo isset($_SESSION['confirmPassword']) ? $_SESSION['confirmPassword'] : ''; ?>">
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" style="cursor: pointer;"><i
                                    class="fa fa-eye-slash"></i></span>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                        id="roleButton">
                        Select Role
                    </button>
                    <input type="hidden" id="role" name="role">
                    <!-- Hidden input field to store the selected role -->
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" data-value="Admin" selected>Admin</a>
                        <a class="dropdown-item" href="#" data-value="User">User</a>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary my-5" name="submit">Submit</button>
                    <button class="btn btn-danger my-5"><a href="account.php" class="text-light"
                            style="text-decoration:none;">Back</a></button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>

    <script>
        // Update button text and hidden input field with selected role huh ano daw? basically yung selected role na dropdown mapapalitan lang ng naselect like Admin or user
        $(".dropdown-item").click(function () {
            var role = $(this).data("value");
            $("#roleButton").text(role);
            $("#role").val(role);
        });

        // Toggle password visibility 
        $(".toggle-password").click(function () {
            var input = $(this).closest(".input-group").find("input");
            var icon = $(this).find("i");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye-slash");
                icon.addClass("fa-eye");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye");
                icon.addClass("fa-eye-slash");
            }
        });
    </script>
    <footer class="footer">
        <div class="container">
            <p>&copy;
                <?php echo date('Y'); ?> Wox to Box. All rights reserved.
            </p>
        </div>
    </footer>
</body>

</html>