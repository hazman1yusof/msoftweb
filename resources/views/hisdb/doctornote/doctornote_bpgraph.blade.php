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
		.dxt{color: green}
		.legendtepi{position:absolute;left:-200px;color:#666;font-size:small;
			-webkit-transform: rotate(270deg);
			-moz-transform: rotate(270deg);
			-o-transform: rotate(270deg);
			writing-mode: lr-tb;
			width: 40%;
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

	$(function() {

		$("#rangeshow").text($("#customRange2").val());
		$("#customRange2").change(function() {
			$("#rangeshow").text($("#customRange2").val());
		});

		// fetchBio();
		// function fetchBio(){
		// 	fetch('bio.txt')
		// 		.then(response => response.text())
		// 		.then(function(data){
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

			$.get( "./doctornote/table"+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				console.log(data.data);

				var obj = data.data;

				var sis_dis = obj.reduce(function(accum,value,i){
					let diff = (parseFloat(value.bphistolic) - parseFloat(value.bpdiastolic)) / 2;
					let center = parseFloat(value.bphistolic) - parseFloat(diff);
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
					if(all){ arr.push([index+1,value.hr]); accum.i+=1;}
					else{
						let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.hr]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var temp = obj.reduce(function(accum,value,i){
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

				var dxt = obj.reduce(function(accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.dxt]); accum.i+=1;}
					else{
						let mom = moment(value.time, "YYYY-MM-DD HH:mm:ss");
						if(mom.isBetween(datefr_,dateto_,'day','[]')){
							arr.push([index+1,value.dxt]); accum.i+=1;
						}
					}
					return accum;
				},{arr:[],i:0}).arr;

				var tick1 = obj.reduce(function(accum,value,i){
					let arr = accum.arr,index = accum.i;
					if(all){ arr.push([index+1,value.roomair]); accum.i+=1;}
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
					if(all){ arr.push([index+1,value.oxygen]); accum.i+=1;}
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
					if(all){ arr.push([index+1,value.breathnormal]); accum.i+=1;}
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
					if(all){ arr.push([index+1,value.datetaken+' '+value.timetaken]); accum.i+=1;}
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

				doPlot(x = parseInt(range)+1,sis_dis = filterRange(sis_dis,range),pulse = filterRange(pulse,range),temp = filterRange(temp,range),dxt = filterRange(dxt,range),tick1 = filterRange(tick1,range),tick2 = filterRange(tick2,range),tick3 = filterRange(tick3,range),time = filterRange(time,range), painScore = filterRange(painScore,range));
				xAxisReplot("#placeholder div.x1Axis");

			});
		}

		var plot;
		function doPlot(x,sis_dis,pulse,temp,dxt,tick1,tick2,tick3,time,painScore){
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
				{color: "green", lines: {show: true, lineWidth: 1}, points: {show:true}, data: dxt, label: "DXT", yaxis: 2},
				{lines: {show: false}, data: painScore},
				{lines: {show: false}, data: tick1},
				{lines: {show: false}, data: tick2},
				{lines: {show: false}, data: tick3},
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
				{color: "black", points: {symbol:"cross",show:true}, data: tick1, label: "Best Motor Response"},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(tick2,6), label: "Best Verbal Response"},
				{color: "black", points: {symbol:"cross",show:true}, data: accum_more_tick(tick3,11), label: "tick3"},
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
					max: 15.5,
					ticks: [
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
							[13.5,"Oxygen</br></br></br>"],
							[14.5,"Room Air</br></br></br>"],
							[15.5,""]]
				},{
							// align if we are to the right
							min: 1,
							max: 2,
							ticks: tick_yaxis(2,1,nbsp(6)),
							position: 'right'
				}]

			});
			// plot_total(plot2,total_tick(tick1,tick2,tick3));
			// legend_tepi2(plot2);
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

		function updateLegend() {
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
				dxt = arr[3][1];
				painScore = arr[4][1];
				date = arr[8][1];

			$("#tooltip").html(`<b>Date: </b><span class='Date'>`+date+`<hr>`
								+`</span><b>Sistole: </b><span class='sistole'>`+sistole
								+`</span><br/><b>Diastole: </b><span class='diastole'>`+diastole
								+`</span><br/><b>Pulse: </b><span class='pulse'>`+pulse
								+`</span><br/><b>Temperature: </b><span class='temp'>`+temp
								+`</span><br/><b>DXT: </b><span class='dxt'>`+dxt
								+`</span><br/><b>SP02 (%): </b><span>`+painScore
								+`</span>`)
					.css({top: pos.pageY+20, left: pos.pageX+20})
					.fadeIn(500);

		}

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

		fetchdata(true,$("#customRange2").val(),firstPlot=true);

		$('#customRange2').change(function(){
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
			<div id="placeholder2" class="demo-placeholder" style="height: 40%; margin-top: -34px"></div>
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
