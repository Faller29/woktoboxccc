<?php
// get_product.php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['productId'])) {
    $productId = $_GET['productId'];

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

    // Prepare and bind a statement to select the product data from the database
    $stmt = $conn->prepare("SELECT product_name, product_price, product_image FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($productName, $productPrice, $imageData);

    // Fetch the data
    if ($stmt->fetch()) {
        // Encode the image data as base64
        $base64Image = base64_encode($imageData);
        $imageType = isset($product['image_type']) ? $product['image_type'] : '';
        $imageSrc = 'data:' . $imageType . ';base64,' . $base64Image;

        // Return the product data as JSON
        $responseData = array(
            'productName' => $productName,
            'productPrice' => $productPrice,
            'imageSrc' => $imageSrc
        );

        echo json_encode($responseData);
    } else {
        // Return an error message if the product is not found
        echo json_encode(array('error' => 'Product not found'));
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
