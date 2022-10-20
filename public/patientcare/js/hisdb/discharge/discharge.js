$(document).ready(function () {

	var fdl = new faster_detail_load();
	disableForm('#form_discharge');

	// $("#new_discharge").click(function(){
	// 	hideatdialogForm(false);
	// });
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
		if( $('#discharge_form').isValid({requiredFields: ''}, conf, true) ) {

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

	$("#jqGrid_discharge_panel").on("shown.bs.collapse", function(){
		SmoothScrollTo("#jqGrid_discharge_panel", 500);
		$("#jqGrid_doctor_disc").jqGrid ('setGridWidth', Math.floor($("#jqGrid_doctor_disc_c")[0].offsetWidth-$("#jqGrid_doctor_disc_c")[0].offsetLeft-0));
		urlParam_doctor_disc.filterCol = ['da.compcode','da.episno','da.mrn'],
		urlParam_doctor_disc.filterVal = ['session.compcode',$("#episno_discharge").val(),$("#mrn_discharge").val()]
		refreshGrid("#jqGrid_doctor_disc", urlParam_doctor_disc);
	});

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
		pager: "#jqGridPager_doctor",
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

//screen bed management//
function populate_form_discharge(obj,rowdata){	
	//panel header	
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

	//formdischarge	
	$('#mrn_discharge').val(obj.mrn);	
	$("#episno_discharge").val(obj.episno);

	// document.getElementById('showdischarge_bedmgmt').style.display = 'inline'; //to show hidden data

	var saveParam={	
        action:'get_table_discharge',	
    }	
    var postobj={	
    	_token : $('#csrf_token').val(),	
    	mrn:obj.mrn,	
    	episno:obj.episno	
    };	
}

function populate_discharge_empty(obj){
	//panel header	
	$('#name_show_discharge').text('');
	$('#mrn_show_discharge').text('');
	$('#sex_show_discharge').text('');
	$('#dob_show_discharge').text('');
	$('#age_show_discharge').text('');
	$('#race_show_discharge').text('');
	$('#religion_show_discharge').text('');
	$('#occupation_show_discharge').text('');
	$('#citizenship_show_discharge').text('');
	$('#area_show_discharge').text('');

	//formdischarge	
	$('#mrn_discharge').val('');	
	$("#episno_discharge").val('');
}

//screen current patient//
function populate_discharge_currpt(obj){

	//panel header	
	$('#name_show_discharge').text(obj.Name);
	$('#mrn_show_discharge').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_discharge').text((obj.Sex).toUpperCase());
	$('#dob_show_discharge').text(dob_chg(obj.DOB));
	$('#age_show_discharge').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_discharge').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_discharge').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_discharge').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_discharge').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_discharge').text(if_none(obj.areaDesc).toUpperCase());

	//formdischarge	
	$('#mrn_discharge').val(obj.MRN);	
	$("#episno_discharge").val(obj.Episno);

	// document.getElementById('showdischarge_curpt').style.display = 'inline'; //to show hidden data

	var tableParam={	
        action:'get_table_discharge',
        mrn:obj.MRN,
        episno:obj.Episno
	}

	$.get( "./discharge/table?"+$.param(tableParam), function( data ) {
			
	},'json').done(function(data) {
		if(data.data.bed != null || data.data.bed != undefined){
			$('div#jqGrid_discharge_panel #bedtype_discharge').val(data.data.bed.bedtype);
			$('div#jqGrid_discharge_panel #bedtype_text_discharge').val(data.data.bed.description);
			$('div#jqGrid_discharge_panel #bed_discharge').val(data.data.bed.bednum);
			$('div#jqGrid_discharge_panel #room_discharge').val(data.data.bed.room);
		}

		if(data.data.episode != null || data.data.episode != undefined ){
			$('div#jqGrid_discharge_panel #regdate_discharge').val(data.data.episode.reg_date);
			$('div#jqGrid_discharge_panel #regby_discharge').val(data.data.episode.adduser);
			$('div#jqGrid_discharge_panel #regtime_discharge').val(data.data.episode.reg_time);
		}
	});
	
}

