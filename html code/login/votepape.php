<?php
session_start();

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a choice was selected
    if (!isset($_POST['choice']) || empty($_POST['choice'])) {
        echo "Error: No choice selected. Please go back and select an option.";
        exit;
    }

    // Retrieve required POST data
    $poll_id   = $_POST['poll_id'];
    $choice_id = $_POST['choice'];
    
 

    // Database connection parameters
    $servername  = "localhost";
    $DB_username = "root";
    $DB_password = "";
    $DB_name     = "3117";

    // Connect to the database
    $db = new mysqli($servername, $DB_username, $DB_password, $DB_name);
    if ($db->connect_errno) {
        echo "Connection Error: " . $db->connect_error;
        exit();
    }

    // Prepare the SQL statement to insert the vote record
    $stmt = $db->prepare("INSERT INTO poll_record (Poll_ID, Choice_ID) VALUES (?, ?)");
    if (!$stmt) {
        echo "Error preparing statement: " . $db->error;
        exit;
    }

    // Bind the parameters to the statement (both as integers)
    $stmt->bind_param("ii", $poll_id, $choice_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Vote inserted successfully
        echo "<h3>Vote Recorded Successfully</h3>";
        echo "<p>Thank you for voting!</p>";
        echo "<p><a href='MainContent.php'>Return to Main Page</a></p>";
    } else {
        // Handle errors during execution
        echo "Error recording vote: " . $stmt->error;
    }

    // Close the statement and the database connection
    $stmt->close();
    $db->close();
} else {
    echo "Invalid request method.";
}
?>
