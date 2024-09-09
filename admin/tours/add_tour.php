<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind SQL statement
    $sql = "INSERT INTO tours 
            (name, description, price, special_price, start_date, end_date, highlights, inclusions, exclusions, additional_info, spiritual_guide, season, photo_url, brochure_url)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddssssssssss", $name, $description, $price, $special_price, $start_date, $end_date, $highlights, $inclusions, $exclusions, $additional_info, $spiritual_guide, $season, $photo_url, $brochure_url);

    // Set parameters and execute
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

    // Execute statement
    if ($stmt->execute()) {
        $tour_id = $conn->insert_id;
        echo "New tour added successfully";

        // Insert destinations
        if (!empty($_POST['destination_ids'])) {
            foreach ($_POST['destination_ids'] as $destination_id) {
                $conn->query("INSERT INTO tours_destinations (tour_id, destination_id) VALUES ('$tour_id', '$destination_id')");
            }
        }

        // Insert itinerary details
        foreach ($_POST['itineraries'] as $day_number => $description) {
            if (!empty($description)) {
                $conn->query("INSERT INTO tour_details (tour_id, day_number, description) VALUES ('$tour_id', '$day_number', '$description')");
            }
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    // $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>Add Tour</title>
    <script>
        let dayCount = 1;

        function addItineraryField() {
            dayCount++;
            const itineraryContainer = document.getElementById('itineraryContainer');
            const newField = document.createElement('div');
            newField.innerHTML = `<label for="itineraries[${dayCount}]">Day ${dayCount}:</label><br>
                                  <textarea name="itineraries[${dayCount}]" rows="4" cols="50"></textarea><br>`;
            itineraryContainer.appendChild(newField);
        }
    </script>
</head>
<body>

<h2>Add Tour</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Name: <input type="text" name="name"><br>
  Description: <textarea name="description"></textarea><br>
  Price: <input type="text" name="price"><br>
  Special Price: <input type="text" name="special_price"><br>
  Start Date: <input type="date" name="start_date"><br>
  End Date: <input type="date" name="end_date"><br>
  Highlights: <input type="text" name="highlights"><br>
  Inclusions: <input type="text" name="inclusions"><br>
  Exclusions: <input type="text" name="exclusions"><br>
  Additional Info: <input type="text" name="additional_info"><br>
  Spiritual Guide: <input type="text" name="spiritual_guide"><br>
  Season: <input type="text" name="season"><br>
  Photo URL: <input type="text" name="photo_url"><br>
  Brochure URL: <input type="text" name="brochure_url"><br>

  <h3>Destinations</h3>
  <?php
  // Fetch destinations from the database
  $dest_result = $conn->query("SELECT id, name FROM destinations");
  if ($dest_result->num_rows > 0) {
      while ($row = $dest_result->fetch_assoc()) {
          echo "<label><input type='checkbox' name='destination_ids[]' value='{$row['id']}'> {$row['name']}</label><br>";
      }
  }
  ?>

  <h3>Itinerary</h3>
  <div id="itineraryContainer">
      <label for="itineraries[1]">Day 1:</label><br>
      <textarea name="itineraries[1]" rows="4" cols="50"></textarea><br>
  </div>
  <button type="button" onclick="addItineraryField()">Add Day</button><br><br>

  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">Back to Admin Index</a>

</body>
</html>
