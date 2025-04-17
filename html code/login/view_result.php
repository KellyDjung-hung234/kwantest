<?php
$poll_id = $_POST['poll_id'];
$creater = $_POST['creater'];
$poll_title = $_POST['poll_title'];
$starting_time = $_POST['starting_time'];
$ending_time = $_POST['ending_time'];
$description = $_POST['description'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLL result</title>
</head>
<body>
    <h3><?php echo $poll_title; ?></h3>
    <p>Creater: <?php echo $creater;  ?></p>
    <p>Starting time: <?php echo $starting_time;?></p>
    <p>Ending time: <?php echo $ending_time;?></p>
    <p><?php echo $description; ?></p>

    <?php
$servername = "localhost";
$DB_username = "root";
$DB_password = "";
$DB_name = "3117";
@ $db = new mysqli($servername, $DB_username, $DB_password, $DB_name);
if (mysqli_connect_errno()) {
    echo "connect_error";
    $_SESSION['state'] = "connect_fail";
    header("Location: state.php");
    exit();
}

// 获取每个选项的投票数量
$query_votes = "SELECT Choice_ID, COUNT(*) as num_votes FROM poll_record WHERE Poll_id = '$poll_id' GROUP BY Choice_ID";
$result_votes = $db->query($query_votes);

$vote_counts = []; // 初始化陣列來存儲投票數
if ($result_votes) {
    while ($row_votes = $result_votes->fetch_assoc()) {
        $vote_counts[$row_votes['Choice_ID']] = $row_votes['num_votes']; // 使用 Choice_ID 作為鍵
    }
}

// 获取投票选项
$query = "SELECT Choices_number, Choice_Content FROM poll_choices WHERE Poll_ID = '$poll_id'";
$result_choices = $db->query($query);

if ($result_choices && $result_choices->num_rows > 0) {
    while ($row = $result_choices->fetch_assoc()) {
        $choice_number = $row['Choices_number'];
        $choice_content = $row['Choice_Content'];

        $num_votes = isset($vote_counts[$choice_number]) ? $vote_counts[$choice_number] : 0; // 如果未找到，則設置為 0

        echo "<p>Choice $choice_number: $choice_content - voting number: $num_votes</p>";
    }
} else {
    echo "No choices found。";
}

$db->close();
?>
     
        <form action="MainContent.php">
        <input type="submit" value="Back">
    </form>

</body>
</html>
