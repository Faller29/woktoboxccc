<?php
// get_ingredients.php

// Assuming you have established the database connection already
// Replace these with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_system";

// Create a new connection
$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    $sql = "SELECT ingredients.ingredient_name, productingredient.quantity
            FROM ingredients
            INNER JOIN productingredient ON ingredients.ingredient_id = productingredient.ingredient_id
            WHERE productingredient.product_id = '$productId'";

    $result = $con->query($sql);

    if ($result) {
        $ingredients = array();
        while ($row = $result->fetch_assoc()) {
            $ingredients[] = $row;
        }
        echo json_encode($ingredients);
    } else {
        echo json_encode(array());
    }
} else {
    echo json_encode(array());
}

// Close the database connection
$con->close();
?>
