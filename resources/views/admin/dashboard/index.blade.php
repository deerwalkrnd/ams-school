@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')

    @include('admin.dashboard.midsection')

    <!-- view teachers section -->
    <div class="table_container">
        <table class="_table mx-auto amsTable" id="amsTable">
            <thead>
                <tr class="table_title">
                    <th>S.N</th>
                    <th>Teacher's Name</th>
                    <th>Email Address</th>
                    <th>Last Login</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Teacher 1</td>
                    <td>teacher1@gmail.com</td>
                    <td>12:00 PM 2021-09-01</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Bijay 2</td>
                    <td>teacher2@gmail.com</td>
                    <td>12:00 PM 2021-09-01</td>
            </tbody>
        </table>
    </div>

    {{-- @endsection --}}


    {{-- @section('scripts') --}}
    {{-- <script>

    var chart1;
    var chart2
    var  pieData = JSON.parse(`<?php echo $piechart_data; ?>`);
    var lineData = JSON.parse(`<?php echo $linechart_data; ?>`);

    $(document).ready(function() {
        $('.amsTable').DataTable();
        piechart(pieData);       //pie-chart
        linechart(lineData);      //line-chart

    })


    //Pie Chart Creation
    function piechart(pieData){
        var ctx = $("#my_piechart");
        console.log(pieData[1]);
        // console.log('aszxd bairea',pieData);
        // console.log(pieData[0],pieData[1]);
        var data = {
        labels: pieData[1],
        datasets: [
            {
            label: "Users Count",
            data: pieData[0],
            backgroundColor: [
                "#2E8B57",
                "#DC143C",
                "#F4A460",
            ],
            borderColor: [
                "#2E8B57",
                "#DC143C",
                "#F4A460",
            ],
            borderWidth: [1, 1, 1]
            }
        ]
        };

        //options
        var options = {
        responsive: true,
        title: {
            display: true,
            position: "top",
            text: "Monthly Attendance",
            fontSize: 16,
            fontColor: "#111"
        },
        legend: {
            display: true,
            position: "bottom",
            labels: {
            fontColor: "#333",
            fontSize: 9
            }
        },
        };

        //create Pie Chart class object
        chart1 = new Chart(ctx, {
            type: "pie",
            data: data,
            options: options
        });
    }


    // Pie-Chart Filter
    var select_piechart_month = document.getElementById('select_piechart_month');
    var select_piechart_batch = document.getElementById('select_piechart_batch');
    select_piechart_month.addEventListener('change', function handleChange(event) {
        console.log(select_piechart_month.value,'month');
        event.preventDefault();
        // selected_piechart_month = event.target.value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('admin.dashboard.piechart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "month": select_piechart_month.value,
                "batch": select_piechart_batch.value,
                },
            // dataType:'json',
            success: function(data) {
                pieData = JSON.parse(data);
                if (chart1) chart1.destroy();
                piechart(pieData);
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    });

    select_piechart_batch.addEventListener('change', function handleChange(event) {
        console.log(select_piechart_batch.value,'batch');
        event.preventDefault();
        // selected_piechart_month = event.target.value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('admin.dashboard.piechart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "month": select_piechart_month.value,
                "batch": select_piechart_batch.value
                },
            // dataType:'json',
            success: function(data) {
                pieData = JSON.parse(data);
                if (chart1) chart1.destroy();
                piechart(pieData);
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    });



    //Line Chart Creation
    function linechart(lineData){
        var ctx =  document.getElementById('line_chart');
        const data = {
        labels: lineData[1],
        datasets: [{
            label: 'Presentees',
            data: lineData[0],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1,
        }],
        };
    //create Line Chart class object
        chart2 = new Chart(ctx, {
            type: "line",
            data: data,

            options : {
                responsive: true,
                title: {
                    display: true,
                    position: "top",
                    text: "Monthly Presentees",
                    fontSize: 16,
                    fontColor: "#111"
                },
                scales: {
                    yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'No. of Presentees',
                        }
                    }],
                    xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Days',
                        }
                    }]
                }
            }
        });
    }




    // Line Chart Filter
    var select_linechart_month = document.getElementById('select_linechart_month');
    var select_linechart_batch = document.getElementById('select_linechart_batch');
    //month-linechart-filter
    select_linechart_month.addEventListener('change', function handleChange(event) {
        event.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('admin.dashboard.linechart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "month": select_linechart_month.value,
                "batch": select_linechart_batch.value,
                },
            // dataType:'json',
            success: function(data) {
                lineData = JSON.parse(data);
                if (chart2) chart2.destroy();
                linechart(lineData);
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    });
    //batch-linechart-filter
    select_linechart_batch.addEventListener('change', function handleChange(event) {
        event.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('admin.dashboard.linechart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "month": select_linechart_month.value,
                "batch": select_linechart_batch.value,
                },
            // dataType:'json',
            success: function(data) {
                lineData = JSON.parse(data);
                if (chart2) chart2.destroy();
                linechart(lineData);
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    });



    // Pie-Chart Filter
    var select_piechart_month = document.getElementById('select_piechart_month');
    select_piechart_month.addEventListener('change', function handleChange(event) {
        event.preventDefault();
        selected_piechart_month = event.target.value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('admin.dashboard.piechart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "month": selected_piechart_month,
                },
            // dataType:'json',
            success: function(data) {
                pieData = JSON.parse(data);
                if (chart1) chart1.destroy();
                piechart(pieData);
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    });


    //Total Yearly Month Absent Count Filter
    var select_absent_year = document.getElementById('select_absent_year');
    var select_absent_month = document.getElementById('select_absent_month');
    var select_absent_batch= document.getElementById('select_absent_batch');
    // select_absent_batch.addEventListener('change', function handleChange(event) {
    function absentees(event){
        event.preventDefault();
        // select_absent_month = event.target.value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('admin.dashboard.yearlyMonthAbsentees') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "year" : select_absent_year.value,
                "month": select_absent_month.value,
                "batch": select_absent_batch.value,
                },
            // dataType:'json',
            success: function(data) {
                document.getElementById('absentees_count').innerHTML = data;
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    }
</script>
@endsection --}}
