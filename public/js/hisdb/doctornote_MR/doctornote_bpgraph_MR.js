function ticktengah(tick,nbsp){
	var arr = [];
	for(x=0;x<tick;x++){
		arr.push([x+.5,''+nbsp]);
	}
	return arr;
}

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

function nbsp(i){
	var str = "";
	for(x=0;x<i;x++){
		str = str+"&nbsp;";
	}
	return str;
}

function tick_y(tick){
	var arr = [];
	for(x=0;x<tick;x++){
		arr.push([x+1.5,'']);
	}
	return arr;
}

function xAxisReplot(id){
	let countertoplus = 0; 
	let nbspmargin = 38;
	$(id).children('.tickLabel').each(function(index){

		let left = parseInt($(this).css('left'));
		if(index == 0){
			countertoplus = left/2;
		}
		$(this).css('left',left-countertoplus+nbspmargin);
		
	});
}

function tick_yaxis(max,min,nbsp,placeholder1=false){
	let tick = (max-min)/5;
	var arr = [];
	if(placeholder1 == true){

		//ini untuk pain score
		arr.push([min,nbsp+'0']);
		arr.push([min+10,'<b>Pain Score</b> 5']);

		//ini untuk bp, pulse, temp
		for(x=min+20;x<max;x+=5){
			arr.push([x,nbsp+x]);
		}

		//ini untuk date
		arr.push([max,nbsp+'']);
		arr.push([max+10,'<b><br>TIME</b>']);
		arr.push([max+20,'<b><br>DATE</b>']);


	}else{
		for(x=min;x<max;x+=5){
			arr.push([x,nbsp]);
		}
	}
	// console.log(arr)
	return arr;
}

function updRange(min,max,range){
	$("#customRange2").attr('min',min);
	$("#customRange2").attr('max',max);
	$("#customRange2").val(range);
	$('#rangeshow').text(range);
	$('#rangemax').text(max);
}

function filterRange(array,range){
	return array.slice(0,range);
}

function plot_pain_score(plot,array){
	array.forEach(function(item,index){
		let o = plot.pointOffset({ x: index+1, y: 23});
		$("#placeholder_bpgraph").append("<div style='position:absolute;left:" + (o.left-10) + "px;top:" + o.top + "px;color:#666;font-size:smaller'>"+item[1]+"</div>");
	});
}

function plot_time_date(plot,array){
	array.forEach(function(item,index){
		let o = plot.pointOffset({ x: index+1, y: 190});
		let mom = moment(item[1], "YYYY-MM-DD HH:mm:ss");
		$("#placeholder_bpgraph").append("<div style='position:absolute;left:" + (o.left-18) + "px;top:" + o.top + "px;color:#666;font-size:smaller'>"+mom.format('h:m</br>a')+"</div>");
		$("#placeholder_bpgraph").append("<div style='position:absolute;left:" + (o.left-18) + "px;top:" + (o.top-35) + "px;color:#666;font-size:smaller'>"+mom.format('DD-MM<br>YYYY')+"</div>");
	});
}

function plot_total(plot,array){
	array.forEach(function(item,index){
		let o = plot.pointOffset({ x: index+1, y: 0.2});
		$("#placeholder2_bpgraph").append("<div style='position:absolute;left:" + (o.left-10) + "px;top:" + o.top + "px;color:#666;font-size:smaller'><b>"+item[1]+"</b></div>");
	});
}

function markings1(){
	var markings = [
		{ color: "#f6f6f6", yaxis: { from: 180 } },
		{ color: "#f6f6f6", yaxis: { to: 30 } },
		{ color: "#000", lineWidth: 2, yaxis: { from: 30, to: 30 } },
		{ color: "#000", lineWidth: 2, yaxis: { from: 180, to: 180 } }
	];

	return markings;
}

function markings2(){
	var markings = [
		{ color: "#f6f6f6", yaxis: { from: 15.5} },
		{ color: "#f6f6f6", yaxis: { to: 0.5} },
		{ color: "#000", lineWidth: 2, yaxis: { from: 11.5, to: 11.5 } },
		{ color: "#000", lineWidth: 2, yaxis: { from: 6.5, to: 6.5 } },
		{ color: "#000", lineWidth: 2, yaxis: { from: 0.5, to: 0.5 } },
	];

	return markings;
}

function accum_more_tick(array,accum){
	array = array.map(function(item,index){
		return [item[0],parseInt(item[1])+accum];
	});
	return array;
}

function total_tick(tick1,tick2,tick3){
	let total = tick1.map(function(item,index){
		return [index,parseInt(item[1])+parseInt(tick2[index][1])+parseInt(tick3[index][1])];
	});

	return total
}

function formatTime(array){
	array = array.map(function(item,index){
		let mom = moment(item, "YYYY-MM-DD HH:mm:ss");
		return [index+1,mom.format('h:ma D/M/YY')];
	});

	return array;
}

function legend_tepi1(plot){
	let o = plot.pointOffset({ x: 0, y: 120});
	let bp=`<div class="row" >
				<div class="col-3">
					<b>Blood Pressure</b></br>
					<span class='sistole'>(BLUE)</span>
				</div>
				<div class="col-3">
					<b>Heart Rate</b></br>
					<span class='pulse'>(RED)</span>
				</div>
				<div class="col-3">
					<b>Temperature</b></br>
					<span class='temp'>(ORANGE)</span>
				</div>
			</div>`;

	$("#placeholder_bpgraph").append("<div class='legendtepi' style='top:" + o.top + "px;'>"+bp+"</div>");
}

function legend_tepi2(plot){
	let o = plot.pointOffset({ x: 0, y: 11});
	let bp=`<div class="row" >
				<div class="col-3">
					<b>Best Motor Response</b>
				</div>
				<div class="col-3">
					<b>Best Verbal Response</b>
				</div>
				<div class="col-3">
					<b>Eyes Open</b>
				</div>
			</div>`;

	$("#placeholder2_bpgraph").append("<div class='legendtepi' style='top:" + (o.top-25) + "px;left:-270px;'>"+bp+"</div>");
}

function fetchdata_bpgrah(all,range,firstPlot=false){
	// let dateChange = false;
	// if(datefr_ != $('#datefr').val() || dateto_ != $('#dateto').val()){
	// 	datefr_ = $('#datefr').val();
	// 	dateto_ = $('#dateto').val();
	// 	dateChange = true;
	// }

	let param = {
		action: 'get_bp_graph'
	}

	$.get( "./doctornote/table"+"?"+$.param(param), function( data ) {
	
	},'json').done(function(data) {
		console.log(data.data);

		var obj = data.data;

		var sis_dis = obj.reduce(function(accum,value,i){
			let diff = (parseFloat(value.sistole) - parseFloat(value.diastole)) / 2;
			let center = parseFloat(value.sistole) - parseFloat(diff);
			let arr = accum.arr,index = accum.i;

			if(all){
				arr.push([index+1,center,diff]);
				accum.i+=1; 
			}else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,center,diff]);
					accum.i+=1; 
				}
			}
			return accum;

		},{arr:[],i:0}).arr;

		var pulse = obj.reduce(function(accum,value,i){
			let arr = accum.arr,index = accum.i;
			if(all){ arr.push([index+1,value.pulse]); accum.i+=1;}
			else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,value.pulse]); accum.i+=1;
				}
			}
			return accum;
		},{arr:[],i:0}).arr;

		var temp = obj.reduce(function(accum,value,i){
			let arr = accum.arr,index = accum.i;
			if(all){ arr.push([index+1,value.temp]); accum.i+=1;}
			else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,value.temp]); accum.i+=1;
				}
			}
			return accum;
		},{arr:[],i:0}).arr;

		var tick1 = obj.reduce(function(accum,value,i){
			let arr = accum.arr,index = accum.i;
			if(all){ arr.push([index+1,value.tick1]); accum.i+=1;}
			else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,value.tick1]); accum.i+=1;
				}
			}
			return accum;
		},{arr:[],i:0}).arr;

		var tick2 = obj.reduce(function(accum,value,i){
			let arr = accum.arr,index = accum.i;
			if(all){ arr.push([index+1,value.tick2]); accum.i+=1;}
			else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,value.tick2]); accum.i+=1;
				}
			}
			return accum;					
		},{arr:[],i:0}).arr;

		var tick3 = obj.reduce(function(accum,value,i){
			let arr = accum.arr,index = accum.i;
			if(all){ arr.push([index+1,value.tick3]); accum.i+=1;}
			else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,value.tick3]); accum.i+=1;
				}
			}
			return accum;					
		},{arr:[],i:0}).arr;

		var time = obj.reduce(function(accum,value,i){
			let arr = accum.arr,index = accum.i;
			if(all){ arr.push([index+1,value.time]); accum.i+=1;}
			else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,value.time]); accum.i+=1;
				}
			}
			return accum;
		},{arr:[],i:0}).arr;

		var painScore = obj.reduce(function(accum,value,i){
			let arr = accum.arr,index = accum.i;
			if(all){ arr.push([index+1,value.painScore]); accum.i+=1;}
			else{
				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
				if(mom.isBetween(datefr_,dateto_,'day','[]')){
					arr.push([index+1,value.painScore]); accum.i+=1;
				}
			}
			return accum;
		},{arr:[],i:0}).arr;

		range = time.length;

		doPlot(x = parseInt(range)+1,sis_dis = filterRange(sis_dis,range),pulse = filterRange(pulse,range),temp = filterRange(temp,range),tick1 = filterRange(tick1,range),tick2 = filterRange(tick2,range),tick3 = filterRange(tick3,range),time = filterRange(time,range), painScore = filterRange(painScore,range));

	});

	// fetch('file.txt')
	// 	.then(response => response.text())
	// 	.then(function(data){
	// 		var obj = $.csv.toObjects(data);

	// 		var sis_dis = obj.reduce(function(accum,value,i){
	// 			let diff = (parseFloat(value.sistole) - parseFloat(value.diastole)) / 2;
	// 			let center = parseFloat(value.sistole) - parseFloat(diff);
	// 			let arr = accum.arr,index = accum.i;

	// 			if(all){
	// 				arr.push([index+1,center,diff]);
	// 				accum.i+=1; 
	// 			}else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,center,diff]);
	// 					accum.i+=1; 
	// 				}
	// 			}
	// 			return accum;

	// 		},{arr:[],i:0}).arr;

	// 		console.log(sis_dis);


	// 		var pulse = obj.reduce(function(accum,value,i){
	// 			let arr = accum.arr,index = accum.i;
	// 			if(all){ arr.push([index+1,value.pulse]); accum.i+=1;}
	// 			else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,value.pulse]); accum.i+=1;
	// 				}
	// 			}
	// 			return accum;
	// 		},{arr:[],i:0}).arr;
	// 		console.log(pulse);

	// 		var temp = obj.reduce(function(accum,value,i){
	// 			let arr = accum.arr,index = accum.i;
	// 			if(all){ arr.push([index+1,value.temp]); accum.i+=1;}
	// 			else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,value.temp]); accum.i+=1;
	// 				}
	// 			}
	// 			return accum;
	// 		},{arr:[],i:0}).arr;

	// 		var tick1 = obj.reduce(function(accum,value,i){
	// 			let arr = accum.arr,index = accum.i;
	// 			if(all){ arr.push([index+1,value.tick1]); accum.i+=1;}
	// 			else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,value.tick1]); accum.i+=1;
	// 				}
	// 			}
	// 			return accum;
	// 		},{arr:[],i:0}).arr;

	// 		var tick2 = obj.reduce(function(accum,value,i){
	// 			let arr = accum.arr,index = accum.i;
	// 			if(all){ arr.push([index+1,value.tick2]); accum.i+=1;}
	// 			else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,value.tick2]); accum.i+=1;
	// 				}
	// 			}
	// 			return accum;					
	// 		},{arr:[],i:0}).arr;

	// 		var tick3 = obj.reduce(function(accum,value,i){
	// 			let arr = accum.arr,index = accum.i;
	// 			if(all){ arr.push([index+1,value.tick3]); accum.i+=1;}
	// 			else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,value.tick3]); accum.i+=1;
	// 				}
	// 			}
	// 			return accum;					
	// 		},{arr:[],i:0}).arr;

	// 		var time = obj.reduce(function(accum,value,i){
	// 			let arr = accum.arr,index = accum.i;
	// 			if(all){ arr.push([index+1,value.time]); accum.i+=1;}
	// 			else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,value.time]); accum.i+=1;
	// 				}
	// 			}
	// 			return accum;
	// 		},{arr:[],i:0}).arr;
	// 		console.log(time);

	// 		var painScore = obj.reduce(function(accum,value,i){
	// 			let arr = accum.arr,index = accum.i;
	// 			if(all){ arr.push([index+1,value.painScore]); accum.i+=1;}
	// 			else{
	// 				let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
	// 				if(mom.isBetween(datefr_,dateto_,'day','[]')){
	// 					arr.push([index+1,value.painScore]); accum.i+=1;
	// 				}
	// 			}
	// 			return accum;
	// 		},{arr:[],i:0}).arr;

	// 		// range = (dateChange||firstPlot)?time.length:range;
	// 		// updRange(0,time.length,range);

	// 		doPlot(x = parseInt(range)+1,sis_dis = filterRange(sis_dis,range),pulse = filterRange(pulse,range),temp = filterRange(temp,range),tick1 = filterRange(tick1,range),tick2 = filterRange(tick2,range),tick3 = filterRange(tick3,range),time = filterRange(time,range), painScore = filterRange(painScore,range));
	// 		xAxisReplot("#placeholder_bpgraph div.x1Axis");
	// 	});
}
	// var datefr_ = $('#datefr').val();
	// var dateto_ = $('#dateto').val();
	

var plot;
function doPlot(x,sis_dis,pulse,temp,tick1,tick2,tick3,time,painScore){
	var data_points = {
		//do not show points
		radius: 0,
		errorbars: "y", 
		yerr: {show:true, upperCap: drawArrow2, lowerCap: drawArrow, radius: 5, lineWidth: 2.5}
	};

	var data = [
		{color: "blue", lines: {show: false}, points: data_points, data: sis_dis, label: "Blood Pressure", yaxis: 1},
		{color: "red", lines: {show: true, lineWidth: 0.5}, points: {show:true}, data: pulse, label: "Pulse", yaxis: 1},
		{color: "orange", lines: {show: true, lineWidth: 1}, points: {show:true}, data: temp, label: "Temperature", yaxis: 2},
		{lines: {show: false}, data: painScore},
		{lines: {show: false}, data: tick1},
		{lines: {show: false}, data: tick2},
		{lines: {show: false}, data: tick3},
		{lines: {show: false}, data: time}
	];

	plot = $.plot($("#placeholder_bpgraph"), data , {
		legend: {show: false},
		crosshair: {mode: "x"},
		grid: {
			hoverable: true,
			autoHighlight: false,
			markings: markings1()
		},
		xaxes: [{
			min: 0.5,
			max: x-.5,
			ticks: tick_y(x-1),
			position: 'top'
		}],
		yaxes: [{
					min: 30-20,
					max: 180+20,
					ticks: tick_yaxis(180,30-20,nbsp(17),placeholder1 = true)
				},{
					// align if we are to the right
					min: -35,
					max: 60,
					ticks: 30,
					alignTicksWithAxis: 3,
					position: 'right'
				}],
	});
	plot_pain_score(plot,painScore);
	plot_time_date(plot,time);
	legend_tepi1(plot);

	var data2 = [
		{color: "black", points: {symbol:"cross",show:true}, data: tick1, label: "Best Motor Response"},
		{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(tick2,6), label: "Best Verbal Response"},
		{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(tick3,11), label: "tick3"},
		{lines: {show: false}, data: temp, yaxis: 2}
	]

	plot2 = $.plot($("#placeholder2_bpgraph"),data2,{
		legend: {show: false},
		grid: {
			hoverable:true,
			markings: markings2()
		},
		xaxes: [{
			min: 0.5,
			max: x-.5,
			ticks: ticktengah(x,nbsp(10))
		}],
		yaxes: [{
			min: -0.5,
			max: 15.5,
			ticks: [
					[-0.5,"<b>Total</b></br></br></br>"],
					[0.5,"flaccid</br></br></br>"],
					[1.5,"Extension</br></br></br>"],
					[2.5,"Abnormal</br>Flexion</br></br></br>"],
					[3.5,"Flexor</br>withdrawal</br></br></br></br>"],
					[4.5,"Localise pain</br></br></br></br>"],
					[5.5,"Obey</br>commands</br></br></br></br>"],
					[6.5,"None</br></br></br>"],
					[7.5,"Incomprehe</br>nsible Sound</br></br></br>"],
					[8.5,"Inappro<br>priate Words</br></br></br>"],
					[9.5,"Confused</br></br></br>"],
					[10.5,"Oriented</br></br></br>"],
					[11.5,"None</br></br></br>"],
					[12.5,"To Pain</br></br></br>"],
					[13.5,"To Speech</br></br></br>"],
					[14.5,"Spontaneous</br></br></br>"],
					[15.5,""]]
		},{
					// align if we are to the right
					min: 1,
					max: 2,
					ticks: tick_yaxis(2,1,nbsp(6)),
					position: 'right'
		}]

	});
	plot_total(plot2,total_tick(tick1,tick2,tick3));
	legend_tepi2(plot2);
}

$("<div id='tooltip'></div>").css({
	'font-size' : '0.8rem',
	'font-weight' : 'bold',
	'border-radius' : '.28571429rem',
	position: "absolute",
	display: "none",
	border: "2px solid #bababc",
	padding: "2px",
	"background-color": "#bababc",
	opacity: 0.70
}).appendTo("body");

function updateLegend() {
	updateLegendTimeout = null;
	var pos = latestPosition_bpgraph;
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
		pulse = arr[1][1];
		temp = arr[2][1];
		painScore = arr[3][1];
		tick1 = arr[4][1];
		tick2 = arr[5][1];
		tick3 = arr[6][1];
		date = arr[7][1];

	$("#tooltip").html(`<b>Date: </b><span class='Date'>`+date+`<hr>`
						+`</span><b>Sistole: </b><span class='sistole'>`+sistole
						+`</span><br/><b>Diastole: </b><span class='diastole'>`+diastole
						+`</span><br/><b>Pulse: </b><span class='pulse'>`+pulse
						+`</span><br/><b>Temperature: </b><span class='temp'>`+temp
						+`</span><br/><b>Pain Score: </b><span>`+painScore
						+`</span><br/><b>Best Motor Response: </b><span>`+tick1
						+`</span><br/><b>Best Verbal Response: </b><span>`+tick2
						+`</span><br/><b>Eyes Open: </b><span>`+tick3
						+`</span>`)
			.css({top: pos.pageY+20, left: pos.pageX+20})
			.fadeIn(500);

}

var updateLegendTimeout = null;
var latestPosition_bpgraph = null;
	
$(function() {
	$("#placeholder_bpgraph").bind("plothover",  function (event, pos, item) {
		latestPosition_bpgraph = pos;
		if (!updateLegendTimeout) {
			updateLegendTimeout = setTimeout(updateLegend, 50);
		}
	});

	$("#placeholder_bpgraph").mouseout(function() {
		$("#tooltip").hide();
	});

	// fetchdata(document.getElementById('showall').checked,$("#customRange2").val(),firstPlot=true);

});