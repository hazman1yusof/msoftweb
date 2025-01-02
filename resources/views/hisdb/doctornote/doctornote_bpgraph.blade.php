<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Blood Pressure Plot</title>
	<link href="css/bpgraph.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css">

	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.errorbars.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.navigate.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.crosshair.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.symbol.js"></script>
	<!-- <script language="javascript" type="text/javascript" src="plugins/jquery.csv.min.js"></script> -->
	<script language="javascript" type="text/javascript" src="plugins/moment.js"></script>
	<script language="javascript" type="text/javascript" src="js/bpgraph.js"></script>


	<style type="text/css">
		.sistole{color: blue}
		.diastole{color: blue}
		.pulse{color: red}
		.temp{color: darkorange}
		.gxt{color: green}
		.legendtepi{position:absolute;left:-200px;color:#666;font-size:small;
			-webkit-transform: rotate(270deg);
			-moz-transform: rotate(270deg);
			-o-transform: rotate(270deg);
			writing-mode: lr-tb;
			width: 40%;
		}
		.legendtepi2{position:absolute;left:-200px;color:#666;font-size:small;
			writing-mode: lr-tb;
		}
		#tooltip hr { 
		    display: block;
		    margin-top: 0px;
		    margin-bottom: 0px;
		    margin-left: auto;
		    margin-right: auto;
		    border-style: inset;
		    border-color: black;
		} 
		#placeholder{
			line-height: 1;
		}
		#placeholder2 .y1Axis, #placeholder3 .y1Axis{
			line-height: 1;
		}

	</style>

	<script type="text/javascript">

	$(function (){

		$("#rangeshow").text($("#customRange2").val());
		$("#customRange2").change(function (){
			$("#rangeshow").text($("#customRange2").val());
		});

		// fetchBio();
		// function fetchBio(){
		// 	fetch('bio.txt')
		// 		.then(response => response.text())
		// 		.then(function (data){
		// 			var obj = $.csv.toObjects(data)[0];
		// 			for (var prop in obj) {
		// 		        if(!obj.hasOwnProperty(prop)) continue;
		// 		        $('#'+prop).text(obj[prop]);
		// 		    }


		// 		});
		// }

		var datefr_ = $('#datefr').val();
		var dateto_ = $('#dateto').val();
		function fetchdata(all,range,firstPlot=false){

			let dateChange = false;
			if(datefr_ != $('#datefr').val() || dateto_ != $('#dateto').val()){
				datefr_ = $('#datefr').val();
				dateto_ = $('#dateto').val();
				dateChange = true;
			}

			let param = {
				action: 'get_bp_graph',
				mrn: "{{request()->get('mrn')}}",
				episno: "{{request()->get('episno')}}",
			}

			$.get("./doctornote/table"+"?"+$.param(param), function (data){
			
			},'json').done(function (data){
				console.log(data.data);

				var obj = data.data;

				var sis_dis = obj.reduce(function (accum,value,i){
					let diff = (parseFloat(value.bpsys_stand) - parseFloat(value.bpdias_stand)) / 2;
					let center = parseFloat(value.bpsys_stand) - parseFloat(diff);
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

				var pulse = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.hr]); accum.i+=1;}
					else{
						let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.hr]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var temp = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.temp_]); accum.i+=1;}
					else{
						let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.temp]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var gxt = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.gxt]); accum.i+=1;}
					else{
						let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.gxt]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var roomair = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.roomair]); accum.i+=1;}
					else{
						let mom = moment(value.roomair, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick1]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var oxygen = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.oxygen]); accum.i+=1;}
					else{
						let mom = moment(value.oxygen, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick2]); accum.i+=1;
						}
					}
					return accum;					
				},{arr:[],i:0}).arr;

				var breathnormal = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.breathnormal]); accum.i+=1;}
					else{
						let mom = moment(value.breathnormal, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var breathdifficult = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.breathdifficult]); accum.i+=1;}
					else{
						let mom = moment(value.breathdifficult, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var circarrythmias = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.circarrythmias]); accum.i+=1;}
					else{
						let mom = moment(value.circarrythmias, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var circhbp = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.circhbp]); accum.i+=1;}
					else{
						let mom = moment(value.circhbp, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var circirregular = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.circirregular]); accum.i+=1;}
					else{
						let mom = moment(value.circirregular, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var circlbp = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.circlbp]); accum.i+=1;}
					else{
						let mom = moment(value.circlbp, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var drainnone = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.drainnone]); accum.i+=1;}
					else{
						let mom = moment(value.drainnone, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var draindrainage = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.draindrainage]); accum.i+=1;}
					else{
						let mom = moment(value.draindrainage, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var ivlnone = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.ivlnone]); accum.i+=1;}
					else{
						let mom = moment(value.ivlnone, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var ivlsite = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.ivlsite]); accum.i+=1;}
					else{
						let mom = moment(value.ivlsite, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var gucontinent = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.gucontinent]); accum.i+=1;}
					else{
						let mom = moment(value.gucontinent, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var gufoley = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.gufoley]); accum.i+=1;}
					else{
						let mom = moment(value.gufoley, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var frhigh = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.frhigh]); accum.i+=1;}
					else{
						let mom = moment(value.frhigh, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var frlow = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.frlow]); accum.i+=1;}
					else{
						let mom = moment(value.frlow, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.tick3]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var time = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.datetaken+' '+value.timetaken]); accum.i+=1;}
					else{
						let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.time]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var painScore = obj.reduce(function (accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.spo2]); accum.i+=1;}
					else{
						let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.painScore]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				// range = time.length;
				range = (dateChange||firstPlot)?time.length:range;
				updRange(0,time.length,range);

				doPlot(
						x = parseInt(range)+1,
						sis_dis = filterRange(sis_dis,range),
						pulse = filterRange(pulse,range),
						temp = filterRange(temp,range),
						gxt = filterRange(gxt,range),
						roomair = filterRange(roomair,range),
						oxygen  = filterRange(oxygen,range),
						breathnormal  = filterRange(breathnormal,range),
						breathdifficult  = filterRange(breathdifficult,range),
						circarrythmias  = filterRange(circarrythmias,range),
						circhbp  = filterRange(circhbp,range),
						circirregular  = filterRange(circirregular,range),
						circlbp  = filterRange(circlbp,range),
						drainnone  = filterRange(drainnone,range),
						draindrainage  = filterRange(draindrainage,range),
						ivlnone  = filterRange(ivlnone,range),
						ivlsite  = filterRange(ivlsite,range),
						gucontinent  = filterRange(gucontinent,range),
						gufoley  = filterRange(gufoley,range),
						frhigh  = filterRange(frhigh,range),
						frlow  = filterRange(frlow,range),
						time = filterRange(time,range),
						painScore = filterRange(painScore,range)
					  );
				xAxisReplot("#placeholder div.x1Axis");

			});
		}

		var plot;
		function doPlot(x,sis_dis,pulse,temp,gxt,roomair,oxygen,breathnormal,breathdifficult,circarrythmias,circhbp,circirregular,circlbp,drainnone,draindrainage,ivlnone,ivlsite,gucontinent,gufoley,frhigh,frlow,time,painScore){
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
				{color: "green", lines: {show: true, lineWidth: 1}, points: {show:true}, data: gxt, label: "GXT", yaxis: 2},
				{lines: {show: false}, data: painScore},
				{lines: {show: false}, data: time}
			];

			plot = $.plot($("#placeholder"), data , {
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
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(roomair,15)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(oxygen,14)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(drainnone,13)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(draindrainage,12)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(breathnormal,11)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(breathdifficult,10)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(ivlnone,9)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(ivlsite,8)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(circarrythmias,7)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(circhbp,6)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(circirregular,5)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(circlbp,4)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(gucontinent,3)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(gufoley,2)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(frhigh,1)},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(frlow,0)},
				{lines: {show: false}, data: temp, yaxis: 2}
			]

			plot2 = $.plot($("#placeholder2"),data2,{
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
					min: 0.5,
					max: 16.5,
					ticks: [
							[0.5,"Low</br></br></br>"],
							[1.5,"High</br></br></br>"],
							[2.5,"Foley</br></br></br>"],
							[3.5,"Continent</br></br></br>"],
							[4.5,"Irregular HR</br></br></br>"],
							[5.5,"High BP</br></br></br>"],
							[6.5,"Low BP</br></br></br>"],
							[7.5,"Arrhythmias</br></br></br>"],
							[8.5,"Site</br></br></br>"],
							[9.5,"None</br></br></br>"],
							[10.5,"Difficult</br></br></br>"],
							[11.5,"Normal</br></br></br>"],
							[12.5,"Drainage</br></br></br>"],
							[13.5,"None</br></br></br>"],
							[14.5,"Oxygen</br></br></br>"],
							[15.5,"Room Air</br></br></br>"],
							[16.5,""]
						   ]
				},{
							// align if we are to the right
							min: 1,
							max: 2,
							ticks: tick_yaxis(2,1,nbsp(8)),
							position: 'right'
				}]

			});
			// plot_total(plot2,total_tick(tick1,tick2,tick3));
			legend_tepi2(plot2);
		}

		$("<div id='tooltip'></div>").css({
			'font-size' : '1.4rem',
			'font-weight' : 'bold',
			'border-radius' : '.28571429rem',
			position: "absolute",
			display: "none",
			border: "2px solid #bababc",
			padding: "2px",
			"background-color": "#bababc",
			opacity: 0.70
		}).appendTo("body");

		function updateLegend(){
			updateLegendTimeout = null;
			var pos = latestPosition;
			var axes = plot.getAxes();
			if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max || pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) {
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
				gxt = arr[3][1];
				painScore = arr[4][1];
				date = arr[5][1];

			$("#tooltip").html(`<b>Date: </b><span class='Date'>`+date+`<hr>`
								+`</span><b>Sistole: </b><span class='sistole'>`+sistole
								+`</span><br/><b>Diastole: </b><span class='diastole'>`+diastole
								+`</span><br/><b>Pulse: </b><span class='pulse'>`+pulse
								+`</span><br/><b>Temperature: </b><span class='temp'>`+temp
								+`</span><br/><b>GXT: </b><span class='gxt'>`+gxt
								+`</span><br/><b>SP02 (%): </b><span>`+painScore
								+`</span>`)
					.css({top: pos.pageY+20, left: pos.pageX+20})
					.fadeIn(500);

		}

		var updateLegendTimeout = null;
		var latestPosition = null;
		$("#placeholder").bind("plothover", function (event, pos, item){
			latestPosition = pos;
			if (!updateLegendTimeout) {
				updateLegendTimeout = setTimeout(updateLegend, 50);
			}
		});

		$("#placeholder").mouseout(function (){
			$("#tooltip").hide();
		});

		fetchdata(true,$("#customRange2").val(),firstPlot=true);

		$('#customRange2').change(function (){
			fetchdata(true,$("#customRange2").val());
		});

	});

	</script>
</head>
<body>

	<div id="content">
		<form class="alert alert-warning" style="-webkit-box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
			<div class="row">
				<div class="col-md-6">
					<br>
					<label for="customRange2">Data Range show <span id="rangeshow"></span> out of <span id="rangemax"></span> </label>
					<input type="range" class="custom-range" min="0" max="5" step="1" id="customRange2">
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-2">
					<b>MRN</b>: <span id="mrn">{{$pat->MRN}}</span>
				</div>
				<div class="col-md-6">
					<b>Name</b>: <span id="name">{{$pat->Name}}</span>
				</div>
				<div class="col-md-2">
					<b>Sex</b>: <span id="sex">{{$pat->Sex}}</span>
				</div>
				<div class="col-md-2">
					<b>Telephone</b>: <span id="telno">{{$pat->telhp}}</span>
				</div>
			</div>
		</form>

		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder" style="height: 60%"></div>
			<div id="placeholder2" class="demo-placeholder" style="height: 40%; margin-top: -24px; margin-left: 10px;"></div>
		</div>

	</div>
	<!-- <div class="card border-success mb-3" style="width: 10rem;">
	  <div class="card-header">Header</div>
	  <div class="card-body">
	    <h5 class="card-title">Card title</h5>
	    <p class="card-text"><b>sistole</b> 90</p>
	    <p class="card-text"><b>diastole</b> 12</p>
	    <p class="card-text"><b>temp</b> 12</p>
	    <p class="card-text"><b>temp</b> 12</p>
	  </div>
	</div> -->

</body>
</html>
