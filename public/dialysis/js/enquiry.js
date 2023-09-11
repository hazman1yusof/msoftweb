$(document).ready(function () {

	$('#month_year_calendar').calendar({
    	initialDate: new Date(),
   		type: 'month',
   		onChange: function(newDate){
			$('#excel_month_m').val(moment(newDate).format('MM'));
			$('#excel_year_m').val(moment(newDate).format('YYYY'));
   		},
 	});

	$("#tab_weekly").on("show.bs.collapse", function(){
		closealltab("#tab_weekly");
	});

	$("#tab_weekly").on("shown.bs.collapse", function(){
		closealltab("#tab_weekly");
		SmoothScrollTo('#tab_weekly', 300,undefined,90);
	});

	$("#tab_monthly").on("show.bs.collapse", function(){
		$('#excel_month_m').val(moment($('#month_year_calendar').calendar('get date')).format('MM'));
		$('#excel_year_m').val(moment($('#month_year_calendar').calendar('get date')).format('YYYY'));
		closealltab("#tab_monthly");
	});

	$("#tab_monthly").on("shown.bs.collapse", function(){
		closealltab("#tab_monthly");
		SmoothScrollTo('#tab_monthly', 300,undefined,90);
	});

	$("#tab_yearly").on("show.bs.collapse", function(){
		closealltab("#tab_yearly");
	});

	$("#tab_yearly").on("shown.bs.collapse", function(){
		closealltab("#tab_yearly");
		SmoothScrollTo('#tab_yearly', 300,undefined,90);
	});

	// $("#tab_userlog").on("show.bs.collapse", function(){
	// 	closealltab("#tab_userlog");
	// });

	// $("#tab_userlog").on("shown.bs.collapse", function(){
	// 	closealltab("#tab_userlog");
	// 	SmoothScrollTo('#tab_userlog', 300,undefined,90);
	// });

	$('#year_calendar').calendar({type: 'year'});

	$('#yearly_sel_month div.step').click(function(){
		if($("#year_text").val() == ''){
			alert('select year first');
		}else{
			$('#yearly_sel_month div.step').removeClass('active');
			$(this).addClass('active');

			cleartabledata('yearly');

			var param = {
				action: 'get_dia_yearly',
				year:$("#year_text").val(),
				month:$(this).data('month'),
				mrn:$("#mrn").val(),
				episno:$("#episno").val()
			}

			$.get("./dialysis_get_data_dialysis?"+$.param(param), function(data) {
				populate_data('yearly',data.data);
			},'json');
		}
	});

	$('#rec_monthly_but').click(function(){
		cleartabledata('monthly');
		var param = {
			action: 'get_dia_monthly',
			date:moment($('#month_year_calendar').calendar('get date')).format('YYYY-MM'),
			mrn:$("#mrn").val(),
			episno:$("#episno").val()
		}

		$.get("./dialysis_get_data_dialysis?"+$.param(param), function(data) {
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

		$.get("./dialysis_get_data_dialysis?"+$.param(param), function(data) {
			populate_data('weekly',data.data);
		},'json');

	});

});

function populate_data(type,data){

	if(type == 'yearly'){
		data.forEach(function(e,i){
			$('table#dia_yearly tr#visit_date_y').children('td').eq(i).text(e.visit_date);
			$('table#dia_yearly tr#start_time_y').children('td').eq(i+1).text(e.start_time);
			$('table#dia_yearly tr#dialyser_y').children('td').eq(i+1).text(e.dialyser);
			$('table#dia_yearly tr#no_of_use_y').children('td').eq(i+1).text(e.no_of_use);
			$('table#dia_yearly tr#target_uf_y').children('td').eq(i+1).text(e.target_uf);
			$('table#dia_yearly tr#heparin_bolus_y').children('td').eq(i+1).text(e.heparin_bolus);
			$('table#dia_yearly tr#heparin_yaintainance_y').children('td').eq(i+1).text(e.heparin_maintainance);
			$('table#dia_yearly tr#dialysate_ca_y').children('td').eq(i+1).text(e.dialysate_ca);
			$('table#dia_yearly tr#pre_weight_y').children('td').eq(i+1).text(e.pre_weight);
			$('table#dia_yearly tr#idwg_y').children('td').eq(i+1).text(e.idwg);
			$('table#dia_yearly tr#prehd_tmp_y').children('td').eq(i+1).text( e.prehd_temperature+' / '+e.prehd_pulse+' / '+e.prehd_respiratory);
			$('table#dia_yearly tr#prehd_bp_y').children('td').eq(i+1).text(e.prehd_systolic+' / '+e.prehd_diastolic);
			$('table#dia_yearly tr#pulse_pre_y').children('td').eq(i+1).text(e.prehd_pulse);
			$('table#dia_yearly tr#prehd_bfr_y').children('td').eq(i+1).text(e['0_bfr']);
			$('table#dia_yearly tr#prehd_dfr_y').children('td').eq(i+1).text(e.prehd_dfr);
			$('table#dia_yearly tr#prehd_vp_y').children('td').eq(i+1).text(e['0_vp']);
			$('table#dia_yearly tr#user_prehd_y').children('td').eq(i+1).text(e['user_prehd']);

			$('table#dia_yearly tr#1_tc_y').children('td').eq(i+1).text(e['1_tc']);
			$('table#dia_yearly tr#1_bp_y').children('td').eq(i+1).text(e['1_bp']);
			$('table#dia_yearly tr#1_pulse_y').children('td').eq(i+1).text(e['1_pulse']);
			$('table#dia_yearly tr#1_dh_y').children('td').eq(i+1).text(e['1_dh']);
			$('table#dia_yearly tr#1_bfr_y').children('td').eq(i+1).text(e['1_bfr']);
			$('table#dia_yearly tr#1_vp_y').children('td').eq(i+1).text(e['1_vp']);
			$('table#dia_yearly tr#1_tmp_y').children('td').eq(i+1).text(e['1_tmp']);
			$('table#dia_yearly tr#1_uv_y').children('td').eq(i+1).text(e['1_uv']);
			$('table#dia_yearly tr#1_f_y').children('td').eq(i+1).text(e['1_f']);
			$('table#dia_yearly tr#user_1_y').children('td').eq(i+1).text(e['user_1']);

			$('table#dia_yearly tr#2_tc_y').children('td').eq(i+1).text(e['2_tc']);
			$('table#dia_yearly tr#2_bp_y').children('td').eq(i+1).text(e['2_bp']);
			$('table#dia_yearly tr#2_pulse_y').children('td').eq(i+1).text(e['2_pulse']);
			$('table#dia_yearly tr#2_dh_y').children('td').eq(i+1).text(e['2_dh']);
			$('table#dia_yearly tr#2_bfr_y').children('td').eq(i+1).text(e['2_bfr']);
			$('table#dia_yearly tr#2_vp_y').children('td').eq(i+1).text(e['2_vp']);
			$('table#dia_yearly tr#2_tmp_y').children('td').eq(i+1).text(e['2_tmp']);
			$('table#dia_yearly tr#2_uv_y').children('td').eq(i+1).text(e['2_uv']);
			$('table#dia_yearly tr#2_f_y').children('td').eq(i+1).text(e['2_f']);	
			$('table#dia_yearly tr#user_2_y').children('td').eq(i+1).text(e['user_2']);		

			$('table#dia_yearly tr#3_tc_y').children('td').eq(i+1).text(e['3_tc']);
			$('table#dia_yearly tr#3_bp_y').children('td').eq(i+1).text(e['3_bp']);
			$('table#dia_yearly tr#3_pulse_y').children('td').eq(i+1).text(e['3_pulse']);
			$('table#dia_yearly tr#3_dh_y').children('td').eq(i+1).text(e['3_dh']);
			$('table#dia_yearly tr#3_bfr_y').children('td').eq(i+1).text(e['3_bfr']);
			$('table#dia_yearly tr#3_vp_y').children('td').eq(i+1).text(e['3_vp']);
			$('table#dia_yearly tr#3_tmp_y').children('td').eq(i+1).text(e['3_tmp']);
			$('table#dia_yearly tr#3_uv_y').children('td').eq(i+1).text(e['3_uv']);
			$('table#dia_yearly tr#3_f_y').children('td').eq(i+1).text(e['3_f']);		
			$('table#dia_yearly tr#user_3_y').children('td').eq(i+1).text(e['user_3']);	

			$('table#dia_yearly tr#4_tc_y').children('td').eq(i+1).text(e['4_tc']);
			$('table#dia_yearly tr#4_bp_y').children('td').eq(i+1).text(e['4_bp']);
			$('table#dia_yearly tr#4_pulse_y').children('td').eq(i+1).text(e['4_pulse']);
			$('table#dia_yearly tr#4_dh_y').children('td').eq(i+1).text(e['4_dh']);
			$('table#dia_yearly tr#4_bfr_y').children('td').eq(i+1).text(e['4_bfr']);
			$('table#dia_yearly tr#4_vp_y').children('td').eq(i+1).text(e['4_vp']);
			$('table#dia_yearly tr#4_tmp_y').children('td').eq(i+1).text(e['4_tmp']);
			$('table#dia_yearly tr#4_uv_y').children('td').eq(i+1).text(e['4_uv']);
			$('table#dia_yearly tr#4_f_y').children('td').eq(i+1).text(e['4_f']);		
			$('table#dia_yearly tr#user_4_y').children('td').eq(i+1).text(e['user_4']);	

			$('table#dia_yearly tr#5_tc_y').children('td').eq(i+1).text(e['5_tc']);
			$('table#dia_yearly tr#5_bp_y').children('td').eq(i+1).text(e['5_bp']);
			$('table#dia_yearly tr#5_pulse_y').children('td').eq(i+1).text(e['5_pulse']);
			$('table#dia_yearly tr#5_dh_y').children('td').eq(i+1).text(e['5_dh']);
			$('table#dia_yearly tr#5_bfr_y').children('td').eq(i+1).text(e['5_bfr']);
			$('table#dia_yearly tr#5_vp_y').children('td').eq(i+1).text(e['5_vp']);
			$('table#dia_yearly tr#5_tmp_y').children('td').eq(i+1).text(e['5_tmp']);
			$('table#dia_yearly tr#5_uv_y').children('td').eq(i+1).text(e['5_uv']);
			$('table#dia_yearly tr#5_f_y').children('td').eq(i+1).text(e['5_f']);
			$('table#dia_yearly tr#user_5_y').children('td').eq(i+1).text(e['user_5']);

			$('table#dia_yearly tr#posthd_bp_y').children('td').eq(i+1).text(e.posthd_systolic+' / '+e.posthd_diastolic);
			$('table#dia_yearly tr#posthd_temperatue_y').children('td').eq(i+1).text(e.posthd_temperatue);
			$('table#dia_yearly tr#posthd_pulse_y').children('td').eq(i+1).text(e.posthd_pulse);
			$('table#dia_yearly tr#posthd_respiratory_y').children('td').eq(i+1).text(e.posthd_respiratory);
			$('table#dia_yearly tr#post_weight_y').children('td').eq(i+1).text(e.post_weight);
			$('table#dia_yearly tr#weight_loss_y').children('td').eq(i+1).text(e.weight_loss);
			$('table#dia_yearly tr#time_complete_y').children('td').eq(i+1).text(e.time_complete);
			$('table#dia_yearly tr#hd_adequancy_y').children('td').eq(i+1).text(e.hd_adequancy);
			$('table#dia_yearly tr#ktv_y').children('td').eq(i+1).text(e.ktv);
			$('table#dia_yearly tr#urr_y').children('td').eq(i+1).text(e.urr);
			$('table#dia_yearly tr#user_posthd_y').children('td').eq(i+1).text(e['user_1']);
			// $('table#dia_yearly tr#medication_y').children('td').eq(i+1).text(e.medication);
			if(e.table_patmedication != undefined || e.table_patmedication != null){
				let patmed = `<ol>`;
				e.table_patmedication.forEach(function(e,i){
					patmed = patmed +  `<li>`+e.chg_desc+` - `+e.enteredby+`</li>`;
				});
				patmed = patmed + `</ol>`
				$('table#dia_yearly tr#medication_y').children('td').eq(i+1).html(patmed);
			}else{
				$('table#dia_yearly tr#medication_y').children('td').eq(i+1).html('');
			}
		});

	}else if(type == 'monthly'){
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

			$('table#dia_monthly tr#posthd_bp_m').children('td').eq(i+1).text(e.posthd_systolic+' / '+e.posthd_diastolic);
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


function cleartabledata(type){
	if(type == 'monthly'){
		$('table#dia_monthly td[align=center],table#dia_monthly td.med_td').html('&nbsp;');
	}else if(type == 'weekly'){
		$('table#dia_weekly td[align=center],table#dia_weekly td.med_td').html('&nbsp;');
	}else if(type == 'yearly'){
		$('table#dia_yearly td[align=center],table#dia_yearly td.med_td').html('&nbsp;');
	}else if(type == 'all'){
		$('table#dia_weekly td[align=center],table#dia_weekly td.med_td,table#dia_monthly td[align=center],table#dia_monthly td.med_td,table#dia_yearly td[align=center],table#dia_yearly td.med_td').html('&nbsp;');
	}
}