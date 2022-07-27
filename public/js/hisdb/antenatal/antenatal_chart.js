
$(document).ready(function () {
	var data = [
		{color: "blue", lines: {show: false}, points: {show:true}, data: [[10, 300], [14, 238], [38, 245], [49, 233]], label: "Blood Pressure", yaxis: 1},
	];

	plot = $.plot($("#placeholder"), data , {
		legend: {show: false},
		crosshair: {mode: "x"},
		grid: {
			hoverable: true,
			autoHighlight: false
		},
		xaxes: [{
			min: 10,
			max: 50,
			position: 'top'
		}],
		yaxes: [{
					min: 0,
					max: 400,
				}],
	});
});