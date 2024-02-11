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

    // Retrieve the product ID from the POST request
    $productId = $_POST['restoreProduct'];

    // Fetch the product details from the "product" table
    $selectStmt = $con->prepare("SELECT * FROM product WHERE product_id = ?");
    $selectStmt->bind_param("i", $productId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the product details
        $productRow = $result->fetch_assoc();
        $productState = 'available';

        // Close the SELECT statement
        $selectStmt->close();

        // Update the product state
        $updateStmt = $con->prepare("UPDATE product SET product_state = ? WHERE product_id = ?");
        $updateStmt->bind_param("si", $productState, $productId);
        $updateStmt->execute();
        $updateStmt->close();

        // Close the connection
        $con->close();

        // Redirect back to the product catalog page with a success message
        $successMessage = urlencode("Product Restored successfully!");
        header("Location: display.php?success=true&message=$successMessage");
        exit;
    }

// Close the database connection
mysqli_close($con);
?>
