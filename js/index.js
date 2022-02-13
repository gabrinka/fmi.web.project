
let lecturerRoom = document.getElementsByClassName("lecturer-room");
let saveRoomElement = document.getElementById("saveFromRoom");


// let roomTitle = document.querySelector("#pop-up-room-img-title");
// create new element
//const elem = document.createElement('pre');
//elem.setAttribute("class", "teacher-info")

// add text
// if(roomTitle.innerText === 'Зала 402'){
//    elem.innerHTML = 'Учител: Ивана Иванова <br>Специалност: Приложна <br>математика';
// }
// if(roomTitle.innerText === 'Зала 312'){
//     elem.innerHTML = 'Учител: Надя Петрова <br>Специалност: Информатика';
// }


let popUpRoomText = document.getElementById("pop-up-room-img-side-text");

if(lecturerRoom){
    saveRoomElement.style.display = "none";
    // popUpRoomText.parentNode.insertBefore(elem,popUpRoomText)
}


