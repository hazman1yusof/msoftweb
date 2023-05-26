
var urlparam_episodelist = {
	action:'episodelist',
	url:'./pat_enq/table',
	mrn:null
}

$(document).ready(function () {

	$("#jqGrid_episodelist").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Episode', name: 'episno', classes: 'wrap', width:10},
			{ label: 'Type', name: 'epistycode', classes: 'wrap', width:10},
			{ label: 'Reg Date', name: 'reg_date', classes: 'wrap', width:10},
			{ label: 'Case', name: 'case_desc', classes: 'wrap', width:20},
			{ label: 'Pay Type', name: 'pay_type_desc', classes: 'wrap', width:20},
			{ label: 'Doctor', name: 'doctorname', classes: 'wrap', width:50},
			{ label: 'End Date', name: 'dischargedate', classes: 'wrap', width:20},
			{ label: 'Status', name: 'episstatus', classes: 'wrap', width:10}
		],
		autowidth:true,
		viewrecords: true,
		loadonce:false,
		rowNum: 30,
		pager: "#jqGrid_episodelistPager",
		onSelectRow:function(rowid, selected){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
		loadComplete: function(data){
			let jq_max_hgt = $('#jqGrid_episodelist>tbody').closest('div.paneldiv').prop('clientHeight') - 120;
			calc_jq_height_onchange("jqGrid_episodelist",jq_max_hgt);
		}
		
	});


	$('#episodelist_panel').on('shown.bs.collapse', function () {
		SmoothScrollTo("#episodelist_panel", 500);
		$("#jqGrid_episodelist").jqGrid ('setGridWidth', Math.floor($("#jqGrid_episodelist_c")[0].offsetWidth-$("#jqGrid_episodelist_c")[0].offsetLeft));
		init_episodelist();
	});
});

function populate_episodelist(obj){
	$('#name_show_episodelist').text(obj.Name);
	$('#mrn_show_episodelist').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_episodelist').text(obj.Sex);
	$('#dob_show_episodelist').text(dob_chg(obj.DOB));
	$('#age_show_episodelist').text(get_age(obj.DOB));
	$('#race_show_episodelist').text(obj.RaceCode);
	$('#religion_show_episodelist').text(if_none(obj.Religion));
	$('#occupation_show_episodelist').text(if_none(obj.OccupCode));
	$('#citizenship_show_episodelist').text(obj.Citizencode);
	$('#area_show_episodelist').text(obj.AreaCode);
	urlparam_episodelist.mrn = obj.MRN;
}

function init_episodelist(){
	var row = bootgrid_last_row;
	if(row!=null){
		refreshGrid('#jqGrid_episodelist',urlparam_episodelist);
	}
}