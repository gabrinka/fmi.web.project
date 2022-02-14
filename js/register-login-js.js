
function shouldInputFn(userTypeSelected) {
    studentTypeValue = document.getElementById("student").value;
    if (studentTypeValue == userTypeSelected.value) {
        document.getElementById("fn").style.display = "block";
        document.getElementById("fn-label").style.display = "block";
    } else {
        document.getElementById("fn").style.display = "none";
        document.getElementById("fn-label").style.display = "none";
    }
}