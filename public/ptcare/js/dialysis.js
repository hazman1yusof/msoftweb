$(document).ready(function () {

	disableForm('form#daily_form');

	$("#tab_daily").on("shown.bs.collapse", function(){
		SmoothScrollTo('#tab_daily', 300,undefined,90);
	});

	$("#tab_weekly").on("shown.bs.collapse", function(){
		SmoothScrollTo('#tab_weekly', 300,undefined,90);
	});

	$("#tab_monthly").on("shown.bs.collapse", function(){
		SmoothScrollTo('#tab_monthly', 300,undefined,90);
	});

	$("#tab_trans").on("shown.bs.collapse", function(){
		SmoothScrollTo('#tab_trans', 300,function(){
			$("#jqGrid_trans").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trans_c")[0].offsetWidth-$("#jqGrid_trans_c")[0].offsetLeft-14));
		},90);
	});

	$('#submit').click(function(){

		if($('form#daily_form').form('validate form')) {
			var param = {
				_token: $("#_token").val(),
				action: 'save_dialysis',
				oper: $(this).data('oper'),
				mrn:$("#mrn").val(),
				episno:$("#episno").val(),
				seldate:$("#seldate").val()
			}

			var values = $("form#daily_form").serializeArray();

			$.post( "./save_dialysis?"+$.param(param),$.param(values), function( data ){
				if(data.success == 'success'){
					$('#addnew_dia').prop('disabled',true);
					$('#edit_dia').prop('disabled',false);
					disableForm('form#daily_form');
					$('#toTop').click();
					toastr.success('Dialysis data saved!',{timeOut: 1000});
					SmoothScrollTo('#tab_daily', 300,undefined,90);
				}
			},'json');
		}

	});

	$('#daily_form').form({
	    fields: {
	      start_time     : 'empty',
	      prehd_weight   : 'empty',
	      hep_loading : 'empty'
	    },
	    onFailure: function (event, fields) {
	        let elem = $('.field.error input[type=text]:first');
	        $('html, body').animate({scrollTop: $(elem).offset().top -100 }, 'slow', function () {
	          $(elem).focus();
	        });
	    },

	});

	$('#addnew_dia').click(function(){
		$('#submit').data('oper','add')
		enableForm('form#daily_form');
		$('form#daily_form #start_time').focus();
	});

	$('#edit_dia').click(function(){
		$('#submit').data('oper','edit')
		enableForm('form#daily_form');
		$('form#daily_form #start_time').focus();
	});

	$('#rec_monthly_but').click(function(){
		cleartabledata('monthly');
		var param = {
			action: 'get_dia_monthly',
			date:$("#selectmonth").val(),
			mrn:$("#mrn").val()
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
			mrn:$("#mrn").val()
		}

		$.get("./get_data_dialysis?"+$.param(param), function(data) {
			populate_data('weekly',data.data);
		},'json');

	});

});

function populate_data(type,data){
	if(type == 'monthly'){
		data.forEach(function(e,i){
			$('table#dia_monthly tr#monthly_date').children('td').eq(i).text(e.start_date);
			$('table#dia_monthly tr#start_time').children('td').eq(i+1).text(e.start_time);
			$('table#dia_monthly tr#end_time').children('td').eq(i+1).text(e.end_time);
			$('table#dia_monthly tr#type_dialyser').children('td').eq(i+1).text(e.type_dialyser);
			$('table#dia_monthly tr#noofuse').children('td').eq(i+1).text(e.noofuse);
			$('table#dia_monthly tr#total_uf').children('td').eq(i+1).text(e.total_uf);
			$('table#dia_monthly tr#hep_loading').children('td').eq(i+1).text(e.hep_loading);
			$('table#dia_monthly tr#hep_infusion').children('td').eq(i+1).text(e.hep_infusion);
			$('table#dia_monthly tr#dialysate_calcium').children('td').eq(i+1).text(e.dialysate_calcium);
			$('table#dia_monthly tr#prehd_weight').children('td').eq(i+1).text(e.prehd_weight);
			$('table#dia_monthly tr#dialytic_weight').children('td').eq(i+1).text(e.dialytic_weight);
			$('table#dia_monthly tr#bp_pre').children('td').eq(i+1).text(e.bp_pre);
			$('table#dia_monthly tr#pulse_pre').children('td').eq(i+1).text(e.pulse_pre);
			$('table#dia_monthly tr#rec_2').children('td').eq(i+1).text(e.rec_2);
			$('table#dia_monthly tr#tmp_2').children('td').eq(i+1).text(e.tmp_2);
			$('table#dia_monthly tr#bp_2').children('td').eq(i+1).text(e.bp_2);
			$('table#dia_monthly tr#pulse_2').children('td').eq(i+1).text(e.pulse_2);
			$('table#dia_monthly tr#hepn_1').children('td').eq(i+1).text(e.hepn_1);
			$('table#dia_monthly tr#bf_2').children('td').eq(i+1).text(e.bf_2);
			$('table#dia_monthly tr#uf_rate_1').children('td').eq(i+1).text(e.uf_rate_1);
			$('table#dia_monthly tr#df_2').children('td').eq(i+1).text(e.df_2);
			$('table#dia_monthly tr#vp_2').children('td').eq(i+1).text(e.vp_2);
			$('table#dia_monthly tr#rec_3').children('td').eq(i+1).text(e.rec_3);
			$('table#dia_monthly tr#tmp_3').children('td').eq(i+1).text(e.tmp_3);
			$('table#dia_monthly tr#bp_3').children('td').eq(i+1).text(e.bp_3);
			$('table#dia_monthly tr#pulse_3').children('td').eq(i+1).text(e.pulse_3);
			$('table#dia_monthly tr#hepn_2').children('td').eq(i+1).text(e.hepn_2);
			$('table#dia_monthly tr#uf_rate_2').children('td').eq(i+1).text(e.uf_rate_2);
			$('table#dia_monthly tr#df_3').children('td').eq(i+1).text(e.df_3);
			$('table#dia_monthly tr#vp_3').children('td').eq(i+1).text(e.vp_3);
			$('table#dia_monthly tr#rec_4').children('td').eq(i+1).text(e.rec_4);
			$('table#dia_monthly tr#tmp_4').children('td').eq(i+1).text(e.tmp_4);
			$('table#dia_monthly tr#bp_4').children('td').eq(i+1).text(e.bp_4);
			$('table#dia_monthly tr#pulse_4').children('td').eq(i+1).text(e.pulse_4);
			$('table#dia_monthly tr#hepn_3').children('td').eq(i+1).text(e.hepn_3);
			$('table#dia_monthly tr#bf_4').children('td').eq(i+1).text(e.bf_4);
			$('table#dia_monthly tr#uf_rate_3').children('td').eq(i+1).text(e.uf_rate_3);
			$('table#dia_monthly tr#df_4').children('td').eq(i+1).text(e.df_4);
			$('table#dia_monthly tr#vp_4').children('td').eq(i+1).text(e.vp_4);
			$('table#dia_monthly tr#rec_post').children('td').eq(i+1).text(e.rec_post);
			$('table#dia_monthly tr#tmp_post').children('td').eq(i+1).text(e.tmp_post);
			$('table#dia_monthly tr#bp_post').children('td').eq(i+1).text(e.bp_post);
			$('table#dia_monthly tr#pulse_post').children('td').eq(i+1).text(e.pulse_post);
			$('table#dia_monthly tr#hepn_4').children('td').eq(i+1).text(e.hepn_4);
			$('table#dia_monthly tr#bf_post').children('td').eq(i+1).text(e.bf_post);
			$('table#dia_monthly tr#uf_rate_4').children('td').eq(i+1).text(e.uf_rate_4);
			$('table#dia_monthly tr#df_post').children('td').eq(i+1).text(e.df_post);
			$('table#dia_monthly tr#vp_post').children('td').eq(i+1).text(e.vp_post);
			$('table#dia_monthly tr#bp_sit').children('td').eq(i+1).text(e.bp_sit);
			$('table#dia_monthly tr#pulse_stand').children('td').eq(i+1).text(e.pulse_stand);
			$('table#dia_monthly tr#hd_weight').children('td').eq(i+1).text(e.hd_weight);
			$('table#dia_monthly tr#ultra').children('td').eq(i+1).text(e.ultra);
			$('table#dia_monthly tr#kt_v').children('td').eq(i+1).text(e.kt_v);
			$('table#dia_monthly tr#pre_verifier_name').children('td').eq(i+1).text(e.pre_verifier_name);
			$('table#dia_monthly tr#verifier_by').children('td').eq(i+1).text(e.verifier_by);
			$('table#dia_monthly tr#epo_type').children('td').eq(i+1).text(e.epo_type);
			$('table#dia_monthly tr#dose_type').children('td').eq(i+1).text(e.dose_type);
		});

	}else if(type == 'daily'){
		let array = Object.keys(data);
		$('form#daily_form input[type=text],form#daily_form input[type=time]').each(function(i){
			let index = array.indexOf($(this).attr('id'));
			$(this).val(data[$(this).attr('id')]);
		});
	
	}else if(type == 'weekly'){
		data.forEach(function(e,i){
			$('table#dia_weekly tr#monthly_date_w').children('td').eq(i).text(e.start_date);
			$('table#dia_weekly tr#start_time_w').children('td').eq(i+1).text(e.start_time);
			$('table#dia_weekly tr#end_time_w').children('td').eq(i+1).text(e.end_time);
			$('table#dia_weekly tr#type_dialyser_w').children('td').eq(i+1).text(e.type_dialyser);
			$('table#dia_weekly tr#noofuse_w').children('td').eq(i+1).text(e.noofuse);
			$('table#dia_weekly tr#total_uf_w').children('td').eq(i+1).text(e.total_uf);
			$('table#dia_weekly tr#hep_loading_w').children('td').eq(i+1).text(e.hep_loading);
			$('table#dia_weekly tr#hep_infusion_w').children('td').eq(i+1).text(e.hep_infusion);
			$('table#dia_weekly tr#dialysate_calcium_w').children('td').eq(i+1).text(e.dialysate_calcium);
			$('table#dia_weekly tr#prehd_weight_w').children('td').eq(i+1).text(e.prehd_weight);
			$('table#dia_weekly tr#dialytic_weight_w').children('td').eq(i+1).text(e.dialytic_weight);
			$('table#dia_weekly tr#bp_pre_w').children('td').eq(i+1).text(e.bp_pre);
			$('table#dia_weekly tr#pulse_pre_w').children('td').eq(i+1).text(e.pulse_pre);
			$('table#dia_weekly tr#rec_2_w').children('td').eq(i+1).text(e.rec_2);
			$('table#dia_weekly tr#tmp_2_w').children('td').eq(i+1).text(e.tmp_2);
			$('table#dia_weekly tr#bp_2_w').children('td').eq(i+1).text(e.bp_2);
			$('table#dia_weekly tr#pulse_2_w').children('td').eq(i+1).text(e.pulse_2);
			$('table#dia_weekly tr#hepn_1_w').children('td').eq(i+1).text(e.hepn_1);
			$('table#dia_weekly tr#bf_2_w').children('td').eq(i+1).text(e.bf_2);
			$('table#dia_weekly tr#uf_rate_1_w').children('td').eq(i+1).text(e.uf_rate_1);
			$('table#dia_weekly tr#df_2_w').children('td').eq(i+1).text(e.df_2);
			$('table#dia_weekly tr#vp_2_w').children('td').eq(i+1).text(e.vp_2);
			$('table#dia_weekly tr#rec_3_w').children('td').eq(i+1).text(e.rec_3);
			$('table#dia_weekly tr#tmp_3_w').children('td').eq(i+1).text(e.tmp_3);
			$('table#dia_weekly tr#bp_3_w').children('td').eq(i+1).text(e.bp_3);
			$('table#dia_weekly tr#pulse_3_w').children('td').eq(i+1).text(e.pulse_3);
			$('table#dia_weekly tr#hepn_2_w').children('td').eq(i+1).text(e.hepn_2);
			$('table#dia_weekly tr#uf_rate_2_w').children('td').eq(i+1).text(e.uf_rate_2);
			$('table#dia_weekly tr#df_3_w').children('td').eq(i+1).text(e.df_3);
			$('table#dia_weekly tr#vp_3_w').children('td').eq(i+1).text(e.vp_3);
			$('table#dia_weekly tr#rec_4_w').children('td').eq(i+1).text(e.rec_4);
			$('table#dia_weekly tr#tmp_4_w').children('td').eq(i+1).text(e.tmp_4);
			$('table#dia_weekly tr#bp_4_w').children('td').eq(i+1).text(e.bp_4);
			$('table#dia_weekly tr#pulse_4_w').children('td').eq(i+1).text(e.pulse_4);
			$('table#dia_weekly tr#hepn_3_w').children('td').eq(i+1).text(e.hepn_3);
			$('table#dia_weekly tr#bf_4_w').children('td').eq(i+1).text(e.bf_4);
			$('table#dia_weekly tr#uf_rate_3_w').children('td').eq(i+1).text(e.uf_rate_3);
			$('table#dia_weekly tr#df_4_w').children('td').eq(i+1).text(e.df_4);
			$('table#dia_weekly tr#vp_4_w').children('td').eq(i+1).text(e.vp_4);
			$('table#dia_weekly tr#rec_post_w').children('td').eq(i+1).text(e.rec_post);
			$('table#dia_weekly tr#tmp_post_w').children('td').eq(i+1).text(e.tmp_post);
			$('table#dia_weekly tr#bp_post_w').children('td').eq(i+1).text(e.bp_post);
			$('table#dia_weekly tr#pulse_post_w').children('td').eq(i+1).text(e.pulse_post);
			$('table#dia_weekly tr#hepn_4_w').children('td').eq(i+1).text(e.hepn_4);
			$('table#dia_weekly tr#bf_post_w').children('td').eq(i+1).text(e.bf_post);
			$('table#dia_weekly tr#uf_rate_4_w').children('td').eq(i+1).text(e.uf_rate_4);
			$('table#dia_weekly tr#df_post_w').children('td').eq(i+1).text(e.df_post);
			$('table#dia_weekly tr#vp_post_w').children('td').eq(i+1).text(e.vp_post);
			$('table#dia_weekly tr#bp_sit_w').children('td').eq(i+1).text(e.bp_sit);
			$('table#dia_weekly tr#pulse_stand_w').children('td').eq(i+1).text(e.pulse_stand);
			$('table#dia_weekly tr#hd_weight_w').children('td').eq(i+1).text(e.hd_weight);
			$('table#dia_weekly tr#ultra_w').children('td').eq(i+1).text(e.ultra);
			$('table#dia_weekly tr#kt_v_w').children('td').eq(i+1).text(e.kt_v);
			$('table#dia_weekly tr#pre_verifier_name_w').children('td').eq(i+1).text(e.pre_verifier_name);
			$('table#dia_weekly tr#verifier_by_w').children('td').eq(i+1).text(e.verifier_by);
			$('table#dia_weekly tr#epo_type_w').children('td').eq(i+1).text(e.epo_type);
			$('table#dia_weekly tr#dose_type_w').children('td').eq(i+1).text(e.dose_type);
		});
	}
}

function cleartabledata(type){
	if(type == 'monthly'){
		$('table#dia_monthly td[align=center]').html('&nbsp;');
	}else if(type == 'weekly'){
		$('table#dia_weekly td[align=center]').html('&nbsp;');
	}
}

function populatedialysis(data,seldate){
	$('#edit_dia').prop('disabled',true);
	$('#addnew_dia').prop('disabled',false);
	disableForm('form#daily_form');
	$('#seldate').val(seldate);
	$('span.metal').text(data.Name+' - MRN:'+data.MRN);
	$('#mrn').val(data.MRN);
	$('#episno').val(data.Episno);
	load_daily_dia(seldate);
	emptyFormdata([],'form#daily_form');
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

function load_daily_dia(seldate){

	var param = {
		action: 'get_dia_daily',
		date:seldate,
		mrn:$("#mrn").val()
	}

	$.get("./get_data_dialysis?"+$.param(param), function(data) {
		if(data.data.length>0){
			$('#edit_dia').prop('disabled',false);
			$('#addnew_dia').prop('disabled',true);
			populate_data('daily',data.data[0]);
		}
		
	},'json');

}
