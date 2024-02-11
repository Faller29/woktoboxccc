<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";

$con = mysqli_connect($servername, $username, $password, $database);

$id = $_GET['updateid'];
$sql = "SELECT * FROM `accounts` WHERE id=$id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$firstName = $row['firstName'];
$lastName = $row['lastName'];
$password1 = $row['password'];
$username1 = $row['username'];
$role = $row['role'];

if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password2 = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $username2 = $_POST['username'];
    $role = $_POST['role'];
    $success = false;
    $sql1 = "SELECT * FROM `accounts` WHERE username='$username1'";
    $result1 = mysqli_query($con, $sql1);

    if (mysqli_num_rows($result1) > 0) {
        // Check if username exists in the database
        $myName = $row['username'];

        if (mysqli_num_rows($result1) == 1 && $username1 != $myName) {
            $error = "Username is already taken.";
        } else {
            // Check if the user entered a new password
            if (!empty($password2)) {
                // Check if passwords match
                if ($password2 !== $confirmPassword) {
                    $error = "Passwords do not match.";
                } else {
                    // Hash the new password
                    $hashedPassword = password_hash($password2, PASSWORD_DEFAULT);
                }
            } else {
                // Keep the old hashed password if the user didn't enter a new one
                $hashedPassword = $password1;
            }

            if (!isset($error)) {
                // Update the database
                $sql = "UPDATE `accounts` SET id='$id', firstName='$firstName', lastName='$lastName', username ='$username2', password='$hashedPassword', role='$role' WHERE id=$id";
                $result2 = mysqli_query($con, $sql);
                $username1 = $username2;
                $password1 = $username2;

                if ($result2) {
                    $successMessage = urlencode("Account updated successfully!");
                    header("Location: account.php?success=true&message=$successMessage");
                    exit;
                } else {
                    $errorMessage = urlencode("Failed to update account. Please try again later.");
                    header("Location: account.php?success=false&message=$errorMessage");
                    die(mysqli_error($con));
                }
            }
        }
    }
}
?>



<!doctype html>
<html lang="en">

<head>

    <title>Update Account</title>

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

        .shadow-container {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            margin: auto;
            width: 50%;
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

    <div class="container my-5">


        <div class="shadow-container">
            <!-- Add Account Heading -->
            <div class="container text-center mt-4">
                <h4 class="center-heading">Add Account</h4>
            </div>
            <form method="post">


                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" placeholder="Enter your First Name" name="firstName"
                        autocomplete="off" value="<?php echo $firstName; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" placeholder="Enter your Last Name" name="lastName"
                        autocomplete="off" value="<?php echo $lastName; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" placeholder="Enter your name" name="username"
                        autocomplete="off" required value="<?php echo $username1; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Enter your password" name="password"
                            autocomplete="off" required value="" required>
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" style="cursor: pointer;">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Confirm Password"
                            name="confirmPassword" autocomplete="off" required value="" required>
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" style="cursor: pointer;">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                    <!-- Error message for password mismatch -->
                    <div class="invalid-feedback" id="passwordMismatchError" style="display: none;">
                        Passwords do not match.
                    </div>
                </div>
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                        id="roleButton">
                        <?php echo $role; ?>
                    </button>
                    <input type="hidden" id="role" name="role" value="<?php echo $role; ?>">
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" data-value="Admin">Admin</a>
                        <a class="dropdown-item" href="#" data-value="User">User</a>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary my-5" name="submit">Update</button>
                    <button class="btn btn-danger my-5">
                        <a href="account.php" class="text-light" style="text-decoration:none;">Back</a>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>


    <script>
        // Function to check if the passwords match on form submission
        function checkPasswordMatch() {
            var passwordInput = document.querySelector('input[name="password"]');
            var confirmPasswordInput = document.querySelector('input[name="confirmPassword"]');
            var passwordMismatchError = document.getElementById('passwordMismatchError');

            if (passwordInput.value !== confirmPasswordInput.value) {
                // Show the error message
                passwordMismatchError.style.display = 'block';
                return false;
            } else {
                // Hide the error message
                passwordMismatchError.style.display = 'none';
                return true;
            }
        }

        // Attach event listener to form submission
        document.querySelector('form').addEventListener('submit', function (event) {
            if (!checkPasswordMatch()) {
                // Prevent form submission if passwords don't match
                event.preventDefault();
            }
        });
    </script>
    <script>
        // Update button text and hidden input field with selected role
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
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });
    </script>
    <script>
        // Check if the URL contains success parameter
        <?php if (isset($_GET['success'])) { ?>
            $(document).ready(function () {
                <?php if ($_GET['success'] === 'true') { ?>
                    // Create the success alert
                    var successAlert = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text("<?php echo urldecode($_GET['message']); ?>");

                    // Show the success alert in the alertContainer element
                    $('#alertContainer').html(successAlert);
                    $('#alertContainer').addClass('show');
                                                        <?php } elseif ($_GET['success'] === 'false') { ?>
                                                                    // Create the error alert
                                                                    var errorAlert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                    .text("<?php echo urldecode($_GET['message']); ?>");

                // Show the error alert in the alertContainer element
                $('#alertContainer').html(errorAlert);
                $('#alertContainer').addClass('show');
                    <?php } ?>

                // Countdown to hide the alert
                setTimeout(function () {
                    $('#alertContainer').removeClass('show');
                }, 3000);
                                                    });
    <?php } ?>
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