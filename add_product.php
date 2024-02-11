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

    // Retrieve form data
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productImage = $_FILES['productImage']['tmp_name'];
    $ingredients = $_POST['ingredients'];
    $ingredientQuantities = $_POST['ingredient_quantity']; // Newly added array for ingredient quantities
    $productState = 'available';

    // Prepare and bind a statement to insert into the product table
    $stmt = $conn->prepare("INSERT INTO product (product_name, product_price, product_image, product_state) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $productName, $productPrice, $imageData, $productState);

    // Read the contents of the image file
    $imageData = file_get_contents($productImage);

    // Execute the statement to insert into the product table
    $stmt->execute();

    // Retrieve the product ID of the inserted row
    $productId = $stmt->insert_id;

    // Close the statement
    $stmt->close();

    $stmt2 = $conn->prepare("INSERT INTO productIngredient (product_id, ingredient_id, quantity_required) VALUES (?, ?, ?)");

    // Iterate over the selected ingredients and insert them into the productIngredient table
    foreach ($ingredients as $ingredientId) {
        $quantityRequired = $ingredientQuantities[$ingredientId]; // Get the quantity_required for this ingredient

        // Prepare and bind the statement inside the loop
        $stmt2->bind_param("iii", $productId, $ingredientId, $quantityRequired);
        $stmt2->execute();
    }

    // Close the statement
    $stmt2->close();

    // Close the database connection
    $conn->close();

    $successMessage = urlencode("Product added successfully!");
    header("Location: display.php?success=true&message=$successMessage");
}

?>