
var urlparam_episodelist = {
	action:'episodelist',
	url:'./pat_enq/table',
	mrn:null
}

var rowdata_episodelist=null;

$(document).ready(function () {

	$('#episodelist_panel').on('shown.bs.collapse', function () {
		SmoothScrollTo("#episodelist_panel", 500);
		$("#jqGrid_episodelist").jqGrid ('setGridWidth', Math.floor($("#jqGrid_episodelist_c")[0].offsetWidth-$("#jqGrid_episodelist_c")[0].offsetLeft));
		init_episodelist();
	});

	$("#jqGrid_episodelist").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'idno', name: 'idno', hidden:true},
			{ label: 'Episode', name: 'episno', classes: 'wrap', width:5, canSearch:true, checked:true},
			{ label: 'Type', name: 'epistycode', classes: 'wrap', width:5, canSearch:true},
			{ label: 'Adm Source', name: 'admsrccode_desc', classes: 'wrap', width:9, canSearch:true},
			{ label: 'Reg Dept', name: 'regdept_desc', classes: 'wrap', width:8, canSearch:true},
			{ label: 'Reg Date', name: 'reg_date', classes: 'wrap', width:9, canSearch:true},
			{ label: 'Reg Time', name: 'reg_time', classes: 'wrap', width:8},
			{ label: 'Case', name: 'case_desc', classes: 'wrap', width:8},
			{ label: 'Bill Type', name: 'billtype_desc', classes: 'wrap', width:15},
			{ label: 'Payer', name: 'payer_desc', classes: 'wrap', width:20},
			{ label: 'Doctor', name: 'doctorname', classes: 'wrap', width:20},
			{ label: 'End Date', name: 'dischargedate', classes: 'wrap', width:9, canSearch:true},
			{ label: 'Add By', name: 'adduser', classes: 'wrap', width:8, canSearch:true},
			{ label: 'Add Date', name: 'adddate', classes: 'wrap', width:9, canSearch:true},
			{ label: 'Status', name: 'episstatus', classes: 'wrap', width:10, canSearch:true},
			{ label: 'Status', name: 'episstatus', hidden:true},
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
			$("#jqGrid_episodelist").setSelection($("#jqGrid_episodelist").getDataIDs()[0]);
		},
		loadComplete: function(data){
			rowdata_episodelist = data.rows;
			let jq_max_hgt = $('#jqGrid_episodelist>tbody').closest('div.paneldiv').prop('clientHeight') - 120;
			calc_jq_height_onchange("jqGrid_episodelist",jq_max_hgt);
		}
		
	});
	populateSelect3("#jqGrid_episodelist","#eplist_searchform");
	searchClick("#jqGrid_episodelist","#eplist_searchform",urlparam_episodelist);
});

function populate_episodelist(obj){
	$('#name_show_episodelist').text(obj.Name);
	$('#mrn_show_episodelist').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_episodelist').text(obj.Sex);
	$('#dob_show_episodelist').text(dob_chg(obj.DOB));
	$('#age_show_episodelist').text(get_age(obj.DOB));
	$('#race_show_episodelist').text(obj.raceDesc);
	$('#religion_show_episodelist').text(if_none(obj.religionDesc));
	$('#occupation_show_episodelist').text(if_none(obj.occupDesc));
	$('#citizenship_show_episodelist').text(obj.cityDesc);
	$('#area_show_episodelist').text(obj.areaDesc);
	urlparam_episodelist.mrn = obj.MRN;

	$('b.epno-textmrn').text(("0000000" + obj.MRN).slice(-7));
	$('b.epno-textname').text(obj.Name);
}

function init_episodelist(){
	var row = bootgrid_last_row;
	if(row!=null){
		refreshGrid('#jqGrid_episodelist',urlparam_episodelist);
	}
}