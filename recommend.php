<?php
// Connection to your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";

$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Define your margin of error multiplier
$marginOfError = 1.2;

// Fetch average product sold in a week
$sql = "SELECT ci.product_name, AVG(ci.quantity) as avg_sold
        FROM cart_items ci
        WHERE ci.date_added >= NOW() - INTERVAL 1 WEEK
        GROUP BY ci.product_name";

$result = mysqli_query($con, $sql);

$averageProductSold = [];
while ($row = mysqli_fetch_assoc($result)) {
    $averageProductSold[$row['product_name']] = $row['avg_sold'];
}

// Calculate average ingredient usage and total estimated ingredients
$ingredientSuggestions = '';
$totalEstimatedIngredients = [];

// Start flex container for products
$ingredientSuggestions .= '<div style="display: flex; flex-wrap: wrap;">';

foreach ($averageProductSold as $product_name => $avg_sold) {
    $sql = "SELECT i.ingredient_name, pi.quantity_required * $avg_sold * $marginOfError as avg_usage
            FROM productingredient pi
            JOIN product p ON pi.product_id = p.product_id
            JOIN ingredients i ON pi.ingredient_id = i.ingredient_id
            WHERE p.product_name = '$product_name'";

    $result = mysqli_query($con, $sql);

    // Enclose the section with a border
    $ingredientSuggestions .= '<div style="border: 1px solid #000; padding: 10px; margin: 10px; flex: 1;">';
    $ingredientSuggestions .= '<h2>' . $product_name . '</h2>';

    while ($row = mysqli_fetch_assoc($result)) {
        $avgUsage = number_format($row['avg_usage'], 2);
        // Use new lines instead of commas
        $ingredientSuggestions .= '<p>Ingredient Name: ' . $row['ingredient_name'] . '<br>Average Usage: ' . $avgUsage . '</p>';

        // Accumulate total estimated ingredients
        if (!isset($totalEstimatedIngredients[$row['ingredient_name']])) {
            $totalEstimatedIngredients[$row['ingredient_name']] = ceil($row['avg_usage']);
        } else {
            $totalEstimatedIngredients[$row['ingredient_name']] += ceil($row['avg_usage']);
        }
    }

    $ingredientSuggestions .= '</div>';
}

// End flex container for products
$ingredientSuggestions .= '</div>';

// Start flex container for TOTAL ESTIMATED INGREDIENTS
$ingredientSuggestions .= '<div style="border: 1px solid #000; padding: 10px; margin: 10px; flex: 100%;">';
$ingredientSuggestions .= '<h2>TOTAL ESTIMATED INGREDIENTS</h2>';
foreach ($totalEstimatedIngredients as $ingredient_name => $total) {
    $ingredientSuggestions .= '<p>Ingredient Name: ' . $ingredient_name . '<br>Total Needed: ' . $total . '</p>';
}
$ingredientSuggestions .= '</div>';
// End flex container for TOTAL ESTIMATED INGREDIENTS

// Close the database connection
mysqli_close($con);

// Output HTML directly
echo $ingredientSuggestions;
?>
