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

if (isset($_POST['removeIngredient'])) {
  $ingredientId = $_POST['removeIngredient'];

  // Delete the selected product from the productIngredient table
  $sqlDeleteSupplier = "DELETE FROM ingredientsupplier WHERE ingredient_id = '$ingredientId'";
  $resultDeleteSupplier = mysqli_query($con, $sqlDeleteSupplier);

  // Delete the selected product from the product table
  $sqlDeleteIngredient = "DELETE FROM ingredients WHERE ingredient_id = '$ingredientId'";
  $resultDeleteIngredient = mysqli_query($con, $sqlDeleteIngredient);

  if ($resultDeleteIngredient && $resultDeleteSupplier) {
    $response = array('success' => true);
  } else {
    $response = array('success' => false);
  }

  // Close the database connection
  mysqli_close($con);

  // Send the JSON response
  echo json_encode($response);
}
?>
