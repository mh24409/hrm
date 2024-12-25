var ctx = document.getElementById("todays_attendance");
var today_attenedence = document.getElementById("today_attenedence");
var today_absence = document.getElementById("today_absence");
var myChart = new Chart(ctx, {
    type: "pie",
    data: {
        datasets: [
            {
                data: [today_attenedence, today_absence],
                backgroundColor: ["#0054ae", "#9c27b0"],
                hoverBackgroundColor: ["#2260a3", "#9c32af"],
            },
        ],
        labels: ["Today's Present", "Today's Absent"],
    },
    options: {
        legend: false,
        responsive: true,
    },
});
