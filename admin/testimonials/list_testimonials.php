<?php
include '../../db_config.php';
include('../session_check.php');

$sql = "SELECT * FROM testimonials";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>List All Testimonials</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>List All Testimonials</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Customer Name</th>
        <th>Review</th>
        <th>Rating</th>
        <th>Date</th>
        <th>Video URL</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["customer_name"] . "</td>";
            echo "<td>" . $row["review"] . "</td>";
            echo "<td>" . $row["rating"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["video_url"] . "</td>";
            echo '<td><a href="edit_testimonial.php?id=' . $row["id"] . '">Edit</a> | ';
            echo '<a href="delete_testimonial.php?id=' . $row["id"] . '" onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No testimonials found</td></tr>";
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
