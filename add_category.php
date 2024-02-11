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

if (isset($_POST['categoryName'])) {
    $categoryName = trim($_POST['categoryName']); // Trim the category name to remove leading and trailing spaces

    // Check if the category already exists
    $sql = "SELECT * FROM ingredient_category WHERE category = '$categoryName'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        header("Location: category.php?success=false&message=Category already exists.");
        exit();
    }

    // Insert the category into the database
    $sql = "INSERT INTO ingredient_category (category) VALUES ('$categoryName')";
    if (mysqli_query($con, $sql)) {
        header("Location: category.php?success=true&message=Category added successfully.");
        exit();
    } else {
        header("Location: category.php?success=false&message=Failed to add category.");
        exit();
    }
} else {
    header("Location: category.php");
    exit();
}
?>
