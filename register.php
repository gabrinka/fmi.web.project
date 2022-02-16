<?php
require_once "db.php";
require_once "common.php";

$connection = db_connect();
$username = $userType = $fn = $password = $confirm_password = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);

    if (count(getUserByAttribute($connection, "username", $username)) > 0) {
        alert("Такъв потребител вече съществува!", "register.php");
    }

    $userType = $_POST["user-type"];

    if (strcmp($userType, "Студент")) {
        //check if fn is already existing
        $userType = 's';
        $fn = trim($_POST["fn"]);
        if (count(getUserByAttribute($connection, "fn", $fn)) > 0) {
            alert("Такъв потребител с факултетен номер вече съществува!", "register.php");
        }
    } else {
        $userType = 'e';
    }

    // Validate password
    $password = trim($_POST["password"]);
    $password = password_hash($password, PASSWORD_DEFAULT);


    insertUser($connection, $username, $userType, $fn, $password);

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
        <select id="user-type" name="user-type" onchange="shouldInputFn(this);">
            <option value="student" id="student">Студент</option>
            <option value="educator" disabled>Преподавател</option>
        </select>
        <label id="fn-label">Факултетен номер</label>
        <input type="text" placeholder="Факултетен номер" name="fn" id="fn" style="display:block;">
        <button id="submit" type="submit">Регистрация</button>
        <p>Имате вече акаунт? <a href="login.php">Вход</a>.</p>
    </form>
    <script>
        window.addEventListener("DOMContentLoaded", function() {
            var fname = document.getElementById("username");
            var fpwd = document.getElementById("password");
            var fpwd_conf = document.getElementById("password-confirm");
            var fn = document.getElementById("fn");
            document.getElementById("submit").addEventListener("click", function() {
                if (fname.validity.valueMissing) {
                    fname.setCustomValidity("Потребителското име не може да е празно поле!");
                    fname.reportValidity();
                    return;
                } else {
                    var userNameRegex = new RegExp('^[a-zA-Z0-9_]+$');
                    if (!userNameRegex.test(fname.value)) {
                        fname.setCustomValidity("Потребителското име може да съдържа само цифри букви и долна черта!");
                        fname.reportValidity();
                        return;
                    }
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
                    return;
                } else {
                    fpwd_conf.setCustomValidity("");
                }

                if (fn.style.display == "block") {
                    if (fn.validity.valueMissing) {
                        fn.setCustomValidity("Факултетния номер не може да е празно поле!");
                        fn.reportValidity();
                    } else if (fn.value.length < 5) {
                        fn.setCustomValidity("Факултетния номер трябва да е повече от 5 символа!");
                        fn.reportValidity();
                    } else if (fn.value.length == 5 || fn.value.length == 6) {
                        var oldFnRegex = new RegExp('^[0-9]+$');
                        if (!oldFnRegex.test(fn.value)) {
                            fn.setCustomValidity("Факултетния номер на старите номера трябва да съдържа само цифри, пр. 81920!");
                            fn.reportValidity();
                        }
                    } else if (fn.value.length == 10) {
                        //new format
                        var newFnRegex = new RegExp('^[0-9]MI[0-9]+$');

                        if (!newFnRegex.test(fn.value)) {
                            fn.setCustomValidity("Факултетния номер на новите номера трябва да е този формат, пр. 8MI3400677!");
                            fn.reportValidity();
                        }
                    } else {
                        fn.setCustomValidity("Факултетния номер не отговаря на формата!");
                        fn.reportValidity();
                    }
                }
            });
        });
    </script>
    <script src="./js/register-login-js.js"></script>
</body>

</html>