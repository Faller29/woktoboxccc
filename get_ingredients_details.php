<?php
// get_ingredient_details.php

// Assuming you have established the database connection already
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

    $sql = "SELECT ingredients.ingredient_name,ingredients.ingredient_id, productingredient.quantity_required
            FROM productingredient
            INNER JOIN ingredients ON productingredient.ingredient_id = ingredients.ingredient_id
            WHERE productingredient.product_id = '$productId'";

    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $ingredients = array();
        while ($row = $result->fetch_assoc()) {
            $ingredients[] = $row;
        }
        echo json_encode($ingredients);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$con->close();
?>
