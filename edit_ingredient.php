<?php
// edit_ingredient.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "inventory_system";
    $con = mysqli_connect($servername, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $ingredientId = $_POST['editIngredientId'];
    $ingredientName = $_POST['editIngredientName'];
    $ingredientQuantity = $_POST['editIngredientQuantity'];
    $ingredientLevel = $_POST['editIngredientLevel'];

    // Update the ingredient details in the database
    $sql = "UPDATE `ingredients` SET ingredient_name='$ingredientName', ingredient_quantity='$ingredientQuantity', ingredient_level='$ingredientLevel' WHERE ingredient_id=$ingredientId";

    if (mysqli_query($con, $sql)) {
        echo "Ingredient updated successfully.";
    } else {
        echo "Error updating ingredient: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
