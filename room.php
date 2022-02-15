<table>
<?php 

require_once "db.php";
require_once "common.php";

$connection = db_connect();
    $hour =  $_POST["time"];
    if($hour < 10) $hour = "0" . $hour;
    $tempdate = $_POST["day"] . ' ' .  $hour . ":00:00";
    $day = $_POST["day"];
    $room = $_POST["room"];
    $end = $hour + $_POST["duration"];
    $conflict = false;
    for($i = $hour; $i < $end; $i++)
    {
        $checkAvailable = "SELECT 1 FROM `roomTaken` WHERE DATE(date) = \"$day\" AND HOUR(date) <= $i AND HOUR(date)+duration > $i AND room = $room";
        $availableRooms = get($connection, $checkAvailable);
        $size = sizeof($availableRooms);
        if ($size > 0)
        {
            $conflict = true;
        }
    }
    if (!$conflict) {
    
        $insertStatement = "INSERT INTO `roomTaken` VALUES (:b, :r, :f, :t, :ty, :l, :s, :g, :y, :date, :d)";
        $query = $connection->prepare($insertStatement);
        echo $_POST["building"];echo "<br>";
        echo $_POST["room"];echo "<br>";
        echo $_POST["floor"];echo "<br>";
        echo $_POST["subjectTitle"];echo "<br>";
        echo $_POST["courseType"];echo "<br>";
        echo $_POST["lecturerName"];echo "<br>";
        echo $_POST["speciality"];echo "<br>";
        echo $_POST["groupAdm"];echo "<br>";
        echo $_POST["year"];echo "<br>";
        echo $tempdate;echo "<br>";
        echo $_POST["duration"];echo "<br>";
        $query->execute(['b' => $_POST['building'], 'r' => $_POST['room'],'f' => $_POST['floor'], 't' => $_POST['subjectTitle'],
                        'ty' => $_POST['courseType'], 'l' => $_POST['lecturerName'], 's' => $_POST['speciality'],
                        'g' => $_POST['groupAdm'],'y' => $_POST['year'], 'date' => $tempdate, 'd' => $_POST['duration']]) or die('failed');
    }
    header("location: index.php");
?>
</table>