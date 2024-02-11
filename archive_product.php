<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inventory_system";

    // Create a new connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve the product ID from the POST request
    $productId = $_POST['product_id'];

    // Fetch the product details from the "product" table
    $selectStmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $selectStmt->bind_param("i", $productId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the product details
        $productRow = $result->fetch_assoc();
        $productState = 'unavailable';

        // Close the SELECT statement
        $selectStmt->close();

        // Update the product state
        $updateStmt = $conn->prepare("UPDATE product SET product_state = ? WHERE product_id = ?");
        $updateStmt->bind_param("si", $productState, $productId);
        $updateStmt->execute();
        $updateStmt->close();

        // Close the connection
        $conn->close();

        // Redirect back to the product catalog page with a success message
        $successMessage = urlencode("Product Deleted successfully!");
        header("Location: display.php?success=true&message=$successMessage");
        exit;
    }

    // Close the connection
    $conn->close();

    exit;
}
?>
