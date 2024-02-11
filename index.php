<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";

$con = mysqli_connect($servername, $username, $password, $database);

$error = "";

// Submits the login info for processing
if (isset($_POST['submit'])) {
    $username1 = $_POST['username'];
    $password = $_POST['password'];
    $userRole = ""; // Not yet assigned because we get the role from the logged-in user information

    // Check the database using the login info we got earlier from the user
    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM `accounts` WHERE `username` = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username1);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // Verify the password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Password is correct, login successful
            $userId = $row['id'];
            $userRole = $row['role']; // Get the user's role
            $_SESSION['userRole'] = $userRole; // Place it in session so that we can use it on other pages
            $_SESSION['id'] = $userId; // Place it in session so that we can use it on other pages
            header('location: display.php'); // Redirect to the next page
            exit;
        } else {
            // Prompt for invalid login
            $error = "Invalid username or password";
        }
    } else {
        // Prompt for invalid login
        $error = "Invalid username or password";
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('images/wallpaper.jpg');
            /* Specify the path to your background image */
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            /* Adjust the opacity (0.5) to make it darker or lighter */
            z-index: -1;
        }

        .signup-btn {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #fff;
        }

        .signup-btn:hover {
            background-color: #138496;
            border-color: #138496;
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            /* Add the shadow effect */
        }
    </style>
</head>

<body>

    <div id="alertContainer"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <!-- php code for prompt of log in >insert Let me in Meme< -->
                            <?php if ($error): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Enter your username">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter your password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Show Password">
                                            <i class="fa fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
                            <!-- Forgot Password Link -->
                            <p class="mt-3 text-center"><a href="#" data-toggle="modal"
                                    data-target="#forgotPasswordModal">Forgot Password?</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog"
        aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Enter your email address and token below, and we'll send you a link to reset your password.</p>
                    <form method="post" action="forgot_password.php">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your email address">
                        </div>
                        <div class="form-group">
                            <label for="token">Token</label>
                            <input type="text" class="form-control" id="token" name="token"
                                placeholder="Enter your token">
                        </div>
                        <button type="submit" class="btn btn-primary" name="resetPassword">Send Reset Link</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script>
        $(function () {
            // Toggle password visibility
            $('.toggle-password').click(function () {
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
                var input = $($(this).parent().prev());
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                } else {
                    input.attr('type', 'password');
                }
            });
        });
    </script>
    <!-- JavaScript code for success and failure alerts -->
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
                }, 2000);
                                                        });
    <?php } ?>
    </script>
</body>

</html>