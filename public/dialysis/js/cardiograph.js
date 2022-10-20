
$(document).ready(function () {

    $("<div id='tooltip'></div>").css({
        'font-size' : '1.2rem',
        'font-weight' : 'bold',
        'border-radius' : '.28571429rem',
        position: "absolute",
        display: "none",
        border: "2px solid #bababc",
        padding: "2px",
        "background-color": "#bababc",
        opacity: 0.70
    }).appendTo("body");

    var sis_dis_arr = patcardio.reduce(function(accum,value,i){
        let diff = (parseFloat(value.bp_s) - parseFloat(value.bp_d)) / 2;
        let center = parseFloat(value.bp_s) - parseFloat(diff);
        let arr = accum.arr,index = accum.i;

        arr.push([index+1,center,diff]);
        accum.i+=1; 

        return accum;

    },{arr:[],i:0}).arr;

    var hr_arr = patcardio.reduce(function(accum,value,i){
        let arr = accum.arr,index = accum.i;
        arr.push([index+1,value.hr]); 
        accum.i+=1;

        return accum;
    },{arr:[],i:0}).arr;

    var speed_arr = patcardio.reduce(function(accum,value,i){
        let arr = accum.arr,index = accum.i;
        arr.push([index+1,value.speed]); 
        accum.i+=1;

        return accum;
    },{arr:[],i:0}).arr;

    var rpe_arr = patcardio.reduce(function(accum,value,i){
        let arr = accum.arr,index = accum.i;
        arr.push([index+1,value.rpe]); 
        accum.i+=1;

        return accum;
    },{arr:[],i:0}).arr;

    var date_arr = patcardio.reduce(function(accum,value,i){
        let arr = accum.arr,index = accum.i;
        let mom = moment(value.date, "YYYY-MM-DD HH:mm:ss");
        arr.push([index+1,value.date]); accum.i+=1;

        return accum;
    },{arr:[],i:0}).arr;

    var data_points = {
        //do not show points
        radius: 0,
        errorbars: "y", 
        yerr: {show:true, upperCap: drawArrow2, lowerCap: drawArrow, radius: 5, lineWidth: 0.5}
    };

    var xlength = (hr_arr.length>10)? hr_arr.length + 0.5:10.5;


    var data = [
        {color: "blue", lines: {show: false, lineWidth: 0.5}, points: data_points, data: sis_dis_arr, label: "Blood Pressure", yaxis: 1},
        {color: "red", lines: {show: true, lineWidth: 0.5}, points: {show:true}, data: hr_arr, label: "HR", yaxis: 1},
        {color: "green", lines: {show: true, lineWidth: 0.5}, points: {show:true}, data: speed_arr, label: "speed", yaxis: 2},
        {color: "orange", lines: {show: true, lineWidth: 0.5}, points: {show:true}, data: rpe_arr, label: "rpe", yaxis: 2},
        {lines: {show: false}, data: date_arr}
    ]

    plot = $.plot($("#placeholder"), data , {
        legend: {show: false},
        crosshair: {mode: "x"},
        grid: {
            hoverable: true,
            autoHighlight: false,
            markings: markings(),
            backgroundColor: { colors: [ "#e7ffe7", "#e7ffe7" ] },
        },
        xaxes: [{
            min: 0.5,
            max: xlength,
            ticks: tick_y(10),
            position: 'top'
        }],
        yaxes: [{
                    min: 30,
                    max: 180+20,
                    ticks: tick_yaxis(180,30-40,nbsp(17))
                },{
                    // align if we are to the right
                    min: -20,
                    max: 15,
                    position: 'right',
                    ticks: tick_yaxis2(20,11)
                }],
    });


    plot_time_date(plot,date_arr);
    // plot_btm(plot,date_arr)
    legend_tepi1(plot);

    var updateLegendTimeout = null;
    var latestPosition = null;
    $("#placeholder").bind("plothover",  function (event, pos, item) {
        latestPosition = pos;
        if (!updateLegendTimeout) {
            updateLegendTimeout = setTimeout(updateLegend, 50);
        }
    });

    $("#placeholder").mouseout(function() {
        $("#tooltip").hide();
    });

    function updateLegend() {
        updateLegendTimeout = null;
        var pos = latestPosition;
        var axes = plot.getAxes();
        if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) {
            $("#tooltip").hide(); return;
        }
        var i, j, arr = [], dataset = plot.getData();

        for (i = 0; i < dataset.length; ++i) {
            var series = dataset[i];
            // Find the nearest points, x-wise
            for (j = 1; j < series.data.length; ++j) {
                if (series.data[j][0] > pos.x + .5 ) {
                    break;
                }
            }
            var y,p1 = series.data[j - 1],p2 = series.data[j];
            if (p1 == null) {
                y = p2;
            } else if (p2 == null) {
                y = p1;
            } else {
                y = p1;
            }
            arr[i] = y;
        }

        let sistole = parseFloat(arr[0][1]) + parseFloat(arr[0][2]),
            diastole = parseFloat(arr[0][1]) -  parseFloat(arr[0][2]),
            hr = arr[1][1],
            speed = arr[2][1],
            rpe = arr[3][1],
            date = arr[4][1]

        $("#tooltip").html(`<b>Date: </b><span class='Date'>`+date+`<hr>`
                    +`</span><b class='blue'>Sistole: </b><span>`+sistole
                    +`</span><br/><b class='blue'>Diastole: </b><span>`+diastole
                    +`</span><br/><b class='red'>Hear Rate: </b><span>`+hr
                    +`</span><br/><b class='green'>Speed: </b><span>`+speed
                    +`</span><br/><b class='orange'>RPE: </b><span>`+rpe
                    +`</span>`)
        .css({top: pos.pageY+20, left: pos.pageX+20})
        .fadeIn(500);

    }


	
});

function drawArrow(ctx, x, y, radius){
    ctx.beginPath();
    ctx.moveTo(x + radius, y + radius);
    ctx.lineTo(x, y);
    ctx.lineTo(x - radius, y + radius);
    ctx.stroke();
}

function drawArrow2(ctx, x, y, radius){
    ctx.beginPath();
    ctx.moveTo(x + radius, y - radius);
    ctx.lineTo(x, y);
    ctx.lineTo(x - radius, y - radius);
    ctx.stroke();
}

function tick_y(tick){
    var arr = [];
    for(x=0;x<tick;x++){
        arr.push([x+1.5,'']);
    }
    return arr;
}

function tick_yaxis(max,min,nbsp){
    let tick = (max-min)/5;
    var arr = [];

    //ini untuk pain score
    arr.push([min,nbsp+'0']);
    // arr.push([min+10,'<b>Pain Score</b>']);

    //ini untuk bp, pulse, temp
    for(x=min+40;x<max;x+=5){
        arr.push([x,nbsp+x]);
    }

    //ini untuk date
    arr.push([max,nbsp+'']);
    arr.push([max+10,'<b><br>TIME</b>']);
    arr.push([max+20,'<b><br>DATE</b>']);

    return arr;
}

function tick_yaxis2(hidebil,showbil){
    var arr = [];

    for(x=0;x<hidebil;x++){
        arr.push([x,'']);
    }

    for(x=0;x<=showbil;x++){
        arr.push([x,x]);
    }

    return arr;
}

function nbsp(i){
    var str = "";
    for(x=0;x<i;x++){
        str = str+"\u00A0";
    }
    return str;
}

function plot_time_date(plot,array){
    array.forEach(function(item,index){
        let o = plot.pointOffset({ x: index+1, y: 190});
        let mom = moment(item[1], "YYYY-MM-DD HH:mm:ss");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-18) + "px;top:" + (o.top+5) + "px;color:#666;font-size:smaller'>"+mom.format('h:mm</br>\u00A0\u00A0a')+"</div>");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-18) + "px;top:" + (o.top-30) + "px;color:#666;font-size:smaller'>"+mom.format('DD-MM<br>YYYY')+"</div>");
    });
}

function plot_btm(plot,array){
    array.forEach(function(item,index){
        let o = plot.pointOffset({ x: index+1, y: 30});
        let mom = moment(item[1], "YYYY-MM-DD HH:mm:ss");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-60) + "px;top:" + (o.top+10) + "px;color:#666;font-size:smaller;font-weight:bold'>Before</div>");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-50) + "px;top:" + (o.top+25) + "px;color:#666;font-size:smaller;'>HR: 100</div>");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-50) + "px;top:" + (o.top+40) + "px;color:#666;font-size:smaller;'>SPO2: 100</div>");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-50) + "px;top:" + (o.top+55) + "px;color:#666;font-size:smaller;'>BP: 100</div>");


        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-60) + "px;top:" + (o.top+70) + "px;color:#666;font-size:smaller;font-weight:bold'>After</div>");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-50) + "px;top:" + (o.top+85) + "px;color:#666;font-size:smaller;'>HR: 100</div>");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-50) + "px;top:" + (o.top+100) + "px;color:#666;font-size:smaller;'>SPO2: 100</div>");
        $("#placeholder").append("<div style='position:absolute;left:" + (o.left-50) + "px;top:" + (o.top+115) + "px;color:#666;font-size:smaller;'>BP: 100</div>");
    });
}

function legend_tepi1(plot){
    let o = plot.pointOffset({ x: 0, y: 120});
    let bp=`<div class="row" >
                <div class="col-2">
                    <b>Heart Rate</b></br>
                    <span class='red'>(RED)</span>
                </div>
                <div class="col-2">
                    <b>Blood Pressure</b></br>
                    <span class='blue'>(BLUE)</span>
                </div>
                <div class="col-2">
                    <b>Speed</b></br>
                    <span class='green'>(GREEN)</span>
                </div>
                <div class="col-2">
                    <b>RPE</b></br>
                    <span class='orange'>(ORANGE)</span>
                </div>
            </div>`;

    $("#placeholder").append("<div class='legendtepi' style='top:" + o.top + "px;'>"+bp+"</div>");
}

function markings(){
    var markings = [
        { color: "#f6f6f6", yaxis: { from: 180 } },
        { color: "#f6f6f6", yaxis: { to: 30 } },
        // { color: "#000", lineWidth: 2, yaxis: { from: 30, to: 30 } },
        { color: "#000", lineWidth: 2, yaxis: { from: 180, to: 180 } }
    ];

    return markings;
}
