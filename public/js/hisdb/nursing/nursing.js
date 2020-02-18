
	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';

	$(document).ready(function () {
		$("body").show();
		check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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

		// populate_registerformdata_edit();

		// function populate_registerformdata_edit(obj){

		// 	//panel header
		// 	$('#name_show').text(obj.a_pat_name);
		// 	$('#newic_show').text(obj.newic);
		// 	$('#sex_show').text(obj.sex);
		// 	$('#age_show').text(obj.age);
		// 	$('#race_show').text(obj.race);	
		// 	$("#btn_grp_edit").show();
		
		// }

		//panel header
		$('#name_show').val('name_show');
		$('#newic_show').val();
		$('#sex_show').val();
		$('#age_show').val();
		$('#race_show').val();
			
		////////////////////////////////////start dialog///////////////////////////////////////
		// var butt1=[{
		// 	text: "Save",click: function() {
		// 		if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
		// 			saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
		// 		}
		// 	}
		// },{
		// 	text: "Cancel",click: function() {
		// 		$(this).dialog('close');
		// 	}
		// }];

		// var butt2=[{
		// 	text: "Close",click: function() {
		// 		$(this).dialog('close');
		// 	}
		// }];

		// var oper='add';
		// $("#dialogForm")
		//   .dialog({ 
		// 	width: 9/10 * $(window).width(),
		// 	modal: true,
		// 	autoOpen: false,
		// 	open: function( event, ui ) {
		// 		parent_close_disabled(true);
		// 		switch(oper) {
		// 			case state = 'add':
		// 				enableForm('#formdata');
		// 				rdonly('#formdata');
		// 				break;
		// 			case state = 'edit':
		// 				enableForm('#formdata');
		// 				rdonly('#formdata');
		// 				frozeOnEdit("#dialogForm");
		// 				recstatusDisable();
		// 				break;
		// 			case state = 'view':
		// 				disableForm('#formdata');
		// 				break;
		// 		}
		// 		if(oper!='view'){
		// 			set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
		// 			dialog_chggroup.on();
		// 			dialog_ipdept.on();
		// 			dialog_opdept.on();
		// 			dialog_ipacccode.on();
		// 			dialog_opacccode.on();
		// 			dialog_otcacccode.on();
		// 			dialog_invcategory.on();
		// 		}
		// 		if(oper!='add'){
		// 			dialog_chggroup.check(errorField);
		// 			dialog_ipdept.check(errorField);
		// 			dialog_opdept.check(errorField);
		// 			dialog_ipacccode.check(errorField);
		// 			dialog_opacccode.check(errorField);
		// 			dialog_otcacccode.check(errorField);
		// 			dialog_invcategory.check(errorField);
		// 		}
		// 	},
		// 	close: function( event, ui ) {
		// 		parent_close_disabled(false);
		// 		emptyFormdata(errorField,'#formdata');
		// 		//$('.alert').detach();
		// 		$('.my-alert').detach();
		// 		dialog_chggroup.off();
		// 		dialog_ipdept.off();
		// 		dialog_opdept.off();
		// 		dialog_ipacccode.off();
		// 		dialog_opacccode.off();
		// 		dialog_otcacccode.off();
		// 		dialog_invcategory.off();
		// 		if(oper=='view'){
		// 			$(this).dialog("option", "buttons",butt1);
		// 		}
		// 	},
		// 	buttons :butt1,
		// });
		////////////////////////////////////////end dialog///////////////////////////////////////////

	});