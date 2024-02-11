<!DOCTYPE html>
<html>
<head>
  <title>Graph Example</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    #chartContainer {
      width: 400px;
      height: 300px;
    }

    @media print {
      #chartContainer {
        width: 100%; /* Expand to full width for printing */
        height: auto; /* Auto height for printing */
      }

      #printButton {
        display: none; /* Hide print button during printing */
      }
    }
  </style>
</head>
<body>
  <canvas id="myChart"></canvas>
  <script src="script.js"></script>
  <button id="printButton">Print</button>
  <button id="todayButton">Today</button>
  <button id="weekButton">This Week</button>
  <button id="monthButton">This Month</button>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Generate date range for the last five days
      var dates = [];
      var currentDate = new Date();
      for (var i = 9; i >= 0; i--) {
        var date = new Date(currentDate);
        date.setDate(date.getDate() - i);
        dates.push(date.toLocaleDateString());
      }

      // Revenue data for the last five days
      var revenueData = [100000, 200000, 80000, 50000, 10000, 10000, 1000, 100, 1000, 100,100000, 200000, 80000, 50000, 10000, 10000, 1000, 100, 1000, 100];

      // Create a line chart
      var ctx = document.getElementById('myChart').getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: dates,
          datasets: [{
            label: 'Revenue',
            data: revenueData,
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Area fill color
            borderColor: 'rgba(75, 192, 192, 1)', // Line color
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      var printButton = document.getElementById('printButton');
      printButton.addEventListener('click', function () {
        window.print();
      });

      var todayButton = document.getElementById('todayButton');
      todayButton.addEventListener('click', function () {
        updateChart(1);
      });

      var weekButton = document.getElementById('weekButton');
      weekButton.addEventListener('click', function () {
        updateChart(7);
      });

      var monthButton = document.getElementById('monthButton');
      monthButton.addEventListener('click', function () {
        updateChart(30);
      });

      function updateChart(days) {
        // Generate date range
        var newDates = [];
        var newRevenueData = [];
        var currentDate = new Date();
        for (var i = days - 1; i >= 0; i--) {
          var date = new Date(currentDate);
          date.setDate(date.getDate() - i);
          newDates.push(date.toLocaleDateString());

          if (i < revenueData.length) {
            newRevenueData.push(revenueData[i]);
          } else {
            newRevenueData.push(0);
          }
        }

        // Update chart data
        myChart.data.labels = newDates;
        myChart.data.datasets[0].data = newRevenueData;
        myChart.update();
      }
    });
  </script>
</body>
</html>
