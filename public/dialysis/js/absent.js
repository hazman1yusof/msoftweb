$(document).ready(function () {
	$('#month_year_calendar_absent').calendar({
    	initialDate: new Date(),
   		type: 'month'
 	});


	$("#tab_absent").on("show.bs.collapse", function(){
		closealltab("#tab_absent");
	});

	$("#tab_absent").on("shown.bs.collapse", function(){
        table_absent.clear().draw();
		closealltab("#tab_absent");
		SmoothScrollTo('#tab_absent', 300,undefined,90);
	});

 	$('#rec_absent_but').click(function(){
        table_absent.clear().draw();
		table_absent.ajax.reload();
	});

	$('#print_absent').click(function(){
		$('table#table_absent').printThis();
	});
});

var table_absent = $('#table_absent').DataTable({
    ajax: './get_data_dialysis?action=get_absent',
	scrollY: 450,
	paging: false,
    order: [[ 0, "desc" ]],
    columns: [
        { data: 'idno', visible: false},
        { data: 'mrn', 'width':'5%'},
        { data: 'episno', 'width':'5%'},
        { data: 'arrival_date'},
        { data: 'regdept'},
        { data: 'name', 'width':'55%'},
        { data: 'status'}
    ],
    drawCallback: function( settings ) {
    },
    initComplete: function( settings, json ) {
    }
}).on('xhr.dt', function ( e, settings, json, xhr ) {
}).on('preXhr.dt', function ( e, settings, data ) {
    data.date = moment($('#month_year_calendar_absent').calendar('get date')).format('YYYY-MM');
});