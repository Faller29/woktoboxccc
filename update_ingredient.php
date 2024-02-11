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

if (isset($_POST['editIngredientId'])) {
    $ingredientId = $_POST['editIngredientId'];
    $ingredientName = $_POST['editIngredientName'];
    $ingredientCategory = $_POST['editIngredientCategory']; // Get the updated category ID
    $ingredientQuantity = $_POST['editIngredientQuantity'];
    $ingredientLevel = $_POST['editIngredientLevel'];

    // Update the ingredient details in the database, including the category
    $sqlUpdateIngredient = "UPDATE ingredients SET ingredient_name = '$ingredientName', category_id = '$ingredientCategory', ingredient_quantity = '$ingredientQuantity', ingredient_level = '$ingredientLevel' WHERE ingredient_id = '$ingredientId'";
    $resultUpdateIngredient = mysqli_query($con, $sqlUpdateIngredient);

    if ($resultUpdateIngredient) {
        $successMessage = urlencode("Ingredient updated successfully!");
        header("Location: inventory.php?success=true&message=$successMessage");
    } else {
        $errorMessage = urlencode("Failed to update ingredient.");
        header("Location: inventory.php?success=false&message=$errorMessage");
    }

    // Close the database connection
    mysqli_close($con);
}
?>
