<?php
// Start the session (if not started already)
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['token'])) {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inventory_system";

    // Get the email and token from the form submission
    $email = $_POST['email'];
    $token = $_POST['token'];

    // Create a new connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind a statement to select the user data from the database
    $stmt = $conn->prepare("SELECT id, role FROM accounts WHERE email = ? AND token = ?");
    $stmt->bind_param("si", $email, $token);
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($userId, $userRole);

    // Fetch the data
    if ($stmt->fetch()) {
        // Set the user role in the session
        $_SESSION['userRole'] = $userRole;
        header("Location: display.php"); 
        exit();
    } else {
        $errorMessage = urlencode("Invalid Email or Token!");
        header("Location: login.php?success=false&message=$errorMessage");
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
