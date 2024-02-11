<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredient Suggestions</title>
</head>

<body>

    <div id="ingredientSuggestions"></div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fetch ingredient suggestions using AJAX
            $.ajax({
                url: 'recommend.php',
                method: 'GET',
                success: function (data) {
                    // Display ingredient suggestions
                    $('#ingredientSuggestions').html(data);
                },
                error: function () {
                    console.error('Failed to fetch ingredient suggestions.');
                }
            });
        });
    </script>

</body>

</html>
