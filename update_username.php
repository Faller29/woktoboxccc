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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the current user's ID from the session
    $userId = $_SESSION["id"];

    // Get the new username from the form data
    $newUsername = $_POST["newUsername"];

    // Check if the new username is already taken
    $sql = "SELECT * FROM accounts WHERE username = '$newUsername' AND id != '$userId'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        header("Location: account_user.php?success=false&message=" . urlencode("Username already taken"));
        exit;
    }

    // Update the user's username
    $sql = "UPDATE accounts SET username = '$newUsername' WHERE id = '$userId'";
    if (mysqli_query($con, $sql)) {
        header("Location: account_user.php?success=true&message=" . urlencode("Username updated successfully"));
        exit;
    } else {
        header("Location: account_user.php?success=false&message=" . urlencode("Error updating username"));
        exit;
    }
}
?>
