
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
		SmoothScrollTo("#jqGrid_ordcom_panel", 500);
		$('a#ordcom_navtab_phar').tab('show')
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
		$("#jqGrid_phar").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));

		let rowid = $("#grid-command-buttons tr.justbc").data("rowId");
		let getCurrentRow = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];
		write_detail_phar('#jqgrid_detail_phar_docname',getCurrentRow.q_doctorname);
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
			myerrorIt_only(fail_msg.id,true);
		}
		this.pop_fail();
	}
	this.del_fail=function(fail_msg){
		var new_msg_array = this.fail_msg_array.filter(function(e,i){
			if(e.id == fail_msg.id){
				return false;
			}
			return true;
		});

		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
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
}