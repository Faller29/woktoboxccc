<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate that the new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        $errorMessage = urlencode("New password and confirm password do not match.");
        header("Location: account_user.php?success=false&message=$errorMessage");
        exit;
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "inventory_system";
    $con = mysqli_connect($servername, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch the current user's data
    $sqlFetchUser = "SELECT * FROM accounts WHERE id = '$userId'";
    $resultFetchUser = mysqli_query($con, $sqlFetchUser);

    if ($resultFetchUser) {
        $userData = mysqli_fetch_assoc($resultFetchUser);
        $hashedCurrentPassword = $userData['password'];

        // Verify the current password

        if (password_verify($currentPassword, $hashedCurrentPassword)) {
            // Debugging statement
            echo "Password verification successful.";

            // Hash the new password before saving it to the database
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $sqlUpdatePassword = "UPDATE accounts SET password = '$hashedNewPassword' WHERE id = '$userId'";

            if (mysqli_query($con, $sqlUpdatePassword)) {
                $successMessage = urlencode("Password updated successfully!");
                header("Location: account_user.php?success=true&message=$successMessage");
            } else {
                $errorMessage = urlencode("Failed to update password. Error: " . mysqli_error($con));
                header("Location: account_user.php?success=false&message=$errorMessage");
            }
        } else {
            // Debugging statement
            echo "Password verification failed.";

            $errorMessage = urlencode("Current password is incorrect.");
            header("Location: account_user.php?success=false&message=$errorMessage");
        }

    } else {
        $errorMessage = urlencode("Failed to fetch user data. Error: " . mysqli_error($con));
        header("Location: account_user.php?success=false&message=$errorMessage");
    }

    // Close the database connection
    mysqli_close($con);
}