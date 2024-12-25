 // Project chart
 var project_name_lang = $("#project_name_lang").val();
 var complete_percentages = JSON.parse($("#complete_percentages").val());
 var project_names = JSON.parse($("#project_names").val());

 var options = {
     series: [{
         name: project_name_lang,
         data: complete_percentages.map(function (item) {
             return item;
         }),
     }, ],
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
     dataLabels: {
         enabled: false,
     },
     plotOptions: {
         bar: {
             horizontal: false,
         },
     },
     xaxis: {
         categories: project_names.map(function (item) {
             return item;
         }),
     },
     fill: {
         opacity: 1,
     },
     colors: ["#00b175"],
     yaxis: {},
     legend: {
         position: "top",
         horizontalAlign: "right",
     },
 };

 var attendanceChart = new ApexCharts(
     document.querySelector("#attendance"),
     options
 );
 attendanceChart.render();

    // The JSON data provided
    const jsonData = JSON.parse($("#current_year_holidays").val());
    
    // Mapping month names to abbreviations used in the chart
    const monthMap = {
        "January": {abbr: "Jan", order: 1},
        "February": {abbr: "Feb", order: 2},
        "March": {abbr: "Mar", order: 3},
        "April": {abbr: "Apr", order: 4},
        "May": {abbr: "May", order: 5},
        "June": {abbr: "Jun", order: 6},
        "July": {abbr: "Jul", order: 7},
        "August": {abbr: "Aug", order: 8},
        "September": {abbr: "Sep", order: 9},
        "October": {abbr: "Oct", order: 10},
        "November": {abbr: "Nov", order: 11},
        "December": {abbr: "Dec", order: 12}
    };

    // Initialize an empty array for the series data
    const seriesData = [];

    // Sort the months by their order
    const sortedMonths = Object.keys(jsonData).sort((a, b) => monthMap[b].order - monthMap[a].order);

    // Iterate over each month and its date ranges in sorted order
    sortedMonths.forEach(month => {
        const monthAbbr = monthMap[month].abbr;
        const ranges = jsonData[month];

        ranges.forEach(range => {
            const [start, end] = range;
            seriesData.push({
            x: monthAbbr,
            y: [new Date(start).getTime(), new Date(end).getTime()],
            fillColor: '#00E396'
            });
        });
    });

    var options = {
        series: [
        {
            name: 'Holidays',
            data: seriesData
        },
    ],
        chart: {
        height: 250,
        type: 'rangeBar'
    },
    plotOptions: {
        bar: {
            horizontal: true,
            barHeight: '90%',
        }
    },
    xaxis: {
        type: 'datetime'
    },
    stroke: {
        width: 1
    },
    fill: {
        type: 'solid',
        opacity: 0.6,
    },
    legend: {
        position: 'top',
        horizontalAlign: 'left'
    }
    };

    var chart = new ApexCharts(document.querySelector("#calender-chart"), options);
    chart.render();
