

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
		arr.push([min+10,'<b>SP02 (%)</b>']);

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
		$("#placeholder").append("<div style='position:absolute;left:" + (o.left-10) + "px;top:" + o.top + "px;color:#666;font-size:smaller'>"+item[1]+"</div>");
	});
}

function plot_time_date(plot,array){
	array.forEach(function(item,index){
		let o = plot.pointOffset({ x: index+1, y: 190});
		let mom = moment(item[1], "YYYY-MM-DD HH:mm:ss");
		$("#placeholder").append("<div style='position:absolute;left:" + (o.left-18) + "px;top:" + o.top + "px;color:#666;font-size:smaller'>"+mom.format('h:m</br>a')+"</div>");
		$("#placeholder").append("<div style='position:absolute;left:" + (o.left-18) + "px;top:" + (o.top-35) + "px;color:#666;font-size:smaller'>"+mom.format('DD-MM<br>YYYY')+"</div>");
	});
}

function plot_total(plot,array){
	array.forEach(function(item,index){
		let o = plot.pointOffset({ x: index+1, y: 0.2});
		$("#placeholder2").append("<div style='position:absolute;left:" + (o.left-10) + "px;top:" + o.top + "px;color:#666;font-size:smaller'><b>"+item[1]+"</b></div>");
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
				<div class="col-md-3">
					<b>DXT</b></br>
					<span class='dxt'>(GREEN)</span>
				</div>
				<div class="col-md-3">
					<b>Blood Pressure</b></br>
					<span class='sistole'>(BLUE)</span>
				</div>
				<div class="col-md-3">
					<b>Heart Rate</b></br>
					<span class='pulse'>(RED)</span>
				</div>
				<div class="col-md-3">
					<b>Temperature</b></br>
					<span class='temp'>(ORANGE)</span>
				</div>
			</div>`;

	$("#placeholder").append("<div class='legendtepi' style='top:" + o.top + "px;'>"+bp+"</div>");
}

function legend_tepi2(plot){
	let o = plot.pointOffset({ x: 0, y: 11});
	let bp=`<div class="row" >
				<div class="col-md-3">
					<b>Best Motor Response</b>
				</div>
				<div class="col-md-3">
					<b>Best Verbal Response</b>
				</div>
				<div class="col-md-3">
					<b>Eyes Open</b>
				</div>
			</div>`;

	$("#placeholder2").append("<div class='legendtepi' style='top:" + (o.top-25) + "px;left:-270px;'>"+bp+"</div>");
}