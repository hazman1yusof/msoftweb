		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
			
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
			var Type = $('#Type').val();

			if(Type =='DOC') {
	             $('#TSBtn').show();
	             $("#ALBtn").html('<span class = "fa fa-calendar fa-lg"></span> Leave').show();
	             $('#PHBtn').show();

	           
	        }else {
	             $('#TSBtn').hide();
	             $("#ALBtn").html('<span class = "fa fa-calendar fa-lg"></span> Unavailable').show();
	             $('#PHBtn').show();
            }

			////////////////////////////////////start dialog///////////////////////////////////////
			var tsbtn=[{
				text: "Save",click: function() {
					if( $('#tsformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridtime","#tsdialogForm","#tsformdata",oper,saveParamtime,urlParamtime,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];

			var phbtn=[{
				text: "Save",click: function() {
					if( $('#phformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridph","#phdialogForm","#phformdata",oper,saveParamph,urlParamph);
						
					}
					// checkDate();
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];
            
            var albtn=[{
				text: "Save",click: function() {
					if( $('#alformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridleave","#aldialogForm","#alformdata",oper,saveParamleave,urlParamleave,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];
            
            var rscbtn=[{
				text: "Save",click: function() {
					if( $('#resourceformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#jqGrid","#resourceAddform","#resourceformdata",oper,saveParam,urlParam,null);
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];

			var btnclose=[{
				text: "Close",click: function() {
					$(this).dialog('close');
				}
			}];

			  $("#TSBtn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select row');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamtime.filterVal[0] = selrowData('#jqGrid').resourcecode;
	            	
					$("#TSBox").dialog("open");
            	}
            });

			   $("#PHBtn").click(function(){
            	// var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	// if(!selRowId){
            	// 	alert('Please select row');
            	// }else{
	            // 	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            // 	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamph.filterVal[] = selrowData('#jqGrid').idno;
					$("#PHBox").dialog("open");
            	// }
            });
			
			$("#ALBtn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select row');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamph.filterVal[] = selrowData('#jqGrid').idno;
					$("#ALBox").dialog("open");
            	}
            });

	        $("#allTimeBtn").click(function(){
	          	// var allTimeBtn = $("#time1").val();
	          	$("#gridtime :input[name='timefr1']").val($("#time1").val());
	          	$("#gridtime :input[name='timeto1']").val($("#time2").val());
	          	$("#gridtime :input[name='timefr2']").val($("#time3").val());
	          	$("#gridtime :input[name='timeto2']").val($("#time4").val());
	          	$("#gridtime :input[name='status']").prop('checked', true);
	        });

			var oper;
			$("#tsdialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#tsformdata');
							rdonly("#tsdialogForm");
							hideOne('#tsformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#tsformdata');
							frozeOnEdit("#tsdialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#tsformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#tsformdata');
					$('#tsformdata .alert').detach();
					$("#tsformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",tsbtn);
					}
				},
				buttons :tsbtn,
			});

			var oper;
			$("#phdialogForm")
			  .dialog({ 
				width: 8/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#phformdata');
							rdonly("#phdialogForm");
							hideOne('#phformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#phformdata');
							frozeOnEdit("#phdialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#phformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
						
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#phformdata');
					$('#phformdata .alert').detach();
					$("#phformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",phbtn);
					}
				},
				buttons :phbtn,
			  });
               


			var oper = 'add';
			$("#aldialogForm")
			  .dialog({ 
				width: 8/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#alformdata');
							rdonly("#aldialogForm");
							hideOne('#alformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#alformdata');
							frozeOnEdit("#aldialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#alformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
						
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#alformdata');
					$('#alformdata .alert').detach();
					$("#alformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",albtn);
					}
				},
				buttons :albtn,
			  });

			  var oper;
			$("#resourceAddform")
			  .dialog({ 
				width: 8/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#resourceformdata');
							rdonly("#resourceAddform");
							hideOne('#resourceformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#resourceformdata');
							frozeOnEdit("#resourceAddform");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#resourceformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#resourceformdata');
					$('#resourceformdata .alert').detach();
					$("#resourceformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",rscbtn);
					}
				},
				buttons :rscbtn,
			  });

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			
			var urlParam={
				action:'get_table_default',
				url: 'util/get_table_default',
				field:['resourcecode','description','TYPE'],
				table_name:'hisdb.apptresrc',
				table_id:'idno',
				sort_idno:true,
				filterCol:['TYPE'],
				filterVal:[ $('#Type').val()]
			}

			if(Type =='DOC'){
				urlParam.url = "doctor_maintenance/table";
			}
            var saveParam={
				action:'save_table_default',
				url:"doctor_maintenance/form",
				field:['resourcecode','description','TYPE'],
				oper:oper,
				table_name:'hisdb.apptresrc',
				table_id:'resourcecode'
				
			};
			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
                    { label: 'idno', name: 'idno', hidden: true},
					{ label: 'Code', name: 'resourcecode', width: 40, classes: 'wrap', canSearch: true},						
				    { label: 'Description', name: 'description', width: 40, classes: 'wrap', canSearch: true, checked:true},
				    { label: 'Type', name: 'TYPE', width: 40, classes: 'wrap', hidden:true},
				    { label: 'session', name: 'countsession', width: 40, classes: 'wrap', hidden:true},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				height: 250,
				//width: 100,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){

					 // $('#doctorcode').val(selrowData('#jqGrid').resourcecode);
					// $('#description').val(selrowData('#jqGrid').description);

					$('#doctorcode').val(selrowData('#jqGrid').resourcecode);
					urlParamtime.filterVal[0] = selrowData('#jqGrid').resourcecode;
					urlParamleave.filterVal[0] = selrowData('#jqGrid').resourcecode;

					// urlParamleave.filterVal[1] = selrowData('#jqGrid').resourcecode;

				},
				ondblClickRow: function(rowid, iRow, iCol, e){

					if(Type !='DOC'){
						$("#jqGridPager td[title='Edit Selected Row']").click();
					}

				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
					$("#searchForm input[name=Stext]").focus();

					if(Type =='DOC'){
						$("#pg_jqGridPager td[title='Add New Row']").hide();
						$("#pg_jqGridPager td[title='Edit Selected Row']").hide();
					}
				},
			});

			
             

			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
			{	
				edit:false,view:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam,oper);
				}

			}).jqGrid('navButtonAdd', "#jqGridPager", {
				caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
				buttonicon: "glyphicon glyphicon-edit",
				title: "Edit Selected Row",
				onClickButton: function () {
					oper = 'edit';
					selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
					populateFormdata("#jqGrid", "#resourceAddform", "#resourceformdata", selRowId, 'edit');
					refreshGrid("#jqGrid", urlParam);
				},

			}).jqGrid('navButtonAdd', "#jqGridPager", {
			    caption: "", cursor: "pointer", position: "first",
			    buttonicon: "glyphicon glyphicon-plus",
			    id: 'glyphicon-plus',
			    title: "Add New Row",
			    onClickButton: function () {
					oper = 'add';
					$("#resourceAddform").dialog("open");
				},
			});

			////////////////////////////formatter//////////////////////////////////////////////////////////
			function formatter(cellvalue, options, rowObject) {
				if (cellvalue == 'A') {
					return "Active";
				}
				if (cellvalue == 'D') {
					return "Deactive";
				}
			}

			function unformat(cellvalue, options) {
				if (cellvalue == 'Active') {
					return "Active";
				}
				if (cellvalue == 'Deactive') {
					return "Deactive";
				}
			}
			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

			var TSoper = 'add';
			$("#TSBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					if(parseInt(selrowData('#jqGrid').countsession)==0){
						TSoper = 'add';
						['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'].forEach(function(elem,id) {
							$("#gridtime").jqGrid('addRowData', id,
								{
									idno:id,
									doctorcode:$('#doctorcode').val(),
									days:elem,
									timefr1:'',
									timeto1:'',
									timefr2:'',
									timeto2:'',
									status:'False',
								}
							);
						});
					}else{
						TSoper = 'edit';
						addParamField("#gridtime",true,urlParamtime);
					}
					$("#gridtime").jqGrid ('setGridWidth', Math.floor($("#gridtime_c")[0].offsetWidth-$("#gridtime_c")[0].offsetLeft));
				},
				close:function(){
					$("#gridtime").jqGrid("clearGridData", true);
				}
            });
          

            var urlParamtime = {
				action:'get_table_default',
				url: '/util/get_table_default',
				field:"['doctorcode','days','timefr1','timeto1','timefr2','timeto2','status']",
				table_name:'hisdb.apptsession',
				table_id:'idno',
				filterCol:['doctorcode'],
				filterVal:[$('#resourcecode').val()],
				// filterVal:[''],
			}

			var saveParamtime={
				action:'save_table_default',
				url:"/doctor_maintenance/form",
				field:['doctorcode','days','timefr1','timeto1','timefr2','timeto2','status'],
				oper:oper,
				table_name:'hisdb.apptsession',
				table_id:'idno',
				noduplicate:true,
				
			};

			function timefr1CustomEdit(val,opt){
				// val = (val=="undefined"||val=="")? "--:--" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function timeto1CustomEdit(val,opt){  	
				// val = (val=="undefined"||val=="")? "--:--" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function timefr2CustomEdit(val,opt){  	
				// val = (val=="undefined"||val=="")? "--:--" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function timeto2CustomEdit(val,opt){  	
				// val = (val=="undefined"||val=="")? "--:--" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function galGridCustomValue (elem, operation, value){
				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}
			
			$("#tsbutton").click(function(){
				var rowsArray=[];
				$("#gridtime").getDataIDs().forEach(function(elem,id) {
					let objRow = $("#gridtime").jqGrid ('getRowData', elem);
					objRow.timefr1 = $("#"+elem+"_timefr1").val();
					objRow.timeto1 = $("#"+elem+"_timeto1").val();
					objRow.timefr2 = $("#"+elem+"_timefr2").val();
					objRow.timeto2 = $("#"+elem+"_timeto2").val();
					rowsArray.push(objRow);
				});
				$.post( "/doctor_maintenance/save_session", {rowsArray:rowsArray,_token:$('#csrf_token').val(),oper:TSoper} , function( data ) {
		
				}).success(function(data){
					$("#TSBox").dialog('close');
					refreshGrid("#jqGrid",urlParam,'edit');
				});
			});

            $("#gridtime").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Resource Code', name: 'doctorcode', classes: 'wrap',hidden:true},
					{label: 'Day', name: 'days', classes: 'wrap'},
					{label: 'Start Time', name: 'timefr1', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						    	custom_element:timefr1CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					},
					{label: 'End Time', name: 'timeto1', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						        custom_element:timeto1CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					},
					{label: 'Start Time', name: 'timefr2', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						        custom_element:timefr2CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					},
					{label: 'End Time', name: 'timeto2', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						        custom_element:timeto2CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					},
					{label:'Status',name: 'status', edittype:'checkbox',formatter:'checkbox',editable:true, 
					editoptions:
					{
                           value:'True:False',
                           defaultValue:'False'
					},
					}
				
				],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 380,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'asc',
				pager: "#gridtimepager",
				onSelectRow:function(rowid, selected){
					$('#resourcecode').val(selrowData('#jqGrid').resourcecode);
					$('#doctorcode').val(selrowData('#jqGrid').doctorcode);
					$('#description').val(selrowData('#jqGrid').description);
					
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					// $("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					var $this = $(this), rows = this.rows, l = rows.length, i, row;
				    for (i = 0; i < l; i++) {
				        row = rows[i];
				        if ($.inArray('jqgrow', row.className.split(' ')) >= 0) {
				            $this.jqGrid('editRow', row.id, true);
				        }
				    }
				},
			});

			$("#gridtime").jqGrid('setGroupHeaders', {
	            useColSpanStyle: true, 
	            groupHeaders:[
			        {startColumnName: 'timefr1', numberOfColumns: 2, titleText: 'Morning Session'},
			        {startColumnName: 'timefr2', numberOfColumns: 2, titleText: 'Evening Session'}
  
	            ]	
            });

			$("#gridtime").jqGrid('navGrid', '#gridtimepager', {
				view: false, edit: false, add: false, del: false, search: false, refresh:false,
				beforeRefresh: function () {
					refreshGrid("#gridtime", urlParamtime, oper);
				},
			});


			// gridph //
			$("#PHBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					// addParamField("#gridph",true,urlParamph);
					$("#gridph").jqGrid ('setGridWidth', Math.floor($("#gridph_c")[0].offsetWidth-$("#gridph_c")[0].offsetLeft));

				}, 

            });

            var urlParamph = {
				action:'get_table_default',
				url: '/util/get_table_default',
				field:['apptph.idno','apptph.YEAR','apptph.datefr','apptph.dateto','apptph.remark','apptphcolor.color as backgroundcolor','apptph.backgroundcolor as colorpicker'],
				table_name:['hisdb.apptph','hisdb.apptphcolor'],
				join_type:['LEFT JOIN'],
				join_onCol:['apptph.idno'],
				join_onVal:['apptphcolor.phidno'],
				table_id:'idno',
				filterCol:['apptph.recstatus'],
				filterVal:['A'],
				
			}

			var saveParamph={
				action:'save_table_default',
				url:"/doctor_maintenance/form",
				field:['YEAR','datefr','dateto','remark'],
				oper:oper,
				table_name:'hisdb.apptph',
				table_id:'idno',
				noduplicate:true,
			};

            $("#gridph").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Year', name: 'YEAR', classes: 'wrap',hidden:true,canSearch:true,checked:true},
					{label: 'From', name: 'datefr', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
					{label: 'To', name: 'dateto', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
					{label: 'Remark', name: 'remark', classes: 'wrap',},
					{label: 'Color', name: 'backgroundcolor', classes: 'wrap',hidden:true},
					{label: 'colorpicker', name: 'colorpicker', width: 50, classes: 'wrap', formatter: formatterColorpicker, unformat: unformatColorpicker},	
				],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 200,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'desc',
				pager: "#gridphpager",
				onSelectRow:function(rowid, selected){
					$('#resourcecode').val(selrowData('#jqGrid').resourcecode);
					$('#description').val(selrowData('#jqGrid').description);
					
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#gridphpager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(iRow){ 
					// $("#gridph").jqGrid('setCell',"1","remark","",{'background-color':'yellow'});
					setColor();

					// var rows = $("#gridph").getDataIDs(); 
     //            	for (var i = 0; i < rows.length; i++)
     //               	{
     //              		var backgroundcolor =$("#gridph").getCell(rows[i],"backgroundcolor");
     //             		$("#gridph").jqGrid('setRowData',rows[i],false, {background:backgroundcolor});
					// }

					if(oper == 'add'){
						$("#gridph").setSelection($("#gridph").getDataIDs()[0]);
					}

					$('#'+$("#gridph").jqGrid ('getGridParam', 'selrow')).focus();

					$(".colorpointer").click(function(){
						var idno = $(this).data('column');
						$('#dp_'+idno).click();
					});

					$('.bg_color').change(function(){
						var idno = $(this).data('column');
						var color = $(this).val();
						$('#pt_'+idno).css('background-color',color);
						save_colorph(color,idno);
					});
				},
			});

			function setColor(){
				$('.bg_color').each(function(){
					var idno = $(this).data('column');
					$('#pt_'+idno).css('background-color',$(this).val());
				});
			}

			function formatterColorpicker(cellvalue, options, rowObject){
				var idno = rowObject.idno;
				var color = rowObject.backgroundcolor;
				return `
		  				<span style="cursor: pointer;display:inline-block;border: 1px solid black;" class="colorpointer" id='pt_`+idno+`' data-column='`+idno+`'>
							<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
						</span>
		  				<input type='color' id='dp_`+idno+`' data-column="`+idno+`" class="form-control input-sm bg_color" value="`+color+`" style="display: none;">
		  				`;
			}

			function unformatColorpicker(cellvalue, options, rowObject){
				return null;
			}

			function save_colorph(color,idno){
				$.post( "/doctor_maintenance/save_colorph", {color:color,_token:$('#csrf_token').val(),phidno:idno} , function( data ) {
				}).success(function(data){

				});
			}
		

			$("#gridph").jqGrid('navGrid', '#gridphpager', {
				view: false, edit: false, add: false, del: false, search: false,
				beforeRefresh: function () {
					refreshGrid("#gridph", urlParamph, oper);
				},
			}).jqGrid('navButtonAdd',"#gridphpager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					oper = 'del';
			        selRowId = $("#gridph").jqGrid('getGridParam', 'selrow');
			        if (!selRowId) {
					    alert('Please select row');
						return emptyFormdata(errorField, '#phformdata');
					} else {
						saveFormdata("#gridph", "#phdialogForm", "#phformdata", 'del', saveParamph, urlParamph, null,  { 'idno': selrowData('#gridph').idno });
					}
				},
			}).jqGrid('navButtonAdd', "#gridphpager", {
					caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
					buttonicon: "glyphicon glyphicon-edit",
					title: "Edit Selected Row",
					onClickButton: function () {
						oper = 'edit';
						selRowId = $("#gridph").jqGrid('getGridParam', 'selrow');
						populateFormdata("#gridph", "#phdialogForm", "#phformdata", selRowId, 'edit');
						$("#phformdata :input[name='YEAR']").val($("#YEAR").val());
						refreshGrid("#gridph", urlParamph);
					},

			}).jqGrid('navButtonAdd', "#gridphpager", {
					caption: "", cursor: "pointer", position: "first",
					buttonicon: "glyphicon glyphicon-plus",
					id: 'glyphicon-plus',
					title: "Add New Row",
					onClickButton: function () {
						oper = 'add';
						$("#phdialogForm").dialog("open");
						$("#phformdata :input[name='YEAR']").val($("#YEAR").val());
					},
			});

           
            // gridleave //
			$("#ALBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					addParamField("#gridleave",true,urlParamleave);
					$("#gridleave").jqGrid ('setGridWidth', Math.floor($("#gridleave_c")[0].offsetWidth-$("#gridleave_c")[0].offsetLeft));
					load_bg_leave();
				}, 

            });

            var urlParamleave = {
				action:'get_table_default',
				url: '/util/get_table_default',
				field:"['YEAR','datefr','dateto','remark','resourcecode']",
				table_name:'hisdb.apptleave',
				table_id:'idno',
				filterCol:['resourcecode','recstatus'],
				filterVal:['','A'],
			}

			var saveParamleave={
				action:'save_table_default',
				url:"/doctor_maintenance/form",
				field:['YEAR','datefr','dateto','remark','resourcecode'],
				oper:oper,
				table_name:'hisdb.apptleave',
				table_id:'idno',
				noduplicate:true,
			};

            $("#gridleave").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Year', name: 'YEAR', classes: 'wrap',hidden:true},
					{label: 'Date From', name: 'datefr', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
					{label: 'Date To', name: 'dateto', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
					{label: 'Remark', name: 'remark', classes: 'wrap'},
					{label: 'resourcecode', name: 'resourcecode', classes: 'wrap',hidden:true},
				],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 200,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'desc',
				pager: "#gridleavepager",
				onSelectRow:function(rowid, selected){
					$('#resourcecode').val(selrowData('#jqGrid').resourcecode);
					$('#description').val(selrowData('#jqGrid').description);
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#gridleavepager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#gridleave").setSelection($("#gridleave").getDataIDs()[0]);
					}

					$('#'+$("#gridleave").jqGrid ('getGridParam', 'selrow')).focus();
				},
			});

			

			$("#gridleave").jqGrid('navGrid', '#gridleavepager', {
					view: false, edit: false, add: false, del: false, search: false,
					beforeRefresh: function () {
						refreshGrid("#gridleave", urlParamleave, oper);
					},
				}).jqGrid('navButtonAdd',"#gridleavepager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
				    oper = 'del';
			        selRowId = $("#gridleave").jqGrid('getGridParam', 'selrow');
			        if (!selRowId) {
				    alert('Please select row');
				    return emptyFormdata(errorField, '#alformdata');
			       	} else {
				    	saveFormdata("#gridleave", "#alformdata", "#alformdata", 'del', saveParamleave, urlParamleave, null,  { 'idno': selrowData('#gridleave').idno });
					}
				},
				}).jqGrid('navButtonAdd', "#gridleavepager", {
						caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
						buttonicon: "glyphicon glyphicon-edit",
						title: "Edit Selected Row",
						onClickButton: function () {
							oper = 'edit';
							selRowId = $("#gridleave").jqGrid('getGridParam', 'selrow');
							populateFormdata("#gridleave", "#aldialogForm", "#alformdata", selRowId, 'edit');
							$("#alformdata :input[name='YEAR']").val($("#YEAR").val());
							refreshGrid("#gridleave", urlParamleave);
						},

				}).jqGrid('navButtonAdd', "#gridleavepager", {
						caption: "", cursor: "pointer", position: "first",
						buttonicon: "glyphicon glyphicon-plus",
						id: 'glyphicon-plus',
						title: "Add New Row",
						onClickButton: function () {
							oper = 'add';
							$("#aldialogForm").dialog("open");
							$("#alformdata :input[name='YEAR']").val($("#YEAR").val());
						},
				});
   


			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			toogleSearch('#sbut1','#searchForm1','on');
			populateSelect('#gridph','#searchForm1');
			searchClick('#gridph','#searchForm1',urlParamph);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			// addParamField('#jqGrid',true,urlParam);
			refreshGrid("#jqGrid",urlParam);
			addParamField('#gridtime',true,urlParamtime);
			// addParamField('#gridph',true,urlParamph);
			refreshGrid("#gridph",urlParamph);
			addParamField('#gridleave',true,urlParamleave);

			 
			 /////////////////// FUNCTION DATE /////////////////////////////////////////////////////////////////////

	            $('#datefr,#dateto').datetimepicker({
	            	format: 'YYYY-MM-DD',
	                useCurrent: false,
	                // minDate: moment()
	            });
	            $('#datefr').datetimepicker().on('dp.change', function (e) {
	                var incrementDay = moment();
	                incrementDay.add(0, 'days');
	                // $('#dateto').data('DateTimePicker').minDate(incrementDay);
	                $(this).data("DateTimePicker").hide();
	            });

	            $('#dateto').datetimepicker().on('dp.change', function (e) {
	                var decrementDay = moment();
	                decrementDay.subtract(0, 'days');
	                // $('#datefr').data('DateTimePicker').maxDate(decrementDay);
	                $(this).data("DateTimePicker").hide();
	            });

		function savecolor(){
			var bg_leave = $('#bg_leave').val();
			$.post( "/doctor_maintenance/save_bgleave", {bg_leave:bg_leave,_token:$('#csrf_token').val()} , function( data ) {
		
			}).success(function(data){

			});
		}

		function load_bg_leave(){
			var urlParam={
				action:'load_bg_leave',
				url: '/util/get_value_default',
				field:['pvalue1'],
				table_name:'sysdb.sysparam',
				filterCol:['source','trantype'],
				filterVal:['HIS','ALCOLOR']
			}

			$.get( "util/get_value_default"+"?"+$.param(urlParam), function( data ) {
			
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					$('#bg_leave').val(data.rows[0].pvalue1);

					$('#imgid_leave').css('border-bottom-color',data.rows[0].pvalue1);
				}
			});
		}

		$("#colorpointer").click(function(){
			$('#bg_leave').click();
		});

		$('#bg_leave').change(function(){
			$('#imgid_leave').css('border-bottom-color',$(this).val());
			savecolor();
		});
		$("#bg_leave").hide();

	});
		