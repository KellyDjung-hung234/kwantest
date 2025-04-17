<?php
session_start();
$username = $_COOKIE['C_username'];
$poll_title = $_POST['PollTitle'];
$starting_time = $_POST['PollStartingTime'];
$ending_time = $_POST['PollEndingTime'];
$description = $_POST['PollDescription'];
$options = $_POST['options'];

// 检查$options数组中是否至少有两个选项
if(count($options) < 2) {
    echo "you must input at least two options.<br />"
        . "please go back and input again.";
    echo '<script>setTimeout(function() { window.location = "MainContent.php#PollCreation"; }, 4000);</script>';
    exit();
}

$choice1 = $options[0];
$choice2 = $options[1];

$servername = "localhost";
$DB_username = "root";
$DB_password = "";
$DB_name = "3117";
@$db = new mysqli($servername, $DB_username, $DB_password, $DB_name);

if (mysqli_connect_errno()) {
     $_SESSION['state'] = "connect_fail";
    echo '<script>setTimeout(function() { window.location = "MainContent.php#PollCreation"; }, 4000);</script>';
    exit();
}

if (!$poll_title || !$choice1 || !$choice2) {
    $_SESSION['state'] = "have_empty";
    echo '<script>setTimeout(function() { window.location = "MainContent.php#PollCreation"; }, 4000);</script>';
    exit();
} else {
    // 首先插入投票数据，并获取生成的 Poll_ID
    $query = "INSERT INTO poll (Creater, Poll_Title, Starting_Time, Ending_Time, Description) 
              VALUES ('$username', '$poll_title', '$starting_time', '$ending_time', '$description')";
    $result = $db->query($query);

    if ($result) {
        // 获取最后插入的 Poll_ID
        $poll_id = $db->insert_id;

        // 然后插入选项数据
        foreach ($options as $index => $option) {
            if (!empty($option)) {
                $choice_content = $db->real_escape_string($option);
                $choices_number = $index + 1; // 选项索引加一
                $query_choices = "INSERT INTO poll_choices (Poll_ID, Choices_number, Choice_Content) 
                                  VALUES ('$poll_id', '$choices_number', '$choice_content')";
                $result_choices = $db->query($query_choices);

                if (!$result_choices) {
                    $_SESSION['state'] = "create_fail";
                    echo '<script>setTimeout(function() { window.location = "MainContent.php#PollCreation"; }, 4000);</script>';
                    exit();
                }
            }
        }

        $db->close();
        echo "poll create success.";
        echo '<script>setTimeout(function() { window.location = "MainContent.php#PollCreation"; }, 4000);</script>';
        exit();
    } else {
        $db->close();
        echo "poll create fail, some input data is wrong.";
        echo '<script>setTimeout(function() { window.location = "MainContent.php#PollCreation"; }, 4000);</script>';
        exit();
    }
}
?>
