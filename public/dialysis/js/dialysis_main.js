
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	var urlParam = {
		action: 'patmast_current_patient',
		url: './dialysis_pat_mast/post_entry',
		curpat: 'true',
		showall:false,
		showcomplete:false,
	}

	$("input[name='Stext']").click(function(){
		$('button#timer_stop').click();
	})

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno',hidden: true, key:true},
			{ label: 'MRN', name: 'MRN', width: 5, classes: 'wrap', formatter: padzero, unformat: unpadzero },
			{ label: 'Episode', name: 'Episno', width: 5 ,align: 'left',classes: 'wrap' },
			// { label: 'Time', name: 'reg_time', width: 10 ,classes: 'wrap', formatter: timeFormatter, unformat: timeUNFormatter},
			{ label: 'Name', name: 'Name', width: 30 ,classes: 'wrap' , formatter:nameformatter, unformat:nameunformatter},
			{ label: 'Payer', name: 'payer', width: 20 ,classes: 'wrap' },
			{ label: 'I/C', name: 'Newic', width: 15 ,classes: 'wrap' },
			{ label: 'DOB', name: 'DOB', hidden: true},
			{ label: 'HP', name: 'telhp', hidden:true},
			{ label: 'Sex', name: 'Sex', width: 5 ,classes: 'wrap' },
			{ label: 'Last<br/>Arrival Date', name: 'arrival_date', width: 7. ,align: 'center', formatter:dateFormatter2, unformat:dateUNFormatter2},
			{ label: 'dialysis date', name: 'arrival_time',hidden: true},
			{ label: 'Arrival', name: 'arrival', width: 5 ,align: 'center', formatter:formatterstatus_tick, unformat:UNformatterstatus_tick},
			{ label: 'Complete', name: 'complete', width: 6 ,align: 'center', formatter:formatterstatus_tick, unformat:UNformatterstatus_tick},
			{ label: 'Nursing', name: 'nurse', width: 5 ,align: 'center', formatter:formatterstatus_tick2, unformat:UNformatterstatus_tick},
			{ label: 'Order', name: 'order', hidden: true},
			{ label: 'RaceCode', name: 'RaceCode', hidden: true },
			{ label: 'religion', name: 'religion', hidden: true },
			{ label: 'OccupCode', name: 'OccupCode', hidden: true },
			{ label: 'Citizencode', name: 'Citizencode', hidden: true },
			{ label: 'AreaCode', name: 'AreaCode', hidden: true },
			{ label: 'packagecode', name: 'packagecode', hidden: true },
			{ label: 'dialysis_status', name: 'dialysis_status', hidden: true },
		],
		autowidth: true,
		viewrecords: true,
		width: 900,
		height: 300,
		rowNum: 50,
		loadonce:false,
		scroll: true,
		onSelectRow:function(rowid, selected){
			closealltab();
			cleartabledata('all');
			button_state_dialysis('disableAll');
			$('button#timer_stop').click();
			var selrowdata = selrowData('#jqGrid');

			if($('#viewallcenter').val() != 1){
				if(selrowData('#jqGrid').arrival != 0){
					$('#dialysis_episode_idno').val(selrowdata.arrival);
					if(selrowData('#jqGrid').complete == 0){
						hide_tran_button(false);
					}else{
						hide_tran_button(true);
					}
				}else{
					$('#dialysis_episode_idno').val(0);
					hide_tran_button(false);
				}
			}else{
				if(selrowData('#jqGrid').arrival != 0){
					$('#dialysis_episode_idno').val(selrowdata.arrival);
					hide_tran_button(false);
				}else{
					$('#dialysis_episode_idno').val(0);
					hide_tran_button(false);
				}
			}

			// if(selrowdata.order==1){
			// 	$('#toggle_daily').removeClass('divpanel_disable');
			// }else{
			// 	$('#toggle_daily').addClass('divpanel_disable');
			// }
			
			populatedialysis(selrowdata);
			urlParam_trans.mrn = selrowdata.MRN;
			urlParam_trans.episno = selrowdata.Episno;
			addmore_onadd = false;
			curpage_tran = null;

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
		},
		onSortCol: function(index, iCol, sortorder) {
		    curpage = null;
		},
		gridComplete: function () {
			urlParam_trans.mrn = "";
			urlParam_trans.episno =  "";
			empty_dialysis();
			empty_transaction();
			$('#no_of_pat').text($('#jqGrid').jqGrid('getGridParam', 'reccount'));

			if($('#jqGrid').data('lastidno') != null || $('#jqGrid').data('lastidno') != undefined){
				$('#jqGrid').jqGrid('setSelection', $('#jqGrid').data('lastidno'));
			}
			$('#jqGrid').data('lastidno',null);
		},
		beforeProcessing: function(data, status, xhr){
			if(curpage == data.current){
				return false;
			}else{
				curpage = data.current;
			}
		}
	});
	jqgrid_label_align('#jqGrid');
	addParamField('#jqGrid',true,urlParam,['action']);
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			curpage=null;
			refreshGrid("#jqGrid", urlParam);
		},
	});

	searchClick_scroll("#jqGrid","#SearchForm",urlParam);

	$('.ui.checkbox.myslider.showall').checkbox({
		onChecked: function() {
			urlParam.showall = true;
			curpage = null;
			refreshGrid("#jqGrid", urlParam);
	    },
	    onUnchecked: function() {
			urlParam.showall = false;
			curpage = null;
			refreshGrid("#jqGrid", urlParam);
	    },
	});

	$('.ui.checkbox.myslider.showcomplete').checkbox({
		onChecked: function() {
			urlParam.showcomplete = true;
			curpage = null;
			refreshGrid("#jqGrid", urlParam);
	    },
	    onUnchecked: function() {
			urlParam.showcomplete = false;
			curpage = null;
			refreshGrid("#jqGrid", urlParam);
	    },
	});

	stop_scroll_on();

	$('button#timer_play').click(function(){
		timer_start_tbl();
		$('button#timer_play').addClass('disabled');
		$('button#timer_stop').removeClass('disabled');
	});

	$('button#timer_stop').click(function(){
		timer_stop_tbl();
		$('button#timer_play').removeClass('disabled');
		$('button#timer_stop').addClass('disabled');
	});

	$('button#timer_refresh').click(function(){
		curpage=null;
		$('#jqGrid').data('lastidno',selrowData('#jqGrid').idno);
		refreshGrid("#jqGrid", urlParam);
	});

	var fetch_tbl;
	timer_start_tbl();
	function timer_start_tbl(){
		fetch_tbl = setInterval(function(){
			$('.jqgridsegment').addClass('loading');
			curpage=null;
			refreshGrid("#jqGrid", urlParam);
		}, 5000);
	}

	function timer_stop_tbl(){
	  	clearInterval(fetch_tbl);
	}

});

var curpage=null; // to prevent duplicate entry curpage kena falsekan blk setiap kali nak refresh dari awal 
function closealltab(except){
	var tab_arr = ["#tab_trans","#tab_daily","#tab_weekly","#tab_monthly"];
	tab_arr.forEach(function(e,i){
		if(e != except){
			$(e).collapse('hide');
		}
	});
}

function searchClick_scroll(grid,form,urlParam){
	$(form+' [name=Stext]').on( "keyup", function() {
		delay(function(){
			curpage = null;
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		}, 500 );
	});

	$(form+' [name=Scol]').on( "change", function() {
		curpage = null;
		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	});
}

function formatterstatus_tick(cellvalue, option, rowObject) {
	if (cellvalue != null && cellvalue != 0 && moment().isSame(rowObject.arrival_date, 'day')) {
		return '<span class="fa fa-check" data-value="'+cellvalue+'"></span>';
	}else{
		return "";//if value is zero will capture as "" when unformat
	}
}

function formatterstatus_tick2(cellvalue, option, rowObject) {
	if (cellvalue != null && cellvalue != 0 ) {
		return '<span class="fa fa-check" data-value="'+cellvalue+'"></span>';
	}else{
		return "";//if value is zero will capture as "" when unformat
	}
}

function UNformatterstatus_tick(cellvalue, option, cell) {
	if($('span.fa', cell).data('value') == undefined){
		return 0;
	}else{
		return $('span.fa', cell).data('value');
	}
}

function stop_scroll_on(){
	$('div.paneldiv').on('mouseenter',function(){
		SmoothScrollTo('#'+$('div.mainpanel[aria-expanded=true]').parent('div.panel.panel-default').attr('id'), 300,undefined,40);
		$('body').addClass('stop-scrolling');
	});

	$('div.paneldiv').on('mouseleave',function(){
		$('body').removeClass('stop-scrolling')
	});
}

function dateFormatter2(cellvalue, options, rowObject){
	if(cellvalue == null) return '';
	return moment(cellvalue).format("DD/MM/YYYY") + '</br>' + rowObject.arrival_time + `<span data-original=`+cellvalue+`><span>`;
}

function dateUNFormatter2(cellvalue, options, cell){
	return $(cell).children('span').data('original');
}

function nameformatter(cellvalue, options, rowObject){
	if(cellvalue == null) return '';
	if(rowObject.dialysis_status == 'ABSENT'){
		return cellvalue+'<div style="color:darkred;">'+rowObject.dialysis_status +'</div>'+ `<span data-original='`+cellvalue+`'><span>`;
	}else{
		return cellvalue+`<span data-original='`+cellvalue+`'><span>`;
	}
}

function nameunformatter(cellvalue, options, cell){
	return $(cell).children('span').data('original');
}
