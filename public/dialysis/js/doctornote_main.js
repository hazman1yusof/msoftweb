
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	var urlParam = {
		action: 'patmast_current_patient',
		url: './pat_mast/post_entry',
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
			{ label: 'Name', name: 'Name', width: 30 ,classes: 'wrap' },
			{ label: 'Payer', name: 'payer', width: 20 ,classes: 'wrap' },
			{ label: 'I/C', name: 'Newic', width: 15 ,classes: 'wrap' },
			{ label: 'DOB', name: 'DOB', hidden: true},
			{ label: 'HP', name: 'telhp', hidden:true},
			{ label: 'Sex', name: 'Sex', width: 5 ,classes: 'wrap' },
			{ label: 'Last<br/>Arrival Date', name: 'arrival_date', width: 7. ,align: 'center', formatter:dateFormatter2, unformat:dateUNFormatter2},
			{ label: 'dialysis date', name: 'arrival_time',hidden: true},
			{ label: 'Arrival', name: 'arrival', width: 5. ,align: 'center', formatter:formatterstatus_tick, unformat:UNformatterstatus_tick},
			{ label: 'Complete', name: 'complete', width: 6. ,align: 'center', formatter:formatterstatus_tick, unformat:UNformatterstatus_tick},
			{ label: 'Nursing', name: 'nurse', width: 5 ,align: 'center', formatter:formatterstatus_tick2, unformat:UNformatterstatus_tick},
			{ label: 'Order', name: 'order', hidden: true},
			{ label: 'RaceCode', name: 'RaceCode', hidden: true },
			{ label: 'religion', name: 'religion', hidden: true },
			{ label: 'OccupCode', name: 'OccupCode', hidden: true },
			{ label: 'Citizencode', name: 'Citizencode', hidden: true },
			{ label: 'AreaCode', name: 'AreaCode', hidden: true },
		],
		autowidth: true,
		viewrecords: false,
		width: 900,
		height: 300,
		rowNum: 50,
		loadonce:false,
		scroll: true,
		onSelectRow:function(rowid, selected){
			closealltab();
			empty_userfile();
			$('button#timer_stop').click();

			// urlParam_trans.mrn = selrowData('#jqGrid').MRN;
			// urlParam_trans.episno = selrowData('#jqGrid').Episno;

			addmore_onadd = false;
			addmore_onadd_phys = false;
			addmore_onadd_diet = false;
			// refreshGrid("#jqGrid_trans", urlParam_trans);
			// refreshGrid("#jqGrid_trans_diet", urlParam_trans_diet);
			// refreshGrid("#jqGrid_trans_phys", urlParam_trans_phys);
			// refreshGrid("#jqGrid_card", urlParam_card);
            populate_currDoctorNote(selrowData('#jqGrid'));
            populate_triage_currpt(selrowData('#jqGrid'));
            populate_userfile(selrowData('#jqGrid'));
            // populate_dieteticCareNotes_currpt(selrowData('#jqGrid'));
            // populate_phys(selrowData('#jqGrid'));

			// if(selrowData('#jqGrid').e_ordercomplete){ //kalau dah completed
			// 	$('#checkbox_completed').prop('disabled',true);
			// 	$('#checkbox_completed').prop('checked', true);
			// }else{//kalau belum completed
			// 	$('#checkbox_completed').prop('disabled',false);
			// 	$('#checkbox_completed').prop('checked', false);
			// }

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
		},
		onSortCol: function(index, iCol, sortorder) {
		    curpage = null;
		},
		gridComplete: function () {
			$('.jqgridsegment').removeClass('loading');
			hide_tran_button(true);			
			$('#no_of_pat').text($("#jqGrid").getGridParam("reccount"));
			// empty_currDoctorNote();
			empty_transaction();
			// empty_transaction_diet();
			// empty_transaction_phys();
			// empty_dietcarenote();
			empty_userfile();
			// empty_currphys();
			empty_formNursing();

			// let discharge_btn_data = $('#discharge_btn').data('idno');
			// if(discharge_btn_data == undefined || discharge_btn_data == 'none'){
			// 	if(!$("button#timer_play").hasClass("disabled")){
			// 		$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			// 	}
			// }else{
			// 	$("#jqGrid").setSelection(discharge_btn_data);
			// }

		},
		beforeProcessing: function(data, status, xhr){
			if(curpage == data.current){
				return false;
			}else{
				curpage = data.current;
			}
		}
	});

	$("#jqGrid").jqGrid('setGroupHeaders', {
		useColSpanStyle: true, 
		groupHeaders:[
			{startColumnName: 'reff_rehab', numberOfColumns: 3, titleText: '<em>Referral</em>'},
		]
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

	function ordercompleteFormatter(cellvalue, option, rowObject) {
		if (cellvalue == '1') {
			// return '<span class="fa fa-check"></span>';
			return `<input type="checkbox" class="checkbox_completed" data-rowid="`+option.rowId+`" checked onclick="return false;">`;
		}else if (cellvalue == '0') {
			return `<input type="checkbox" class="checkbox_completed" data-rowid="`+option.rowId+`" >`;
		}
	}

	function ordercompleteUNFormatter(cellvalue, option, rowObject) {
		return $(rowObject).children('input[type=checkbox]').is("[checked]");
	}

	function visiblecancel(){
		var editing = true;
		var cont = true;

		if($('td#jqGrid_trans_ilcancel').hasClass("ui-disabled")){
			editing = false;
		}

		let records = $("#jqGrid_trans").jqGrid('getGridParam', 'records');

		if(records==1 && editing ){
			cont = false;
		}else if(records==0){
			cont = false;
		}

		return cont

	}

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

	var fetch_tbl,fetch_evt;
	timer_start_tbl();
	// timer_start_evt();

	function timer_start_tbl(){
		fetch_tbl = setInterval(function(){
			$('.jqgridsegment').addClass('loading');
			curpage=null;
			refreshGrid("#jqGrid", urlParam);
		}, 5000);
	}

	// function timer_start_evt(){
	// 	fetch_evt = setInterval(function(){
	// 		$('#calendar').fullCalendar( 'refetchEventSources', 'doctornote_event' );
	// 	}, 5000);
	// }

	function timer_stop_tbl(){
	  	clearInterval(fetch_tbl);
	}

	// function ordercompleteInit(){

	// 	$('input[type=checkbox].checkbox_completed').on('change',function(e){
	// 		let cont = visiblecancel();

	// 		if(cont ==  false){
	// 			$.alert({
	// 			    title: 'Alert',
	// 			    content: 'Please enter charges',
	// 			});
	// 			$(this).prop('checked', false);
	// 		}else{
	// 			let self = this;
	// 			let rowid = $(this).data('rowid');
	// 			let rowdata = $('#jqGrid').jqGrid ('getRowData', rowid);

	// 			$.confirm({
	// 			    title: 'Confirm',
	// 			    content: 'Do you want to complete all entries?',
	// 			    buttons: {
	// 			        Yes:{
	// 			        	btnClass: 'btn-blue',
	// 			        	action: function () {
	// 				        	var param = {
	// 								_token: $("#_token").val(),
	// 								action: 'change_status',
	// 								mrn: rowdata.mrn,
	// 								episno: rowdata.episno,
	// 							}

	// 							$.post( "./change_status?"+$.param(param),{}, function( data ){
	// 								if(data.success == 'success'){
	// 									toastr.success('Patient status completed',{timeOut: 1000})
	// 									refreshGrid("#jqGrid", urlParam);
	// 								}
	// 							},'json');
	// 				         }

	// 			        },
	// 			        No: {
	// 			        	action: function () {
	// 							$(self).prop('checked', false);
	// 				        },
	// 			        }
	// 			    }

	// 			});

	// 		}
	// 	});
		
	// }

	function checkifedited(){
		if(!$('#save_doctorNote').is(':disabled')){
			if(!$('#tab_doctornote').hasClass('in')){
				$('#toggle_doctornote').click();
			}
			gotobtm("#tab_doctornote",'#save_doctorNote','#cancel_doctorNote');
		}else{
			return false;
		}
		return true;
	}

	function gotobtm(tab,save,cancel){
		SmoothScrollTo(tab, 300, function(){
			$.confirm({
				closeIcon: true,
			    title: 'Confirm',
			    content: 'Do you wish to save or cancel your changes?',
			    buttons: {
			        Save:{
			        	btnClass: 'btn-green',
			        	action: function () {
				        	$(save).click();
			        	}
			        },
			        Cancel: {
			        	action: function () {
				        	$(cancel).click();
				        },
			        },
			    }

			});
		});
	}

	stop_scroll_on();

	user_groupid();
	function user_groupid(){
		var groupid = $('#user_groupid').val().toUpperCase();

		$('#btn_grp_edit_doctorNote, #btn_grp_edit_phys, #btn_grp_edit_phys_ncase, #btn_grp_edit_dieteticCareNotes, #btn_grp_edit_dieteticCareNotes_fup').hide();
		switch(groupid) {
		  case 'DOCTOR':
		  case 'ADMIN':
		  case 'CLINICAL':
		    $('#btn_grp_edit_doctorNote').show();
		    break;
		  case 'PHYSIOTERAPHY':
		    // $('#btn_grp_edit_phys').show();
		    $('#btn_grp_edit_phys_ncase').show();
		    break;
		  case 'REHABILITATION':
		    $('#btn_grp_edit_phys_ncase').show();
		    break;
		  case 'DIETICIAN':
		    $('#btn_grp_edit_dieteticCareNotes, #btn_grp_edit_dieteticCareNotes_fup').show();
		    break;
		  default:
		    // code block
		}
	}

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

function dateFormatter2(cellvalue, options, rowObject){
	if(cellvalue == null) return '';
	return moment(cellvalue).format("DD/MM/YYYY") + '</br>' + rowObject.arrival_time + `<span data-original=`+cellvalue+`><span>`;
}

function dateUNFormatter2(cellvalue, options, rowObject){
	return $(rowObject).children('span').data('original');
}

function stop_scroll_on(){
	$('div.paneldiv').on('mouseenter',function(){
		SmoothScrollTo('#'+$('div.mainpanel[aria-expanded=true]').parent('div.panel.panel-default').attr('id'), 300,undefined,30);
		$('body').addClass('stop-scrolling');
	});

	$('div.paneldiv').on('mouseleave',function(){
		$('body').removeClass('stop-scrolling')
	});
}



