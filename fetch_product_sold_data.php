<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to fetch product sold data from the "product_sold" table
function fetchProductSoldData()
{
    global $con;

    // Calculate the start date (30 days ago from today)
    $startDate = date('Y-m-d', strtotime('-30 days'));
    $endDate = date('Y-m-d');

    $query = "SELECT DATE(date_added) AS date, SUM(total_quantity) AS total_quantity FROM product_sold WHERE DATE(date_added) BETWEEN '$startDate' AND '$endDate' GROUP BY DATE(date_added) ORDER BY date_added";

    $result = mysqli_query($con, $query);
    $productSoldData = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $productSoldData[] = $row;
    }

    return $productSoldData;
}

// Fetch product sold data
$data = fetchProductSoldData();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
