<?php
session_start();

$servername  = "localhost";
$DB_username = "root";
$DB_password = "";
$DB_name     = "3117";

// Retrieve posted data
$poll_id = $_POST['poll_id'];
$userid  = $_POST['userid'];  
$choice  = isset($_POST['choice']) ? $_POST['choice'] : '';  

// Debug: Check if required fields are set
if (empty($poll_id) || empty($choice)) {
    echo "Missing poll ID or selected choice.";
    exit();
}

// Connect to the database
$db = new mysqli($servername, $DB_username, $DB_password, $DB_name);
if ($db->connect_errno) {
    echo "Connection error: " . $db->connect_error;
    $_SESSION['state'] = "connect_fail";
    header("Location: state.php");
    exit();
}

// Check if the choice exists
$check_choice_query = "SELECT * FROM poll_choices WHERE Choice_ID = '$choice'";
$check_result = $db->query($check_choice_query);

if ($check_result->num_rows == 0) {
    echo "Invalid choice selected.";
    exit();
}

// Prepare the SQL statement to prevent SQL injection
$stmt = $db->prepare("INSERT INTO poll_record (Poll_ID, Choice_ID, Userid) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $poll_id, $choice, $userid);

// Execute the statement
if ($stmt->execute()) {
    echo "Vote recorded successfully.";
    echo '<script>setTimeout(function() { window.location = "MainContent.php"; }, 4000);</script>';
} else {
    echo "Error recording vote: " . $stmt->error;
    echo '<script>setTimeout(function() { window.location = "MainContent.php"; }, 4000);</script>';
}

// Close the statement and database connection
$stmt->close();
$db->close();
?>