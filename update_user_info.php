<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];
    $newFirstName = $_POST['newFirstName'];
    $newLastName = $_POST['newLastName'];
    $newEmail = $_POST['newEmail'];
    $newToken = $_POST['newToken'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "inventory_system";
    $con = mysqli_connect($servername, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Update the user information in the database
    $sqlUpdateUser = "UPDATE accounts SET firstName = '$newFirstName', lastName = '$newLastName', email = '$newEmail', token = '$newToken' WHERE id = '$userId'";

    if (mysqli_query($con, $sqlUpdateUser)) {
        // Update the session variables to reflect the changes
        $_SESSION['firstName'] = $newFirstName;
        $_SESSION['lastName'] = $newLastName;
        $_SESSION['email'] = $newEmail;
        $_SESSION['token'] = $newToken;

        $successMessage = urlencode("User information updated successfully!");
        header("Location: account_user.php?success=true&message=$successMessage");
    } else {
        $errorMessage = urlencode("Failed to update user information. Error: " . mysqli_error($con));
        header("Location: account_user.php?success=false&message=$errorMessage");
    }

    // Close the database connection
    mysqli_close($con);
}
