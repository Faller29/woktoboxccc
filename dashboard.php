<?php

session_start();

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
    <title>Product Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">

    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; /* Light Gray background */
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 30px;
            height: 350px;
        }

        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 250px;
            cursor: pointer;
            transition: background-color 0.3s;
            background-color: #fff; /* White background */
            text-align: center;
            color: #333; /* Text color */
        }

        .dashboard-card h2 {
            margin: 0;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .dashboard-card p {
            margin:0 0;
            font-size: 18px;
        }

        /* Custom colors for each dashboard card based on the title */
        .dashboard-card.product-card {
        }

        .dashboard-card.ingredient-card {
        }

        .dashboard-card.low-ingredient-card {
        }

        /* Hover effect */
        .dashboard-card:hover {
            background-color: #f8f9fa; /* Light Gray on hover */
            opacity: 0.9; /* Dim the background color on hover */
            transform: scale(1.02); /* Slight scale up on hover */
        }
        .logo-img {
            height: 50px;
            width: auto;
            /* This will scale the logo proportionally based on the height */
        }
        .container{
            padding-top: 100px;
        }
        .title{
            height: 40px;
            width: 100%;
            margin: 5px 0px;
            padding-top:10px;
            padding-bottom:15px;
            border-radius: 10px;
            background-color:darkred;
            color: white;
            font-family: sans-serif;
        }
    </style>
</head>

<body>

     <!-- Navbar -->
     <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand navbar-logo" href="display.php"><img src="images/logo.png" alt="Logo" class="logo-img">Wox to
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
                    <a class="nav-link " href="account_user.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>



    <div class="container">
        <div class="dashboard-container row">
            <!-- Product Count -->
            <div class="dashboard-card product-card col-md-4">
                <div class="title"><h5>Product Count</h5></div>
                <p>Total Products: 100</p>
                <p>Active Products: 80</p>
                <p>Inactive Products: 20</p>
            </div>

            <!-- Ingredient Count -->
            <div class="dashboard-card ingredient-card col-md-4">
            <div class="title"><h5>Ingredient</h5></div>
                <p>Total Ingredients: 200</p>
                <p>In Stock: 180</p>
                <p>Out of Stock: 20</p>
            </div>

            <!-- Low Ingredient Count -->
            <div class="dashboard-card low-ingredient-card col-md-4">
            <div class="title"><h5>Ingredient State</h5></div>
                <p>Low Ingredients: 15</p>
                <p>Medium Ingredients: 5</p>
                <p>Critical Ingredients: 2</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- jQuery to handle click interactions -->
    <script>
        $(document).ready(function () {
            $(".dashboard-card").click(function () {
                // Replace this with the URL or action you want to perform on click
                alert("You clicked on " + $(this).find("h2").text());
            });
        });
    </script>

</body>

</html>
