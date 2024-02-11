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

        /* Add custom CSS for responsive design */
        @media (max-width: 767px) {

            /* Hide ID no. column on small screens */
            th:nth-child(1),
            td:nth-child(1) {
                display: none;
            }

            /* Adjust font size for small screens */
            th,
            td {
                font-size: 14px;
            }
        }

        .table-shadow {
            border: 1px solid grey;
            border-radius: 10px;
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
                    <a class="nav-link" href="revenue_graph.php">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="account_user.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>
    <br>

    <!-- Display the Account-->


    <div class="container">
        <div class="table-responsive"> <!-- Add the 'table-responsive' class here -->

            <div class="d-flex justify-content-between">

                <button class="btn btn-primary my-5"><a href="add_account.php" class="text-light"
                        style="text-decoration:none;">Add user</a></button>
                <form method="GET" action="account_user.php">
                    <button type="submit" name="goback" class="btn btn-danger my-5">Go back</button>
                </form>
            </div>

            <table class="table  table-shadow">
                <thead>
                    <tr>
                        <th style="display:none;" scope="col">ID no.</th>
                        <th scope="col">Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Role</th>
                        <th scope="col">Operation</th>

                    </tr>
                </thead>
                <tbody>
                    <?php

                    // Retrieve all the accounts from the database
                    $sql = "SELECT * FROM `accounts`";
                    $result = mysqli_query($con, $sql);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['id'];
                            $firstName = $row['firstName'];
                            $lastName = $row['lastName'];
                            $username = $row['username'];
                            $role = $row['role'];

                            // Temporarily unhash the password (not recommended for production use)
                            $unhashedPassword = password_hash($password, PASSWORD_DEFAULT);

                            echo '<tr>
                <th style="display:none;" scope="row">' . $id . '</th>
                <td>' . $firstName . ' ' . $lastName . '</td>';

                            echo '<td>' . $username . '</td>
                
                <td>' . $role . '</td>
                <td>
                    <button class="btn btn-primary"><a href="update_account.php?updateid=' . $id . '" class="text-light" style="text-decoration:none;">Update</a></button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-id="' . $id . '">Delete</button>
                </td>
            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Include Bootstrap's modal JavaScript -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

            <!-- Confirmation delete modal -->
            <!-- nakakatamad mag comment sa html ang haba e -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
                aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <!-- so dito ay confirmation lang kase delete yun so para iwas human error -->
                <div class="modal-dialog" role="document">
                    <!-- dont worry naka hide naman to unless macall using data-target atsaka yung anong id see line 136-->
                    <div class="modal-content"> <!-- id netong dev, not the php -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span> <!-- &times is just a symbol ng html "X" -->
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this record?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                            <!-- if confirm hahanapin neto yungg confirmDeleteBtn jsq-->
                        </div>
                    </div>
                </div>
            </div>


            <!-- JavaScript code for handling delete action and password toggle -->

        </div>
    </div>
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






    <script>
        $(document).ready(function () {
            var deleteId;

            // Triggered when the delete button in the table is clicked
            $('.btn-danger[data-toggle="modal"]').click(function () {
                deleteId = $(this).data('id');  //assign the ID of table to be deleted
            });

            // Triggered when the delete button in the confirmation modal is clicked
            $('#confirmDeleteBtn').click(function () {
                // Redirect to the delete.php file with the specified deleteid parameter
                window.location.href = "delete_account.php?deleteid=" + deleteId;
            });

            // Password toggle to show or hide password
            $('.password-toggle').click(function () {
                var passwordContainer = $(this).closest('.password-container');
                var passwordSpan = passwordContainer.find('.password-hidden');
                var isPasswordVisible = passwordSpan.hasClass('password-visible');

                if (isPasswordVisible) {
                    passwordSpan.text('*'.repeat(passwordSpan.text().length));
                    passwordSpan.removeClass('password-visible');
                    $(this).attr('title', 'Show Password');
                } else {
                    passwordSpan.text(passwordSpan.data('password'));
                    passwordSpan.addClass('password-visible');
                    $(this).attr('title', 'Hide Password');
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
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