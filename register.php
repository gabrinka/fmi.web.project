<?php
require_once "db.php";
require_once "common.php";

$connection = db_connect();
$username = $password = $confirm_password = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        alert("Username can only contain letters, numbers, and underscores.","register.php");
    } else {
        $username = trim($_POST["username"]);

        if (count(getUser($connection, $username)) > 0) {
            alert("Такъв потребител вече съществува!","register.php");
            
        } 
    }

    // Validate password
    $password = trim($_POST["password"]);
    $password = password_hash($password, PASSWORD_DEFAULT);
    insertUser($connection,$username,$password);

    $connection = null;
    header("location:login.php");
}
?>

<!DOCTYPE html5>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Регистрация</h2>
        <label>Потребителско име</label>
        <input type="text" placeholder="Въведете потребителско име" name="username" id="username" required>
        <label>Парола</label>
        <input type="password" placeholder="Въведете парола" name="password" id="password" required>
        <label>Потвърдете парола</label>
        <input type="password" placeholder="Потвърдете парола" name="password-confirm" id="password-confirm" required>
        <label>Тип потребител</label>
        <select id="user-type" name="user-type">
            <option value="student">Студент</option>
            <option value="educator">Преподавател</option>
        </select>
        <button id="submit" type="submit">Регистрация</button>
        <p>Имате вече акаунт? <a href="login.php">Вход</a>.</p>
    </form>
    <script>
        window.addEventListener("DOMContentLoaded", function() {
            var fname = document.getElementById("username");
            var fpwd = document.getElementById("password");
            var fpwd_conf = document.getElementById("password-confirm");
            document.getElementById("submit").addEventListener("click", function() {
                if (fname.validity.valueMissing) {
                    fname.setCustomValidity("Потребителското име не може да е празно поле!");
                    fname.reportValidity();
                    return;
                } else {
                    fname.setCustomValidity("");
                }

                if (fpwd.validity.valueMissing) {
                    fpwd.setCustomValidity("Паролата не може да е празно поле!");
                    fpwd.reportValidity();
                    return
                } else {
                    fpwd.setCustomValidity("");
                }

                if (fpwd_conf.validity.valueMissing) {
                    fpwd_conf.setCustomValidity("Паролата не може да е празно поле!");
                    fpwd_conf.reportValidity();
                    return
                } else {
                    fpwd_conf.setCustomValidity("");
                }

                if (fpwd.value != fpwd_conf.value) {
                    fpwd_conf.setCustomValidity("Паролите не съвпадат!");
                    fpwd_conf.reportValidity();
                } else {
                    fpwd_conf.setCustomValidity("");
                }
            });
        });
    </script>
</body>
</html>