var attendanceChart;
var recruitmentChart;

var AttendanceReport = JSON.parse($("#departmentWiseAttendanceReport").val());
console.log(AttendanceReport);
// Attendance chart
var optionsAttendanceReport = {
    series: [
        {
            name: "Leave %",
            data: AttendanceReport.map(function (item) {
                return item.leave;
            }),
        },
        {
            name: "Present %",
            data: AttendanceReport.map(function (item) {
                return item.present;
            }),
        },
        {
            name: "Absent %",
            data: AttendanceReport.map(function (item) {
                return item.absent;
            }),
        },
    ],
    chart: {
        type: "bar",
        height: 418,
        stacked: true,
        toolbar: {
            show: false,
        },
    },
    stroke: {
        width: 1,
        colors: ["#fff"],
    },
    dataLabels: {
        formatter: (val) => {
            return val.toFixed(0) + "%";
        },
    },
    plotOptions: {
        bar: {
            horizontal: false,
        },
    },
    xaxis: {
        categories: AttendanceReport.map(function (item) {
            const names = item.department.split(" ");
            return names.length > 1 ? names : item.department;
        }),
        labels: {
            rotate: 0,
        },
    },
    fill: {
        opacity: 1,
    },
    colors: ["#FD4830", "#00B074", "#F7C604"],
    yaxis: {
        labels: {
            formatter: (val) => {
                return val.toFixed(0) + "%";
            },
        },
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
    },
};

attendanceChart = new ApexCharts(
    document.querySelector("#attendance"),
    optionsAttendanceReport
);
attendanceChart.render();

// Position Wise Recruitment chart
var positionWiseRecruitmentChart = JSON.parse(
    $("#positionWiseAttendanceReport").val()
);
var optionsPositionWiseRecruitmentChart = {
    series: [
        {
            data: positionWiseRecruitmentChart.map(function (item) {
                return item.candidate;
            }),
        },
    ],
    chart: {
        type: "bar",
        height: 318,
        toolbar: {
            show: false,
        },
        labels: {
            show: false,
        },
    },
    plotOptions: {
        bar: {
            barHeight: "100%",
            distributed: true,
            horizontal: true,
            dataLabels: {
                position: "bottom",
                show: false,
            },
        },
    },
    colors: [
        "#FD4830",
        "#00B074",
        "#F7C604",
        "#008FFB",
        "#FD8830",
        "#00B054",
        "#F7C304",
        "#008AFB",
        "#FD8830",
        "#00B254",
        "#F7C904",
        "#009AFB",
    ],
    dataLabels: {
        enabled: true,
        textAnchor: "start",
        style: {
            colors: ["#fff"],
        },
        formatter: function (val, opt) {
            return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val;
        },
        offsetX: 0,
    },
    stroke: {
        width: 1,
        colors: ["#fff"],
    },
    xaxis: {
        categories: positionWiseRecruitmentChart.map(function (item) {
            return item.position;
        }),
        labels: {
            show: false,
        },
    },
    yaxis: {
        labels: {
            show: false,
        },
    },
    legend: {
        show: false,
        labels: {
            show: false,
        },
    },

    tooltip: {
        theme: "dark",
        x: {
            show: false,
        },
        y: {
            title: {
                formatter: function () {
                    return "";
                },
            },
        },
    },
};

var recruitmentChart = new ApexCharts(
    document.querySelector("#recruitment"),
    optionsPositionWiseRecruitmentChart
);
recruitmentChart.render();

// awarded chart
var awardChartSeries = JSON.parse($("#awardChartSeries").val());
var optionsAwardChart = {
    series: awardChartSeries,
    chart: {
        type: "bar",
        height: 300,
        stacked: true,
        toolbar: {
            show: false,
        },
    },
    stroke: {
        width: 1,
        colors: ["#fff"],
    },
    plotOptions: {
        bar: {
            horizontal: false,
        },
    },
    xaxis: {
        categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
    },
    fill: {
        opacity: 1,
    },
    colors: [
        "#FD4830",
        "#00B074",
        "#F7C604",
        "#008FFB",
        "#FD8830",
        "#00B054",
        "#F7C304",
        "#008AFB",
    ],
    yaxis: {
        labels: {
            show: false,
        },
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
    },
};

var awardedChart = new ApexCharts(
    document.querySelector("#awarded"),
    optionsAwardChart
);
awardedChart.render();

// Loan chart
var totalLoanPaid = $("#totalLoanPaid").val();
var optionsLoanPaid = {
    series: [totalLoanPaid],
    chart: {
        height: 312,
        type: "radialBar",
        toolbar: {
            show: false,
        },
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 225,
            hollow: {
                margin: 0,
                size: "70%",
                background: "#fff",
                image: undefined,
                imageOffsetX: 0,
                imageOffsetY: 0,
                position: "front",
                dropShadow: {
                    enabled: true,
                    top: 3,
                    left: 0,
                    blur: 4,
                    opacity: 0.24,
                },
            },
            track: {
                background: "#fff",
                strokeWidth: "67%",
                margin: 0, // margin is in pixels
                dropShadow: {
                    enabled: true,
                    top: -3,
                    left: 0,
                    blur: 4,
                    opacity: 0.35,
                },
            },

            dataLabels: {
                show: true,
                name: {
                    offsetY: -10,
                    show: true,
                    color: "#888",
                    fontSize: "17px",
                },
                value: {
                    formatter: function (val) {
                        return parseInt(val);
                    },
                    color: "#111",
                    fontSize: "36px",
                    show: true,
                },
            },
        },
    },
    fill: {
        type: "gradient",
        gradient: {
            shade: "dark",
            type: "horizontal",
            shadeIntensity: 0.5,
            gradientToColors: ["#ABE5A1"],
            inverseColors: true,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100],
        },
    },
    stroke: {
        lineCap: "round",
    },
    labels: ["Total Loan Amount"],
};
var loanchart = new ApexCharts(
    document.querySelector("#loan"),
    optionsLoanPaid
);
loanchart.render();

var csrf = $('meta[name="csrf-token"]').attr("content");

function positionWiseRecruitment(event) {
    var url = $("#positionWiseRecruitmentUrl").val();
    // ajax request for position wise recruitment
    $.ajax({
        url: url + "/" + event.target.value,
        type: "POST",
        data: {
            type: event.target.value,
        },
        success: function (result) {
            var newPositionOptions = {
                series: [
                    {
                        data: result.map(function (item) {
                            return item.candidate;
                        }),
                    },
                ],
                chart: {
                    type: "bar",
                    height: 318,
                    toolbar: {
                        show: false,
                    },
                    labels: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        barHeight: "100%",
                        distributed: true,
                        horizontal: true,
                        dataLabels: {
                            position: "bottom",
                            show: false,
                        },
                    },
                },
                colors: [
                    "#51BDE4",
                    "#00B074",
                    "#2B3674",
                    "#51BDE4",
                    "#FD4830",
                    "#F7C604",
                    "#4BA1A0",
                    "#7A1DB4",
                ],
                dataLabels: {
                    enabled: true,
                    textAnchor: "start",
                    style: {
                        colors: ["#fff"],
                    },
                    formatter: function (val, opt) {
                        return (
                            opt.w.globals.labels[opt.dataPointIndex] +
                            ":  " +
                            val
                        );
                    },
                    offsetX: 0,
                },
                stroke: {
                    width: 1,
                    colors: ["#fff"],
                },
                xaxis: {
                    categories: result.map(function (item) {
                        return item.position;
                    }),
                    labels: {
                        show: false,
                    },
                },
                yaxis: {
                    labels: {
                        show: false,
                    },
                },
                legend: {
                    show: false,
                    labels: {
                        show: false,
                    },
                },

                tooltip: {
                    theme: "dark",
                    x: {
                        show: false,
                    },
                    y: {
                        title: {
                            formatter: function () {
                                return "";
                            },
                        },
                    },
                },
            };
            recruitmentChart.updateOptions(newPositionOptions);
        },
        error: function (err) {
            console.log(err);
        },
    });
}

function departmentWiseAttendance(event) {
    var url = $("#departmentWiseAttendanceUrl").val();
    // ajax request for department wise attendance
    $.ajax({
        url: url + "/" + event.target.value,
        type: "POST",
        data: {
            _token: csrf,
            type: event.target.value,
        },
        success: function (result) {
            var newDepartmentOptions = {
                series: [
                    {
                        name: "Leave %",
                        data: result.map(function (item) {
                            return item.leave;
                        }),
                    },
                    {
                        name: "Present %",
                        data: result.map(function (item) {
                            return item.present;
                        }),
                    },
                    {
                        name: "Absent %",
                        data: result.map(function (item) {
                            return item.absent;
                        }),
                    },
                ],
                chart: {
                    type: "bar",
                    height: 300,
                    stacked: true,
                    toolbar: {
                        show: false,
                    },
                },
                stroke: {
                    width: 1,
                    colors: ["#fff"],
                },
                legend: {
                    horizontalAlign: "center",
                    floating: false,
                },
                dataLabels: {
                    formatter: (val) => {
                        return val.toFixed(0) + "%";
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                    },
                },
                xaxis: {
                    categories: result.map(function (item) {
                        return item.department;
                    }),
                },
                fill: {
                    opacity: 1,
                },
                colors: ["#FD4830", "#00B074", "#F7C604"],
                yaxis: {
                    labels: {
                        formatter: (val) => {
                            return val.toFixed(0) + "%";
                        },
                    },
                },
                legend: {
                    position: "top",
                    horizontalAlign: "right",
                },
            };
            attendanceChart.updateOptions(newDepartmentOptions);
        },
        error: function (err) {
            // console.log(err);
        },
    });
}
