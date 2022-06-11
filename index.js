var select = document.getElementById('course_date');
var value = select.options[select.selectedIndex].value;
$valid = 'date' + value;

if (value == 1) {
    document.getElementById($valid).style.display = "none";
}