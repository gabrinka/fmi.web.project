<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}


require_once "db.php";
require_once "common.php";

$connection = db_connect();
$username = $userType = $password = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $result = getUserCredentials($connection, $username);

    if (empty($result)) {
        alert("Не съществува такова потребителско име!","login.php");
    }

    $id = $result[0]['id'];
    $hashed_password = $result[0]['password'];
    if (password_verify($password, $hashed_password)) {
        // Password is correct, so start a new session
        session_start();

        // Store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $id;
        $_SESSION["username"] = $username;
        $_SESSION["userType"] = get($connection,"SELECT userType from users WHERE id = {$id}")[0]["userType"];

        header("location: index.php");
    } else {
        alert("Грешна парола!","login.php");
    }

    $connection = null;
}
?>

<!DOCTYPE html5>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Вписване</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Вписване</h2>
        <label>Потребителско име</label>
        <input type="text" placeholder="Въведете потребителско име" name="username" id="username" required>
        <label>Парола</label>
        <input type="password" placeholder="Въведете парола" name="password" id="password" required>
        <button id="submit" type="submit">Вписване</button>
        <p>Нямате акаунт? <a href="register.php">Регистрация</a>.</p>
    </form>
    <script>
        window.addEventListener("DOMContentLoaded", function() {
            var fname = document.getElementById("username");
            var fpwd = document.getElementById("password");
            document.getElementById("submit").addEventListener("click", function() {
                if (fname.validity.valueMissing) {
                    fname.setCustomValidity("Потребителското име не може да е празно поле!");
                    fname.reportValidity();
                } else {
                    fname.setCustomValidity("");
                }

                if (fpwd.validity.valueMissing) {
                    fpwd.setCustomValidity("Паролата не може да е празно поле!");
                    fpwd.reportValidity();
                } else {
                    fpwd.setCustomValidity("");
                }
            });
        });
    </script>
</body>
</html>