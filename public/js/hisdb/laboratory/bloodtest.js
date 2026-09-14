$(document).ready(function () {

	$("#tab_bloodtest").on("shown.bs.collapse", function(){
	    $('#bloodtest_panel_title').show();
		clear_bloodres();
		SmoothScrollTo('#tab_bloodtest', 400,75);
	});

	$("#tab_bloodtest").on("hidden.bs.collapse", function(){
	    $('#bloodtest_panel_title').hide();
	});

	$('#month_year_br').calendar({
    	initialDate: new Date(),
   		type: 'month'
 	});

 	$('#rec_but_br').click(function(){
 		get_pagination_br();
 	});

	$('#print_rec_br').click(function(){
		$('table#bloodres').printThis();
	});
});

function paging_br_init(){
	$('div#paging_br a.item').off();
	$('div#paging_br a.item').on("click", function(){
 		get_pagination_br($(this).data('page'));
	});
}

function make_pagintaion_br(data){
		
	var curpage = parseInt(data.page);
	var lastpage = parseInt(data.total);

	$('div#paging_br').html('');
	for (var i = 0; i < data.total; i++) {
		if(i == parseInt(curpage-1)){
			$('div#paging_br').append(`<a class="active item" data-page="`+parseInt(i+1)+`">`+parseInt(i+1)+`</a>`);
		}else{
			$('div#paging_br').append(`<a class="item" data-page="`+parseInt(i+1)+`">`+parseInt(i+1)+`</a>`);
		}
	}

	data.rows.forEach(function(e,i){
		const entries = Object.entries(e);
		entries.forEach(function(e2,i2){
			if(e2[0] == 'sampledate'){
				$('table#bloodres tr#br_'+e2[0]).children('td').eq(i).text(e2[1]);
			}else{
				$('table#bloodres tr#br_'+e2[0]).children('td').eq(i+1).text(e2[1]);
			}
		});
	});

	paging_br_init();
}

function get_pagination_br(page=1){
	clear_bloodres();
	var param={
        mrn:$('#mrn_bloodtest').val(),
        episno:$('#episno_bloodtest').val(),
		action:'get_pagination_br',
		rows:10,
		newic:selrowData($('#jqGrid')).Newic,
		month:moment($('#month_year_br').calendar('get date')).format('YYYY-MM'),
		page:page
    };

    $.get( "./dialysis_bloodtest/table?"+$.param(param), function( data ) {

    },'json').done(function(data) {
    	make_pagintaion_br(data);
    }).fail(function(data){
    });
}

function clear_bloodres(){
	$('table#bloodres td[align=center],table#bloodres td.med_td').html('&nbsp;');
}

function populate_bloodtest(obj){
    // panel header
    $('#name_show_bloodtest').text(obj.Name);
    $('#mrn_show_bloodtest').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_bloodtest').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_bloodtest').text(dob_chg(obj.DOB));
    $('#age_show_bloodtest').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_bloodtest').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_bloodtest').text(if_none(obj.religion).toUpperCase());
    $('#occupation_show_bloodtest').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_bloodtest').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_bloodtest').text(if_none(obj.AreaCode).toUpperCase());
   
    // formOccupTherapy
    $('#mrn_bloodtest').val(obj.MRN);
    $("#episno_bloodtest").val(obj.Episno);
    $("#age_bloodtest").val(dob_age(obj.DOB));

    // $("#tab_occupTherapy").collapse('hide');

}