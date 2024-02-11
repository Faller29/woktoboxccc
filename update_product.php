<?php
// update_product.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the product ID is provided
    if (!isset($_POST['productId'])) {
        echo json_encode(array('error' => true, 'message' => 'Product ID not provided'));
        exit;
    }
    $updateId = $_GET['updateid'];
    $productId = $_POST['productId'];
    $productName = $_POST['updateProductName'];
    $productPrice = $_POST['updateProductPrice'];
    $productImage = $_FILES['updateProductImage']['tmp_name'];

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

    // Prepare and bind a statement to update the product data in the database
    $stmt = $conn->prepare("UPDATE product SET product_name = ?, product_price = ?, product_image = ? WHERE product_id = ?");
    $stmt->bind_param("sdsi", $productName, $productPrice, $imageData, $productId);

    // Check if a new image is uploaded
    if (!empty($productImage)) {
        // Read the contents of the new image file
        $imageData = file_get_contents($productImage);
    } else {
        // If no new image is uploaded, keep the existing image data in the database
        $stmt2 = $conn->prepare("SELECT product_image FROM product WHERE product_id = ?");
        $stmt2->bind_param("i", $productId);
        $stmt2->execute();
        $stmt2->bind_result($existingImage);
        $stmt2->fetch();
        $stmt2->close();

        $imageData = $existingImage;
    }

    // Execute the update statement
    if ($stmt->execute()) {
        // Success message
        $successMessage = "Product updated successfully!";
        header("Location: display.php?success=true&message=" . urlencode($successMessage));
    } else {
        // Error message
        $errorMessage = "Failed to update product";
        header("Location: display.php?success=false&message=" . urlencode($errorMessage));
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
