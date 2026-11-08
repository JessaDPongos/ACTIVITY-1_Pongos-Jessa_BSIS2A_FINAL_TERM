<?php
    require_once "config.php";

    function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

    $errors = [];
    $mess = "";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $uname = $_POST['uname'] ?? '';
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if ($password === $password2) {

            $stmt = $conn->prepare(
                "INSERT INTO users (username, password) VALUES (?, ?)"
            );

            if ($stmt) {

                $stmt->bind_param("ss", $uname, $password);

                if ($stmt->execute()) {
                    $mess = "Registered Successfully! Please wait for the admin approval.";
                } else {
                    $errors[] = "Something went wrong. Please try again later.";
                }

                $stmt->close();

            } else {
                $errors[] = "Database query failed.";
            }

        } else {
            $errors[] = "Passwords do not match.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>
</head>
<body>

    <div class="login-container">

        <h1>Clothing Store</h1>
        <h2>Create your Account</h2>

        <form action="" method="post">

            <label for="uname">Username:</label>
            <input type="text" name="uname" id="uname"
                   placeholder="Enter Username" required autofocus>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password"
                   placeholder="Enter Password" required>

            <label for="password2">Confirm Password:</label>
            <input type="password" name="password2" id="password2"
                   placeholder="Confirm your password" required>

            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $e): ?>
                    <p class="error-message">
                        <?= h($e) ?>
                    </p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($mess)): ?>
                <p class="success-message">
                    <?= h($mess) ?>
                </p>
            <?php endif; ?>

            <input type="submit" value="Register">

        </form>

        <p class="register-link">
            Already have an Account?
            <a href="index.php">Sign In</a>
        </p>

    </div>

</body>
</html>