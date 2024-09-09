<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST["question"];
    $answer = $_POST["answer"];

    $sql = "INSERT INTO faq (question, answer) 
            VALUES ('$question', '$answer')";

    if ($conn->query($sql) === TRUE) {
        echo "New FAQ added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
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

<h2>Add FAQ</h2>
<form method="post" action="">
  Question: <input type="text" name="question" required><br>
  Answer: <textarea name="answer" required></textarea><br>
  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
