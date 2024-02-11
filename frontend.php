

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- ... (Your existing HTML code) -->

<h1>Reorder Recommendations</h1>

<button onclick="fetchReorderRecommendations()">Get Reorder Recommendations</button>

<table id="reorderTable">
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Recommended Quantity</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
  function fetchReorderRecommendations() {
    fetch('backend.php?action=reorder_recommendations')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.querySelector('#reorderTable tbody');
            tableBody.innerHTML = '';

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.product_id}</td>
                    <td>${row.recommended_quantity}</td>
                `;
                tableBody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching reorder recommendations:', error.message));
}

</script>

<!-- ... (Your existing JavaScript code) -->

</body>
</html>