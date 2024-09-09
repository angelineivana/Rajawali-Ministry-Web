<?php
include '../../db_config.php';
include('../session_check.php');

// Initialize variables
$tours = []; // Array to store all tours for selection

// Fetch all tours from the database
$result = $conn->query("SELECT id, name FROM tours");

if ($result->num_rows > 0) {
    // Fetch all rows into an associative array
    while ($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }
} else {
    die("No tours found");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate selected tour ID
    $selected_id = $_POST["selected_id"];
    
    // Redirect to edit page with selected tour ID
    header("Location: edit_tour.php?id=$selected_id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<h2>Select Tour to Edit</h2>
<form method="post" action="">
  <label for="selected_id">Select Tour:</label>
  <select name="selected_id" id="selected_id">
    <?php foreach ($tours as $tour): ?>
        <option value="<?php echo $tour['id']; ?>"><?php echo $tour['name']; ?></option>
    <?php endforeach; ?>
  </select>
  <br>
  <input type="submit" value="Edit Selected Tour">
</form>

<a href="../admin-index.php">
Back to Admin Index</a>

</body>
</html>
