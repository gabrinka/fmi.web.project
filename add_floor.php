<?php
require_once "db.php";
require_once "common.php";

$connection = db_connect();


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $building = trim($_POST["building"]);
    $floor = trim($_POST["floors"]);
    $rooms = trim($_POST["rooms"]);
    insertFloor($connection,$building,$floor,$rooms);
    $roomArray = str_replace("|", " ", $rooms);
    $roomArray = explode(" ", $roomArray);
    for ($i = 0; $i < count($roomArray); $i++) {
        if ($roomArray[$i] != 'x' && $roomArray[$i] != 't' && $roomArray[$i] != 's')
        {
            insertRoom($connection, $building, $roomArray[$i], $floor, 1,0,'н','н','н','н');
        }
    }
    $connection = null;
    header("location:index.php");
}
?>

<!DOCTYPE html5>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Добавяне на карта на етажа</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Добавяне на карта на етажа</h2>
        <label>Сграда </label>
        <input type="text" placeholder="Въведете име на сграда" name="building" id="building" required>
        <label>Етаж</label>
        <input type="text" placeholder="Въведете етаж" name="floors" id="floors" required>
        <label>Разположение на стаите на етажа</label>
        <input type="text" placeholder="Въведете разположение на стаите на етажа" name="rooms" id="rooms" required>
        
        <a href="index.php">  
        <button id="submit" type="submit">Добави етаж</button>
        </a>
       
       
    </form>
    </body>


    
</html>