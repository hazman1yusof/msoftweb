$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
	$("body").show();
	/////////////////////////////////////////validation/////////////////////////////////////////
	$.validate({
		language: {
			requiredFields: ''
		},
	});
	
	var errorField = [];
	conf = {
		onValidate: function ($form){
			if(errorField.length > 0){
				return {
					element: $(errorField[0]),
					message: ' '
				}
			}
		},
	};
	
	var fdl = new faster_detail_load();
	// var err_reroll = new err_reroll('#jqGrid',['grpcode', 'description']);
	
	//////////////////////////////////parameter for jqgrid url//////////////////////////////////
	var urlParam = {
		action: 'post_entry',
		url: 'casenote/table',
		// url: "casenote/post_entry?action=get_patient_list&epistycode="+$("#epistycode").val()+"&curpat="+$("#curpat").val()+"&PatClass="+$("#PatClass").val(),
		epistycode: $("#epistycode").val(),
		curpat: $("#curpat").val(),
		PatClass: $("#PatClass").val(),
	};
	
	//////////////////////////////////parameter for saving url//////////////////////////////////
	var addmore_jqgrid = {more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqgrid///////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "./casenote/form",
		colModel: [
			{ label: 'MRN', name: 'MRN', width: 30, canSearch: true, hidden: true },
			{ label: 'Episode', name: 'ep_episno', width: 30, canSearch: false },
			{ label: 'Type', name: 'ep_epistycode', width: 30 },
			{ label: 'Reg Date', name: 'ep_regdate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Case', name: 'case_description', width: 50, editoptions: { style: "text-transform: uppercase" } },
			{ label: 'Pt Visit', name: 'ep_regdept', width: 30 },
			{ label: 'Doctor', name: 'doctorname', width: 70, editoptions: { style: "text-transform: uppercase" } },
			{ label: 'End Date', name: 'ep_dischargedate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: ' ', width: 20, classes: 'wrap', formatter: buttonformatter_casenote },
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'CompCode', name: 'CompCode', hidden: true },
			{ label: 'Episno', name: 'Episno', width: 30, canSearch: false, hidden: true },
			{ label: 'iPesakit', name: 'iPesakit', hidden: true },
			{ label: 'Name', name: 'Name', width: 100, canSearch: false, checked: true, editoptions: { style: "text-transform: uppercase" }, hidden: true },
			{ label: 'telhp', name: 'telhp', width: 20, hidden: true },
			{ label: 'IC', name: 'Newic', width: 20, canSearch: false, hidden: true },
			{ label: 'Sex', name: 'Sex', width: 20, hidden: true },
			{ label: 'DOB', name: 'DOB', width: 20, formatter: dateFormatter, unformat: dateUNFormatter, canSearch: false, hidden: true },
			{ label: 'Religion', name: 'Religion', width: 25, hidden: true },
			{ label: 'Citizencode', name: 'Citizencode', width: 50, hidden: true },
			{ label: 'RaceCode', name: 'RaceCode', width: 50, hidden: true },
			{ label: 'Reg_Date', name: 'Reg_Date', width: 50, hidden: true },
			{ label: 'AddUser', name: 'AddUser', width: 50, hidden: true },
			{ label: 'AddDate', name: 'AddDate', width: 50, hidden: true },
			{ label: 'Lastupdate', name: 'Lastupdate', width: 50, hidden: true },
			{ label: 'LastUser', name: 'LastUser', width: 50, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 50, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 50, hidden: true },
			{ label: 'recstatus', name: 'recstatus', width: 50, hidden: true },
			{ label: 'computerid', name: 'computerid', width: 50, hidden: true },
			{ label: 'raceDesc', name: 'raceDesc', width: 50, hidden: true },
			{ label: 'religionDesc', name: 'religionDesc', width: 50, hidden: true },
			{ label: 'occupDesc', name: 'occupDesc', width: 50, hidden: true },
			{ label: 'cityDesc', name: 'cityDesc', width: 50, hidden: true },
			{ label: 'areaDesc', name: 'areaDesc', width: 50, hidden: true },
			// { label: 'ep_regdept', name: 'ep_regdept', width: 50, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected){
			populate_patDetail(selrowData("#jqGrid"));
		},
		loadComplete: function (){
			if($("#jqGrid").data('lastselrow') == undefined){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function (){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300);
			}
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			
		},
		gridComplete: function (){
			init_btn_casenote();
			fdl.set_array().reset();
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
		},
	});
	
	// function check_cust_rules(rowid){
	// 	var chk = ['grpcode','description','seqno'];
	// 	chk.forEach(function (e,i){
	// 		var val = $("#jqGrid input[name='"+e+"']").val();
	// 		if(val.trim().length <= 0){
	// 			myerrorIt_only("#jqGrid input[name='"+e+"']",true);
	// 		}else{
	// 			myerrorIt_only("#jqGrid input[name='"+e+"']",false);
	// 		}
	// 	})
	// }
	
	/////////////////////////////////////jqGridPager inline/////////////////////////////////////
	$("#jqGrid").inlineNav('#jqGridPager', {
		add: false,
		edit: false,
		cancel: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			// addRowParams: myEditOptions
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGrid", urlParam);
		},
	});
	//////////////////////////////////////////end grid//////////////////////////////////////////
	
	///////////////////////handle searching, its radio button and toggle ///////////////////////
	// toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);
	
	////////////////////////add field into param, refresh grid if needed////////////////////////
	// addParamField('#jqGrid', true, urlParam);
	// addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);
	
	/////////////////////////////changing status and trigger search/////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#dob_search').on('click', searchDate);
	
	function whenchangetodate(){
		urlParam.dob = null;
		patient_search.off();
		$('#patient_search, #dob').val('');
		$('#patient_search_hb').text('');
		$("input[name='Stext'],#dob_text,#patient_text").hide();
		removeValidationClass(['#patient_search']);
		if($('#Scol').val() == 'DOB'){
			$("#dob_text").show();
		}else if($('#Scol').val() == 'MRN' || $('#Scol').val() == 'Name'){
			$("#patient_text").show("fast");
			patient_search.on();
		}else{
			$("input[name='Stext']").show("fast");
		}
	}
	
	function searchDate(){
		urlParam.dob = $('#dob').val();
		refreshGrid('#jqGrid',urlParam);
	}
	
	function err_reroll(jqgridname,data_array){
		this.jqgridname = jqgridname;
		this.data_array = data_array;
		this.error = false;
		this.errormsg = 'asdsds';
		this.old_data;
		this.reroll = function (){
			$('#p_error').text(this.errormsg);
			var self = this;
			$(this.jqgridname+"_iladd").click();
			
			this.data_array.forEach(function (item,i){
				$(self.jqgridname+' input[name="'+item+'"]').val(self.old_data[item]);
			});
			this.error = false;
		}
	}
	
	function buttonformatter_casenote(cellvalue, options, rowObject){
		var retbut = `<div class="mini ui icon buttons"`+rowObject.idno+`>`
			retbut += 	  `<button type='button' class="btn btn-primary btn-sm btn_casenote" data-idno='`+rowObject.idno+`' data-mrn='`+rowObject.MRN+`' data-episno='`+rowObject.ep_episno+`' data-epistycode='`+rowObject.ep_epistycode+`' data-regdept='`+rowObject.ep_regdept+`'>`
			retbut += 	    `Case Note`
			retbut += 	  `</button></div>`;
		return retbut;
	}
	
	function init_btn_casenote(){
		$('button.btn_casenote').off('click');
		$('button.btn_casenote').on('click',function (e){
			oper = 'view';
			var idno = $(this).data('idno');
			var mrn = $(this).data('mrn');
			var episno = $(this).data('episno');
			var epistycode = $(this).data('epistycode');
			var regdept = $(this).data('regdept');
			
			if(regdept == 'A&E'){
				// Emergency
			
			}else if(regdept == 'PHY'){
				// Rehabilitation
			
			}else{
				// TH2, EYE, BEACON
				// Appointment
			
			}
			
			if(epistycode == 'IP'){
				window.open('./pat_mast_MR?epistycode='+epistycode+'&curpat=true&PatClass=HIS&mrn='+mrn+'&episno='+episno, '_blank');
			}
		});
	}
	
	$('#profile').click(function (){
		disableForm('#mdl_patient_info_MR');
		$('#jqGridPager_nok_pat_MR_left, #jqGridPager_nok_emr_MR_left').hide();
		
		// populate_patient();
		$('#mdl_patient_info_MR').modal({backdrop: "static"});
	});
	
	function populate_patDetail(obj){
		$('#name_show_casenote').text(obj.Name);
		$('#mrn_show_casenote').text(("0000000" + obj.MRN).slice(-7));
		$('#sex_show_casenote').text(obj.Sex);
		$('#dob_show_casenote').text(dob_chg(obj.DOB));
		$('#age_show_casenote').text(dob_age(obj.DOB)+' (YRS)');
		$('#race_show_casenote').text(obj.raceDesc);
		$('#religion_show_casenote').text(if_none(obj.religionDesc));
		$('#occupation_show_casenote').text(if_none(obj.occupDesc));
		$('#citizenship_show_casenote').text(obj.cityDesc);
		$('#area_show_casenote').text(obj.areaDesc);
	}
	
	var patient_search = new ordialog(
		'patient_search', 'hisdb.pat_mast', '#patient_search', 'errorField',
		{
			colModel: [
				{ label: 'MRN', name: 'MRN', width: 200, classes: 'pointer', canSearch: true, or_search: true, formatter: padzero, unformat: unpadzero },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
				{ label: 'IC', name: 'Newic', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'DOB', name: 'DOB', width: 200, classes: 'pointer', canSearch: true, or_search: true, formatter: dateFormatter, unformat: dateUNFormatter },
			],
			urlParam: {
				filterCol: ['compcode','recstatus','ACTIVE'],
				filterVal: ['session.compcode','ACTIVE','1']
			},
			ondblClickRow: function (){
				let data = selrowData('#' + patient_search.gridname).MRN;
				let data_name = selrowData('#' + patient_search.gridname).name;
				
				// if($('#Scol').val() == 'MRN'){
				// 	urlParam.searchCol=["MRN"];
				// 	urlParam.searchVal=[data];
				// }else if($('#Scol').val() == 'Name'){
				// 	urlParam.searchCol=["Name"];
				// 	urlParam.searchVal=[data_name];
				// }
				// refreshGrid('#jqGrid', urlParam);
				
				// var param = {
				// 	action: 'post_entry',
				// 	url: 'casenote/table',
				// 	MRN: data,
				// 	name: data_name,
				// };
				
				// $.get("./casenote/table?" + $.param(param), function (data){
					
				// }, 'json').done(function (data){
				// 	if(!$.isEmptyObject(data)){
				// 		refreshGrid('#jqGrid', urlParam);
				// 	}
				// });
				
				urlParam.MRN = data;
				refreshGrid('#jqGrid',urlParam);
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					// $('#'+obj.dialogname).dialog('close');
				}
			},
			loadComplete: function (data,obj){
				
			}
		},{
			title: "Select MRN",
			open: function (){
				patient_search.urlParam.filterCol = ['compcode','recstatus','ACTIVE'];
				patient_search.urlParam.filterVal = ['session.compcode','ACTIVE','1'];

				$("input[type='radio'][name='dcolr']").click(function (){
					if($("input[name='dcolr']:checked").val() == 'DOB'){
						$('#Dtext_patient_search').attr('type','date');
						$("#Dtext_patient_search").on('change',function(){
							console.log('asd');
							$( "#Dtext_patient_search" ).trigger( "keyup" );
						});
					}else{
						$('#Dtext_patient_search').attr('type','text');
						$("#Dtext_patient_search").off('change');
					}
				});
			},
			close: function(){
				$("input[type='radio'][name='dcolr']").off('click');
				$("#Dtext_patient_search").off('change');
			}
		},'urlParam','radio','tab'
	);
	patient_search.makedialog(true);
	$('#patient_search').on('keyup',ifnullsearch);
	
	function ifnullsearch(){
		if($('#patient_search').val() == ''){
			urlParam.searchCol = [];
			urlParam.searchVal = [];
			$('#jqGrid').data('inputfocus','patient_search');
			refreshGrid('#jqGrid', urlParam);
		}
	}
	
});