<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="password.js"></script>
</head>
<body>

<?php
session_start();
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['UserID'];
    $password = $_POST['Password'];
    $password2 = $_POST['confirmPassword'];
    $nickname = $_POST['Nickname'];
    $email = $_POST['Email'];

    // 檢查密碼是否一致
    if ($password !== $password2) {
        $error_message = "the two passwords do not match!";
    } else {
        // 檢查上傳的文件
        if (isset($_FILES['ProfileImage']) && $_FILES['ProfileImage']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['ProfileImage']['tmp_name'];
            $fileName = $_FILES['ProfileImage']['name'];
            $fileSize = $_FILES['ProfileImage']['size'];
            $fileType = $_FILES['ProfileImage']['type'];

            // 檢查文件類型
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($fileType, $allowedFileTypes)) {
                // 設定目標路徑
                $uploadFileDir = './uploads/';
                $destPath = $uploadFileDir . uniqid() . '-' . basename($fileName);

                // 檢查目錄是否存在，若不存在則創建
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                // 移動上傳的文件
                if (!move_uploaded_file($fileTmpPath, $destPath)) {
                    $error_message = "failed to move uploaded file！";
                } else {
                    // 數據庫連接
                    $servername = "localhost";
                    $DB_username = "root";
                    $DB_password = "";
                    $DB_name = "3117";
                    $db = new mysqli($servername, $DB_username, $DB_password, $DB_name);

                    if ($db->connect_errno) {
                        $error_message = "Database connection failed, please try again later.";
                    } else {
                        // 檢查UserID是否已經存在
                        $stmt_check = $db->prepare("SELECT userid FROM users WHERE userid = ?");
                        $stmt_check->bind_param("s", $userid);
                        $stmt_check->execute();
                        $stmt_check->store_result();

                        if ($stmt_check->num_rows > 0) {
                            $error_message = "UserID already exists, please choose a different UserID.";
                        } else {
                            // 密碼哈希
                    

                            // 插入用戶數據
                            $query = "INSERT INTO users (userid, password, nick_name, email, profileimage) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $db->prepare($query);
                            $stmt->bind_param("sssss", $userid, $password, $nickname, $email, $destPath);

                            if ($stmt->execute()) {
                                $success_message = "Registration successful! Please log in.";
                            } else {
                                $error_message = "Registration failed. Please try again.";
                            }

                            $stmt->close();
                        }

                        $stmt_check->close();
                    }

                    $db->close();
                }
            } else {
                $error_message = "Invalid file type！";
            }
        } else {
            $error_message = "Please upload a profile image！";
        }
    }
}

?>

<!-- Display Success/Error Messages -->
<?php if (!empty($error_message)): ?>
    <div style="color: red; font-weight: bold;"><?= $error_message ?></div>
<?php elseif (!empty($success_message)): ?>
    <div style="color: green; font-weight: bold;"><?= $success_message ?></div>
<?php endif; ?>

<h1>PolyU voting system - Registration</h1>

<form method="post" action="" enctype="multipart/form-data">
    <label><H2>Login Information</H2></label>
    <label>User ID:</label>
    <input type="text" id="UserId" name="UserID" required><br><br>

    <label>Password:</label>
    <input type="password" id="Password" name="Password" required onblur="checkVaildPassword()"><br><br>

    <label>Password Confirm:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" required>
    <input type="button" id="showButton" value="Show Password" onclick="showPassword()"><br><br>

    <label><H2>Personal Information</H2></label>

    <label>Nick Name:</label>
    <input type="text" id="Nickname" name="Nickname" required><br><br>

    <label>Email:</label>
    <input type="email" id="Email" name="Email" required><br><br>

    <label>Profile Image:</label>
    <input type="file" id="ProfileImage" name="ProfileImage" required><br><br>  

    <input type="submit" value="Submit">
</form>

<a href="login.php">Return to the login page</a><br>

</body>
</html>
