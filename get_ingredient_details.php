<?php
// get_ingredient_details.php
// This file fetches ingredient details from the database based on the ingredient_id sent via AJAX

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['ingredientId'])) {
    $ingredientId = $_POST['ingredientId'];

    // Retrieve the ingredient details from the database
    $sql = "SELECT * FROM `ingredients` WHERE `ingredient_id` = $ingredientId";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Send the ingredient details in JSON format
        echo json_encode($row);
    } else {
        echo json_encode(array("error" => "Ingredient not found"));
    }
} else {
    echo json_encode(array("error" => "Invalid request"));
}
?>
