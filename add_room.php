<?php
require_once "db.php";
require_once "common.php";

$connection = db_connect();


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    $building = trim($_POST["building"]);
    $room = trim($_POST["room"]);
    $floor = trim($_POST["floor"]);
    $type = trim($_POST["type"]);
    $seatsCnt = trim($_POST["seatsCnt"]);
    $computers = trim($_POST["computers"]);
    $whiteBoard = trim($_POST["whiteBoard"]);
    $projector = trim($_POST["projector"]);
    $sector = trim($_POST["sector"]);

  
    if (count(selectRoom($connection, $building, $room)) == 0) 
    {
        insertRoom($connection,$building,$room,$floor,$type,$seatsCnt,$computers,$whiteBoard,$projector,$sector);
    }
    else{
        editRoom($connection,$building,$room,$floor,$type,$seatsCnt,$computers,$whiteBoard,$projector,$sector);
    }
    $connection = null;
    header("location:index.php");
}
?>

<!DOCTYPE html5>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Промени стая</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2> Редактирай стая </h2>
        <h3> Забележка: Ако стаята не съществува ще се създаде нова, ако вече съществува ще се редактира! </h3>
        <label>Сграда </label>
        <input type="text" placeholder="Въведете име на сграда" name="building" id="username" required>
        <label>Номер на стая</label>
        <input type="text" placeholder="Номер на стая" name="room" id="room" required>
        <label>Номер на етаж</label>
        <input type="text" placeholder="Въведете номер на  етаж" name="floor" id="floor" required>
        <label>Тип</label>
        <input type="text" placeholder="Тип" name="type" id="type" required>
        <label>Брой места</label>
        <input type="text" placeholder="Брой места" name="seatsCnt" id="seatsCnt" required>
    
        <label>С/Без компютри</label>
        <input type="text" placeholder="д/н" name="computers" id="computers" required>
        <label>С/Без бяла дъска</label>
        <input type="text" placeholder="д/н" name="whiteBoard" id="whiteBoard" required>
        <label>С/Без проектор</label>
        <input type="text" placeholder="д/н" name="projector" id="projector" required>
        <label>Сектор</label>
        <input type="text" placeholder="д/л/ц" name="sector" id="sector" required>
        <button id="submit" type="submit">Редактирай зала</button>
        <button type="back-button" onclick="window.location='index.php';return false;">Отказ</button>
       
    </form>
    
    </body>
</html>