$(document).ready(function () {
	disableForm('form#daily_form');
	disableForm('form#daily_form_completed');

	$('#password_mdl').modal({centered: true,closable:false});

	$("form#daily_form").validate({
		ignore: [], //check jgk hidden
		rules:{
			general_assesment:{
    			required: true,
    			minlength: 3
			}
		},
		messages: {
		    general_assesment: {
      			required: "",
		      	minlength: jQuery.validator.format("At least {0} characters required!")
		    }
		  },
	  	invalidHandler: function(event, validator) {
	  		validator.errorList.forEach(function(e,i){
	  			if($(e.element).is("select")){
	  				$(e.element).parent().addClass('error');
	  			}
	  		});
	  		$(validator.errorList[0].element).focus();
	  		alert('Please fill all mandatory field before patient completion');
	  	},
	  	errorPlacement: function(error, element) {
	  		if (element.attr("name") == "general_assesment" ) {
		      error.insertAfter(element);
		    }
	  	}
	});

	$("form#daily_form_completed").validate({
		ignore: [], //check jgk hidden
	  	invalidHandler: function(event, validator) {
	  		validator.errorList.forEach(function(e,i){
	  			if($(e.element).is("select")){
	  				$(e.element).parent().addClass('error');
	  			}
	  		});
	  		$(validator.errorList[0].element).focus();
	  		alert('Please fill all mandatory field before patient completion');
	  	},
	  	errorPlacement: function(error, element) { }
	});

	$("form#verify_form").validate({
		ignore: [], //check jgk hidden
	  	errorPlacement: function(error, element) { }
	});


    $('form#daily_form .ui.dropdown').dropdown({
    	onChange: function(value, text, $selectedItem) {
    		// console.log($selectedItem.parent());
	    	$selectedItem.parent().parent().removeClass('error')
	    }
	});

	$('form#daily_form_completed .ui.dropdown').dropdown({
    	onChange: function(value, text, $selectedItem) {
    		// console.log($selectedItem.parent());
	    	$selectedItem.parent().parent().removeClass('error')
	    }
	});
	
	button_state_dialysis('disableAll');
	$('#new_dialysis').click(function(){
		button_state_dialysis('wait');
		enableForm('form#daily_form');
		rdonly('form#daily_form');
		add_edit_mode('add');
		populate_other_data();
	});

	$('#edit_dialysis').click(function(){
		button_state_dialysis('wait');
		enableForm('form#daily_form');
		enableForm('form#daily_form_completed');
		rdonly('form#daily_form');
		rdonly('form#daily_form_completed');
		add_edit_mode('edit');
		$('#complete_dialysis').prop('disabled',false);
		if(patmedication_trx_tbl.rows().count() > 0){
			$('#complete_dialysis').prop('disabled',true);
		}
	});

	$('#cancel_dialysis').click(function(){
		button_state_dialysis($(this).data('oper'));
		$('#complete_dialysis').prop('disabled',true);
		off_edit_mode();
		if($(this).data('oper') == 'add'){
			disableForm('form#daily_form');
			disableForm('form#daily_form_completed');
			emptyFormdata([],'form#daily_form');
			emptyFormdata([],'form#daily_form_completed');
			$('form#daily_form .ui.dropdown').dropdown('restore defaults');
			$('form#daily_form input,form#daily_form div').removeClass('valid').removeClass('error');
		}else{
			autoinsert_rowdata_dialysis('form#daily_form',last_dialysis_data);
			autoinsert_rowdata_dialysis('form#daily_form_completed',last_dialysis_data);
			disableForm('form#daily_form');
			disableForm('form#daily_form_completed');
			$('form#daily_form input,form#daily_form div').removeClass('valid').removeClass('error');
		}
	});

	$('#save_dialysis').click(function(){
		if(check_valid_prehd()) {
			loader_daily(true);
			let curoper = $('#cancel_dialysis').data('oper');

			var param = {
				_token: $("#_token").val(),
				action: 'save_dialysis',
				oper: $('#cancel_dialysis').data('oper'),
				arrivalno_post: $('#dialysis_episode_idno').val(),
				mrn_post:$("#mrn").val(),
				episno_post:$("#episno").val(),
				idno_post:$("#idno").val(),
				visit_date_post:$("#visit_date").val()
			}

			var daily_form = $("form#daily_form").serializeArray();

			$.post( "./save_dialysis?"+$.param(param),$.param(daily_form), function( data ){
				
			},'json').fail(function(data) {
	            alert(data.responseText);
	            loader_daily(false);
	        }).done(function(data){
	            loader_daily(false);
				$('#cancel_dialysis').data('oper','edit');
				button_state_dialysis('edit');
				$('#complete_dialysis').prop('disabled',true);
				disableForm('form#daily_form');
				disableForm('form#daily_form_completed');

				if(curoper == 'add'){
					$('#idno').val(data.idno);
					$('#arrivalno').val(data.arrivalno);
					urlParam_AddNotesDialysis.mrn=$("#mrn").val();
					urlParam_AddNotesDialysis.episno=$("#episno").val();
					urlParam_AddNotesDialysis.arrivalno=$('#arrivalno').val();

					refreshGrid("#jqGridAddNotesDialysis", urlParam_AddNotesDialysis);
				}
	        });
		}
	});

	$('#complete_dialysis').click(function(){
		if($("form#daily_form").valid() && $("form#daily_form_completed").valid()){
			loader_daily(true);
			var param = {
				_token: $("#_token").val(),
				action: 'save_dialysis_completed',
				arrivalno_post: $('#arrivalno').val(),
				mrn_post:$("#mrn").val(),
				episno_post:$("#episno").val(),
				idno_post:$("#idno").val(),
				visit_date_post:$("#visit_date").val()
			}

			var daily_form = $("form#daily_form").serializeArray();
			var daily_form_completed = $("form#daily_form_completed").serializeArray();

			$.post( "./save_dialysis_completed?"+$.param(param),$.param(daily_form)+'&'+$.param(daily_form_completed), function( data ){
				
			},'json').fail(function(data) {
	            alert(data.responseText);
	            loader_daily(false);
	        }).done(function(data){
	            loader_daily(false);
				$('#cancel_dialysis').data('oper','edit');
				button_state_dialysis('edit');
				$('#complete_dialysis').prop('disabled',true);
				disableForm('form#daily_form');
				disableForm('form#daily_form_completed');
	        });
		}
	});

	$("#tab_daily").on("show.bs.collapse", function(){
		closealltab("#tab_daily");
		button_state_dialysis('disableAll');
		check_pt_mode();
		load_patmedication_trx($("#mrn").val(),$("#episno").val(),$("#visit_date").val());
		load_patmedication($("#mrn").val(),$("#episno").val(),$("#visit_date").val());
	});

	$("#tab_daily").on("hide.bs.collapse", function(){
		$('#cancel_dialysis').click();
		$('select#dialysisbefore').dropdown('restore defaults');
	});

	$("#tab_daily").on("shown.bs.collapse", function(){
		SmoothScrollTo('#tab_daily', 300,undefined,90);
		$("#jqGridAddNotesDialysis").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesDialysis_c")[0].offsetWidth-$("#jqGridAddNotesDialysis_c")[0].offsetLeft-25));
		urlParam_AddNotesDialysis.mrn=$("#mrn").val();
		urlParam_AddNotesDialysis.episno=$("#episno").val();
		urlParam_AddNotesDialysis.arrivalno=$('#arrivalno').val();

		hide_jqGridAddNotesDialysis_button(true);
		refreshGrid("#jqGridAddNotesDialysis", urlParam_AddNotesDialysis);
		calc_jq_height_onchange("jqGridAddNotesDialysis");
	});

	$("#tab_weekly").on("show.bs.collapse", function(){
		closealltab("#tab_weekly");
	});

	$("#tab_weekly").on("shown.bs.collapse", function(){
		// closealltab("#tab_weekly");
		SmoothScrollTo('#tab_weekly', 300,undefined,90);
	});

	$("#tab_monthly").on("show.bs.collapse", function(){
		closealltab("#tab_monthly");
	});

	$("#tab_monthly").on("shown.bs.collapse", function(){
		// closealltab("#tab_monthly");
		SmoothScrollTo('#tab_monthly', 300,undefined,90);
	});

	// $('#submit').click(function(){

	// 	if($('form#daily_form').form('validate form')) {
	// 		var param = {
	// 			_token: $("#_token").val(),
	// 			action: 'save_dialysis',
	// 			oper: $(this).data('oper'),
	// 			mrn:$("#mrn").val(),
	// 			episno:$("#episno").val(),
	// 			seldate:$("#seldate").val()
	// 		}

	// 		var values = $("form#daily_form").serializeArray();

	// 		$.post( "./save_dialysis?"+$.param(param),$.param(values), function( data ){
	// 			if(data.success == 'success'){
	// 				$('#addnew_dia').prop('disabled',true);
	// 				$('#edit_dia').prop('disabled',false);
	// 				disableForm('form#daily_form');
	// 				$('#toTop').click();
	// 				toastr.success('Dialysis data saved!',{timeOut: 1000});
	// 				SmoothScrollTo('#tab_daily', 300,undefined,90);
	// 			}
	// 		},'json');
	// 	}

	// });

	$('#rec_monthly_but').click(function(){
		cleartabledata('monthly');
		var param = {
			action: 'get_dia_monthly',
			date:$("#selectmonth").val(),
			mrn:$("#mrn").val(),
			episno:$("#episno").val()
		}

		$.get("./get_data_dialysis?"+$.param(param), function(data) {
			populate_data('monthly',data.data);
		},'json');

	});

	$('#selectweek_from').change(function(){
		let value =  $(this).val();
		let valueto = moment(value).add(7, 'days').format("YYYY-MM-DD");
		$('#selectweek_to').val(valueto);
	})

	$('#weeklyDatePicker').on('dp.change', function (e) {
    	value = $("#weeklyDatePicker").val();
	    firstDate = moment(value, "MM-DD-YYYY").day(0).format("MM-DD-YYYY");
	    lastDate =  moment(value, "MM-DD-YYYY").day(6).format("MM-DD-YYYY");
	    $("#weeklyDatePicker").val(firstDate + "   -   " + lastDate);
	});

	$('#rec_weekly_but').click(function(){
		cleartabledata('weekly');
		var param = {
			action: 'get_dia_weekly',
			datefrom:$("#selectweek_from").val(),
			dateto:$("#selectweek_to").val(),
			mrn:$("#mrn").val(),
			episno:$("#episno").val()
		}

		$.get("./get_data_dialysis?"+$.param(param), function(data) {
			populate_data('weekly',data.data);
		},'json');

	});	

  	$('#verified_btn').click(function(){
  		if(!$('#save_dialysis').is("[disabled]")){
	  		emptyFormdata([],'form#verify_form');
	  		$('#verify_btn').off();
	  		$('#verify_btn').on('click',function(){
				if($("form#verify_form").valid()) {
	  				verifyuser();
				}
	  		});
	  		$('#password_mdl').modal('show');
	  		$('body,#password_mdl').addClass('scrolling');
	  		$('#verify_error').hide();
  		}
  	});

  	$('#edit_permission').click(function(){
  		// if(!$('#save_dialysis').is("[disabled]")){
	  		emptyFormdata([],'form#verify_form');
	  		$('#verify_btn').off();
	  		$('#verify_btn').on('click',function(){
				if($("form#verify_form").valid()) {
	  				verifyuser_permission();
				}
	  		});
	  		$('#password_mdl').modal('show');
	  		$('body,#password_mdl').addClass('scrolling');
	  		$('#verify_error').hide();
  		// }
  	});

  	$("#jqGridAddNotesDialysis").jqGrid({
		datatype: "local",
		editurl: "./dialysis/form",
		colModel: [
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'arrivalno', name: 'arrivalno', hidden: true },
			{ label: 'Note', name: 'additionalnote', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: {style: "width: -webkit-fill-available;" ,rows: 2}},
			{ label: 'Entered by', name: 'adduser', width: 50, hidden:false},
			{ label: 'Date', name: 'adddate', width: 50, hidden:false},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		scroll: true,
		width: 900,
		height: 20,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesDialysis",
		onSelectRow:function(rowid, selected){
			calc_jq_height_onchange("jqGridAddNotesDialysis");
		},
		loadComplete: function(){
			$('#jqGrid2').jqGrid ('setSelection', "1");
			if($('#arrivalno').val().trim() == ''){
				hide_jqGridAddNotesDialysis_button(true);
			}else{
				hide_jqGridAddNotesDialysis_button(false)
			}
			
			calc_jq_height_onchange("jqGridAddNotesDialysis");
		},
	});

	hide_jqGridAddNotesDialysis_button(true);
	function hide_jqGridAddNotesDialysis_button(hide=true){
		if(hide){
			$('#jqGridAddNotesDialysis_iladd,#jqGridAddNotesDialysis_iledit,#jqGridAddNotesDialysis_ilsave,#jqGridAddNotesDialysis_ilcancel,#jqGridPagerRefresh_addnotesDialysis').hide();
		}else{
			$('#jqGridAddNotesDialysis_iladd,#jqGridAddNotesDialysis_iledit,#jqGridAddNotesDialysis_ilsave,#jqGridAddNotesDialysis_ilcancel,#jqGridPagerRefresh_addnotesDialysis').show();
		}
	}

	//////////////////////////////////////////myEditOptions_add////////////////////////////////////////////////
	var myEditOptions_add_AddNotesDialysis = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerRefresh_addnotesDialysis").hide();
		},
		aftersavefunc: function (rowid, response, options) {
			refreshGrid('#jqGridAddNotesDialysis',urlParam_AddNotesDialysis,'scroll');
			$("#jqGridPagerRefresh_addnotesDialysis").show();
		},
		errorfunc: function(rowid,response){
			refreshGrid('#jqGridAddNotesDialysis',urlParam_AddNotesDialysis,'scroll');
		},
		beforeSaveRow: function (options, rowid) {
			let data = $('#jqGridAddNotesDialysis').jqGrid ('getRowData', rowid);

			let editurl = "./dialysis/form?"+
				$.param({
					action:'additionalnote',
					mrn:$("#mrn").val(),
					episno:$("#episno").val(),
					arrivalno:$('#arrivalno').val(),
					action: 'additionalnote_save',
					_token: $("#_token").val()
				});
			$("#jqGridAddNotesDialysis").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete_addnotesDialysis,#jqGridPagerRefresh_addnotesDialysis").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerAddNotesDialysis////////////////////////////////////////////////
	$("#jqGridAddNotesDialysis").inlineNav('#jqGridPagerAddNotesDialysis', {
		add: true,
		edit: false,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_AddNotesDialysis
		},
	})
	.jqGrid('navButtonAdd', "#jqGridPagerAddNotesDialysis", {
		id: "jqGridPagerRefresh_addnotesDialysis",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridAddNotesDialysis", urlParam_AddNotesDialysis);
		},
	});

});
var urlParam_AddNotesDialysis = {
	action: 'get_table_addnotes',
	url: './dialysis/table'
}

function populate_data(type,data){
	if(type == 'monthly'){
		data.forEach(function(e,i){
			$('table#dia_monthly tr#visit_date_m').children('td').eq(i).text(e.visit_date);
			$('table#dia_monthly tr#start_time_m').children('td').eq(i+1).text(e.start_time);
			$('table#dia_monthly tr#dialyser_m').children('td').eq(i+1).text(e.dialyser);
			$('table#dia_monthly tr#no_of_use_m').children('td').eq(i+1).text(e.no_of_use);
			$('table#dia_monthly tr#target_uf_m').children('td').eq(i+1).text(e.target_uf);
			$('table#dia_monthly tr#heparin_bolus_m').children('td').eq(i+1).text(e.heparin_bolus);
			$('table#dia_monthly tr#heparin_maintainance_m').children('td').eq(i+1).text(e.heparin_maintainance);
			$('table#dia_monthly tr#dialysate_ca_m').children('td').eq(i+1).text(e.dialysate_ca);
			$('table#dia_monthly tr#pre_weight_m').children('td').eq(i+1).text(e.pre_weight);
			$('table#dia_monthly tr#idwg_m').children('td').eq(i+1).text(e.idwg);
			$('table#dia_monthly tr#prehd_tmp_m').children('td').eq(i+1).text( e.prehd_temperature+' / '+e.prehd_pulse+' / '+e.prehd_respiratory);
			$('table#dia_monthly tr#prehd_bp_m').children('td').eq(i+1).text(e.prehd_systolic+' / '+e.prehd_diastolic);
			$('table#dia_monthly tr#pulse_pre_m').children('td').eq(i+1).text(e.prehd_pulse);
			$('table#dia_monthly tr#prehd_bfr_m').children('td').eq(i+1).text(e['0_bfr']);
			$('table#dia_monthly tr#prehd_dfr_m').children('td').eq(i+1).text(e.prehd_dfr);
			$('table#dia_monthly tr#prehd_vp_m').children('td').eq(i+1).text(e['0_vp']);
			$('table#dia_monthly tr#user_prehd_m').children('td').eq(i+1).text(e['user_prehd']);

			$('table#dia_monthly tr#1_tc_m').children('td').eq(i+1).text(e['1_tc']);
			$('table#dia_monthly tr#1_bp_m').children('td').eq(i+1).text(e['1_bp']);
			$('table#dia_monthly tr#1_pulse_m').children('td').eq(i+1).text(e['1_pulse']);
			$('table#dia_monthly tr#1_dh_m').children('td').eq(i+1).text(e['1_dh']);
			$('table#dia_monthly tr#1_bfr_m').children('td').eq(i+1).text(e['1_bfr']);
			$('table#dia_monthly tr#1_vp_m').children('td').eq(i+1).text(e['1_vp']);
			$('table#dia_monthly tr#1_tmp_m').children('td').eq(i+1).text(e['1_tmp']);
			$('table#dia_monthly tr#1_uv_m').children('td').eq(i+1).text(e['1_uv']);
			$('table#dia_monthly tr#1_f_m').children('td').eq(i+1).text(e['1_f']);
			$('table#dia_monthly tr#user_1_m').children('td').eq(i+1).text(e['user_1']);

			$('table#dia_monthly tr#2_tc_m').children('td').eq(i+1).text(e['2_tc']);
			$('table#dia_monthly tr#2_bp_m').children('td').eq(i+1).text(e['2_bp']);
			$('table#dia_monthly tr#2_pulse_m').children('td').eq(i+1).text(e['2_pulse']);
			$('table#dia_monthly tr#2_dh_m').children('td').eq(i+1).text(e['2_dh']);
			$('table#dia_monthly tr#2_bfr_m').children('td').eq(i+1).text(e['2_bfr']);
			$('table#dia_monthly tr#2_vp_m').children('td').eq(i+1).text(e['2_vp']);
			$('table#dia_monthly tr#2_tmp_m').children('td').eq(i+1).text(e['2_tmp']);
			$('table#dia_monthly tr#2_uv_m').children('td').eq(i+1).text(e['2_uv']);
			$('table#dia_monthly tr#2_f_m').children('td').eq(i+1).text(e['2_f']);	
			$('table#dia_monthly tr#user_2_m').children('td').eq(i+1).text(e['user_2']);		

			$('table#dia_monthly tr#3_tc_m').children('td').eq(i+1).text(e['3_tc']);
			$('table#dia_monthly tr#3_bp_m').children('td').eq(i+1).text(e['3_bp']);
			$('table#dia_monthly tr#3_pulse_m').children('td').eq(i+1).text(e['3_pulse']);
			$('table#dia_monthly tr#3_dh_m').children('td').eq(i+1).text(e['3_dh']);
			$('table#dia_monthly tr#3_bfr_m').children('td').eq(i+1).text(e['3_bfr']);
			$('table#dia_monthly tr#3_vp_m').children('td').eq(i+1).text(e['3_vp']);
			$('table#dia_monthly tr#3_tmp_m').children('td').eq(i+1).text(e['3_tmp']);
			$('table#dia_monthly tr#3_uv_m').children('td').eq(i+1).text(e['3_uv']);
			$('table#dia_monthly tr#3_f_m').children('td').eq(i+1).text(e['3_f']);		
			$('table#dia_monthly tr#user_3_m').children('td').eq(i+1).text(e['user_3']);	

			$('table#dia_monthly tr#4_tc_m').children('td').eq(i+1).text(e['4_tc']);
			$('table#dia_monthly tr#4_bp_m').children('td').eq(i+1).text(e['4_bp']);
			$('table#dia_monthly tr#4_pulse_m').children('td').eq(i+1).text(e['4_pulse']);
			$('table#dia_monthly tr#4_dh_m').children('td').eq(i+1).text(e['4_dh']);
			$('table#dia_monthly tr#4_bfr_m').children('td').eq(i+1).text(e['4_bfr']);
			$('table#dia_monthly tr#4_vp_m').children('td').eq(i+1).text(e['4_vp']);
			$('table#dia_monthly tr#4_tmp_m').children('td').eq(i+1).text(e['4_tmp']);
			$('table#dia_monthly tr#4_uv_m').children('td').eq(i+1).text(e['4_uv']);
			$('table#dia_monthly tr#4_f_m').children('td').eq(i+1).text(e['4_f']);		
			$('table#dia_monthly tr#user_4_m').children('td').eq(i+1).text(e['user_4']);	

			$('table#dia_monthly tr#5_tc_m').children('td').eq(i+1).text(e['5_tc']);
			$('table#dia_monthly tr#5_bp_m').children('td').eq(i+1).text(e['5_bp']);
			$('table#dia_monthly tr#5_pulse_m').children('td').eq(i+1).text(e['5_pulse']);
			$('table#dia_monthly tr#5_dh_m').children('td').eq(i+1).text(e['5_dh']);
			$('table#dia_monthly tr#5_bfr_m').children('td').eq(i+1).text(e['5_bfr']);
			$('table#dia_monthly tr#5_vp_m').children('td').eq(i+1).text(e['5_vp']);
			$('table#dia_monthly tr#5_tmp_m').children('td').eq(i+1).text(e['5_tmp']);
			$('table#dia_monthly tr#5_uv_m').children('td').eq(i+1).text(e['5_uv']);
			$('table#dia_monthly tr#5_f_m').children('td').eq(i+1).text(e['5_f']);
			$('table#dia_monthly tr#user_5_m').children('td').eq(i+1).text(e['user_5']);

			$('table#dia_monthly tr#posthd_bp_m').children('td').eq(i+1).text(e.posthd_bp);
			$('table#dia_monthly tr#posthd_temperatue_m').children('td').eq(i+1).text(e.posthd_temperatue);
			$('table#dia_monthly tr#posthd_pulse_m').children('td').eq(i+1).text(e.posthd_pulse);
			$('table#dia_monthly tr#posthd_respiratory_m').children('td').eq(i+1).text(e.posthd_respiratory);
			$('table#dia_monthly tr#post_weight_m').children('td').eq(i+1).text(e.post_weight);
			$('table#dia_monthly tr#weight_loss_m').children('td').eq(i+1).text(e.weight_loss);
			$('table#dia_monthly tr#time_complete_m').children('td').eq(i+1).text(e.time_complete);
			$('table#dia_monthly tr#hd_adequancy_m').children('td').eq(i+1).text(e.hd_adequancy);
			$('table#dia_monthly tr#ktv_m').children('td').eq(i+1).text(e.ktv);
			$('table#dia_monthly tr#urr_m').children('td').eq(i+1).text(e.urr);
			$('table#dia_monthly tr#user_posthd_m').children('td').eq(i+1).text(e['user_posthd']);
			// $('table#dia_monthly tr#medication_m').children('td').eq(i+1).text(e.medication);
			if(e.table_patmedication != undefined || e.table_patmedication != null){
				let patmed = `<ol>`;
				e.table_patmedication.forEach(function(e,i){
					patmed = patmed +  `<li>`+e.chg_desc+` - `+e.enteredby+`</li>`;
				});
				patmed = patmed + `</ol>`
				$('table#dia_monthly tr#medication_m').children('td').eq(i+1).html(patmed);
			}else{
				$('table#dia_monthly tr#medication_m').children('td').eq(i+1).html('');
			}
		});

	}else if(type == 'weekly'){
		data.forEach(function(e,i){
			$('table#dia_weekly tr#visit_date_w').children('td').eq(i).text(e.visit_date);
			$('table#dia_weekly tr#start_time_w').children('td').eq(i+1).text(e.start_time);
			$('table#dia_weekly tr#dialyser_w').children('td').eq(i+1).text(e.dialyser);
			$('table#dia_weekly tr#no_of_use_w').children('td').eq(i+1).text(e.no_of_use);
			$('table#dia_weekly tr#target_uf_w').children('td').eq(i+1).text(e.target_uf);
			$('table#dia_weekly tr#heparin_bolus_w').children('td').eq(i+1).text(e.heparin_bolus);
			$('table#dia_weekly tr#heparin_maintainance_w').children('td').eq(i+1).text(e.heparin_maintainance);
			$('table#dia_weekly tr#dialysate_ca_w').children('td').eq(i+1).text(e.dialysate_ca);
			$('table#dia_weekly tr#pre_weight_w').children('td').eq(i+1).text(e.pre_weight);
			$('table#dia_weekly tr#idwg_w').children('td').eq(i+1).text(e.idwg);
			$('table#dia_weekly tr#prehd_tmp_w').children('td').eq(i+1).text( e.prehd_temperature+' / '+e.prehd_pulse+' / '+e.prehd_respiratory);
			$('table#dia_weekly tr#prehd_bp_w').children('td').eq(i+1).text(e.prehd_systolic+' / '+e.prehd_diastolic);
			$('table#dia_weekly tr#pulse_pre_w').children('td').eq(i+1).text(e.prehd_pulse);
			$('table#dia_weekly tr#prehd_bfr_w').children('td').eq(i+1).text(e['0_bfr']);
			$('table#dia_weekly tr#prehd_dfr_w').children('td').eq(i+1).text(e.prehd_dfr);
			$('table#dia_weekly tr#prehd_vp_w').children('td').eq(i+1).text(e['0_vp']);
			$('table#dia_weekly tr#user_prehd_w').children('td').eq(i+1).text(e['user_prehd']);

			$('table#dia_weekly tr#1_tc_w').children('td').eq(i+1).text(e['1_tc']);
			$('table#dia_weekly tr#1_bp_w').children('td').eq(i+1).text(e['1_bp']);
			$('table#dia_weekly tr#1_pulse_w').children('td').eq(i+1).text(e['1_pulse']);
			$('table#dia_weekly tr#1_dh_w').children('td').eq(i+1).text(e['1_dh']);
			$('table#dia_weekly tr#1_bfr_w').children('td').eq(i+1).text(e['1_bfr']);
			$('table#dia_weekly tr#1_vp_w').children('td').eq(i+1).text(e['1_vp']);
			$('table#dia_weekly tr#1_tmp_w').children('td').eq(i+1).text(e['1_tmp']);
			$('table#dia_weekly tr#1_uv_w').children('td').eq(i+1).text(e['1_uv']);
			$('table#dia_weekly tr#1_f_w').children('td').eq(i+1).text(e['1_f']);
			$('table#dia_weekly tr#user_1_w').children('td').eq(i+1).text(e['user_1']);

			$('table#dia_weekly tr#2_tc_w').children('td').eq(i+1).text(e['2_tc']);
			$('table#dia_weekly tr#2_bp_w').children('td').eq(i+1).text(e['2_bp']);
			$('table#dia_weekly tr#2_pulse_w').children('td').eq(i+1).text(e['2_pulse']);
			$('table#dia_weekly tr#2_dh_w').children('td').eq(i+1).text(e['2_dh']);
			$('table#dia_weekly tr#2_bfr_w').children('td').eq(i+1).text(e['2_bfr']);
			$('table#dia_weekly tr#2_vp_w').children('td').eq(i+1).text(e['2_vp']);
			$('table#dia_weekly tr#2_tmp_w').children('td').eq(i+1).text(e['2_tmp']);
			$('table#dia_weekly tr#2_uv_w').children('td').eq(i+1).text(e['2_uv']);
			$('table#dia_weekly tr#2_f_w').children('td').eq(i+1).text(e['2_f']);
			$('table#dia_weekly tr#user_2_w').children('td').eq(i+1).text(e['user_2']);

			$('table#dia_weekly tr#3_tc_w').children('td').eq(i+1).text(e['3_tc']);
			$('table#dia_weekly tr#3_bp_w').children('td').eq(i+1).text(e['3_bp']);
			$('table#dia_weekly tr#3_pulse_w').children('td').eq(i+1).text(e['3_pulse']);
			$('table#dia_weekly tr#3_dh_w').children('td').eq(i+1).text(e['3_dh']);
			$('table#dia_weekly tr#3_bfr_w').children('td').eq(i+1).text(e['3_bfr']);
			$('table#dia_weekly tr#3_vp_w').children('td').eq(i+1).text(e['3_vp']);
			$('table#dia_weekly tr#3_tmp_w').children('td').eq(i+1).text(e['3_tmp']);
			$('table#dia_weekly tr#3_uv_w').children('td').eq(i+1).text(e['3_uv']);
			$('table#dia_weekly tr#3_f_w').children('td').eq(i+1).text(e['3_f']);
			$('table#dia_weekly tr#user_3_w').children('td').eq(i+1).text(e['user_3']);

			$('table#dia_weekly tr#4_tc_w').children('td').eq(i+1).text(e['4_tc']);
			$('table#dia_weekly tr#4_bp_w').children('td').eq(i+1).text(e['4_bp']);
			$('table#dia_weekly tr#4_pulse_w').children('td').eq(i+1).text(e['4_pulse']);
			$('table#dia_weekly tr#4_dh_w').children('td').eq(i+1).text(e['4_dh']);
			$('table#dia_weekly tr#4_bfr_w').children('td').eq(i+1).text(e['4_bfr']);
			$('table#dia_weekly tr#4_vp_w').children('td').eq(i+1).text(e['4_vp']);
			$('table#dia_weekly tr#4_tmp_w').children('td').eq(i+1).text(e['4_tmp']);
			$('table#dia_weekly tr#4_uv_w').children('td').eq(i+1).text(e['4_uv']);
			$('table#dia_weekly tr#4_f_w').children('td').eq(i+1).text(e['4_f']);
			$('table#dia_weekly tr#user_4_w').children('td').eq(i+1).text(e['user_4']);

			$('table#dia_weekly tr#5_tc_w').children('td').eq(i+1).text(e['5_tc']);
			$('table#dia_weekly tr#5_bp_w').children('td').eq(i+1).text(e['5_bp']);
			$('table#dia_weekly tr#5_pulse_w').children('td').eq(i+1).text(e['5_pulse']);
			$('table#dia_weekly tr#5_dh_w').children('td').eq(i+1).text(e['5_dh']);
			$('table#dia_weekly tr#5_bfr_w').children('td').eq(i+1).text(e['5_bfr']);
			$('table#dia_weekly tr#5_vp_w').children('td').eq(i+1).text(e['5_vp']);
			$('table#dia_weekly tr#5_tmp_w').children('td').eq(i+1).text(e['5_tmp']);
			$('table#dia_weekly tr#5_uv_w').children('td').eq(i+1).text(e['5_uv']);
			$('table#dia_weekly tr#5_f_w').children('td').eq(i+1).text(e['5_f']);
			$('table#dia_weekly tr#user_5_w').children('td').eq(i+1).text(e['user_5']);

			$('table#dia_weekly tr#posthd_bp_w').children('td').eq(i+1).text(e.posthd_systolic +' / '+ e.posthd_diastolic);
			$('table#dia_weekly tr#posthd_temperatue_w').children('td').eq(i+1).text(e.posthd_temperatue);
			$('table#dia_weekly tr#posthd_pulse_w').children('td').eq(i+1).text(e.posthd_pulse);
			$('table#dia_weekly tr#posthd_respiratory_w').children('td').eq(i+1).text(e.posthd_respiratory);
			$('table#dia_weekly tr#post_weight_w').children('td').eq(i+1).text(e.post_weight);
			$('table#dia_weekly tr#weight_loss_w').children('td').eq(i+1).text(e.weight_loss);
			$('table#dia_weekly tr#time_complete_w').children('td').eq(i+1).text(e.time_complete);
			$('table#dia_weekly tr#hd_adequancy_w').children('td').eq(i+1).text(e.hd_adequancy);
			$('table#dia_weekly tr#ktv_w').children('td').eq(i+1).text(e.ktv);
			$('table#dia_weekly tr#urr_w').children('td').eq(i+1).text(e.urr);
			$('table#dia_weekly tr#user_posthd_w').children('td').eq(i+1).text(e['user_posthd']);
			if(e.table_patmedication != undefined || e.table_patmedication != null){
				let patmed = `<ol>`;
				e.table_patmedication.forEach(function(e,i){
					patmed = patmed +  `<li>`+e.chg_desc+` - `+e.enteredby+`</li>`;
				});
				patmed = patmed + `</ol>`
				$('table#dia_weekly tr#medication_w').children('td').eq(i+1).html(patmed);
			}else{
				$('table#dia_weekly tr#medication_w').children('td').eq(i+1).html('');
			}
		});
	}
}

function populate_other_data(data=last_other_data){
	if(data != null){
		$('#duration_of_hd').val(data.duration_hd);
		$('#dry_weight').val(data.dry_weight);
		$('#prev_post_weight').val(data.prev_post_weight);
		$('#initiated_by').val(data.initiated_by);
		if(data.last_visit != ''){
			$('#last_visit').val(data.last_visit);
		}
	}
}

function cleartabledata(type){
	if(type == 'monthly'){
		$('table#dia_monthly td[align=center],table#dia_monthly td.med_td').html('&nbsp;');
	}else if(type == 'weekly'){
		$('table#dia_weekly td[align=center],table#dia_weekly td.med_td').html('&nbsp;');
	}else if(type == 'all'){
		$('table#dia_monthly td[align=center],table#dia_monthly td.med_td,table#dia_weekly td[align=center],table#dia_weekly td.med_td').html('&nbsp;');
	}
}

function populatedialysis(data){
	last_other_data=null;
	last_dialysis_data=null;
    emptied_dialysisb4();
	emptyFormdata([],'form#daily_form');
	emptyFormdata([],'form#daily_form_completed');
	$('form#daily_form .ui.dropdown,form#daily_form_completed .ui.dropdown').dropdown('restore defaults');
	$('#visit_date').val($('#sel_date').val());
	disableForm('form#daily_form');
	$('span.metal').text(data.Name+' - MRN:'+data.MRN);
	$('#mrn').val(data.MRN);
	$('#episno').val(data.Episno);
}

function empty_dialysis(){
	$('#edit_dia,#addnew_dia').prop('disabled',true);
	disableForm('form#daily_form');
	$('#seldate').val('');
	$('span.metal').text('');
	$('#mrn').val('');
	$('#episno').val('');
	emptyFormdata([],'form#daily_form');
}

function button_state_dialysis(state){
	switch(state){
		case 'empty':
			$('#cancel_dialysis').data('oper','add');
			$('#new_dialysis,#save_dialysis,#cancel_dialysis,#edit_dialysis').attr('disabled',true);
			break;
		case 'add':
			$('select#dialysisbefore').parent().removeClass('disabled');
			$('#cancel_dialysis').data('oper','add');
			$("#new_dialysis,#current,#past").attr('disabled',false);
			$('#save_dialysis,#cancel_dialysis,#edit_dialysis').attr('disabled',true);
			break;
		case 'edit':
			$('select#dialysisbefore').parent().removeClass('disabled');
			$('#cancel_dialysis').data('oper','edit');
			$("#edit_dialysis").attr('disabled',false);
			$('#save_dialysis,#cancel_dialysis,#new_dialysis').attr('disabled',true);
			break;
		case 'wait':
			$('select#dialysisbefore').parent().addClass('disabled');
			$("#save_dialysis,#cancel_dialysis").attr('disabled',false);
			$('#edit_dialysis,#new_dialysis').attr('disabled',true);
			break;
		case 'disableAll':
			$('#new_dialysis,#edit_dialysis,#save_dialysis,#cancel_dialysis').attr('disabled',true);
			break;
	}

}

var last_dialysis_data=null;
var last_other_data=null;
var last_mode=null;
function check_pt_mode(){

	if($("#mrn").val().trim()=='' || $("#episno").val().trim()=='' || $("#dialysis_episode_idno").val().trim()==''){
		return false;
	}

	var param={
        action:'check_pt_mode',
		mrn:$("#mrn").val(),
		episno:$("#episno").val(),
        dialysis_episode_idno:$('#dialysis_episode_idno').val()
    };

	$.ajaxSetup({async: false});
    $.get( "./check_pt_mode?"+$.param(param), function( data ) {

    },'json').done(function(data) {

    	if(data.datab4 != undefined || data.datab4 != null){
			dropdown_dialysisb4(data.datab4);
    	}

        if(data.mode == 'edit'){
			button_state_dialysis('edit');
			autoinsert_rowdata_dialysis('form#daily_form',data.data);
			autoinsert_rowdata_dialysis('form#daily_form_completed',data.data);
			last_dialysis_data = data.data;
			last_mode = 'edit';
        }else if(data.mode == 'add'){
			$('div.ui.tiny.userlabel').hide();
			button_state_dialysis('add');
			last_other_data = data.other_data;
			populate_other_data(data.other_data);
			last_mode = 'add';
        }else if(data.mode == 'disableAll'){
			$('div.ui.tiny.userlabel').hide();
			button_state_dialysis('disableAll');
			last_other_data = data.other_data;
			populate_other_data(data.other_data);
			last_mode = 'disableAll';
        }
    }).fail(function(data){
        alert('error in checking this patient mode..');
    });
}

function add_edit_mode(mode){

	$('form#daily_form div.prehddiv input[type=text],form#daily_form div.prehddiv input[type=date],form#daily_form div.prehddiv textarea').change(function(){
		var user_prehd = $('#user_prehd').val().trim();
		if(user_prehd == ''){
			 $('#user_prehd').val($('#user_name').val());
			 $('#span_user_prehd').text($('#user_name').val()).parent().show();
		}
	});

	$('table#preHDListMeasure tr.0_tr input[type=text],table#preHDListMeasure tr.0_tr input[type=date],table#preHDListMeasure tr.0_tr textarea').change(function(){
		var user_0 = $('#user_0').val().trim();
		if(user_0 == ''){
			 $('#user_0').val($('#user_name').val());
			 $('#span_user_0').text($('#user_name').val()).parent().show();
		}
	});

	$('table#preHDListMeasure tr.1_tr input[type=text],table#preHDListMeasure tr.1_tr input[type=date],table#preHDListMeasure tr.1_tr textarea').change(function(){
		var user_1 = $('#user_1').val().trim();
		if(user_1 == ''){
			 $('#user_1').val($('#user_name').val());
			 $('#span_user_1').text($('#user_name').val()).parent().show();
		}
	});

	$('table#preHDListMeasure tr.2_tr input[type=text],table#preHDListMeasure tr.2_tr input[type=date],table#preHDListMeasure tr.2_tr textarea').change(function(){
		var user_2 = $('#user_2').val().trim();
		if(user_2 == ''){
			 $('#user_2').val($('#user_name').val());
			 $('#span_user_2').text($('#user_name').val()).parent().show();
		}
	});

	$('table#preHDListMeasure tr.3_tr input[type=text],table#preHDListMeasure tr.3_tr input[type=date],table#preHDListMeasure tr.3_tr textarea').change(function(){
		var user_3 = $('#user_3').val().trim();
		if(user_3 == ''){
			 $('#user_3').val($('#user_name').val());
			 $('#span_user_3').text($('#user_name').val()).parent().show();
		}
	});

	$('table#preHDListMeasure tr.4_tr input[type=text],table#preHDListMeasure tr.4_tr input[type=date],table#preHDListMeasure tr.4_tr textarea').change(function(){
		var user_4 = $('#user_4').val().trim();
		if(user_4 == ''){
			 $('#user_4').val($('#user_name').val());
			 $('#span_user_4').text($('#user_name').val()).parent().show();
		}
	});

	$('table#preHDListMeasure tr.5_tr input[type=text],table#preHDListMeasure tr.5_tr input[type=date],table#preHDListMeasure tr.5_tr textarea').change(function(){
		var user_5 = $('#user_5').val().trim();
		if(user_5 == ''){
			 $('#user_5').val($('#user_name').val());
			 $('#span_user_5').text($('#user_name').val()).parent().show();
		}
	});

	$('form#daily_form_completed input[type=text],form#daily_form_completed input[type=date]').change(function(){
		var user_posthd = $('#user_posthd').val().trim();
		if(user_posthd == ''){
			 $('#user_posthd').val($('#user_name').val());
			 $('#span_user_posthd').text($('#user_name').val()).parent().show();
		}
	});

	//part dialyser
	$('#no_of_use').parent().addClass('disabled');
	$('#dialyser').on('change',function(){
		if($(this).val() == 'REUSE'){
			$('#no_of_use').attr('required','').parent().removeClass('disabled');

		}else{
			$('#no_of_use').val('');
			$('#no_of_use').dropdown('set text', '');
			$('#no_of_use').removeAttr('required').parent().addClass('disabled').removeClass('error');
		}
	});
	if(mode == 'edit'){
		$('#dialyser').change();
	}

	//part pre weight 
	$('#pre_weight').on('blur',function(){
		let prev_post_weight = $('#prev_post_weight').val();
		let pre_weight = $('#pre_weight').val();
		let dry_weight = $('#dry_weight').val();
		let post_weight = $('#post_weight').val();

		if(pre_weight.trim() != '' && prev_post_weight.trim() != ''){
			let idwg = parseFloat(pre_weight) - parseFloat(prev_post_weight);
			$('#idwg').val(idwg.toFixed(2));
		}

		if(pre_weight.trim() != '' && dry_weight.trim() != '' ){
			let target_weight = parseFloat(pre_weight) - parseFloat(dry_weight);
			$('#target_weight').val(target_weight.toFixed(2));
		}

		if(pre_weight.trim() != '' && post_weight.trim() != ''){
			let weight_loss = parseFloat(pre_weight) - parseFloat(post_weight);
			$('#weight_loss').val(weight_loss.toFixed(2));
		}
	});

	$('#post_weight').on('blur',function(){
		let pre_weight = $('#pre_weight').val();
		let post_weight = $('#post_weight').val();

		if(pre_weight.trim() != '' && post_weight.trim() != ''){
			let weight_loss = parseFloat(pre_weight) - parseFloat(post_weight);
			$('#weight_loss').val(weight_loss.toFixed(2));
		}

	});

	//part access
	$('#access_placeholder').on('focus',function(){
		$('#type').dropdown('show');
	});

	// type
	$('#bruit').parent().addClass('disabled');
	$('#type').on('change',type_procedure);
	$('#type').on('change',type_change);

	function type_procedure(){
		switch($('#type').val().trim()){
			case 'ARTERIOVENOUS FISTULA':
			case 'BRACHIOCEPHALIC FISTULA':
			case 'BRACHIOBASILIC FISTULA':
			case 'ARTERIOVENOUS GRAFT':
				$('#bruit').attr('required','').parent().removeClass('disabled');
				break;
			default:
				$('#bruit').removeAttr('required').parent().addClass('disabled');
				break;

		}
	}

	function type_change(){
		write_access();
		$('#site').dropdown('show');
	}

	if(mode == 'edit'){
		type_procedure();
		write_access();
	}

	// type
	$('#site').on('change',function(){
		write_access();
		$('#access').dropdown('show');
	});
	//access
	$('#access').on('change',function(){
		write_access();
	});

	function write_access(){
		let access_placeholder = '';
		if($('#site').val().trim() == '' || $('#site').val().trim() == 'NA'){
			access_placeholder = '';
		}else{
			access_placeholder = $('#site').val().trim()+ ' ';
		}
		if($('#access').val().trim() == '' || $('#access').val().trim() == 'NA'){
			access_placeholder = access_placeholder;
		}else{
			access_placeholder = access_placeholder + $('#access').val().trim() + ' ';
		}
		if($('#type').val().trim() != ''){
			access_placeholder = access_placeholder + $('#type').val().trim();
		}
		$('#access_placeholder').val(access_placeholder);
	}

	//part heparin
	$('#heparin_bolus,#heparin_maintainance,#1_dh,#2_dh,#3_dh,#4_dh,#5_dh').prop('disabled',true);
	$('#heparin_type').on('change',function(){
		if($(this).val() == 'FREE'){
			$('#heparin_bolus,#heparin_maintainance,#1_dh,#2_dh,#3_dh,#4_dh,#5_dh').val('');
			$('#heparin_bolus,#heparin_maintainance,#1_dh,#2_dh,#3_dh,#4_dh,#5_dh').prop('disabled',true);

		}else{
			$('#heparin_bolus,#heparin_maintainance,#1_dh,#2_dh,#3_dh,#4_dh,#5_dh').prop('disabled',false);
		}
	});
	if(mode == 'edit'){
		$('#heparin_type').change();
	}

	//part delivery duration
	$('#time_complete,#0_tc').on('blur',function(){
		if($('#0_tc').val().trim() != ''){
			$('#delivered_duration_errortext').text('');
			if($('#time_complete').val().trim() != ''){

				var startTime = moment($('#0_tc').val().trim(), 'hh:mm:ss a');
				var endTime = moment($('#time_complete').val().trim(), 'hh:mm:ss a');
				if(endTime.diff(startTime) > 0){
					var duration_hrs = moment.utc(endTime.diff(startTime)).format("H");
					var duration_min = moment.utc(endTime.diff(startTime)).format("m");
					$('#delivered_duration').val([duration_hrs, duration_min].join(' hour and ')+' minute');
				}else{
					$('#delivered_duration').val();
				}
			}
		}else{
			$('#delivered_duration_errortext').text('Time commencing is empty, cant calculate duration of hd');
		}
	});

	//part terminate by
	if($('#terminate_by').val().trim() == ''){
		$('#terminate_by').val($('#user_name').val());
	}

	canonlyeditself();
}

function off_edit_mode(){
	$('#dialyser,#heparin_type,#type').off('change');
	$('#pre_weight,#time_complete,#post_weight,#0_tc').off('blur');
}

function autoinsert_rowdata_dialysis(form,rowData){
	$('div.ui.tiny.userlabel').hide();
	$.each(rowData, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else if(input.is("[type=checkbox]")){
			if(value==1){
				$(form+" [name='"+index+"']").prop('checked', true);
			}
		}else if(input.is("textarea")){
			if(value !== null){
				let newval = value.replaceAll("</br>",'\n');
				input.val(newval);
			}
		}else if(input.is("select")){
			if(value !== null){
				input.dropdown('set selected', value);
			}
		}else{
			input.val(value);
		}
		if(value!=null && value!=undefined && value!=''){
			span_user_label_init(index,value);
		}
	});
}

function emptied_dialysisb4(){
	$('select#dialysisbefore').dropdown('setup menu', {values: [
		{value:0,text:'No dialysis Record',name:'No dialysis Record'}
	]});
}

function dropdown_dialysisb4(datab4){
	let arrayb4 =  [{
			value:0,text:'Today',name:'Today'
		}];

	datab4.forEach(function(e,i){
		arrayb4.push({
			value:e.idno,text:e.visit_date,name:e.visit_date
		});
	});

	$('select#dialysisbefore').dropdown('setup menu', {values: arrayb4});

	$('select#dialysisbefore').dropdown('setting', 'onChange', function(value, text, $selectedItem){
		if(parseInt(value)>0){
			button_state_dialysis('disableAll');
			get_dialysis_daily(value);
			$("#edit_permission").show();
		}else{
			$("#edit_permission").hide();
			if(last_mode == 'edit'){
				button_state_dialysis('edit');
				autoinsert_rowdata_dialysis('form#daily_form',last_dialysis_data);
				autoinsert_rowdata_dialysis('form#daily_form_completed',last_dialysis_data);
				$('#visit_date').val(last_dialysis_data.visit_date);
			}else if(last_mode == 'add'){
				check_pt_mode();
				emptyFormdata([],'form#daily_form');
				emptyFormdata([],'form#daily_form_completed');
				$('form#daily_form .ui.dropdown,form#daily_form_completed .ui.dropdown').dropdown('restore defaults');
				$('#visit_date').val($('#sel_date').val());
				populate_other_data();
			}else{
				check_pt_mode();
				emptyFormdata([],'form#daily_form');
				emptyFormdata([],'form#daily_form_completed');
				$('form#daily_form .ui.dropdown,form#daily_form_completed .ui.dropdown').dropdown('restore defaults');
				$('#visit_date').val($('#sel_date').val());
				populate_other_data();
			}
		}
	});
}

function get_dialysis_daily(idno){
	loader_daily(true);
	var param={
        idno:idno,
		action:'get_dia_daily'
    };

    $.get( "./get_data_dialysis?"+$.param(param), function( data ) {

    },'json').done(function(data) {
		loader_daily(false);
		autoinsert_rowdata_dialysis('form#daily_form',data.data);
		autoinsert_rowdata_dialysis('form#daily_form_completed',data.data);
		$('#visit_date').val(data.data.visit_date);
		load_patmedication(data.data.mrn,data.data.episno,data.data.visit_date);
    }).fail(function(data){
		loader_daily(false);
        alert('error in get data');
    });
}

function verifyuser(){
	var param={
		action:'verifyuser',
		username:$('#username_verify').val(),
		password:$('#password_verify').val(),
    };

    $.get( "./verifyuser_dialysis?"+$.param(param), function( data ) {

    },'json').done(function(data) {
    	if(data.success == 'fail'){
  			$('#verify_error').show();
    	}else{
    		$('#verified_by').val($('#username_verify').val());
  			$('#verify_error').hide();
  			$('#password_mdl').modal('hide');
    	}
    }).fail(function(data){
        alert('error verify');
    });
}

function verifyuser_permission(){
	var param={
		action:'verifyuser_admin_dialysis',
		username:$('#username_verify').val(),
		password:$('#password_verify').val(),
    };

    $.get( "./verifyuser_admin_dialysis?"+$.param(param), function( data ) {

    },'json').done(function(data) {
    	if(data.success == 'fail'){
  			$('#verify_error').show();
    	}else{
    		button_state_dialysis('edit');
  			$('#verify_error').hide();
  			$('#password_mdl').modal('hide');
    	}
    }).fail(function(data){
        alert('error verify');
    });
}

function loader_daily(load){
	if(load){
		$('#loader_daily').addClass('active');
	}else{
		$('#loader_daily').removeClass('active');
	}
}

function check_valid_prehd(){
	var proceed1,proceed2,proceed3;
	if($('#start_time').val().trim() == ''){
		errorinp('#start_time',true);
		$('#start_time').focus();
		proceed1=false;
	}else{
		errorinp('#start_time',false);
		proceed1=true;
	}
	if($('#machine_no').val().trim() == ''){
		errorinp('#machine_no',true);
		$('#machine_no').focus();
		proceed2=false;
	}else{
		errorinp('#machine_no',false);
		proceed2=true;
	}
	if($('#prime_by').val().trim() == ''){
		errorinp('#prime_by',true);
		$('#prime_by').focus();
		proceed3=false;
	}else{
		errorinp('#prime_by',false);
		proceed3=true;
	}

	if(proceed1 && proceed2 && proceed3){
		return true;
	}else{
		return false;
	}
}

function errorinp(inp,err){
	if(err){
		$(inp).addClass('error');
	}else{
		$(inp).removeClass('error');
	}
}

function span_user_label_init(index,value){
	if(value=='')return false;
	switch(index){
		case 'user_prehd' :
				$('#span_user_prehd').text(value).parent().show();
				break
		case 'user_posthd' : 
				$('#span_user_posthd').text(value).parent().show();
				break
		case 'user_0' : 
				$('#span_user_0').text(value).parent().show();
				break
		case 'user_1' : 
				$('#span_user_1').text(value).parent().show();
				break
		case 'user_2' : 
				$('#span_user_2').text(value).parent().show();
				break
		case 'user_3' : 
				$('#span_user_3').text(value).parent().show();
				break
		case 'user_4' : 
				$('#span_user_4').text(value).parent().show();
				break
		case 'user_5' : 
				$('#span_user_5').text(value).parent().show();
				break
		default:
			return false;
			break
	}
}

function canonlyeditself(){
	var user_name = $('#user_name').val().trim();
	if($('#user_prehd').val().trim() != '' && $('#user_prehd').val().trim() != user_name){
		disableForm('form#daily_form div.prehddiv');
	}
	if($('#user_posthd').val().trim() != '' && $('#user_posthd').val().trim() != user_name){
		disableForm('form#daily_form_completed');
	}
	if($('#user_0').val().trim() != '' && $('#user_0').val().trim() != user_name){
		disableForm('table#preHDListMeasure tr.0_tr');
	}
	if($('#user_1').val().trim() != '' && $('#user_1').val().trim() != user_name){
		disableForm('table#preHDListMeasure tr.1_tr');
	}
	if($('#user_2').val().trim() != '' && $('#user_2').val().trim() != user_name){
		disableForm('table#preHDListMeasure tr.2_tr');
	}
	if($('#user_3').val().trim() != '' && $('#user_3').val().trim() != user_name){
		disableForm('table#preHDListMeasure tr.3_tr');
	}
	if($('#user_4').val().trim() != '' && $('#user_4').val().trim() != user_name){
		disableForm('table#preHDListMeasure tr.4_tr');
	}
	if($('#user_5').val().trim() != '' && $('#user_5').val().trim() != user_name){
		disableForm('table#preHDListMeasure tr.5_tr');
	}
}