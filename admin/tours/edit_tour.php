<?php
include '../../db_config.php';
include('../session_check.php');

// Initialize variables
$id = isset($_GET['id']) ? $_GET['id'] : null;
$tour = [];
$existing_destinations = [];
$existing_itineraries = [];

// Handle form submission for updating tour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $id = $_POST["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $special_price = empty($_POST["special_price"]) || $_POST["special_price"] == 0 ? NULL : $_POST["special_price"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $highlights = $_POST["highlights"];
    $inclusions = $_POST["inclusions"];
    $exclusions = $_POST["exclusions"];
    $additional_info = $_POST["additional_info"];
    $spiritual_guide = $_POST["spiritual_guide"];
    $season = $_POST["season"];
    $photo_url = $_POST["photo_url"];
    $brochure_url = $_POST["brochure_url"];

    // Update existing tour
    $sql = "UPDATE tours SET 
            name=?, description=?, price=?, special_price=?, 
            start_date=?, end_date=?, highlights=?, inclusions=?, 
            exclusions=?, additional_info=?, spiritual_guide=?, 
            season=?, photo_url=?, brochure_url=? 
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssddssssssssssi",
        $name,
        $description,
        $price,
        $special_price,
        $start_date,
        $end_date,
        $highlights,
        $inclusions,
        $exclusions,
        $additional_info,
        $spiritual_guide,
        $season,
        $photo_url,
        $brochure_url,
        $id
    );

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "Tour updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();

    // Update tour-destinations relationships
    $conn->query("DELETE FROM tours_destinations WHERE tour_id = '$id'");
    if (!empty($_POST['destination_ids'])) {
        foreach ($_POST['destination_ids'] as $destination_id) {
            $conn->query("INSERT INTO tours_destinations (tour_id, destination_id) VALUES ('$id', '$destination_id')");
        }
    }

    // Update itinerary details
    foreach ($_POST['itineraries'] as $day_number => $description) {
        if (!empty($description)) {
            $conn->query("REPLACE INTO tour_details (tour_id, day_number, description) VALUES ('$id', '$day_number', '$description')");
        }
    }
} elseif ($id) {
    // Retrieve tour data for editing
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        die("Invalid tour ID");
    }

    // Fetch tour details from database
    $sql = "SELECT * FROM tours WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tour = $result->fetch_assoc();
    } else {
        die("Tour not found for ID: $id");
    }

    $stmt->close();

    // Fetch existing destinations for the selected tour
    $dest_result = $conn->query("SELECT destination_id FROM tours_destinations WHERE tour_id = $id");
    while ($row = $dest_result->fetch_assoc()) {
        $existing_destinations[] = $row['destination_id'];
    }

    // Fetch existing itineraries for the selected tour
    $itinerary_result = $conn->query("SELECT day_number, description FROM tour_details WHERE tour_id = $id");
    while ($row = $itinerary_result->fetch_assoc()) {
        $existing_itineraries[$row['day_number']] = $row['description'];
    }

    // Determine the duration of the tour
    $duration = (strtotime($tour['end_date']) - strtotime($tour['start_date'])) / (60 * 60 * 24) + 1;
} else {
    die("Tour ID not provided");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>Edit Tour</title>
</head>

<body>

    <h2>Edit Tour</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo isset($tour['id']) ? $tour['id'] : ''; ?>">
        Name: <input type="text" name="name"
            value="<?php echo isset($tour['name']) ? htmlspecialchars($tour['name']) : ''; ?>"><br>
        Description: <textarea
            name="description"><?php echo isset($tour['description']) ? htmlspecialchars($tour['description']) : ''; ?></textarea><br>
        Price: <input type="text" name="price" value="<?php echo isset($tour['price']) ? $tour['price'] : ''; ?>"><br>
        Special Price: <input type="text" name="special_price"
            value="<?php echo isset($tour['special_price']) ? $tour['special_price'] : ''; ?>"><br>
        Start Date: <input type="text" name="start_date"
            value="<?php echo isset($tour['start_date']) ? $tour['start_date'] : ''; ?>"><br>
        End Date: <input type="text" name="end_date"
            value="<?php echo isset($tour['end_date']) ? $tour['end_date'] : ''; ?>"><br>
        Highlights: <textarea
            name="highlights"><?php echo isset($tour['highlights']) ? htmlspecialchars($tour['highlights']) : ''; ?></textarea><br>
        Inclusions: <textarea
            name="inclusions"><?php echo isset($tour['inclusions']) ? htmlspecialchars($tour['inclusions']) : ''; ?></textarea><br>
        Exclusions: <textarea
            name="exclusions"><?php echo isset($tour['exclusions']) ? htmlspecialchars($tour['exclusions']) : ''; ?></textarea><br>
        Additional Info: <textarea
            name="additional_info"><?php echo isset($tour['additional_info']) ? htmlspecialchars($tour['additional_info']) : ''; ?></textarea><br>
        Spiritual Guide: <input type="text" name="spiritual_guide"
            value="<?php echo isset($tour['spiritual_guide']) ? htmlspecialchars($tour['spiritual_guide']) : ''; ?>"><br>
        Season: <input type="text" name="season"
            value="<?php echo isset($tour['season']) ? htmlspecialchars($tour['season']) : ''; ?>"><br>

        Photo URL: <input type="text" name="photo_url"
            value="<?php echo isset($tour['photo_url']) ? htmlspecialchars($tour['photo_url']) : ''; ?>"><br>
        Brochure URL: <input type="text" name="brochure_url"
            value="<?php echo isset($tour['brochure_url']) ? htmlspecialchars($tour['brochure_url']) : ''; ?>"><br>

        <h3>Destinations</h3>
        <?php
        // Fetch destinations from the database
        $dest_result = $conn->query("SELECT id, name FROM destinations");
        if ($dest_result->num_rows > 0) {
            while ($row = $dest_result->fetch_assoc()) {
                $checked = in_array($row['id'], $existing_destinations) ? 'checked' : '';
                echo "<label><input type='checkbox' name='destination_ids[]' value='{$row['id']}' $checked> {$row['name']}</label><br>";
            }
        }
        ?>

        <h3>Itinerary</h3>
        <?php
        // Determine the duration of the tour
        $duration = isset($tour['duration']) ? $tour['duration'] : 0;

        // Display itinerary fields for each day of the tour
        for ($day_number = 1; $day_number <= $duration; $day_number++) {
            // Retrieve existing description if available
            $description = isset($existing_itineraries[$day_number]) ? htmlspecialchars($existing_itineraries[$day_number]) : '';

            echo "<label for='itineraries[$day_number]'>Day $day_number:</label><br>";
            echo "<textarea name='itineraries[$day_number]' rows='4' cols='50'>$description</textarea><br>";
        }
        ?>

        <br>
        <input type="submit" value="Update Tour">
    </form>

    <a href="choose_edit.php">Back to Select Tour</a>

</body>

</html>