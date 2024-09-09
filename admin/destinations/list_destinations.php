<?php
include '../../db_config.php';
include('../session_check.php');

$sql = "SELECT * FROM destinations";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>List All Destinations</title>
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

<h2>List All Destinations</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Country</th>
        <th>City</th>
        <th>Photo Link</th>
        <th>Hotel Name</th> 
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>" . $row["country"] . "</td>";
            echo "<td>" . $row["city"] . "</td>";
            echo "<td>" . $row["photo_url"] . "</td>";
            echo "<td>" . $row["hotel_name"] . "</td>";
            echo '<td><a href="edit_destination.php?id=' . $row["id"] . '">Edit</a> | ';
            echo '<a href="delete_destination.php?id=' . $row["id"] . '" onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No destinations found</td></tr>";
    }
    ?>

</table>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>

<?php
$conn->close();
?>
