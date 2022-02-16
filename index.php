<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
include('rooms.php');
?>
<!DOCTYPE html5>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <title>Проект по Уеб технологии - Управление на график по етажи</title>
    <meta name="author" content="Антония Няголова, Яна Спасова,Александър Георгиев, Габриела Петрова, Хюлиа Мустафа">
    <meta name="description" content="Проект по Уеб технологии 2022">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body onload="appendBuilding()">
    <nav id="building-nav" class="topNavBar">
    </nav>
    <main id="main">
        <nav id="setTime">
            <div class="coolLabel">Дата:</div>
            <input id="dateInput" type="date" class="glowyBox">
            <div class="coolLabel">Час:</div>
            <input id="timeInput" type="time" class="glowyBox">
            <div id="checkAvailability" class="coolButton selectedButton">Виж заетост</div>
            <div id="openForm" class="coolButton selectedButton">Запази зала</div>
            <a class="coolButton selectedButton " href="add_building.php">Добави сграда</a>
            <a class="coolButton selectedButton " href="add_floor.php">Добави карта на етаж</a>
            <a class="coolButton selectedButton " href="add_room.php">Добави стая</a>
        </nav>
        <figure id="map">
        </figure>

        <figure id="pop-up-room" class="hidden">
            <div class="darker"></div>
            <div id="pop-up-room-img">
                <p id="pop-up-room-img-title"></p>
                <div id="pop-up-room-img-side-text"></div>
            </div>
            <div id="x">X</div>
        </figure>

        <div id='formContainer' class='hidden'>
            <div class="darker"></div>
            <div id="formBackground"></div>
            <form id="saveRoom" method=post name="saveRoom">
                <ul style="list-style: none;">
                    <div class="coolLabel biggerTitle">Запази стая:</div>
                    <select id="building" type="text" name="building"></li>
                        <option value="ФМИ">ФМИ</option>
                        <option value="ФзФ">ФзФ</option>
                        <option value="ФХФ">ФХФ</option>
                    </select>
                    <li><input id="floor" type="number" name="floor" placeholder="Етаж"></li>
                    <li><input id="room" type="text" name="room" placeholder="Стая"></li>
                    <input id="saveDate" type="date" name="day">
                    <input id="saveTime" type="number" min="7" max="20" name="time" placeholder="Начален час">
                    <input id="duration" type="number" name="duration" placeholder="Продължителност">
                    <li><input id="lecturerName" type="text" name="lecturerName" placeholder="Преподавател"></li>
                    <li><input id="subjectTitle" type="text" name="subjectTitle" placeholder="Предмет"></li>
                    <li><select id="courseType" type="text" name="courseType"></li>
                    <option value="с">семинар</option>
                    <option value="л">лекция</option>
                    <option value="п">практикум</option>
                    </select></li>
                    <li><input id="speciality" type="text" name="speciality" placeholder="Специалност"></li>
                    <li><input id="online-link" type="text" name="online-link" placeholder="Онлайн линк"></li>
                    <input id="year" type="number" min="1" max="4" name="year" placeholder="Курс">
                    <input id="groupAdm" type="number" min="1" max="8" name="groupAdm" placeholder="Група">
                    <div id="formButtons">
                        <div id="saveForm" class="coolButton selectedButton">Запази зала</div>
                        <div id="closeForm" class="coolButton selectedButton">Отказ</div>
                    </div>
                </ul>
            </form>
        </div>

    </main>
    <?php include('footer.php'); ?>

    <script>
        window.onload=createBuildingFloorButtons();
    </script>
</body>

</html>