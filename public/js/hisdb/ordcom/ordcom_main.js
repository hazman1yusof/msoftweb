
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

$(document).ready(function(){
	$("#jqGrid_ordcom_panel").on("shown.bs.collapse", function(){
		var lastrowdata = getrow_bootgrid();
		get_billtype();
		SmoothScrollTo("#jqGrid_ordcom_panel", 500,70);
		$('a#ordcom_navtab_phar').tab('show')
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
		$("#jqGrid_phar").jqGrid('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
		$("#cyclebill_dtl").attr('href',"./ordcom/table?action=showpdf_detail&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);
		$("#cyclebill_summ").attr('href',"./ordcom/table?action=showpdf_summ&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);

		// if($('#isdoctor').val() != '1'){
        // 	let bootgrid_last_rowid = $("#grid-command-buttons tr.justbc").data("row-id");
		// 	let rows = $("#grid-command-buttons").bootgrid("getCurrentRows");
        // 	var lastrowdata = getrow_bootgrid(bootgrid_last_rowid,rows);
		// 	write_detail_phar('#jqgrid_detail_phar_docname',lastrowdata.q_doctorname);
		// 	write_detail_phar('#jqgrid_detail_phar_dept',$('#userdeptdesc').val());
		// }
	});

	$('.nav-tabs a').on('shown.bs.tab', function(e){
		let ordcomtype = $(this).data('ord_chgtype');
		switch(ordcomtype){
			case 'PHAR':
				$("#jqGrid_phar").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_phar',urlParam_phar,'add');
				break;
			case 'DISP':
				$("#jqGrid_disp").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_disp',urlParam_disp,'add');
				break;
			case 'LAB':
				$("#jqGrid_lab").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_lab',urlParam_lab,'add');
				break;
			case 'RAD':
				$("#jqGrid_rad").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_rad',urlParam_rad,'add');
				break;
			case 'DFEE':
				$("#jqGrid_dfee").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
				break;
			case 'PHYS':
				$("#jqGrid_phys").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_phys',urlParam_phys,'add');
				break;
			case 'REHAB':
				$("#jqGrid_rehab").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
				break;
			case 'DIET':
				$("#jqGrid_diet").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_diet',urlParam_diet,'add');
				break;
			case 'OTH':
				$("#jqGrid_oth").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
				refreshGrid('#jqGrid_oth',urlParam_oth,'add');
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
					console.log(e);
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
	var lastrowdata = getrow_bootgrid();

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

	return discamount;
}

function abscurrency(val,opt,rowObject ){
	return Math.abs(val);
}