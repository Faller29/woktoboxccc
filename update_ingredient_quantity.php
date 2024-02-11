<?php
// Assuming you have established the database connection already
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_system";

// Create a new connection
$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['productId']) && isset($_POST['ingredientId']) && isset($_POST['quantityRequired'])) {
    $productId = $_POST['productId'];
    $ingredientId = $_POST['ingredientId'];
    $quantityRequired = $_POST['quantityRequired'];

    // Update the ingredient quantity in the database
    $sql = "UPDATE productingredient 
            SET quantity_required = '$quantityRequired' 
            WHERE product_id = '$productId' AND ingredient_id = '$ingredientId'";

    if ($con->query($sql) === TRUE) {
        echo "Ingredient quantity updated successfully.";
    } else {
        echo "Error updating ingredient quantity: " . $con->error;
    }
} else {
    echo "Invalid request parameters.";
}

$con->close();
?>
