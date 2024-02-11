<?php
session_start();
$userRole = $_SESSION['userRole'];
$userId = $_SESSION['id'];
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

$sql = "SELECT * FROM `accounts` WHERE `id` = '$userId'";
$result = mysqli_query($con, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $username1 = $row['username'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $userRole1 = $row['role'];
        $email = $row['email'];
        $token = $row['token'];

    }
}
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">

    <style>
        tr {
            height: 80px;
        }

        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        .logo-img {
            height: 50px;
            width: auto;
            /* This will scale the logo proportionally based on the height */
        }

        .floating-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin-bottom: 30px;
        }

        /* Add custom CSS for edit mode */
        .edit-mode .form-control[readonly] {
            background-color: #fff;
        }

        .edit-mode .btn-save,
        .view-mode .btn-edit {
            display: none;
        }

        .edit-mode .btn-edit,
        .view-mode .btn-save {
            display: inline-block;
        }

        .container {
            padding-top: 50px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }

        .overlay .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: auto;
            height: auto;
        }

        .overlay .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        tr {
            height: 80px;
        }

        td,
        th {
            text-align: center;
            vertical-align: middle;
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
                <?php if ($userRole == "Admin") { ?>
                    <li class="nav-item">
                        <a class="nav-link " href="revenue_graph.php">Revenue</a>
                    </li>
                    <?php
                } ?>
                <li class="nav-item">
                    <a class="nav-link disabled" href="account_user.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Add the navigation bar and other content -->

    <!-- Center the container on the page -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <!-- Left Column: User Information -->
            <div class="col-md-6">
                <!-- User Information Card -->
                <div class="floating-card">
                    <h3 class="mb-4">User Information</h3>
                    <form action="update_user_info.php" method="POST">
                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" class="form-control info-field" id="firstName" name="newFirstName"
                                value="<?php echo $firstName; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" class="form-control info-field" id="lastName" name="newLastName"
                                value="<?php echo $lastName; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control info-field" id="username"
                                value="<?php echo $username1; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <input type="text" class="form-control info-field" id="role"
                                value="<?php echo $userRole1; ?>" readonly>
                        </div>
                        <!-- New fields for email and token -->
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control info-field" id="email" name="newEmail"
                                value="<?php echo $email; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="token">Token:</label>
                            <div class="input-group">
                                <input type="password" class="form-control info-field" id="token" name="newToken"
                                    value="<?php echo $token; ?>" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary show-token-btn" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add a hidden input to indicate user information update -->
                        <input type="hidden" name="updateUserInfo" value="1">
                        <button type="submit" class="btn btn-primary btn-save-info mt-3">Save</button>
                    </form>
                </div>



                <?php if ($userRole == "Admin") { ?>
                    <div class="col-md-14">
                        <div class="floating-card">
                            <h3 class="mb-6" style="text-align:center">Manager</h3>
                            <p class="mb-2" style="text-align:center">Manage User accounts and Archived Products</p>
                            <div class="text-center"> <!-- Center the buttons using "text-center" class -->
                                <div class="d-inline-block mr-2">
                                    <!-- Use "d-inline-block" class to display buttons side by side -->
                                    <form method="GET" action="account.php">
                                        <button class="btn btn-primary btn-manage-accounts">Manage Accounts</button>
                                    </form>
                                </div>
                                <div class="d-inline-block">
                                    <!-- Use "d-inline-block" class to display buttons side by side -->
                                    <form method="GET" action="archived_product.php">
                                        <button class="btn btn-danger btn-product-bin">Product Bin</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>

            <!-- Right Column: Change Password and Username -->
            <!-- Right Column: Change Password and Username -->
            <div class="col-md-6">

                <!-- Change Username Card -->
                <div class="floating-card mt-4">
                    <h3 class="mb-4">Change Username</h3>
                    <form action="update_username.php" method="POST">
                        <div class="form-group">
                            <label for="newUsername">New Username:</label>
                            <input type="text" class="form-control" id="newUsername" name="newUsername" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Username</button>
                    </form>
                </div>

                <!-- Change Password Card -->
                <div class="floating-card">
                    <h3 class="mb-4">Change Password</h3>
                    <form action="update_password.php" method="POST">
                        <div class="form-group">
                            <label for="currentPassword">Current Password:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                    required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary show-password-btn" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="newPassword"
                                    required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary show-password-btn" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                    required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary show-password-btn" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Password</button>
                    </form>
                </div>




            </div>

        </div>
    </div>




    <!-- Add the necessary Bootstrap JS scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Function to toggle the visibility of the token field
        function toggleTokenVisibility() {
            const tokenField = document.getElementById('token');
            if (tokenField.type === 'password') {
                tokenField.type = 'text';
            } else {
                tokenField.type = 'password';
            }
        }

        // Add event listener to the show password button
        document.querySelector('.show-token-btn').addEventListener('click', toggleTokenVisibility);
    </script>
    <script>
        function togglePasswordVisibility(field) {
            if (field.type === 'password') {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        }

        // Add event listener to the show password buttons
        document.querySelectorAll('.show-password-btn').forEach(button => {
            button.addEventListener('click', function () {
                const passwordField = this.closest('.input-group').querySelector('input[type="password"]');
                togglePasswordVisibility(passwordField);
            });
        });
    </script>

    <script>
        // Add JavaScript to toggle edit mode
        $(document).ready(function () {
            $('.btn-edit').on('click', function () {
                $(this).closest('.floating-card').find('.form-control').removeAttr('readonly');
                $(this).closest('.floating-card').addClass('edit-mode');
                $(this).closest('.floating-card').removeClass('view-mode');
            });

            $('.btn-save').on('click', function () {
                $(this).closest('.floating-card').find('.form-control').attr('readonly', true);
                $(this).closest('.floating-card').addClass('view-mode');
                $(this).closest('.floating-card').removeClass('edit-mode');
            });
        });
    </script>




    <!-- JavaScript code for success and failure alerts -->
    <script>
        function showAlert(message, isSuccess) {
            var alertClass = isSuccess ? 'alert-success' : 'alert-danger';
            var alertDiv = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">')
                .text(message);

            $('#alertContainer').html(alertDiv);
            $('#alertContainer').addClass('show');

            // Countdown to hide the alert
            setTimeout(function () {
                $('#alertContainer').removeClass('show');
            }, 3000);
        }

        $(document).ready(function () {
    <?php if (isset($_GET['success'])) { ?>
                                                    <?php if ($_GET['success'] === 'true') { ?>
                            showAlert("<?php echo urldecode($_GET['message']); ?>", true);
                                                  <?php } elseif ($_GET['success'] === 'false') { ?>
                            showAlert("<?php echo urldecode($_GET['message']); ?>", false);
                                                  <?php } ?>
    <?php } ?>
  });
    </script>

    <!-- JavaScript code for handling the overlay -->
    <script>
        $(document).ready(function () {
            // Show the overlay when the "Manage Accounts" button is clicked
            $('.btn-manage-accounts').click(function () {
                $('.overlay').show();
            });

            // Hide the overlay when the close button or outside the overlay is clicked
            $('.overlay .close-button, .overlay').click(function () {
                $('.overlay').hide();
            });

            // Prevent the click event from propagating to the parent elements when clicking inside the overlay content
            $('.overlay .content').click(function (event) {
                event.stopPropagation();
            });
        });
    </script>

    <footer class="footer">
        <div class="container1">
            <p>&copy;
                <?php echo date('Y'); ?> Wox to Box. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>