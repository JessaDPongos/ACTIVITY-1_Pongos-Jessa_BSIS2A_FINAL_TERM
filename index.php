<?php
    require_once "config.php";
    
    function h($str){
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }

    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $uname = $_POST['uname'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param('s', $uname);
        $stmt->execute();

        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        $stmt->close();

        if($user && $password === $user['Password']){
            if($user['Status'] !== "Approved"){
                $errors[] = "Your account has not been approved by the admin.";
            }else{
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('location: dashboard.php');
                
                exit;
            }
        }else{
            $errors[] = "Incorrect username or password.";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Sign In</title>
</head>
<body>

    <div class="login-container">

        <h1>Clothing Store</h1>
        <h2>Sign In to your Account</h2>

        <form action="" method="post">

            <label for="uname">Username:</label>
            <input type="text" name="uname" id="uname"
                   placeholder="Enter Username" required autofocus>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password"
                   placeholder="Enter Password" required>

            <?php foreach ($errors as $e): ?>
                <p class="error-message"><?= h($e) ?></p>
            <?php endforeach; ?>

            <input type="submit" value="Sign In">

        </form>

        <p class="register-link">
            Don't have an Account?
            <a href="register.php">Register</a>
        </p>

    </div>

</body>
</html>