
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

		////////////////////////////////pass parameter in url////////////////////////////////

		////////////////////////////////one////////////////////////////////

		//url: http://msoftweb.test/nursing?name_show=SAFIAH%20MD%20SALLEH&newic_show=430307015232&sex_show=F&age_show=20&race_show=MALAY
		
		function getQueryVariable(variable) {
			var query = window.location.search.substring(1);
			var parms = query.split('&');
			for (var i = 0; i < parms.length; i++) {
				var pos = parms[i].indexOf('=');
				if (pos > 0 && variable == parms[i].substring(0, pos)) {
					return parms[i].substring(pos + 1);;
				}
			}
			return "";
		}

		getQueryVariable("name_show, newic_show, sex_show, age_show, race_show");

		$(function () {
			$('#name_show').text(getQueryVariable('name_show'))
			$('#newic_show').text(getQueryVariable('newic_show'))
			$('#sex_show').text(getQueryVariable('sex_show'))
			$('#age_show').text(getQueryVariable('age_show'))
			$('#race_show').text(getQueryVariable('race_show'))
		});

		////////////////////////////////two////////////////////////////////

		// var url = "http://msoftweb.test/nursing?name_show=ABC"
		// var name = url.substring(url.indexOf("=") + 1);  

		// var parent = document.getElementById("name_show");
		// var input = document.createElement("SPAN");
		// input.value = name;
		// parent.appendChild(input)

		////////////////////////////////three////////////////////////////////

		// function getParams() {
		// 	var idx = document.URL.indexOf('?');
		// 	var params = new Array();
		// 	if (idx != -1) {
		// 		var pairs = document.URL.substring(idx+1, document.URL.length).split('&');
		// 		for (var i=0; i<pairs.length; i++) {
		// 			nameVal = pairs[i].split('=');
		// 			params[nameVal[0]] = nameVal[1];
		// 	   	}
		// 	}
		// 	return params;
		// }
		// params = getParams();

		// var para1=document.getElementById('name_show');
		// var para2=document.getElementById('newic_show');

		// para1.value = unescape(params["var1"]);
		// para2.value = unescape(params["var2"]);
				
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