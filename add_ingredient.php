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

if (isset($_POST['ingredientName']) && isset($_POST['ingredientCategory']) && isset($_POST['ingredientQuantity']) && isset($_POST['ingredientLevel'])) {
    $ingredientName = $_POST['ingredientName'];
    $ingredientCategory = $_POST['ingredientCategory'];
    $ingredientQuantity = $_POST['ingredientQuantity'];
    $ingredientLevel = $_POST['ingredientLevel'];

    // Prepare the INSERT statement to add the new ingredient to the database
    $sql = "INSERT INTO `ingredients` (ingredient_name, category_id, ingredient_quantity, ingredient_level) 
            VALUES ('$ingredientName', $ingredientCategory, $ingredientQuantity, $ingredientLevel)";

    if (mysqli_query($con, $sql)) {
        // Redirect to the inventory.php page with success message
        $success = true;
        $message = "Ingredient added successfully.";
        header("Location: inventory.php?success=" . $success . "&message=" . urlencode($message));
        exit();
    } else {
        // Redirect to the inventory.php page with error message
        $success = false;
        $message = "Failed to add ingredient.";
        header("Location: inventory.php?success=" . $success . "&message=" . urlencode($message));
        exit();
    }
}
?>
