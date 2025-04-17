<?php 
session_start();
$userid = $_SESSION["username"];

// 检查表单是否已提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 确保表单字段已定义
    if (isset($_POST["PollStartingTime"]) && isset($_POST["PollEndingTime"])) {
        $pollStartingTime = $_POST["PollStartingTime"];
        $pollEndingTime = $_POST["PollEndingTime"];

        // 这里添加你的表单处理逻辑
    } else {
        echo "表单字段未正确提交";
    }
}
?>


<html>
<head>
   
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>content</title>
    
    <style>
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            text-align: left;
            border: none;
            outline: none;
            transition: 0.4s;
            position: relative; 
        }
        .panel {
            padding: 0 18px;
            display: none;
            background-color: white;
            overflow: hidden;
        }
        .active {
            background-color: #ccc;
        }
        .tablink {
            background-color: #aaa; 
            color: white; 
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            float: left; 
            margin-right: 10px; 
        }
        .tablink:hover {
            background-color: #555; 
        }
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
            clear: both; 
        }
        .accordion-text {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .option {
            margin-bottom: 10px;
        }

    </style>

    </style>
</head>
<body>
    <div>
        <h1>Welcome to the voting system</h1>
    </div>
    <!--    Here is the navigation bar -->

<div style="clear: both; padding-bottom: 20px;">
    <button class="tablink" data-tab="Home" onclick="openPage('Home',this ,'#444')">Home</button>
    <button class="tablink" data-tab="PollCreation" onclick="openPage('PollCreation', this, '#333')">PollCreation</button>
    <button class="tablink" data-tab="PollList" onclick="openPage('PollList', this, '#333')">Poll list</button>
    <button class="tablink" data-tab="MyPoll" onclick="openPage('MyPoll', this, '#333')">My Polls</button>
    <button class="tablink" data-tab="MyProfile" onclick="openPage('MyProfile', this, '#333')">My Profile</button>
    <a href="logout.php" class="tablink" data-tab="Logout" onclick="openPage('Logout',this,'#333'); return false;">
        Logout</a>
</div>

    
    

<div id="Home" class="tabcontent">
    <h1>Welcome to the voting system</h1>
    <p>In here you can :</p>
    <ul>
        <li>Create a Poll</li>
        <li>Vote to a poll</li>
        <li>View the data of the Poll</li>
    </ul>

</div>



<!--Here is the Poll creation funtion-->




<div id="PollCreation" class="tabcontent">




<div id="timeError" style="color: red; margin: 10px 0;"></div>
<form method="post" action="poll_creation.php" onsubmit="return validatePollTimes();">
    <h1>Welcome to the Poll Creation function</h1>
    <p>please enter the details of your poll</p>

    <!-- Existing fields remain the same -->
    <label>Poll Title:</label>
    <input type="text" id="PollTitle" name="PollTitle" required><br><br>
    <label>Poll Stating Time:</label>
    <input type="datetime-local" id="PollStartingTime" name="PollStartingTime"><br>
    <label>Poll Ending Time:</label>
    <input type="datetime-local" id="PollEndingTime" name="PollEndingTime"><br>
    <label>Poll Description:</label>
    <input type="text" id="PollDescription" name="PollDescription" required><br><br>
    <label>Allow mutiple chioces:</label>
    <input type="radio" id="AllowMutipleChoice" name="AllowMutipleChoice"><br>

    <!-- Fixed choices section -->
    <h3>Poll Choices</h3>
    <div id="options">
        <!-- Initial choices -->
        <div class="option">
            <label>Choice 1:</label>
            <input type="text" name="options[]" required>
        </div>
        <div class="option">
            <label>Choice 2:</label>
            <input type="text" name="options[]" required>
        </div>
    </div>
    
    <!-- Add Choice button OUTSIDE options div -->
    <button type="button" onclick="addOption()" style="margin-top: 10px;">Add New Choice</button><br>
    
    <input type="submit" value="submit">
  </form>
</div>




<div id="PollList" class="tabcontent">
    <h3>View Results</h3>
    <p>View the results of the poll by selecting the poll you want to view:</p>
     
    <?php
    $severname = "localhost";
    $DB_username ="root";
    $DB_password = "";
    $DB_name = "3117";
    @ $db = new mysqli($severname, $DB_username, $DB_password, $DB_name);
    
    if (mysqli_connect_errno()){
        echo"Error : Could not connect to database.  Please try again later.'; ";
        exit();
    }

    $query_poll = "select * from poll";
    $result = $db->query($query_poll);
    $num_results = $result->num_rows;

    if ($num_results == 0){
       echo "There are not any poll now.";
    } else{
        for($i = 0; $i < $num_results; $i++){
            $row_poll = $result->fetch_assoc(); 
            $pollid = "Poll" . $i; ?>  
            
            <button class="accordion" onclick="togglePanel('<?php echo $pollid; ?>', this)"><?php echo $row_poll['Poll_Title']; ?>
                <span class="accordion-text">View</span></button>        

            <div id="<?php echo $pollid; ?>" class="panel"> 
              <h3><?php echo $row_poll['Poll_Title']; ?></h3>
              <p>Creator: <?php echo $row_poll['Creater'];  ?></p>
              <p>Poll Starting Time: <?php echo $row_poll['Starting_Time'];?></p>
              <p>Poll Ending Time: <?php echo $row_poll['Ending_Time'];?></p>
              <p><?php echo $row_poll['Description']; ?></p>

            <?php
              // 获取投票选项
              $poll_id = $row_poll['Poll_id'];
              $query = "SELECT Choices_number, Choice_Content FROM poll_choices WHERE Poll_ID = '$poll_id'";
              $result_choices = $db->query($query);
          
              if ($result_choices && $result_choices->num_rows > 0) {
                  while ($row = $result_choices->fetch_assoc()) {
                      $choice_content = $row['Choice_Content'];
                ?>
                    <ul>
                        <li><?php echo $choice_content;?></li>
                    </ul>
                <?php
                  }
              } else {
                  echo "没有找到投票选项。";
              } 
              ?>
     



               <form method="post" action="votepape.php">
                    <input type="hidden" name="poll_id" value="<?php echo $row_poll['Poll_id']; ?>">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <input type="hidden" name="creater" value="<?php echo $row_poll['Creater'];  ?>">
                    <input type="hidden" name="poll_title" value="<?php echo $row_poll['Poll_Title']; ?>">
                    <input type="hidden" name="starting_time" value="<?php echo $row_poll['Starting_Time']; ?>">
                    <input type="hidden" name="ending_time" value="<?php echo $row_poll['Ending_Time']; ?>">
                    <input type="hidden" name="description" value="<?php echo $row_poll['Description']; ?>">
                    <input type="submit" value="vote">
                </form>  
                    
                    
                <form method="post" action="view_result.php">
                <input type="hidden" name="poll_id" value="<?php echo $row_poll['Poll_id']; ?>">
                <input type="hidden" name="creater" value="<?php echo $row_poll['Creater'];  ?>">
                <input type="hidden" name="poll_title" value="<?php echo $row_poll['Poll_Title']; ?>">
                <input type="hidden" name="starting_time" value="<?php echo $row_poll['Starting_Time']; ?>">
                <input type="hidden" name="ending_time" value="<?php echo $row_poll['Ending_Time']; ?>">
                <input type="hidden" name="description" value="<?php echo $row_poll['Description']; ?>">
                <input type="submit" value="view_Result"></form>


            </div>
    
    <?php } } 
    $db->close(); ?>
</div>


<div id="MyPoll" class="tabcontent">
    <h3>My Polls</h3>
    <p>View the polls you have created:</p><br>

    <?php
    @ $db = new mysqli($severname,  $DB_username, $DB_password, $DB_name );
    
    if (mysqli_connect_errno()) { 
      echo 'Error: Could not connect to database.  Please try again later.'; 
       exit();
     } 
     $query_mypoll = "select * from poll where Creater = '".$userid."'";
     $result = $db->query($query_mypoll); 
     $num_results = $result->num_rows;

     if ($num_results == 0) {
        echo "You don't create any polls. <br>"; 
    }else {
      for ($i = 0; $i <$num_results; $i++) {
        $row_mypoll = $result->fetch_assoc(); 
        $pollid = "MyPoll_created" . $i; ?>
    
        <button class="accordion" onclick="togglePanel('<?php echo $pollid; ?>', this)">
            <?php echo $row_mypoll['Poll_Title']; ?> <span class="accordion-text">View</span></button>
            
        <div id="<?php echo $pollid; ?>" class="panel"> 
            <h3><?php echo $row_mypoll['Poll_Title']; ?> </h3>
            <p>Creator: <?php echo $row_mypoll['Creater']; ?></p>
            <p>Poll Starting Time: <?php echo $row_mypoll['Starting_Time']; ?></p>
    
            <p>Poll Ending Time: <?php echo $row_mypoll['Ending_Time']; ?></p>
            

            <p><?php echo $row_mypoll['Description']; ?></p>
            <?php
              // 获取投票选项
              $poll_id = $row_mypoll['Poll_id'];
              $query = "SELECT Choices_number, Choice_Content FROM poll_choices WHERE Poll_ID = '$poll_id'";
              $result_choices = $db->query($query);
          
              if ($result_choices && $result_choices->num_rows > 0) {
                  while ($row = $result_choices->fetch_assoc()) {
                      $choice_content = $row['Choice_Content'];
                ?>
                    <ul>
                        <li><?php echo $choice_content;?></li>
                    </ul>
                <?php
                  }
              } else {
                  echo "没有找到投票选项。";
              } 
              ?>


            <form method="post" action="votepape.php">

                    <input type="hidden" name="poll_id" value="<?php echo $row_mypoll['Poll_id']; ?>">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <input type="hidden" name="creater" value="<?php echo $row_mypoll['Creater'];  ?>">
                    <input type="hidden" name="poll_title" value="<?php echo $row_mypoll['Poll_Title']; ?>">
                    <input type="hidden" name="starting_time" value="<?php echo $row_mypoll['Starting_Time']; ?>">
                    <input type="hidden" name="ending_time" value="<?php echo $row_mypoll['Ending_Time']; ?>">
                    <input type="hidden" name="description" value="<?php echo $row_mypoll['Description']; ?>">
                    <input type="submit" value="vote">
                </form>  
                    
                    
                <form method="post" action="view_result.php">
                <input type="hidden" name="poll_id" value="<?php echo $row_mypoll['Poll_id']; ?>">
                <input type="hidden" name="creater" value="<?php echo $row_mypoll['Creater'];  ?>">
                <input type="hidden" name="poll_title" value="<?php echo $row_mypoll['Poll_Title']; ?>">
                <input type="hidden" name="starting_time" value="<?php echo $row_mypoll['Starting_Time']; ?>">
                <input type="hidden" name="ending_time" value="<?php echo $row_mypoll['Ending_Time']; ?>">
                <input type="hidden" name="description" value="<?php echo $row_mypoll['Description']; ?>">

                <input type="submit" value="view_Result"></form>
    
        </div>
    <?php } } 
    $db->close();
    ?>

    <p>View polls that you voted: </p><br>
    <?php
    @ $db = new mysqli($severname,  $DB_username, $DB_password, $DB_name );
    
    if (mysqli_connect_errno()) { 
      echo 'Error: Could not connect to database.  Please try again later.'; 
       exit();
     } 
     $query_myvote = "
     SELECT r.*, p.*
     FROM poll_record r
     JOIN poll p ON r.Poll_ID = p.Poll_ID
     WHERE r.Userid = '".$db->real_escape_string($userid)."'";
 
 $result_myvote = $db->query($query_myvote); 
 $num_results_myvote = $result_myvote->num_rows;
 
 if ($num_results_myvote == 0) {
    echo "You don't vote any polls. <br>"; 
} else {
    for ($i = 0; $i < $num_results_myvote; $i++) {
        $row_myvote = $result_myvote->fetch_assoc(); 
        $pollid = "MyPoll_voted" . $i; ?>
        
        <button class="accordion" onclick="togglePanel('<?php echo $pollid; ?>', this)">
            <?php echo htmlspecialchars($row_myvote['Poll_Title']); ?> <span class="accordion-text">View</span>
        </button>
        
        <div id="<?php echo $pollid; ?>" class="panel"> 
            <h3><?php echo htmlspecialchars($row_myvote['Poll_Title']); ?></h3>
            <p>Creator: <?php echo htmlspecialchars($row_myvote['Creater']); ?></p>
            <p>Poll Starting Time: <?php echo htmlspecialchars($row_myvote['Starting_Time']); ?></p>
            <p>Poll Ending Time: <?php echo htmlspecialchars($row_myvote['Ending_Time']); ?></p>
            <p><?php echo htmlspecialchars($row_myvote['Description']); ?></p>
            <p>Your choice:</p>

            <?php
            // 获取投票选项 
            $poll_id = $row_myvote['Poll_id'];
            $query_myvote_choice = "SELECT pc.* 
            FROM poll_choices pc 
            JOIN poll_record pr ON pc.Poll_ID = pr.Poll_ID 
            WHERE pr.Userid = '$userid' and pc.Poll_ID = '$poll_id'
            and pr.Choice_ID = pc.Choices_number"  ; // 根據 Poll_ID 查詢

            $result_vote_choices = $db->query($query_myvote_choice);
          
            if ($result_vote_choices && $result_vote_choices->num_rows > 0) {
                while ($row = $result_vote_choices->fetch_assoc()) {
                    $choice_content = $row['Choice_Content'];
            ?> 
                    <ul>
                        <li><?php echo htmlspecialchars($choice_content); ?></li>
                    </ul>
            <?php
                }
            } else {
                echo 'You has not voted';
            }
            ?>

            <form method="post" action="votepape.php">
                <input type="hidden" name="poll_id" value="<?php echo htmlspecialchars($row_myvote['Poll_id']); ?>">
                <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userid); ?>">
                <input type="hidden" name="creater" value="<?php echo htmlspecialchars($row_myvote['Creater']); ?>">
                <input type="hidden" name="poll_title" value="<?php echo htmlspecialchars($row_myvote['Poll_Title']); ?>">
                <input type="hidden" name="starting_time" value="<?php echo htmlspecialchars($row_myvote['Starting_Time']); ?>">
                <input type="hidden" name="ending_time" value="<?php echo htmlspecialchars($row_myvote['Ending_Time']); ?>">
                <input type="hidden" name="description" value="<?php echo htmlspecialchars($row_myvote['Description']); ?>">
                <input type="submit" value="vote">
            </form>  

            <form method="post" action="view_result.php">
                <input type="hidden" name="poll_id" value="<?php echo htmlspecialchars($row_myvote['Poll_id']); ?>">
                <input type="hidden" name="creater" value="<?php echo htmlspecialchars($row_myvote['Creater']); ?>">
                <input type="hidden" name="poll_title" value="<?php echo htmlspecialchars($row_myvote['Poll_Title']); ?>">
                <input type="hidden" name="starting_time" value="<?php echo htmlspecialchars($row_myvote['Starting_Time']); ?>">
                <input type="hidden" name="ending_time" value="<?php echo htmlspecialchars($row_myvote['Ending_Time']); ?>">
                <input type="hidden" name="description" value="<?php echo htmlspecialchars($row_myvote['Description']); ?>">
                <input type="submit" value="view_Result">
            </form>
        </div>
    <?php 
    }
}
$db->close();
?>
         
    
    <h3>View Results</h3>
    <p>View the results of the poll by selecting the poll you want to view:</p>

</div>






<div id="MyProfile" class="tabcontent">
    <h3>My Profile</h3>
    <p>View your profile and edit it:</p>
    
    <?php
    @ $db = new mysqli($severname,  $DB_username, $DB_password, $DB_name );
    
    if (mysqli_connect_errno()) { 
      echo 'Error: Could not connect to database.  Please try again later.'; 
     }  
     $query = "SELECT * FROM users WHERE userid = '".$userid."'";
     $result = $db->query($query);
 
     if ($result) {
             $row = $result->fetch_assoc();
             if (!empty($row['ProfileImage'])){ ?>
                <img src="<?php echo htmlspecialchars($row['ProfileImage']); ?>" alt="Profile Image" style="max-width: 200px; max-height: 200px;">
            <?php }else { ?>
                <p>No profile image uploaded</p>

            <?php }
             echo '<p>user Nickname: ' . htmlspecialchars($row['Nick_name']) . '</p>';
             echo '<p>email: ' . htmlspecialchars($row['Email']) . '</p>'; ?>
             
        <?php } else {
   
         echo 'Error: Could not execute query. Please try again later.';
     }
    $db->close(); ?>

</div>
<div>
    Content information<br>
    Email:whatshouldIeat@gmail.com<br>
    Phone Namber:12345678<br>
</div>

<script>
    function openPage(pageName, elmnt, color) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = color;
        }
        document.getElementById(pageName).style.display = "block";
    }

    function togglePanel(panelId, elmnt) {
        var panel = document.getElementById(panelId);
        var textElement = elmnt.querySelector('.accordion-text');
        if (panel.style.display === "block") {
            panel.style.display = "none";
            elmnt.style.backgroundColor = "#eee"; 
            textElement.textContent = "View";
        } else {
            panel.style.display = "block";
            elmnt.style.backgroundColor = "#333";
            textElement.textContent = "Regain";
        }
    }
    window.addEventListener('DOMContentLoaded', function() {
    // 使用'Home'作为默认值（注意首字母大写）
    const targetTab = window.location.hash.substring(1) || 'Home';
    
    // 查找匹配的tab按钮
    const tabButton = document.querySelector(`[data-tab="${targetTab}"]`);
    
    if (tabButton) {
        tabButton.click();
    } else {
        // 强制跳转到Home标签
        document.querySelector('[data-tab="Home"]').click();
        window.location.hash = 'Home'; // 更新URL哈希
    }
});


function addOption() {
    const optionsDiv = document.getElementById('options');
    // 修正：直接根据现有选项数量计算编号
    const optionCount = optionsDiv.getElementsByClassName('option').length + 1;
    
    const newOption = document.createElement('div');
    newOption.className = 'option';
    newOption.innerHTML =  `
        <label>Choice ${optionCount}:</label>
        <input type="text" name="options[]" required>
        <button type="button" onclick="removeOption(this)">Delete Option</button>
    `;
    optionsDiv.appendChild(newOption);
}

// 更新选项编号时应该从1开始计数
function updateOptionNumbers() {
    const options = document.querySelectorAll('.option');
    options.forEach((option, index) => {
        const label = option.querySelector('label');
        if (label) {
            label.textContent = `Choice ${index + 1}:`; // 从1开始重新编号
        }
    });
}


function removeOption(button) {
    button.parentElement.remove();
    // 可选：更新后续选项的编号
    updateOptionNumbers(); 
}



function validatePollTimes() {
    const startInput = document.getElementById('PollStartingTime');
    const endInput = document.getElementById('PollEndingTime');
    const errorContainer = document.getElementById('timeError');
    
    // 清除之前的错误信息
    errorContainer.textContent = '';
    
    // 获取当前时间（ISO格式，精确到秒）
    const now = new Date();
    now.setSeconds(0, 0); // 去除毫秒
    const currentISO = now.toISOString().slice(0, 16);
    
    // 验证开始时间
    if (startInput.value && new Date(startInput.value) < now) {
        errorContainer.textContent = 'the start time must be later than the current time';
        startInput.focus();
        return false; // 阻止表单提交
    }
    
    // 验证结束时间
    if (endInput.value && startInput.value) {
        if (new Date(endInput.value) <= new Date(startInput.value)) {
            errorContainer.textContent = 'the end time must be later than the start time';
            endInput.focus();
            return false; // 阻止表单提交
        }
    }
    
    return true; // 允许表单提交
}











</script>

</body>
</html>