
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

function reorderRecommendation($products, $cartItems, $ingredients, $productIngredients, $threshold) {
    $reorderRecommendations = [];

    foreach ($products as $product) {
        $productId = $product['product_id'];
        $productName = $product['product_name'];

        // Fetch the ingredient_id(s) associated with the product
        $ingredientIds = array_column(
            array_filter($productIngredients, function ($pi) use ($productId) {
                return $pi['product_id'] == $productId;
            }),
            'ingredient_id'
        );

        // Calculate the total purchased quantity from cart_items for the product
        $purchasedQuantities = array_column(
            array_filter($cartItems, function ($item) use ($productId) {
                return $item['product_name'] == $productId;
            }),
            'quantity'
        );

        $totalPurchasedQuantity = array_sum($purchasedQuantities);

        // Calculate the total ingredient level from ingredients for the associated ingredient_id(s)
        $totalIngredientLevel = 0;
        foreach ($ingredientIds as $ingredientId) {
            $totalIngredientLevel += $ingredients[$ingredientId]['ingredient_level'] ?? 0;
        }

        // Determine if a reorder is needed based on the total ingredient level and total purchased quantity
        if ($totalIngredientLevel <= $threshold * $totalPurchasedQuantity) {
            $recommendedQuantity = max(0, $threshold * $totalPurchasedQuantity - $totalIngredientLevel);
            $reorderRecommendations[] = [
                'product_id' => $productId,
                'product_name' => $productName,
                'recommended_quantity' => $recommendedQuantity
            ];
        }
    }

    return $reorderRecommendations;
}

// API to get reorder recommendations with product information
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"]) && $_GET["action"] === "reorder_recommendations") {
    // Fetch product data
    $productsQuery = "SELECT * FROM products";
    $productsResult = mysqli_query($con, $productsQuery);

    if (!$productsResult) {
        die('Error fetching products: ' . mysqli_error($con));
    }

    $productsData = [];
    while ($row = mysqli_fetch_assoc($productsResult)) {
        $productsData[] = $row;
    }

    // Fetch cart items data
    $cartItemsQuery = "SELECT * FROM cart_items";
    $cartItemsResult = mysqli_query($con, $cartItemsQuery);

    if (!$cartItemsResult) {
        die('Error fetching cart items: ' . mysqli_error($con));
    }

    $cartItemsData = [];
    while ($row = mysqli_fetch_assoc($cartItemsResult)) {
        $cartItemsData[] = $row;
    }

    // Fetch ingredient data
    $ingredientsQuery = "SELECT * FROM ingredients";
    $ingredientsResult = mysqli_query($con, $ingredientsQuery);

    if (!$ingredientsResult) {
        die('Error fetching ingredients: ' . mysqli_error($con));
    }

    $ingredientsData = [];
    while ($row = mysqli_fetch_assoc($ingredientsResult)) {
        $ingredientsData[$row['ingredient_id']] = $row;
    }

    // Fetch product-ingredient relationship data
    $productIngredientsQuery = "SELECT * FROM productingredient";
    $productIngredientsResult = mysqli_query($con, $productIngredientsQuery);

    if (!$productIngredientsResult) {
        die('Error fetching product-ingredient relationships: ' . mysqli_error($con));
    }

    $productIngredientsData = [];
    while ($row = mysqli_fetch_assoc($productIngredientsResult)) {
        $productIngredientsData[] = $row;
    }

    $reorderThreshold = 0.5; // Threshold for triggering a reorder recommendation
    $recommendations = reorderRecommendation(
        $productsData,
        $cartItemsData,
        $ingredientsData,
        $productIngredientsData,
        $reorderThreshold
    );

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($recommendations);
    exit; // Make sure to exit after sending the response
}
?>


