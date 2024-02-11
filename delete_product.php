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

if (isset($_POST['removeProduct'])) {
  $productId = $_POST['removeProduct'];

  // Delete the selected product from the productIngredient table
  $sqlDeleteIngredients = "DELETE FROM productIngredient WHERE product_id = '$productId'";
  $resultDeleteIngredients = mysqli_query($con, $sqlDeleteIngredients);

  // Delete the selected product from the product table
  $sqlDeleteProduct = "DELETE FROM product WHERE product_id = '$productId'";
  $resultDeleteProduct = mysqli_query($con, $sqlDeleteProduct);

  if ($resultDeleteIngredients && $resultDeleteProduct) {
    $successMessage = urlencode("Product deleted successfully!");
    header("Location: display.php?success=true&message=$successMessage");
  } else {
    $warningMessage = urlencode("Product deletion failed with warning");
    header("Location: display.php?success=false&warning=$warningMessage");
  }

  // Close the database connection
  mysqli_close($con);
}
?>
