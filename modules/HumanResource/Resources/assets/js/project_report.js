$(document).ready(function () {
    "use strict"; // Start of use strict

    function getJsonData(req_url) {
        var php_data;
        $.ajax({
            type: "GET",
            url: req_url,
            async: false,
            dataType: "json",
            success: function (res) {
                php_data = res;
            },
        });

        return php_data;
    }

    /*Get remaining vs completed data for a particular project*/
    var route_project_remaining = $("#project_remaining").val();
    var response_remaining_completed = getJsonData(route_project_remaining);
    //dought chart
    var doughtOptions = {
        series: response_remaining_completed,
        chart: {
            type: "donut",
        },
        responsive: [
            {
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200,
                    },
                    legend: {
                        position: "bottom",
                    },
                },
            },
        ],
        labels: ["Completed", "Incomplete"],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val + "%";
            },
        },
        colors: ["#00ff3f", "#ff0005"],
    };

    var doughtChart = new ApexCharts(
        document.querySelector("#doughnutChart"),
        doughtOptions
    );
    doughtChart.render();

    /*Get team_members/employees status based tasks for a particular project*/
    var r_project_all_employees_name = $("#project_all_employees_name").val();
    var r_task_to_do_by_employee = $("#task_to_do_by_employee").val();
    var r_task_in_progress_by_employee = $(
        "#task_in_progress_by_employee"
    ).val();
    var r_task_done_by_employee = $("#task_done_by_employee").val();
    var project_all_employees_name = getJsonData(r_project_all_employees_name);
    var task_to_do_by_employee = getJsonData(r_task_to_do_by_employee);
    var task_in_progress_by_employee = getJsonData(
        r_task_in_progress_by_employee
    );
    var task_done_by_employee = getJsonData(r_task_done_by_employee);

    //bar chart
    var barChartOptions = {
        series: [
            {
                data: [
                    task_to_do_by_employee,
                    task_in_progress_by_employee,
                    task_done_by_employee,
                ],
            },
        ],
        chart: {
            height: 350,
            type: "bar",
        },
        colors: ["#586971", "#26a0fc", "#26e7a6"],
        plotOptions: {
            bar: {
                columnWidth: "45%",
                distributed: true,
            },
        },
        dataLabels: {
            enabled: true,
        },
        legend: {
            show: false,
        },
        xaxis: {
            categories: ["To Do", "In Progress", "Done"],
            labels: {
                style: {
                    colors: ["#586971", "#26a0fc", "#26e7a6"],
                    fontSize: "14px",
                },
            },
        },
    };

    var barChart = new ApexCharts(
        document.querySelector("#barChart"),
        barChartOptions
    );
    barChart.render();

    /*Get To Do , In Progress and Done status data for a particular project*/
    var r_project_various_status_tasks = $(
        "#project_various_status_tasks"
    ).val();
    var project_various_status_tasks = getJsonData(
        r_project_various_status_tasks
    );

    // pie chart
    var options = {
        series: project_various_status_tasks,
        chart: {
            type: "pie",
        },
        labels: ["To Do", "In Progress", "Done"],
        responsive: [
            {
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200,
                    },
                    legend: {
                        position: "bottom",
                    },
                },
            },
        ],
    };

    var pieChart = new ApexCharts(document.querySelector("#pieChart"), options);
    pieChart.render();
});
