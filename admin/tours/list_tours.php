<?php
include '../../db_config.php';
include('../session_check.php');

// Retrieve all tours from the database
$sql = "SELECT * FROM tours";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>List All Tours</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Special Price</th><th>Start Date</th><th>End Date</th><th>Duration</th><th>Highlights</th><th>Inclusions</th><th>Exclusions</th><th>Additional Info</th><th>Spiritual Guide</th><th>Season</th><th>Action</th></tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "<td>" . ($row["special_price"] !== null ? $row["special_price"] : 'N/A') . "</td>";
        echo "<td>" . $row["start_date"] . "</td>";
        echo "<td>" . $row["end_date"] . "</td>";
        echo "<td>" . $row["duration"] . "</td>";
        echo "<td>" . $row["highlights"] . "</td>";
        echo "<td>" . $row["inclusions"] . "</td>";
        echo "<td>" . $row["exclusions"] . "</td>";
        echo "<td>" . $row["additional_info"] . "</td>";
        echo "<td>" . $row["spiritual_guide"] . "</td>";
        echo "<td>" . $row["season"] . "</td>";
        echo "<td>";
        echo "<a href='edit_tour.php?id=" . $row["id"] . "'>Edit</a> | ";
        echo "<a href='delete_tour.php?id=" . $row["id"] . "'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No tours found";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>List All Tours</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
        
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            background-color: #f2f2f2;
            max-width: 300px;
            overflow: hidden;
        }
        
        @media screen and (max-width: 768px) {
            table, th, td {
                display: block;
                width: 100%;
            }
            th, td {
                box-sizing: border-box;
            }
            th {
                position: sticky;
                top: 0;
                background-color: #f2f2f2;
            }
            td {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
