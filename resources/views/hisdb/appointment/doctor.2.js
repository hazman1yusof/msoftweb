$(document).ready(function() {

console.log($(window).height());

$(window).on("resize", function(){
        console.log( "Height: " + $(window).height() );
      });


		$('#calendar').fullCalendar({
			header: {
				left: '',
				center: 'title',
				right: 'prev,next month,agendaWeek,today'
			},
			height: 600,
			editable: false,
			minTime: "07:00:00",
			maxTime: "20:00:00",
			slotDuration: "00:30:00",
			fixedWeekCount: false,
			eventLimit: true, // allow "more" link when too many events
			events: ""
		});
});