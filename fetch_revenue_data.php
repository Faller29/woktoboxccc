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

// Function to fetch weekly, last 30 days, or monthly data from the "cart_items" table
function fetchData($period, $dataType)
{
    global $con;

    // Common part of the query for both revenue and product sold data
    $queryCommon = "SELECT DATE(date_added) AS date";
    // Update the column names for revenue and product sold
    $queryCommon .= ", SUM(total_price) AS total_revenue"; // For revenue
    $queryCommon .= ", SUM(quantity) AS total_quantity"; // For product sold

    $queryCommon .= " FROM cart_items";

    // Add conditions based on the period
    if ($period === 'weekly') {
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        $queryCommon .= " WHERE DATE(date_added) BETWEEN '$startDate' AND '$endDate'";
    } elseif ($period === 'last30days') {
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = date('Y-m-d');
        $queryCommon .= " WHERE DATE(date_added) BETWEEN '$startDate' AND '$endDate'";
    } else { // Monthly data
        $startDate = date('Y-m-01'); // First day of the current month
        $endDate = date('Y-m-d'); // Current date
        $queryCommon .= " WHERE DATE(date_added) BETWEEN '$startDate' AND '$endDate'";
    }

    $queryCommon .= " GROUP BY DATE(date_added) ORDER BY date_added";

    $result = mysqli_query($con, $queryCommon);
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Fetch data based on the provided period and data type (revenue or product sold)
$period = isset($_GET['period']) ? $_GET['period'] : 'monthly';
$dataType = isset($_GET['dataType']) ? $_GET['dataType'] : 'revenue';
$data = fetchData($period, $dataType);

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
