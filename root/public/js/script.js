window.onload = function () {
    function displayYearMonth() {
        const yearMonth = document.getElementById("inputYearMonth").value;
        const year = yearMonth.substr(0, 4);
        if (yearMonth.substr(5, 1) == 0) {
            var month = yearMonth.substr(6, 1);
        } else {
            month = yearMonth.substr(5, 2);
        }
        document.getElementById("year").innerHTML = year;
        document.getElementById("month").innerHTML = month;
    }
    displayYearMonth();

    const buttonDisplay = document.getElementById("buttonDisplay");
    buttonDisplay.onclick = function () {
        displayYearMonth();
    }
}