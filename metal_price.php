<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metal Prices</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .dropdown-container {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .dropdown-container label {
            margin-right: 10px;
        }

        .dropdown-container select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .price-update-info {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px;
            margin-left: 20%;
            margin-right: 20%;
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .price-update-info h2 {
            font-size: 1.5em;
            margin-top: 0;
            color: black;
        }

        .price-update-info p {
            font-size: 1em;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Metal Prices</h1>
        <!-- Dropdown Menu for Dates -->
        <div class="dropdown-container">
            <form method="POST" id="filter-form">
                <label for="date-filter">Filter By Date:</label>
                <select id="date-filter" name="date-filter">
                    <option value="" <?php if (!isset($_POST['date-filter']) || $_POST['date-filter'] === '') echo 'selected'; ?>>Newest price</option>
                    <?php
                    // Database connection
                    include 'connection.php';

                    // Fetch distinct dates from the metal_history table to create dropdown options
                    $sql = "SELECT DISTINCT date FROM metal_history";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = (isset($_POST['date-filter']) && $_POST['date-filter'] === $row["date"]) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row["date"]) . "' $selected>" . htmlspecialchars($row["date"]) . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>
            </form>
        </div>

        <!-- Display Data -->
        <?php
        // Database connection
        include 'connection.php';

        // Handle dropdown selection and display data
        $filterDate = isset($_POST['date-filter']) ? $_POST['date-filter'] : '';

        if ($filterDate === "") {
            // Retrieve data from the `metal` table
            $sql = "SELECT Metal_name, date, Cost_price, Selling_price FROM metal";
        } else {
            // Retrieve data from `metal_history` and join with `metal`
            $sql = "
                SELECT m.Metal_name, mh.date, mh.Cost_price, mh.Selling_price
                FROM metal_history mh
                JOIN metal m ON m.Id = mh.metal_id
                WHERE mh.date = '$filterDate'
            ";
        }

        $result = $conn->query($sql);
        ?>
        <table>
            <tr>
                <th>Metal Name</th>
                <th>Update Time</th>
                <th>Cost Price($)</th>
                <th>Selling Price($)</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $metalName = isset($row["Metal_name"]) ? htmlspecialchars($row["Metal_name"]) : '';
                    $date = isset($row["date"]) ? htmlspecialchars($row["date"]) : '';
                    $costPrice = isset($row["Cost_price"]) ? htmlspecialchars($row["Cost_price"]) : '';
                    $sellingPrice = isset($row["Selling_price"]) ? htmlspecialchars($row["Selling_price"]) : '';

                    echo "<tr><td>{$metalName}</td><td>{$date}</td><td>{$costPrice}</td><td>{$sellingPrice}</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data available</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
    <br>
    <div class="price-update-info">
        <h2>Additional Information About Metal Prices</h2>
        <p>Gold prices are updated daily and accurately. This is the actual gold price applied globally. We use it for all products in the store to synchronize globally and enhance the store's reputation.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateFilter = document.getElementById('date-filter');
            const form = document.getElementById('filter-form');

            dateFilter.addEventListener('change', () => {
                form.submit();
            });
        });
    </script>
</body>
</html>
