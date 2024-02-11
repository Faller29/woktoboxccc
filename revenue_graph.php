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

// Get date today for Top Selling product
$specifiedDate = date('Y-m-d');

$sql = "SELECT product_name, SUM(quantity) as total_quantity
                FROM cart_items
                WHERE DATE(date_added) = '$specifiedDate'
                GROUP BY product_name
                ORDER BY total_quantity DESC LIMIT 3";

$result = mysqli_query($con, $sql);

$predictions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $predictions[$row['product_name']] = $row['total_quantity'];
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Graph Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> <!-- Add this script -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        #chartContainer {
            width: 1000px;
            height: 100%;
            margin: 20px auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            /* Add shadow to the chart container */
            background-color: #fff;
            /* Add white background to the chart container */
            border-radius: 10px;
            /* Add border radius to the chart container */
            padding: 20px;
            /* Add padding to the chart container */
        }

        #buttonContainer {
            text-align: center;
            margin-top: 10px;
        }

        #buttonContainer button {
            margin: 5px;
        }

        .container {
            padding-top: 50px;
            width: 100%;
        }

        .logo-img {
            height: 50px;
            width: auto;
            /* This will scale the logo proportionally based on the height */
        }

        .text-center {
            margin-left: 50%;
        }

        @media (max-width: 767px) {

            /* Adjust the chart container size for screens with a width of 767px or less */
            #chartContainer {
                width: 100%;
                height: 300px;
                padding: 10px;
            }
        }

        .revenue-box {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            border-radius: 10px;
            margin-bottom: 5px;

        }

        .revenue-box h4 {
            margin-bottom: 5px;
        }

        .revenue-box p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        body {
            position: relative;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 100px;
        }

        .revenue-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .revenue-box h6 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .revenue-box p {
            color: #555;
            font-size: 16px;
        }


        .footer {
            position: absolute;
            bottom: -10%;
            width: 100%;
            background-color: #C72225;
            color: #FFFFFF;
            text-align: center;
            padding: 0px 0px;
        }

        .top-selling-card {
            /* Card styles for the top-selling section */
            border: 2px solid #007bff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        }

        .card-title {
            /* Title styles */
            color: #007bff;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .selling-list {
            /* Styles for the selling list */
            list-style-type: none;
            padding: 0;
        }

        .list-group-item {
            /* Styles for each list item */
            background-color: #f8f9fa;
            border: none;
            margin-bottom: 10px;
        }

        .product-name {
            /* Styles for the product name */
            font-weight: bold;
        }

        .badge {
            /* Styles for the badge (quantity sold) */
            background-color: #28a745;
        }



        .ingredient-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 90%;
            text-align: center;
            overflow-y: auto;
            max-height: 80vh;
        }

        .ingredient-content h2 {
            color: #333333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .ingredient-content p {
            color: #555555;
            font-size: 16px;
            margin: 10px 0;
        }

        .ingredient-close {
            color: #777777;
            float: right;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        .ingredient-close:hover,
        .ingredient-close:focus {
            color: #333333;
        }

        .ingredient-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand navbar-logo" href="display.php"><img src="images/logo.png" alt="Logo"
                class="logo-img">Wox to
            Box</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar"
            aria-expanded="true" aria-controls="collapseOne">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="display.php">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="inventory.php">Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="revenue_graph.php">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="account_user.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container d-flex justify-content-center flex-wrap">
        <div class="container d-flex justify-content-center flex-wrap">
            <?php foreach ($predictions as $product => $quantity): ?>
                <div class="col-md-4 mb-4">
                    <div class="card top-selling-card">
                        <div class="card-body">
                            <h6 class="card-title text-primary" style="text-align: center;">
                                <?php echo $product; ?>
                            </h6>
                            <p class="card-text" style="text-align: center;">
                                <span class="badge badge-success">
                                    <?php echo $quantity; ?> sold
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="col-md-3 mb-4">
            <div class="revenue-box p-3">
                <h6>Today's Revenue</h6>
                <p id="todayRevenue">$0</p>
            </div>
        </div>


        <div class="col-md-3 mb-4">
            <div class="revenue-box p-3">
                <h6>Last 7 Days Revenue</h6>
                <p id="last7DaysRevenue">$0</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="revenue-box p-3">
                <h6>Last 30 Days Revenue</h6>
                <p id="last30DaysRevenue">$0</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="revenue-box p-3">
                <h6>Product Sold Today</h6>
                <p id="productSoldToday">0</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="revenue-box p-3">
                <h6>Last 7 Days Product Sold</h6>
                <p id="productSoldLast7Days">0</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="revenue-box p-3">
                <h6>Product Sold Last 30 Days</h6>
                <p id="productSoldLast30Days">0</p>
            </div>
        </div>
    </div>

    <div class="container d-flex justify-content-center">
        <div class="row">
            <div class="col-md-8">
                <div id="chartContainer" class="p-4">
                    <canvas id="myChart"></canvas>
                </div>
                <div id="buttonContainer" class="text-center">
                    <button id="printButton" class="btn btn-primary">Print</button>
                    <button id="weeklyButton" class="btn btn-secondary">Last 7 Days</button>
                    <button id="monthlyButton" class="btn btn-secondary">Monthly Revenue</button>
                    <!-- New button for "Last 30 Days" -->
                    <button id="last30DaysButton" class="btn btn-secondary">Last 30 Days</button>
                    <button id="showIngredientsButton" class="btn btn-primary">ANALytics</button>
                </div>
            </div>
        </div>
    </div>
    <div id="ingredientOverlay" class="ingredient-overlay">
        <div class="ingredient-content">
            <span class="ingredient-close" id="closeOverlay">&times;</span>
            <h2>Ingredient Suggestions</h2>
            <?php
            // Include the recommend.php file
            include('recommend.php');
            ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to fetch revenue data from the backend
            function fetchRevenueData(period) {
                var url = 'fetch_revenue_data.php?period=' + period;
                return fetch(url)
                    .then(response => response.json());
            }

            // Function to fetch product sold data from the backend
            function fetchProductSoldData(period) {
                var url = 'fetch_revenue_data.php?period=' + period + '&dataType=product_sold';
                return fetch(url)
                    .then(response => response.json());
            }

            // Function to update the chart data and redraw
            function updateChart(labels, revenueData, productSoldData) {
                myChart.data.labels = labels;
                myChart.data.datasets[0].data = revenueData;
                if (myChart.data.datasets.length < 2) {
                    myChart.data.datasets.push({ // Add new dataset for product sold data
                        label: 'Product Sold',
                        data: productSoldData,
                        backgroundColor: 'rgba(255, 0, 0, 0.2)', // Red fill color for product sold data
                        borderColor: 'rgba(255, 0, 0, 1)', // Red line color for product sold data
                        borderWidth: 1
                    });
                } else {
                    myChart.data.datasets[1].data = productSoldData; // Update the data of the existing dataset
                }
                myChart.update();
            }

            // Function to update the graph title
            function updateGraphTitle(title) {
                myChart.options.plugins.title.text = title;
                myChart.update();
            }

            // Create a line chart
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Revenue',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Area fill color for revenue data
                        borderColor: 'rgba(75, 192, 192, 1)', // Line color for revenue data
                        borderWidth: 3
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Revenue', // Initial graph title
                            position: 'top'
                        },
                        datalabels: { // Configure data labels
                            display: true,
                            align: 'top',
                            backgroundColor: 'rgba(255, 255, 255, 0.8)',
                            borderRadius: 4,
                            formatter: (value, context) => {
                                return 'Sum: ' + value; // Format the label text as 'Sum: [value]'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var last30DaysButton = document.getElementById('last30DaysButton');
            last30DaysButton.addEventListener('click', function () {
                fetchRevenueData('last30days')
                    .then(data => {
                        const labels = data.map(item => item.date);
                        const revenueData = data.map(item => item.total_revenue);
                        // Fetch and update product sold data for the line chart
                        fetchProductSoldData('last30days') // Fetch product sold data
                            .then(data => {
                                const productSoldData = data.map(item => item.total_quantity);
                                updateChart(labels, revenueData, productSoldData);
                                updateGraphTitle('Last 30 Days'); // Update the chart title
                            });
                    });

                fetchRevenueData('last30days')
                    .then(data => {
                        const last30DaysRevenue = data.reduce((total, item) => total + parseFloat(item
                            .total_revenue), 0);
                        updateDashboardRevenue(null, null, null, last30DaysRevenue);
                    });
            });


            // Function to fetch revenue data from the backend for Today, Last 7 Days, and Monthly
            function fetchDashboardRevenueData() {
                const urlToday = 'fetch_revenue_data.php?period=today';
                const urlLast7Days = 'fetch_revenue_data.php?period=weekly';
                const urlLast30Days = 'fetch_revenue_data.php?period=last30days';
                const urlMonthly = 'fetch_revenue_data.php?period=monthly';

                const requestToday = fetch(urlToday);
                const requestLast7Days = fetch(urlLast7Days);
                const requestLast30Days = fetch(urlLast30Days);
                const requestMonthly = fetch(urlMonthly);

                return Promise.all([requestToday, requestLast7Days, requestLast30Days, requestMonthly])
                    .then(responses => Promise.all(responses.map(response => response.json())))
                    .then(data => {
                        // Calculate total revenue for Today, Last 7 Days, Last 30 Days, and Monthly
                        const todayRevenue = data[0].reduce((total, item) => total + parseFloat(item
                            .total_revenue), 0);
                        const last7DaysRevenue = data[1].reduce((total, item) => total + parseFloat(item
                            .total_revenue), 0);
                        const last30DaysRevenue = data[2].reduce((total, item) => total + parseFloat(item
                            .total_revenue), 0);
                        const monthlyRevenue = data[3].reduce((total, item) => total + parseFloat(item
                            .total_revenue), 0);

                        return [todayRevenue, last7DaysRevenue, last30DaysRevenue, monthlyRevenue];
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        return Promise.reject();
                    });
            }

            // Function to update the revenue data in the dashboard divs
            function updateDashboardRevenue(todayRevenue, last7DaysRevenue, last30DaysRevenue, monthlyRevenue) {
                if (todayRevenue !== null) {
                    document.getElementById('todayRevenue').textContent = '$' + todayRevenue.toFixed(2);
                }
                if (last7DaysRevenue !== null) {
                    document.getElementById('last7DaysRevenue').textContent = '$' + last7DaysRevenue.toFixed(2);
                }
                if (last30DaysRevenue !== null) {
                    document.getElementById('last30DaysRevenue').textContent = '$' + last30DaysRevenue.toFixed(2);
                }
                if (monthlyRevenue !== null) {
                    document.getElementById('monthlyRevenue').textContent = '$' + monthlyRevenue.toFixed(2);
                }
            }

            // Initial data fetch for monthly revenue and product sold for the line chart
            fetchRevenueData('monthly')
                .then(data => {
                    const labels = data.map(item => item.date);
                    const revenueData = data.map(item => item.total_revenue);

                    // Fetch and update product sold data for the line chart
                    fetchProductSoldData('monthly')
                        .then(data => {
                            const productSoldData = data.map(item => item.total_quantity);
                            updateChart(labels, revenueData, productSoldData);
                        });
                });

            // Fetch and update dashboard revenue data on page load
            fetchDashboardRevenueData()
                .then(revenues => {
                    // Update the dashboard divs with revenue data
                    updateDashboardRevenue(revenues[0], revenues[1], revenues[2], revenues[3]);
                });

            var printButton = document.getElementById('printButton');
            printButton.addEventListener('click', function () {
                // Add a class to the chart container for styling in the print stylesheet
                document.getElementById('chartContainer').classList.add('print-chart-container');
                window.print();
            });

            var weeklyButton = document.getElementById('weeklyButton');
            weeklyButton.addEventListener('click', function () {
                fetchRevenueData('weekly')
                    .then(data => {
                        const labels = data.map(item => item.date);
                        const revenueData = data.map(item => item.total_revenue);

                        // Fetch and update product sold data for the line chart
                        fetchProductSoldData('weekly')
                            .then(data => {
                                const productSoldData = data.map(item => item.total_quantity);
                                updateChart(labels, revenueData, productSoldData);
                            });

                        updateGraphTitle('7 Days Revenue'); // Update the chart title
                    });
            });

            var monthlyButton = document.getElementById('monthlyButton');
            monthlyButton.addEventListener('click', function () {
                fetchRevenueData('monthly')
                    .then(data => {
                        const labels = data.map(item => item.date);
                        const revenueData = data.map(item => item.total_revenue);

                        // Fetch and update product sold data for the line chart
                        fetchProductSoldData('monthly')
                            .then(data => {
                                const productSoldData = data.map(item => item.total_quantity);
                                updateChart(labels, revenueData, productSoldData);
                            });

                        updateGraphTitle('Monthly Revenue'); // Update the chart title
                    });
            });
            // Function to fetch product sold data from the backend for Today, Last 7 Days, and Monthly
            function fetchProductSoldData(period) {
                const urlToday = 'fetch_revenue_data.php?period=' + period + '&dataType=product_sold';
                return fetch(urlToday)
                    .then(response => response.json());
            }

            // Function to update the product sold data in the dashboard divs
            function updateDashboardProductSold(productSoldToday, productSoldLast7Days, productSoldMonthly,
                productSoldLast30Days) {
                document.getElementById('productSoldToday').textContent = productSoldToday;
                document.getElementById('productSoldLast7Days').textContent = productSoldLast7Days;
                document.getElementById('productSoldMonthly').textContent = productSoldMonthly;
                document.getElementById('productSoldLast30Days').textContent = productSoldLast30Days;
            }

            // Fetch and update dashboard product sold data on page load
            fetchProductSoldData('today')
                .then(data => {
                    const productSoldToday = data.reduce((total, item) => total + parseInt(item.total_quantity),
                        0);
                    return fetchProductSoldData('weekly')
                        .then(data => {
                            const productSoldLast7Days = data.reduce((total, item) => total + parseInt(item
                                .total_quantity), 0);
                            return fetchProductSoldData('monthly')
                                .then(data => {
                                    const productSoldMonthly = data.reduce((total, item) => total +
                                        parseInt(item.total_quantity), 0);
                                    return fetchProductSoldData('last30days')
                                        .then(data => {
                                            const productSoldLast30Days = data.reduce((total,
                                                item) => total + parseInt(item
                                                    .total_quantity),
                                                0);
                                            updateDashboardProductSold(productSoldToday,
                                                productSoldLast7Days, productSoldMonthly,
                                                productSoldLast30Days);
                                        });
                                });
                        });
                });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var showIngredientsButton = document.getElementById('showIngredientsButton');
            var ingredientOverlay = document.getElementById('ingredientOverlay');
            var closeOverlay = document.getElementById('closeOverlay');

            showIngredientsButton.addEventListener('click', function () {
                ingredientOverlay.style.display = 'block';
            });

            closeOverlay.addEventListener('click', function () {
                ingredientOverlay.style.display = 'none';
            });

            window.addEventListener('click', function (event) {
                if (event.target === ingredientOverlay) {
                    ingredientOverlay.style.display = 'none';
                }
            });
        });
    </script>


    <footer class="footer">
        <div class="container1">
            <p>&copy;
                <?php echo date('Y'); ?> Wox to Box. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>