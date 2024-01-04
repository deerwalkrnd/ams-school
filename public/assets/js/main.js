
function onNavToggleClick(event)
{
    event.preventDefault();
    let toggle = document.getElementById("header-toggle");
    let nav = document.getElementById("nav-bar");
    let bodypd = document.getElementById("body-pd");
    let headerpd = document.getElementById("header");

    nav.classList.toggle("show");
    toggle.classList.toggle("bx-x");
    bodypd.classList.toggle("body-pd");
    headerpd.classList.toggle("body-pd");
}

// for line chart

google.charts.load("current", { packages: ["corechart", "line"] });
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {
    var data = new google.visualization.DataTable();
    data.addColumn("number", "X");
    data.addColumn("number", "students");

    data.addRows([
        [0, 0],
        [1, 10],
        [2, 23],
        [3, 17],
        [4, 18],
        [5, 9],
        [6, 11],
        [7, 27],
        [8, 33],
        [9, 40],
        [10, 32],
        [11, 35],
        [12, 30],
        [13, 40],
        [14, 42],
        [15, 47],
        [16, 44],
        [17, 48],
        [18, 52],
        [19, 54],
        [20, 42],
        [21, 55],
        [22, 56],
        [23, 57],
        [24, 60],
        [25, 50],
        [26, 52],
        [27, 51],
        [28, 49],
        [29, 53],
        [30, 55],
    ]);

    var options = {
        hAxis: {
            title: "Day",
        },
        vAxis: {
            title: "Present",
        },
        backgroundColor: "transparent",
        legend: "none",
    };

    var chart = new google.visualization.LineChart(
        document.getElementById("chart_div")
    );

    chart.draw(data, options);
}

// for pie chart

google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ["Task", "Hours per Day"],
        ["Present", 65],
        ["Absent", 15],
        ["Leave", 20],
    ]);

    var options = {
        slices: {
            0: { color: "#20C997" },
            1: { color: "#F90808" },
            2: { color: "#FFB55E" },
        },
        legend: "none",
        responsive: "false",
        backgroundColor: "transparent",
    };

    var chart = new google.visualization.PieChart(
        document.getElementById("piechart")
    );

    chart.draw(data, options);
}

