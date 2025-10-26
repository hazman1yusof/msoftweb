
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var errorField = [];
var fdl_ordcom = new faster_detail_load();
var conf_ordco = {
	onValidate: function ($form) {
		if (errorField.length > 0) {
			show_errors(errorField,'#formdata');
			return [{
				element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
				message: ' '
			}]
		}
	},
};

function getrow_bootgrid_(){
	if($('#ordcom_phase').val() == '2'){
		return selrowData('#jqGrid');
	}else{
		return getrow_bootgrid();
	}
}

$(document).ready(function(){
	$("#jqGrid_ordcom_panel").on("shown.bs.collapse", function(){
		var lastrowdata = getrow_bootgrid_();
		get_billtype();
		get_ordcom_totamount();
		SmoothScrollTo("#jqGrid_ordcom_panel", 500,70);
		if($('#ordcom_phase').val() != '2'){
			$('a#ordcom_navtab_phar').tab('show');
		}
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
		$("#jqGrid_phar").jqGrid('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
		$("#cyclebill_dtl").attr('href',"./ordcom/table?action=showpdf_detail&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);
		$("#cyclebill_summ").attr('href',"./ordcom/table?action=showpdf_summ&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);
		$("#phar_label_link").attr('href',"./pat_mast/patlabel?action=pharlabel&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);

		// if($('#isdoctor').val() != '1'){
        // 	let bootgrid_last_rowid = $("#grid-command-buttons tr.justbc").data("row-id");
		// 	let rows = $("#grid-command-buttons").bootgrid("getCurrentRows");
        // 	var lastrowdata = getrow_bootgrid_(bootgrid_last_rowid,rows);
		// 	write_detail_phar('#jqgrid_detail_phar_docname',lastrowdata.q_doctorname);
		// 	write_detail_phar('#jqgrid_detail_phar_dept',$('#userdeptdesc').val());
		// }
	});

	$('.nav-tabs a').on('shown.bs.tab', function(e){
		let ordcomtype = $(this).data('ord_chgtype');
		switch(ordcomtype){
			case 'PHAR':
				$("#jqGrid_phar").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_phar',urlParam_phar,'add');
				break;
			case 'DISP':
				$("#jqGrid_disp").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_disp',urlParam_disp,'add');
				break;
			case 'LAB':
				$("#jqGrid_lab").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_lab',urlParam_lab,'add');
				break;
			case 'RAD':
				$("#jqGrid_rad").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_rad',urlParam_rad,'add');
				break;
			case 'DFEE':
				$("#jqGrid_dfee").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
				break;
			case 'PHYS':
				$("#jqGrid_phys").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_phys',urlParam_phys,'add');
				break;
			case 'REHAB':
				$("#jqGrid_rehab").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
				break;
			case 'DIET':
				$("#jqGrid_diet").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_diet',urlParam_diet,'add');
				break;
			case 'OTH':
				$("#jqGrid_oth").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_oth',urlParam_oth,'add');
				break;
			case 'PKG':
				$("#jqGrid_pkg").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
				refreshGrid('#jqGrid_pkg',urlParam_pkg,'add');
				break;
		}
	});
});

//screen current patient//
function populate_ordcom_currpt(obj){
	//panel header	
	$('#name_show_ordcom').text(if_none(obj.Name));
	$('#mrn_show_ordcom').text(if_none(("0000000" + obj.MRN).slice(-7)));
	$('#billtype_show_ordcom').text(if_none($('#billtype_def_desc').val()));
	$('#sex_show_ordcom').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_ordcom').text(dob_chg(obj.DOB));
	$('#age_show_ordcom').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_ordcom').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_ordcom').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_ordcom').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_ordcom').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_ordcom').text(if_none(obj.areaDesc).toUpperCase());

	if($('#ordcom_phase').val() == '2'){
		get_ordcom_totamount();
	}else{
		set_ordcom_totamount(obj.totamount);
	}

	//formordcom	
	$('#mrn_ordcom').val(obj.MRN);	
	$("#episno_ordcom").val(obj.Episno);
	urlParam_phar.mrn = obj.MRN;
	urlParam_phar.episno = obj.Episno;
	urlParam_disp.mrn = obj.MRN;
	urlParam_disp.episno = obj.Episno;
	urlParam_lab.mrn = obj.MRN;
	urlParam_lab.episno = obj.Episno;
	urlParam_rad.mrn = obj.MRN;
	urlParam_rad.episno = obj.Episno;
	urlParam_phys.mrn = obj.MRN;
	urlParam_phys.episno = obj.Episno;
	urlParam_rehab.mrn = obj.MRN;
	urlParam_rehab.episno = obj.Episno;
	urlParam_diet.mrn = obj.MRN;
	urlParam_diet.episno = obj.Episno;
	urlParam_dfee.mrn = obj.MRN;
	urlParam_dfee.episno = obj.Episno;
	urlParam_oth.mrn = obj.MRN;
	urlParam_oth.episno = obj.Episno;
	urlParam_pkg.mrn = obj.MRN;
	urlParam_pkg.episno = obj.Episno;	
}

function remark_formatter(cellvalue, options, rowdata){
	var return_remark=``;
	if(rowdata.ftxtdosage != null){
		return_remark+=`<label>Dose</label><br><span name='span_dose'>`+rowdata.ftxtdosage+`</span><br>`;
	}
	if(rowdata.frequency != null){
		return_remark+=`<label>Frequency</label><br><span name='span_freq'>`+rowdata.frequency+`</span><br>`;
	}
	if(rowdata.addinstruction != null){
		return_remark+=`<label>Instruction</label><br><span name='span_ins'>`+rowdata.addinstruction+`</span><br>`;
	}
	if(rowdata.drugindicator != null){
		return_remark+=`<label>Indicator</label><br><span name='span_ind'>`+rowdata.drugindicator+`</span><br>`;
	}

	return return_remark;
}

function remark_unformatter(cellvalue, options){
	return '';
}

function formatterstatus_tick2(cellvalue, option, rowObject) {
	if (cellvalue == '1') {
		return `<span class="fa fa-check"></span>`;
	}else{
		return '';
	}
}

function unformatstatus_tick2(cellvalue, option, rowObject) {
	if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
		return '1';
	}else{
		return '0';
	}
}

function fail_msg_func(fail_msg_div=null){
	this.fail_msg_div = (fail_msg_div!=null)?fail_msg_div:'div#fail_msg';
	this.fail_msg_array=[];
	this.add_fail=function(fail_msg){
		let found=false;
		this.fail_msg_array.forEach(function(e,i){
			if(e.id == fail_msg.id){
				e.msg=fail_msg.msg;
				found=true;
			}
		});
		if(!found){
			this.fail_msg_array.push(fail_msg);
		}
		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.textfld,true);
		}
		this.pop_fail();
	}
	this.del_fail=function(fail_msg){
		var new_msg_array = this.fail_msg_array.filter(function(e,i){
			if(e.id == fail_msg.id){
				if(e.textfld !=null){
					myerrorIt_only(e.textfld,false);
				}
				return false;
			}
			return true;
		});

		this.fail_msg_array = new_msg_array;
		this.pop_fail();
	}
	this.clear_fail=function(){
		this.fail_msg_array=[];
		this.pop_fail();
	}
	this.pop_fail=function(){
		var self=this;
		$(self.fail_msg_div).html('');
		this.fail_msg_array.forEach(function(e,i){
			$(self.fail_msg_div).append("<li>"+e.msg+"</li>");
		});
	}
	this.refail_textfield=function(){
		var self=this;
		this.fail_msg_array.forEach(function(e,i){
			if(e.textfld !=null){
				myerrorIt_only(e.id,true);
			}
		});
	}
}

var get_billtype_main=null;
function get_billtype(){
	var lastrowdata = getrow_bootgrid_();

	var param={
		action:'get_value_default',
		url:"./SalesOrderDetail/table",
		action: 'get_billtype',
		mrn:lastrowdata.MRN,
		episno:lastrowdata.Episno,
		billtype:lastrowdata.billtype,
	}
	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			get_billtype_main = data.rows;
		}
	});
}

function get_ordcom_totamount(){
	var lastrowdata = getrow_bootgrid_();

	var param={
		url:"./ordcom/table",
		action: 'get_ordcom_totamount',
		mrn:lastrowdata.MRN,
		episno:lastrowdata.Episno
	}
	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			$('span#cyclebill_totmat').text(numeral(data.totamount).format('0,0.00'));
		}else{
			$('span#cyclebill_totmat').text('');
		}
	});
}

function set_ordcom_totamount(totamount){
	$('span#cyclebill_totmat').text(numeral(totamount).format('0,0.00'));
}

function calc_discamt_main(chggroup,chgcode,unitprce,quantity){
	var percent=(get_billtype_main.length>0)?get_billtype_main[0].bm_percent:100;
	var amount=(get_billtype_main.length>0)?get_billtype_main[0].bm_amount:0;
	get_billtype_main.forEach(function(e,i){
		if(e.bs_chggroup == chggroup){
			percent = e.bs_percent;
			amount = e.bs_amount;
			if(e.bi_chgcode == chgcode){
				percent = e.bi_percent;
				amount = e.bi_amount;
			}
		}
	});

	var discamount = ((((100-percent)/100)*unitprce*-1)*quantity) - amount;
	console.log('asd');
	if(isNan(discamount)){
		return 0.00;
	}

	return numeral(discamount).format('0,0.00');
}

function abscurrency(val,opt,rowObject ){
	return Math.abs(val);
}
function abscurrency_unformat(val,opt,rowObject ){
	if(val==null||val==undefined||val==''||val==0){
		return 0;
	}else{
		return val*-1;
	}
}

function set_userdeptcode(tab){
	if($('#epistycode').val() == 'IP' || $('#epistycode').val() == 'DP'){
		let rowdata = getrow_bootgrid_();
		$('#'+tab+'dept_dflt').val('');
	}else{
		let rowdata = getrow_bootgrid_();
		// $('#'+tab+'dept_dflt').val(rowdata.regdept);
		$('#'+tab+'dept_dflt').val('');
	}
}

function final_bill(grid,param){
	if (confirm("Are you sure to run final bill for this patient?") == true) {
		var lastrowdata = getrow_bootgrid_();
		var url = "./ordcom/table?action=final_bill_invoice&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno;
		let urlparam = {	
			action: 'final_bill',
		};
		let urlobj={
			oper:'final_bill',
			_token: $("#csrf_token").val(),
			mrn: lastrowdata.MRN,
			episno: lastrowdata.Episno
		};

		$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
		}).fail(function (data) {
			$('#tabcoverage').collapse('hide');
			refreshGrid(grid, param);
		}).done(function (data) {
			$('#tabcoverage').collapse('hide');
			$("#grid-command-buttons").bootgrid('reload');
			window.scrollTo(0,0);
			$('#jqGrid_ordcom_panel').collapse('hide');
			// refreshGrid(grid, param);
			window.open(url, '_blank').focus();
		});	
	}
}