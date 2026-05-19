$(document).ready(function () {

	var fdl = new faster_detail_load();
	disableForm('#form_discharge');

	$("#new_discharge").click(function (){
		button_state_discharge('wait');
		enableForm('#form_discharge');
		rdonly('#form_discharge');
		// dialog_mrn_edit.on();
	});
	
	$("#edit_discharge").click(function (){
		button_state_discharge('wait');
		enableForm('#form_discharge');
		rdonly('#form_discharge');
		// dialog_mrn_edit.on();
	});
	
	$("#save_discharge").click(function (){
		disableForm('#form_discharge');
		if($('#form_discharge').isValid({requiredFields: ''}, conf, true)){
			saveForm_discharge(function (){
				$("#cancel_discharge").data('oper','edit');
				$("#cancel_discharge").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#form_discharge');
			rdonly('#form_discharge');
		}
	});

	$("#cancel_discharge").click(function (){
		disableForm('#form_discharge');
		button_state_discharge($(this).data('oper'));
		// dialog_mrn_edit.off();
	});

	$("#print_discharge").click(function() {
		window.open('./discharge/showpdf?mrn_discharge='+$('#mrn_discharge').val()+'&episno_discharge='+$('#episno_discharge').val(), '_blank');
	});

	$("#jqGrid_discharge_panel").on("shown.bs.collapse", function (){
		var saveParam = {
			action: 'get_table_discharge',
		}
		var postobj = {
			_token: $('#csrf_token').val(),
			mrn: $("#mrn_discharge").val(),
			episno: $("#episno_discharge").val(),
			mrn: $("#mrn_dischargeForm").val(),
			episno: $("#episno_dischargeForm").val(),
			adduser: $("#reg_by").val(),
			dischargeuser: $("#dischargeuser").val(),

		};
		
		$.post("discharge/form?"+$.param(saveParam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).success(function (data){
			if(!$.isEmptyObject(data.episode)){
				autoinsert_rowdata("#form_discharge",data.episode);
				button_state_discharge('edit');
				textarea_init_discharge();
			}else{
				autoinsert_rowdata("#form_discharge",data.episode);
				button_state_discharge('add');
				textarea_init_discharge();
			}
		});
		
		SmoothScrollTo("#jqGrid_discharge_panel", 500);
		$("#jqGrid_doctor_disc").jqGrid ('setGridWidth', Math.floor($("#jqGrid_doctor_disc_c")[0].offsetWidth-$("#jqGrid_doctor_disc_c")[0].offsetLeft-0));
		urlParam_doctor_disc.filterCol = ['da.compcode','da.episno','da.mrn'],
		urlParam_doctor_disc.filterVal = ['session.compcode',$("#episno_discharge").val(),$("#mrn_discharge").val()]
		refreshGrid("#jqGrid_doctor_disc", urlParam_doctor_disc);
	});
	
	$("#jqGrid_discharge_panel").on("hide.bs.collapse", function (){
		button_state_discharge('empty');
		disableForm('#form_discharge');
	});

	/////////////////////////validation//////////////////////////
	$.validate({
		language : {
			requiredFields: ''
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	////////////////////////////////////start dialog///////////////////////////////////////

	var dialog_dest_discharge = new ordialog(
		'dialog_dest_discharge','hisdb.discharge',"div#jqGrid_discharge_panel #dest_discharge",errorField,
		{	colModel:
			[
				{label:'Code',name:'code',width:200,classes:'pointer',canSearch:true},
				{label:'Description',name:'discharge',width:400,classes:'pointer',canSearch:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE', 'session.compcode'],
			},
			ondblClickRow:function(event){
				//$('#occup').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					//$('#occup').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			},
		},{
			title:"Select ChargeCode",
			open: function(){
				dialog_dest_discharge.urlParam.filterCol = ['recstatus','compcode'];
				dialog_dest_discharge.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
			},
		},'urlParam','radio','tab','table'
	);
	dialog_dest_discharge.makedialog(true);

	$('#discharge_btn').click(function(){
		if( $('#form_discharge').isValid({requiredFields: ''}, conf, true) ) {

			var r = confirm("Do you want to discharge this Patient?");
			if (r == true) {
			  var postobj={	
			    	action: 'discharge_patient',
			    	_token : $('#csrf_token').val(),	
			    	mrn:$('#mrn_discharge').val(),	
			    	episno:$("#episno_discharge").val(),
			    	destination:$('#dest_discharge').val()
				};

				$.post( "./discharge/form", postobj, function( data ) {
				
				}).fail(function(data) {

				}).success(function(data){
					$('#toggle_discharge').click();
					$("#grid-command-buttons").bootgrid("reload");
					SmoothScrollToTop();
				});
			}
			
		}else{
			
		}
	});

	// $("#jqGrid_discharge_panel").on("shown.bs.collapse", function(){
	// 	SmoothScrollTo("#jqGrid_discharge_panel", 500);
	// 	$("#jqGrid_doctor_disc").jqGrid ('setGridWidth', Math.floor($("#jqGrid_doctor_disc_c")[0].offsetWidth-$("#jqGrid_doctor_disc_c")[0].offsetLeft-0));
	// 	urlParam_doctor_disc.filterCol = ['da.compcode','da.episno','da.mrn'],
	// 	urlParam_doctor_disc.filterVal = ['session.compcode',$("#episno_discharge").val(),$("#mrn_discharge").val()]
	// 	refreshGrid("#jqGrid_doctor_disc", urlParam_doctor_disc);
	// });

	var urlParam_doctor_disc = {
		action:'get_table_default',
		url:'util/get_table_default',
		field: '',
		fixPost:'true',
		table_name: ['hisdb.docalloc AS da','hisdb.doctor AS d'],
		join_type:['LEFT JOIN'],
		join_onCol:['da.doctorcode'],
		join_onVal:['d.doctorcode'],
		filterCol:['da.compcode','da.episno','da.mrn'],
		filterVal:['session.compcode',$("#episno_discharge").val(),$("#mrn_discharge").val()],
	}

	$("#jqGrid_doctor_disc").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'Doctorcode', name: 'da_doctorcode' , width: 30 },
            { label: 'Name', name: 'd_doctorname' ,classes: 'wrap', width: 70},
            { label: 'compcode', name: 'da_compcode', hidden: true },
            { label: 'allocno', name: 'da_allocno', hidden: true  },
            { label: 'MRN', name: 'da_mrn', hidden: true  },
            { label: 'Epis no', name: 'da_episno' , hidden: true },
            { label: 'd_disciplinecode', name: 'd_disciplinecode' , hidden: true },
            { label: 'da_asdate', name: 'da_asdate' , hidden: true },
            { label: 'da_astime', name: 'da_astime' , hidden: true },
            { label: 'da_astatus', name: 'da_astatus' , hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		sortname: 'da_allocno',
		sortorder: 'asc',
		height: 250, 
		rowNum: 30,
		pager: "#jqGridPager_doctor_disc",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	addParamField('#jqGrid_doctor_disc', false, urlParam_doctor_disc);
		
});

button_state_discharge('empty');
	function button_state_discharge(state){
		switch(state){
			case 'empty':
				$("#toggle_discharge").removeAttr('data-toggle');
				$('#cancel_discharge').data('oper','add');
				$('#new_discharge,#save_discharge,#cancel_discharge,#edit_discharge').attr('disabled',true);
				break;
			case 'add':
				$("#toggle_discharge").attr('data-toggle','collapse');
				$('#cancel_discharge').data('oper','add');
				$("#new_discharge").attr('disabled',false);
				$('#save_discharge,#cancel_discharge,#edit_discharge').attr('disabled',true);
				break;
			case 'edit':
				$("#toggle_discharge").attr('data-toggle','collapse');
				$('#cancel_discharge').data('oper','edit');
				$("#edit_discharge").attr('disabled',false);
				$('#save_discharge,#cancel_discharge,#new_discharge').attr('disabled',true);
				break;
			case 'wait':
				$("#toggle_discharge").attr('data-toggle','collapse');
				$("#save_discharge,#cancel_discharge").attr('disabled',false);
				$('#edit_discharge,#new_discharge').attr('disabled',true);
				break;
		}
		
		// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
		// 	$('#new_discharge,#save_discharge,#cancel_discharge,#edit_discharge').attr('disabled',true);
		// }
	}

	function populate_discharge(obj,rowdata){
		emptyFormdata(null,"#form_discharge");
		
		// panel header
		$('#name_show_discharge').text(obj.name);
		$('#mrn_show_discharge').text(("0000000" + obj.mrn).slice(-7));
		$('#sex_show_discharge').text(obj.sex);
		$('#dob_show_discharge').text(dob_chg(obj.dob));
		$('#age_show_discharge').text(obj.age+ ' (YRS)');
		$('#race_show_discharge').text(obj.race);
		$('#religion_show_discharge').text(if_none(obj.religion));
		$('#occupation_show_discharge').text(if_none(obj.occupation));
		$('#citizenship_show_discharge').text(obj.citizen);
		$('#area_show_discharge').text(obj.area);
		
		// form_discharge
		$('#mrn_discharge').val(obj.mrn);
		$("#episno_discharge").val(obj.episno);
		$("#mrn_dischargeForm").val(obj.mrn);
		$("#episno_dischargeForm").val(obj.episno);
		
		var saveParam = {
			action: 'get_table_discharge',
		}
		var postobj = {
			_token: $('#csrf_token').val(),
			mrn: obj.mrn,
			episno: obj.episno,
			mrn_dischargeForm: obj.mrn_dischargeForm,
			episno_dischargeForm: obj.episno_dischargeForm,

		};
		
		$.post("discharge/form?"+$.param(saveParam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).success(function (data){
			if(!$.isEmptyObject(data)){
				// autoinsert_rowdata("#form_discharge",data.discharge);
				autoinsert_rowdata("#form_discharge",data.episode);
				button_state_discharge('edit');
			}else{
				button_state_discharge('add');
			}
		});
	}

	// screen current patient //
	function populate_discharge_currpt(obj){
		emptyFormdata(null,"#form_discharge");
		
		// panel header
		$('#name_show_discharge').text(obj.Name);
		$('#mrn_show_discharge').text(("0000000" + obj.MRN).slice(-7));
		$('#sex_show_discharge').text(if_none(obj.Sex).toUpperCase());
		$('#dob_show_discharge').text(dob_chg(obj.DOB));
		$('#age_show_discharge').text(dob_age(obj.DOB)+' (YRS)');
		$('#race_show_discharge').text(if_none(obj.raceDesc).toUpperCase());
		$('#religion_show_discharge').text(if_none(obj.religionDesc).toUpperCase());
		$('#occupation_show_discharge').text(if_none(obj.occupDesc).toUpperCase());
		$('#citizenship_show_discharge').text(if_none(obj.cityDesc).toUpperCase());
		$('#area_show_discharge').text(if_none(obj.areaDesc).toUpperCase());
		
		// form_discharge
		$('#mrn_discharge').val(obj.MRN);
		$("#episno_discharge").val(obj.Episno);
		$('#mrn_dischargeForm').val(obj.MRN);
		$("#episno_dischargeForm").val(obj.Episno);

		$("#reg_by").val(obj.adduser);
		$("#dischargeuser").val(obj.dischargeuser);


	}

	function autoinsert_rowdata(form,rowData){
		$.each(rowData, function (index, value){
			var input = $(form+" [name='"+index+"']");
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else if(input.is("[type=checkbox]")){
				if(value == 1){
					$(form+" [name='"+index+"']").prop('checked', true);
				}
			}else{
				input.val(value);
			}
		});
	}

	function saveForm_discharge(callback){
		var saveParam = {
			action: 'save_table_discharge',
			oper: $("#cancel_discharge").data('oper')
		}
		var postobj = {
			_token: $('#csrf_token').val(),
			// dischargeuser: $('#dischargeuser').val(),
			// idtype_edit: $('#idtype_edit').val()
		};
		
		values = $("#form_discharge").serializeArray();
		
		values = values.concat(
			$('#form_discharge input[type=checkbox]:not(:checked)').map(
				function (){
					return {"name": this.name, "value": 0}
				}).get()
		);
		
		values = values.concat(
			$('#form_discharge input[type=checkbox]:checked').map(
				function (){
					return {"name": this.name, "value": 1}
				}).get()
		);
		
		values = values.concat(
			$('#form_discharge input[type=radio]:checked').map(
				function (){
					return {"name": this.name, "value": this.value}
				}).get()
		);
		
		values = values.concat(
			$('#form_discharge select').map(
				function (){
					return {"name": this.name, "value": this.value}
				}).get()
		);
		
		$.post("./discharge/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function (data){
			
		},'json').fail(function (data){
			// alert('there is an error');
			callback();
		}).success(function (data){
			callback();
		});
	}
	
	function textarea_init_discharge(){
		$('textarea#diagfinal,textarea#patologist,textarea#clinicalnote,textarea#phyexam,textarea#diagprov,textarea#treatment,textarea#summary,textarea#followup').each(function (){
			if(this.value.trim() == ''){
				this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
			}else{
				this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
			}
		}).off().on('input', function (){
			if(this.scrollHeight > 40){
				this.style.height = 'auto';
				this.style.height = (this.scrollHeight) + 'px';
			}else{
				this.style.height = (40) + 'px';
			}
		});
	}


