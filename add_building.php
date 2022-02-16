<?php
require_once "db.php";
require_once "common.php";

$connection = db_connect();


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    $building = trim($_POST["building"]);
    $floors = trim($_POST["floors"]);
    insertBuilding($connection,$building,$floors);

    $connection = null;
    header("location:index.php");
}
?>

<!DOCTYPE html5>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Регистрация на сграда</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Регистрация на нова сграда</h2>
        <label>Сграда </label>
        <input type="text" placeholder="Въведете име на сграда" name="building" id="username" required>
        <label>Етажи</label>
        <input type="text" placeholder="Въведете етажи" name="floors" id="floors" required>
        
        <a href="index.php">  
        <button id="submit" type="submit">Регистрация</button>
        </a>
		
		 <button type="back-button" onclick="window.location='index.php';return false;">Отказ</button>
       
       
    </form>
    </body>


    
</html>