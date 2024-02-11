<?php
// get_ingredient.php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $ingredientId = $_GET['id'];

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

    // Retrieve ingredient data
    $stmt = $conn->prepare("SELECT * FROM ingredients WHERE ingredient_id = ?");
    $stmt->bind_param("i", $ingredientId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $ingredient = $result->fetch_assoc();
        echo json_encode($ingredient);
    } else {
        echo json_encode(null); // No ingredient found with the given ID
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
