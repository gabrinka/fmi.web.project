<?php include('rooms.php');?>
<!DOCTYPE html5>
<html lang = "bg">
    <head>
        <meta charset = "UTF-8">
        <title>Проект по Уеб технологии - Управление на график по етажи</title>
        <meta name="author" content = "Антония Няголова, Яна Спасова">
        <link rel = "stylesheet" href = "./css/style.css">
    </head>
    <body>
        <nav id = "building-nav" class = "topNavBar">
            <div id = "ФХФ-button" class = "topNavButton">ФХФ</div>
            <div id = "ФзФ-button" class = "topNavButton">ФзФ</div>
            <div id = "ФМИ-button" class = "topNavButton selectedButton">ФМИ</div>
        </nav>
        <main>
		  <form action="login.php" method="post">

        <h2>LOGIN</h2>

        <?php if (isset($_GET['error'])) { ?>

            <p class="error"><?php echo $_GET['error']; ?></p>

        <?php } ?>

        <label>User Name</label>

        <input type="text" name="uname" placeholder="User Name"><br>

        <label>Password</label>

        <input type="password" name="password" placeholder="Password"><br> 

        <button type="submit">Login</button>

     </form>
            <nav id = "ФМИ-nav" class = "sideNavBar">
                <div>
                    <div class = "sideNavButton">Етаж 0</div>
                    <div class = "sideNavButton">Етаж 1</div>
                    <div class = "sideNavButton">Етаж 2</div>
                </div>
                <div>
                    <div class = "sideNavButton selectedButton">Етаж 3</div>
                    <div class = "sideNavButton">Етаж 4</div>
                    <div class = "sideNavButton">Етаж 5</div>
                </div>
            </nav>
            <nav id = "ФХФ-nav" class = "sideNavBar hidden">
                <div>
                    <div class = "sideNavButton">Етаж 1</div>
                    <div class = "sideNavButton selectedButton">Етаж 2</div>
                </div>
                <div>
                    <div class = "sideNavButton">Етаж 6</div>
                </div>
            </nav>
            <nav id = "ФзФ-nav" class = "sideNavBar hidden">
                <div>
                    <div class = "sideNavButton selectedButton">Етаж 2</div>
                </div>
            </nav>
            <nav id = "setTime">
                <div class = "coolLabel">Дата:</div>
                <input id = "dateInput" type = "date" class = "glowyBox">
                <div class = "coolLabel">Час:</div>
                <input id = "timeInput" type = "time" class = "glowyBox">
                <div id = "checkAvailability" class = "coolButton selectedButton">Виж заетост</div>
                <div id = "openForm" class = "coolButton selectedButton">Запази зала</div>
            </nav>
            <figure id = "map">
            </figure>

            <figure id = "pop-up-room" class = "hidden">
                <div class = "darker"></div>
                <div  id = "pop-up-room-img">
                    <div id = "pop-up-room-img-title"></div>
                    <div id = "pop-up-room-img-side-text"></div>
                </div>
                <div id = "saveFromRoom">Запази зала</div>
                <div id = "x">X</div>
            </figure>
            <div id = 'formContainer' class = 'hidden'>
                <div class = "darker"></div>
                <div id = "formBackground"></div>
                <form id = "saveRoom" method = post name = "saveRoom" action = "room.php">  
                    <div class = "coolLabel biggerTitle">Запази стая:</div>
                    <div class = "inputContainer">
                        <input id = "building" type="text" name = "building" placeholder = "Сграда">  
                        <input id = "floor" type="text" name = "floor" placeholder = "Етаж">
                        <input id = "room" type="text" name = "room" placeholder = "Стая">
                    </div>
                    <div class = "inputContainer">
                        <input id = "saveDate" type="date" name = "day">  
                        <input id = "saveTime" type="time" name = "time">
                    </div>
                    <div class = "inputContainer">
                        <input id = "duration" type="number" name = "duration" placeholder = "Продължителност">  
                    </div>
                    <div class = "inputContainer">
                        <input id = "lecturerName" type = "text" name = "lecturerName" placeholder = "Преподавател"> 
                        <input id = "subjectTitle" type = "text" name = "subjectTitle" placeholder = "Предмет"> 
                        <select id = "courseType" type = "text" name = "courseType">  
                            <option value = "с">семинар</option> 
                            <option value = "л">лекция</option> 
                            <option value = "п">практикум</option> 
                        </select>
                    </div>
                    <div class = "inputContainer">
                        <input id = "speciality" type = "text" name = "speciality" placeholder = "Специалност"> 
                        <input id = "year" type = "text" name = "year" placeholder = "Курс"> 
                        <input id = "groupAdm" type = "text" name = "groupAdm" placeholder = "Група">  
                        
                    </div>
                    <div id = "formButtons">
                        <div id = "saveForm" class = "coolButton selectedButton">Запази зала</div>
                        <div id = "closeForm" class = "coolButton selectedButton">Отказ</div>
                    </div>
                </form>
            </div>
        </main>

        <!--<script src = "./js/code.js"></script>-->
    </body>
</html>