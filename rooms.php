<?php
include 'db.php';
// include 'add_building.php';

session_start();

$connection = db_connect();

$buildingsStatement = "SELECT building, floors
            FROM buildings";

$buildingsInfo = get($connection, $buildingsStatement);

$buildings = "";
$dataForButtons = "";
for ($i = 0; $i < sizeof($buildingsInfo); ++$i) {
  $buildingName = $buildingsInfo[$i]['building'];
  $buildings .= $buildingName . ':';
  $dataForButtons .= $buildingName . ':' . $buildingsInfo[$i]['floors'] . '|' ;
  $floors = explode(',', $buildingsInfo[$i]['floors']);
  foreach ($floors as $f) {
    $buildings .= $f . '-' . getFloors($connection, $buildingName, $f) . '*';
  }
  $buildings .= '_';
}

$rooms = "";
for ($i = 0; $i < sizeof($buildingsInfo); ++$i) {
  $buildingName = $buildingsInfo[$i]['building'];
  $rooms .= $buildingName . ':';
  $floors = explode(',', $buildingsInfo[$i]['floors']);
  foreach ($floors as $f) {
    $rooms .= $f . '-';
    $roomsInfo = getRooms($connection, $buildingName, $f);
    for ($r = 0; $r < sizeof($roomsInfo); ++$r) {
      $rooms .= $roomsInfo[$r]['room'] . '=' . $roomsInfo[$r]['type'] . ' ' . $roomsInfo[$r]['seatsCnt'] . ' ' . $roomsInfo[$r]['computers'] . ' ' . $roomsInfo[$r]['whiteBoard'] . '?';
    }
    $rooms .= '*';
  }
  $rooms .= '_';
}

$statement = "SELECT date, building, room, floor, title, type, lecturer, speciality,onlineLink, groupAdm, year, duration
      FROM roomTaken";
$roomsTaken = get($connection, $statement);
?>

<script type="text/javascript">
  let building;
  let floorNum;
  let roomTypes = {};
  let allRooms = getMapRooms();
  let allRoomData = getRoomData();
  let availableData = getAvailability();

  

  function createFloor(floors, roomTypes) {
    let map = document.getElementById('map');
    let div = document.createElement('div');
    div.className = 'rooms';
    div.id = `${building}-${floorNum}`;
    for (let i = 0; i < floors.length; ++i) {
      for (let j = 0; j < floors[i].length; ++j) {
        let child = document.createElement('div');
        let room = floors[i][j];
        if (room == 'x' || room == 's' || room == 't') {
          if (room == 'x') {
            child.className = 'not-available';
          } else if (room == 's') {
            child.className = 'stairs';
          } else if (room == 't') {
            child.className = 'wc';
            child.innerHTML = 'WC';
          }
        } else {
          let roomType = roomTypes[room].type;
          child.innerHTML = room;
          if (roomType == '1' && roomTypes[room].computers == '??') {
            child.className = 'seminar-room';
          } else if (roomType == '3') {
            child.className = 'lecture-room';
          } else if (roomType == '2') {
            child.className = 'big-computer-room';
          } else if (roomType == '1') {
            child.className = 'small-computer-room';
          } else if (roomType == '4') {
            child.className = 'lecturer-room';

          }
        }
        div.appendChild(child);
      }
      let breakFlex = document.createElement('div');
      breakFlex.className = 'breakFlex';
      div.appendChild(breakFlex);
    }
    //div.classList.add('hidden');
    map.parentNode.insertBefore(div, map.nextSibling);
  }

  function colorFree(floorName, roomTypes, availableRooms) {
    curFloor = document.getElementById(floorName).children;
    for (let i = 0; i < curFloor.length; ++i) {
      let room = curFloor[i].innerHTML;
      if (room == '' || room == 'WC') {
        continue;
      }
      if (isTaken(availableRooms, room)) {
        curFloor[i].classList.remove('empty-room');
        curFloor[i].classList.add('taken-room');
      } else {
        let roomType = roomTypes[room].type;
        if (roomType == '4') {
          curFloor[i].classList.remove('taken-room');
          curFloor[i].classList.add('lecturer-room-color');
        } else {
          curFloor[i].classList.remove('taken-room');
          curFloor[i].classList.add('empty-room');
        }
      }
    }
  }

  function callSelectFloor(event) {
    floorNum = parseInt(this.innerHTML.substring(5, 6));
    selectFloor(null);
    event.target.classList.add('selectedButton');
  }

  function selectFloor(date) {
    let mapRooms = allRooms[building][floorNum];
    let roomData = allRoomData[building][floorNum];

    if (date == null) {
      let hours = document.getElementById('timeInput').value;
      let day = document.getElementById('dateInput').value;
      date = day + ' ' + hours.substring(0, 3) + '00:00';
    }

    if (availableData[date] == null || availableData[date][building] == null || availableData[date][building][floorNum] == null) {
      availableRooms = null;
    } else {
      availableRooms = availableData[date][building][floorNum];
    }

    roomTypes = {};
    for (key in roomData) {
      let attributes = roomData[key].split(' ');
      roomTypes[key] = {
        type: attributes[0],
        seatsCnt: attributes[1],
        computers: attributes[2],
        whiteBoard: attributes[3]
      }
    }
    document.getElementById('map').nextSibling.remove();
    createFloor(mapRooms.split('|').map(row => row.split(' ')), roomTypes);
    colorFree(`${building}-${floorNum}`, roomTypes, availableRooms);

    let rooms = document.getElementById(`${building}-${floorNum}`).childNodes;
    rooms.forEach(child => {
      child.addEventListener('click', popRoom);
    });

    let floors = document.getElementsByClassName('sideNavButton');
    Array.prototype.forEach.call(floors, floor => {
      floor.classList.remove('selectedButton');
    });
  }

  function popRoom(event) {
    let elem = event.target;
    let popUpRoom = document.getElementById('pop-up-room');
    let title = document.getElementById('pop-up-room-img-title');
    let text = document.getElementById('pop-up-room-img-side-text');

    let roomNum = elem.innerHTML;
    let roomObj = roomTypes[roomNum];

    let popUpRoomImg = document.getElementById('pop-up-room-img');

    let roomType = '????????????????????';
    if (elem.classList.contains('big-computer-room')) {
      roomType = '???????????? ????????????????????';
      popUpRoomImg.style.backgroundImage = 'url("./img/big-computer-room.png")';
    } else if (elem.classList.contains('small-computer-room')) {
      roomType = '?????????? ????????????????????';
      popUpRoomImg.style.backgroundImage = 'url("./img/small-computer-room.png")';
    } else if (elem.classList.contains('lecture-room')) {
      roomType = '?????????????????? ????????';
      popUpRoomImg.style.backgroundImage = 'url("./img/lecture-room.png")';
    } else if (elem.classList.contains('seminar-room')) {
      roomType = '?????????????????? ????????';
      popUpRoomImg.style.backgroundImage = 'url("./img/seminar-room.png")';
    } else if (elem.classList.contains('lecturer-room')) {
      roomType = '?????????????????????????????? ????????';
      popUpRoomImg.style.backgroundImage = 'url("./img/lecturer.png")';
    }

    let whiteBoard = (roomObj.whiteBoard == '??') ? '????' : '????';

    title.innerHTML = `???????? ${elem.innerHTML}`;




    let hours = document.getElementById('timeInput').value;
    let day = document.getElementById('dateInput').value;
    date = day + ' ' + hours.substring(0, 3) + '00:00';

    if (availableData[date] == null || availableData[date][building] == null || availableData[date][building][floorNum] == null || availableData[date][building][floorNum][roomNum] == null) {
      text.innerHTML = `<pre>??????:\n${roomType}\n\n???????? ??????????: ${roomObj.seatsCnt}\n\n???????? ??????????: ${whiteBoard}</pre>`
    } else {
      let temp = availableData[date][building][floorNum][roomNum];

      text.innerHTML = `<pre>????????????-????????:\n${temp.onlineLink}\n\n??????:\n${roomType}\n\n???????? ??????????: ${roomObj.seatsCnt}\n\n???????? ??????????: ${whiteBoard}\n\n??????????\n${temp.title}-${temp.type} ${temp.lecturer}\n${temp.speciality} ${temp.groupAdm} ??????????</pre>`
    }
    popUpRoom.classList.remove('hidden');
  }

  function addZero(attr) {
    return (attr < 10) ? `0${attr.toString()}` : attr.toString();
  }

  function setTimeFieldsToToday() {
    let dateRaw = new Date();

    //set date
    let dateInput = document.getElementById('dateInput');
    let year = dateRaw.getFullYear().toString();
    let month = addZero(dateRaw.getMonth() + 1);
    let day = addZero(dateRaw.getDate());
    let dateFormatted = `${year}-${month}-${day}`;
    dateInput.value = dateFormatted;

    //set time
    let timeInput = document.getElementById('timeInput');
    let hours = addZero(dateRaw.getHours());

    let minutes = addZero(dateRaw.getMinutes());
    let seconds = addZero(dateRaw.getSeconds());
    let timeFormatted = `${hours}:${minutes}:${seconds}`;
    timeInput.value = timeFormatted;
  }

  function closeFormFunc() {
    formContainer = document.getElementById('formContainer');
    formContainer.classList.add('hidden');
    document.querySelectorAll('#saveRoom > div > input').forEach(elem => {
      elem.value = '';
    })
  }

  window.addEventListener('load', (event) => {
    setTimeFieldsToToday();

    let buildingButtons = document.querySelectorAll('.topNavButton');
    buildingButtons.forEach(button => button.addEventListener('click', function(event) {
      let elem = event.target;
      buildingButtons.forEach(button => button.classList.remove('selectedButton'));
      elem.classList.add('selectedButton');
      floorNum = 2;
      building = elem.id.split('-')[0];
      selectFloor(null);

      sideNavBars = document.querySelectorAll(".sideNavBar");
      sideNavBars.forEach(elem => {
        if (!elem.classList.contains('hidden'))
          elem.classList.add('hidden')
      });
      curSideBar = document.getElementById(`${building}-nav`);
      curSideBar.classList.remove('hidden');
      let buttons = document.querySelectorAll(`#${building}-nav > div > .sideNavButton`);
      buttons.forEach(button => {
        if (button.innerHTML.split(' ')[1] == 2) {
          button.classList.add('selectedButton');
        }
      })
    }));

    building = '??????';
    floorNum = 3;
    let mapRooms = allRooms[building][floorNum];
    let roomData = allRoomData[building][floorNum];
    let hours = document.getElementById('timeInput').value;
    let day = document.getElementById('dateInput').value;
    date = day + ' ' + hours.substring(0, 3) + '00:00';

    if (availableData[date] == null || availableData[date][building] == null || availableData[date][building][floorNum] == null) {
      availableRooms = null;
    } else {
      availableRooms = availableData[date][building][floorNum];
    }
    roomTypes = {};
    for (key in roomData) {
      let attributes = roomData[key].split(' ');
      roomTypes[key] = {
        type: attributes[0],
        seatsCnt: attributes[1],
        computers: attributes[2],
        whiteBoard: attributes[3]
      }
    }
    createFloor(mapRooms.split('|').map(row => row.split(' ')), roomTypes);
    colorFree(`${building}-${floorNum}`, roomTypes, availableRooms);

    let list = document.getElementsByClassName('sideNavButton');
    Array.from(list).forEach(item => {
      item.addEventListener("click", callSelectFloor);
    });

    let x = document.getElementById('x');
    x.addEventListener('click', closeCloseUp);


    function defineWarning(warning, message, warningId) {
      warning.id = warningId;
      warning.innerHTML = message;
      warning.classList.add('warning');
      let ok = document.createElement('div');
      ok.id = 'ok';
      ok.innerHTML = 'ok';
      warning.appendChild(ok);

      ok.addEventListener('click', function() {
        document.getElementById(warningId).classList.add('hidden');
        if (warningId == 'open-form-warning') {
          document.getElementById('formContainer').classList.add('hidden');
        }
      });

      formContainer = document.getElementById('formContainer');
      warning.classList.add('hidden');
      formContainer.appendChild(warning);
      formContainer.classList.add('hidden');
    }

    let closeForm = document.getElementById('closeForm');
    closeForm.addEventListener('click', closeFormFunc);

    let openFormWarning = document.createElement('div');
    defineWarning(openFormWarning, "???????????????????? ???? ?????????? ???? ???????????????? ????????!", 'open-form-warning');

    let openForm = document.getElementById('openForm');
    defineOpenForm(openForm);

    function defineOpenForm(openForm) {
      openForm.addEventListener('click', function() {

        var userType = "<?php echo $_SESSION['userType']; ?>";

        if ("s" == userType) {
          openFormWarning.classList.remove('hidden');
          document.getElementById('formContainer').classList.remove('hidden');

        } else {
          formContainer = document.getElementById('formContainer');
          let date = document.getElementById('dateInput').value;
          let time = document.getElementById('timeInput').value;
          document.getElementById('saveDate').value = date;
          document.getElementById('saveTime').value = time;
          document.getElementById('building').value = building;
          document.getElementById('floor').value = floorNum;

          formContainer.classList.remove('hidden');
        }
      });
    }

    let saveFormWarning = document.createElement('div');
    defineWarning(saveFormWarning, "???? ???????? ???? ???????? ???????????????? ???????? ?? ???????????????????? ??????????!", 'save-form-warning');

    let saveForm = document.getElementById('saveForm');
    defineSaveForm(saveForm);

    function defineSaveForm(saveForm) {
      saveForm.addEventListener('click', function(event) {
        let formContainer = document.getElementById('formContainer');

        let building = document.getElementById('building').value;
        let floor = document.getElementById('floor').value;
        let room = document.getElementById('room').value;
        let temphour = document.getElementById('saveTime').value.split(':')[0];
        let tempday = document.getElementById('saveDate').value;
        let date = tempday + ' ' + temphour + ':00:00';
        let duration = document.getElementById('duration').value;
        let lecturerName = document.getElementById('lecturerName').value;
        let subjectTitle = document.getElementById('subjectTitle').value;
        let courseType = document.getElementById('courseType').value;
        let speciality = document.getElementById('speciality').value;
        let onlineLink = document.getElementById('online-link').value;
        let year = document.getElementById('year').value;
        let groupAdm = document.getElementById('groupAdm').value;

        let rooms = document.getElementById(`${building}-${floorNum}`).childNodes;
        let free = true;
        for (elem of rooms) {
          if (elem.innerHTML == room && elem.classList.contains('taken-room')) {
            free = false;
            break;
          }
        }

        if (!free || building == '' || floor == '' || room == '' || temphour == '' || date == '' || duration == '' || lecturerName == '' ||
          subjectTitle == '' || courseType == '' || speciality == '' || year == '' || groupAdm == '') {

          saveFormWarning.classList.remove('hidden');
          document.getElementById('formContainer').classList.remove('hidden');

        } else {
          if (availableData[date] == null) {
            availableData[date] = {};
          }
          if (availableData[date][building] == null) {
            availableData[date][building] = {};
          }
          if (availableData[date][building][floor] == null) {
            availableData[date][building][floor] = {};
          }
          if (availableData[date][building][floor][room] == null) {
            availableData[date][building][floor][room] = {};
          }

          availableData[date][building][floor][room] = {
            'duration': duration,
            'groupAdm': groupAdm,
            'lecturer': lecturerName,
            'speciality': speciality,
            'onlineLink': onlineLink,
            'title': subjectTitle,
            'type': courseType,
            'year': year
          };

          hours = parseInt(temphour);
          for (let d = 1; d < duration; ++d) {
            hours = hours >= 24 ? 0 : hours + 1;
            let temph = tempday + ' ' + addZero(hours) + ':00:00';
            if (availableData[temph] == null) {
              availableData[temph] = {};
            }
            if (availableData[temph][building] == null) {
              availableData[temph][building] = {};
            }
            if (availableData[temph][building][floor] == null) {
              availableData[temph][building][floor] = {};
            }
            availableData[temph][building][floor][room] = availableData[date][building][floor][room];
          }

          selectFloor(date);
          let buttons = document.querySelectorAll(`#${building}-nav > div > .sideNavButton`);
          buttons.forEach(button => {
            if (button.innerHTML.split(' ')[1] == floorNum) {
              button.classList.add('selectedButton');
            }
          })
          closeFormFunc();
          submitForm();
        }
      });
    };

    function submitForm() {
      var data = new FormData(document.getElementById('saveRoom'));

      var http = new XMLHttpRequest();
      http.open("POST", "saveRoom.php", true);
      http.send(data);

      return false;
    }

    let checkAvailability = document.getElementById('checkAvailability');
    checkAvailability.addEventListener('click', function() {
      let hours = document.getElementById('timeInput').value;
      let day = document.getElementById('dateInput').value;
      date = day + ' ' + hours.substring(0, 3) + '00:00';
      selectFloor(date);
      let buttons = document.querySelectorAll(`#${building}-nav > div > .sideNavButton`);
      buttons.forEach(button => {
        if (button.innerHTML.split(' ')[1] == floorNum) {
          button.classList.add('selectedButton');
        }
      })
    });


    let rooms = document.getElementById(`${building}-${floorNum}`).childNodes;
    rooms.forEach(child => {
      child.addEventListener('click', popRoom);
    });
  });




  let clickHandler = function(event) {
    let elem = event.target;
  }
  let closeCloseUp = function(event) {
    let elem = event.target;
    let fig = elem.parentElement;
    fig.classList.add("hidden");
  }

  function getMapRooms() {
    let phpData = "<?php echo $buildings; ?>";
    mapRooms = phpData.split('_').filter(y => y).map(x => x.split(':').map(x => x.split('*').filter(y => y).map(x => x.split('-'))));
    mapObj = {};
    for (let i = 0; i < mapRooms.length; ++i) {
      mapObj[mapRooms[i][0]] = {};
      for (let j = 0; j < mapRooms[i][1].length; ++j) {
        mapObj[mapRooms[i][0]][mapRooms[i][1][j][0]] = mapRooms[i][1][j][1]
      }
    }
    return mapObj;
  }

  function getRoomData() {
    let phpData = "<?php echo $rooms; ?>";
    roomData = phpData.split('_').filter(y => y).map(x => x.split(':').map(x => x.split('*').filter(y => y).map(x => x.split('-').map(x => x.split('?').filter(y => y).map(x => x.split('='))))));
    roomObj = {};
    for (let i = 0; i < roomData.length; ++i) {
      roomObj[roomData[i][0]] = {};
      for (let j = 0; j < roomData[i][1].length; ++j) {
        roomObj[roomData[i][0]][roomData[i][1][j][0]] = {};
        for (let k = 0; k < roomData[i][1][j][1].length; ++k) {
          roomObj[roomData[i][0]][roomData[i][1][j][0]][roomData[i][1][j][1][k][0]] = roomData[i][1][j][1][k][1];
        }
      }
    }
    return roomObj;
  }

  function getAvailability() {
    let availabilityData = <?php echo json_encode($roomsTaken); ?>;

    availabilityObj = {};
    for (let i = 0; i < availabilityData.length; ++i) {
      if (availabilityObj[availabilityData[i]['date']] == null) {
        availabilityObj[availabilityData[i]['date']] = {};
      }
      if (availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']] == null) {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']] = {};
      }
      if (availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']] == null) {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']] = {};
      }

      const notAllowed = ['building', 'floor', 'room', 'date'];
      if (availabilityObj[availabilityData[i]['date']] == null || availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']] == null || availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']] == null ||
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']] == null) {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']] = Object.keys(availabilityData[i]).filter(key => !notAllowed.includes(key))
          .reduce((result, key) => {
            result[key] = availabilityData[i][key];
            return result;
          }, {});
      } else {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']]['groupAdm'] += ',' + availabilityData[i]['groupAdm']
      }
      let day = availabilityData[i]['date'].split(' ')[0];
      let hours = parseInt(availabilityData[i]['date'].split(' ')[1].split(':')[0]);
      for (let d = 1; d < availabilityData[i]['duration']; ++d) {
        hours = hours >= 24 ? 00 : hours + 1;
        let temp = day + ' ' + addZero(hours) + ':00:00';
        if (availabilityObj[temp] == null) {
          availabilityObj[temp] = {};
        }
        if (availabilityObj[temp][availabilityData[i]['building']] == null) {
          availabilityObj[temp][availabilityData[i]['building']] = {};
        }
        if (availabilityObj[temp][availabilityData[i]['building']][availabilityData[i]['floor']] == null) {
          availabilityObj[temp][availabilityData[i]['building']][availabilityData[i]['floor']] = {};
        }
        availabilityObj[temp][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']] = availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']];

      }
    }
    return availabilityObj;
  }

  function isTaken(availableRooms, room) {
    if (availableRooms == null || availableRooms[room] == null) {
      return false;
    }
    return true;
  }

  function createBuildingFloorButtons() {
            let buildingsCnt = "<?php echo sizeof($buildingsInfo); ?>"
            let buildingsInfo = "<?php echo $dataForButtons ?>";
            buildingsInfo = buildingsInfo.split('|');
            for (let i = 0; i < buildingsCnt; i++) {
                let building = buildingsInfo[i].split(':')[0];
                addBuildingButton(building);
                let floors = buildingsInfo[i].split(':')[1];
                floors = floors.split(',');
                for (let j = 0; j < floors.length; j++) {
                    addFloorButton(building, floors[j]);
                }
            }
            setTimeout(function() {document.getElementById("??????-button").click();}, 200);
        }
        function addBuildingButton(building) {
            const node = document.createElement("div");
            node.className += "topNavButton";
            node.id = building + "-button";
            const textnode = document.createTextNode(building);
            node.appendChild(textnode);
            document.getElementById("building-nav").appendChild(node);

            const option = document.createElement("option");
            option.value = building;
            const textnode1 = document.createTextNode(building);
            option.appendChild(textnode1);
            document.getElementById("building").appendChild(option);
        }
        function addFloorButton(building, floor) {
            const nav = document.createElement("nav");
            nav.className += "sideNavBar hidden";
            nav.id = building + "-nav";
            const textnode1 = document.createTextNode("");
            nav.appendChild(textnode1);
            document.getElementById("main").appendChild(nav);

            const node = document.createElement("div");
            node.className += "sideNavButton";
            node.id = building + "-button";
            const textnode = document.createTextNode("???????? " + floor);
            node.appendChild(textnode);
            document.getElementById(nav.id).appendChild(node);
        }
</script>