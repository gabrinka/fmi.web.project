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

<body>
    <nav id="building-nav" class="topNavBar">

        <div id="ФХФ-button" class="topNavButton">ФХФ</div>
        <div id="ФзФ-button" class="topNavButton">ФзФ</div>
        <div id="ФМИ-button" class="topNavButton selectedButton">ФМИ</div>
    </nav>
    <main>
        <nav id="ФМИ-nav" class="sideNavBar">
            <div>
                <div class="sideNavButton">Етаж 0</div>
                <div class="sideNavButton">Етаж 1</div>
                <div class="sideNavButton">Етаж 2</div>
            </div>
            <div>
                <div class="sideNavButton selectedButton">Етаж 3</div>
                <div class="sideNavButton">Етаж 4</div>
                <div class="sideNavButton">Етаж 5</div>
            </div>
        </nav>
        <nav id="ФХФ-nav" class="sideNavBar hidden">
            <div>
                <div class="sideNavButton">Етаж 1</div>
                <div class="sideNavButton selectedButton">Етаж 2</div>
            </div>
            <div>
                <div class="sideNavButton">Етаж 6</div>
            </div>
        </nav>
        <nav id="ФзФ-nav" class="sideNavBar hidden">
            <div>
                <div class="sideNavButton selectedButton">Етаж 2</div>
            </div>
        </nav>
        <nav id="setTime">
            <div class="coolLabel">Дата:</div>
            <input id="dateInput" type="date" class="glowyBox">
            <div class="coolLabel">Час:</div>
            <input id="timeInput" type="time" class="glowyBox">
            <div id="checkAvailability" class="coolButton selectedButton">Виж заетост</div>
            <div id="openForm" class="coolButton selectedButton">Запази зала</div>

            <!-- adding building -->

            <a class = "coolButton selectedButton " href = "add_building.php">Добави сграда</a>
            <a class = "coolButton selectedButton " href = "add_room.php">Добави стая</a>
        </nav>
        <figure id="map">
        </figure>

        <figure id="pop-up-room" class="hidden">
            <div class="darker"></div>
            <div id="pop-up-room-img">
                <p id="pop-up-room-img-title"></p>
                <div id="pop-up-room-img-side-text"></div>
            </div>
            <div id="saveFromRoom">Запази зала</div>
            <!-- <div id="exitFromRoom">Затвори</div> -->
            <div id="x">X</div>
        </figure>
        <div id='formContainer' class='hidden'>
            <div class="darker"></div>
            <div id="formBackground"></div>
            <form id="saveRoom" method=post name="saveRoom" action="room.php">
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
                <input id="saveTime" type="number"  min="7" max="20" name="time" placeholder="Начален час">
                
                
                <input id="duration" type="number" name="duration" placeholder="Продължителност">                
                
                <li><input id="lecturerName" type="text" name="lecturerName" placeholder="Преподавател"></li>
                <li><input id="subjectTitle" type="text" name="subjectTitle" placeholder="Предмет"></li>
                <li><select id="courseType" type="text" name="courseType"></li>
                        <option value="с">семинар</option>
                        <option value="л">лекция</option>
                        <option value="п">практикум</option>
                    </select></li>
                
                
                    <li><input id="speciality" type="text" name="speciality" placeholder="Специалност"></li>
                    <input id="year" type="number"  min="1" max="4" name="year" placeholder="Курс">
                    <input id="groupAdm" type="number"  min="1" max="8" name="groupAdm" placeholder="Група">

                
                <div id="formButtons">
                    <input id="saveForm" type="submit" class="coolButton selectedButton"  value="Запази зала">
                    <div id="closeForm" class="coolButton selectedButton">Отказ</div>
                </div>
                </ul>
            </form>
        </div>
        
    </main>
    <?php include('footer.php'); ?>
    <!--<script src = "./js/code.js"></script>-->
    
    <script src="js/index.js"></script>
    
</body>

</html>