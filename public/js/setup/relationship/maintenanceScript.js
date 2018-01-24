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
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridtime","#dialogForm","#formdata",oper,saveParamtime,urlParamtime,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];

			var buttph=[{
				text: "Save",click: function() {
					if( $('#formdata1').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridph","#dialogForm1","#formdata1",oper,saveParamph,urlParamph,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];
            
            var buttleave=[{
				text: "Save",click: function() {
					if( $('#formdata2').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridleave","#dialogForm2","#formdata2",oper,saveParamleave,urlParamleave,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];


			var butt2=[{
				text: "Close",click: function() {
					$(this).dialog('close');
				}
			}];

			  $("#time").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select doctor');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamtime.filterVal[0] = selrowData('#jqGrid').resourcecode;
	            	
					$("#msgBox").dialog("open");
            	}
            });

			   $("#ph").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select doctor');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamph.filterVal[] = selrowData('#jqGrid').idno;
					$("#phBox").dialog("open");
            	}
            });
			     $("#leave").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select doctor');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamph.filterVal[] = selrowData('#jqGrid').idno;
					$("#leaveBox").dialog("open");
            	}
            });

			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							rdonly("#dialogForm");
							hideOne('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
						case state = 'time':
							$( this ).dialog( "option", "title", "Time Session" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;	
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('#formdata .alert').detach();
					$("#formdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",butt1);
					}
				},
				buttons :butt1,
			  });

			  var oper;
			$("#dialogForm1")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata1');
							rdonly("#dialogForm1");
							hideOne('#formdata1');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata1');
							frozeOnEdit("#dialogForm1");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata1');
							$(this).dialog("option", "buttons",butt2);
							break;
						
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata1');
					$('#formdata1 .alert').detach();
					$("#formdata1 a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttph);
					}
				},
				buttons :buttph,
			  });
               


			  var oper;
			$("#dialogForm2")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata2');
							rdonly("#dialogForm2");
							hideOne('#formdata2');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata2');
							frozeOnEdit("#dialogForm2");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata2');
							$(this).dialog("option", "buttons",butt2);
							break;
						
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata2');
					$('#formdata2 .alert').detach();
					$("#formdata2 a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttleave);
					}
				},
				buttons :buttleave,
			  });

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			
			var urlParam={
				action:'get_table_default',
				url: '/util/get_table_default',
				field:"['resourcecode','description','TYPE']",
				table_name:'hisdb.apptresrc',
				table_id:'idno',
				sort_idno:true,
				filterCol:['TYPE'],
				filterVal:[ $('#Type').val()]
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
                    {label: 'idno', name: 'idno', hidden: true},
					{ label: 'Resource code', name: 'resourcecode', width: 40, classes: 'wrap', canSearch: true, checked:true},						
				    { label: 'Description', name: 'description', width: 40, classes: 'wrap', canSearch: true},
					

					
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

					urlParamtime.filterVal[0] = selrowData('#jqGrid').resourcecode;
					urlParamleave.filterVal[0] = selrowData('#jqGrid').resourcecode;

					// urlParamleave.filterVal[1] = selrowData('#jqGrid').resourcecode;

				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					
				},
			});

			
             

			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refreshGrid("#jqGrid",urlParam);
					},
			// }).jqGrid('navButtonAdd',"#jqGridPager",{
			// 	caption:"",cursor: "pointer",position: "first", 
			// 	buttonicon:"glyphicon glyphicon-time",
			// 	title:"Time Session",  
			// 	onClickButton: function(){
			// 		var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
   //          	if(!selRowId){
   //          		bootbox.alert('Please select doctor');
   //          	}else{

   //          		$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	  //           	$("span[name='description']").text(selrowData('#jqGrid').description);
					
   //          		urlParamtime.filterVal[0] = selrowData('#jqGrid').resourcecode;
			// 		$("#msgBox").dialog("open");
   //          	}
			// 	},
		
					
			// 	}).jqGrid('navButtonAdd',"#jqGridPager",{
			// 	caption:"",cursor: "pointer",position: "first", 
			// 	buttonicon:"glyphicon glyphicon-calendar",
			// 	title:"Public Holiday",  
			// 	onClickButton: function(){
			// 		var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
   //          	if(!selRowId){
   //          		bootbox.alert('Please select doctor');
   //          	}else{

   //          		$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	  //           	$("span[name='description']").text(selrowData('#jqGrid').description);
					
   //          		// urlParamph.filterVal[0] = selrowData('#jqGrid').idno;
			// 		$("#phBox").dialog("open");
   //          	}
			// 	},
		
			// 	}).jqGrid('navButtonAdd',"#jqGridPager",{
			// 	caption:"",cursor: "pointer",position: "first", 
			// 	buttonicon:"glyphicon glyphicon-calendar",
			// 	title:"Leave",  
			// 	onClickButton: function(){
			// 		var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
   //          	if(!selRowId){
   //          		bootbox.alert('Please select doctor');
   //          	}else{

   //          		$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	  //           	$("span[name='description']").text(selrowData('#jqGrid').description);
					
   //          		// urlParamleave.filterVal[0] = selrowData('#jqGrid').resourcecode;
			// 		$("#leaveBox").dialog("open");
   //          	}
			// 	},	
					



			});




			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

			$("#msgBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					addParamField("#gridtime",true,urlParamtime);
					$("#gridtime").jqGrid ('setGridWidth', Math.floor($("#gridtime_c")[0].offsetWidth-$("#gridtime_c")[0].offsetLeft));

				}, 

    //         	buttons: [{
				// 	text: "Add",click: function() {
				// 		// $(this).dialog('close');
				// 		$(this).dialog('close');
				// 		oper='edit';
	   //  				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				// 		populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				// 	}
				// }]
            });

          

            var urlParamtime = {
				action:'get_table_default',
				field:"['doctorcode','days','timefr1','timeto1','timefr2','timeto2']",
				table_name:'hisdb.apptsession',
				table_id:'idno',
				filterCol:['doctorcode'],
				filterVal:[$('#resourcecode').val()],
			}

			var saveParamtime={
				action:'save_table_default',
				field:['doctorcode','days','timefr1','timeto1','timefr2','timeto2'],
				oper:oper,
				table_name:'hisdb.apptsession',
				table_id:'idno'
				
			};

            $("#gridtime").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Resource Code', name: 'doctorcode', classes: 'wrap',hidden:true},
					{label: 'Day', name: 'days', classes: 'wrap'},
					{label: 'Start Time', name: 'timefr1', classes: 'wrap'},
					{label: 'End Time', name: 'timeto1', classes: 'wrap'},
					// {label: '', name: '', classes: 'wrap'},
					{label: 'Start Time', name: 'timefr2', classes: 'wrap'},
					{label: 'End Time', name: 'timeto2', classes: 'wrap'}
					],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 200,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'asc',
				pager: "#gridtimepager",
				onSelectRow:function(rowid, selected){
					$('#resourcecode').val(selrowData('#jqGrid').resourcecode);
					$('#description').val(selrowData('#jqGrid').description);
					
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#gridtime").setSelection($("#gridtime").getDataIDs()[0]);
					}

					$('#'+$("#gridtime").jqGrid ('getGridParam', 'selrow')).focus();
				},
			});

			$("#gridtime").jqGrid('setGroupHeaders', {
            useColSpanStyle: true, 
            groupHeaders:[
	        {startColumnName: 'timefr1', numberOfColumns: 2, titleText: 'Morning Session'},
	        {startColumnName: 'timefr2', numberOfColumns: 4, titleText: 'Evening Session'}
	        
            ]	
            });
           // $("#gridtime").jqGrid('setFrozenColumns');

         //   $("#gridtime").jqGrid('setGroupHeaders', {
         //    useColSpanStyle: false, 
         //    groupHeaders:[
	        // {startColumnName: 'timefr2', numberOfColumns: 4, titleText: 'Evening Session'}
	        
         //    ]	
         //    });
         //   $("#gridtime").jqGrid('setFrozenColumns');


$("#gridtime").jqGrid('navGrid', '#gridtimepager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#gridtime", urlParamtime, oper);
		},
}).jqGrid('navButtonAdd', "#gridtimepager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#gridtime").jqGrid('getGridParam', 'selrow');
			populateFormdata("#gridtime", "#dialogForm", "#formdata", selRowId, 'edit');
			refreshGrid("#gridtime", urlParamtime);
		},

}).jqGrid('navButtonAdd', "#gridtimepager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});
			// gridph //
			$("#phBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					addParamField("#gridph",true,urlParamph);
					$("#gridph").jqGrid ('setGridWidth', Math.floor($("#gridph_c")[0].offsetWidth-$("#gridph_c")[0].offsetLeft));

				}, 

            });

            var urlParamph = {
				action:'get_table_default',
				field:"['YEAR','datefr','dateto','remark']",
				table_name:'hisdb.apptph',
				table_id:'idno',
				// filterCol:['YEAR'],
				// filterVal:[ ''],
			}

			var saveParamph={
				action:'save_table_default',
				field:['YEAR','datefr','dateto','remark'],
				oper:oper,
				table_name:'hisdb.apptph',
				table_id:'idno'
				
			};

            $("#gridph").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Year', name: 'YEAR', classes: 'wrap',hidden:true},
					{label: 'From', name: 'datefr', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
					{label: 'To', name: 'dateto', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
					{label: 'Remark', name: 'remark', classes: 'wrap'},
					
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
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#gridph").setSelection($("#gridph").getDataIDs()[0]);
					}

					$('#'+$("#gridph").jqGrid ('getGridParam', 'selrow')).focus();
				},
			});

			

$("#gridph").jqGrid('navGrid', '#gridphpager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#gridph", urlParamph, oper);
		},
}).jqGrid('navButtonAdd', "#gridphpager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#gridph").jqGrid('getGridParam', 'selrow');
			populateFormdata("#gridph", "#dialogForm1", "#formdata1", selRowId, 'edit');
			$("#formdata1 :input[name='YEAR']").val($("#YEAR").val());
			refreshGrid("#gridph", urlParamph);
		},

}).jqGrid('navButtonAdd', "#gridphpager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm1").dialog("open");
			$("#formdata1 :input[name='YEAR']").val($("#YEAR").val());
		},
	});

                               // gridleave //
			$("#leaveBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					addParamField("#gridleave",true,urlParamleave);
					$("#gridleave").jqGrid ('setGridWidth', Math.floor($("#gridleave_c")[0].offsetWidth-$("#gridleave_c")[0].offsetLeft));

				}, 

            });

            var urlParamleave = {
				action:'get_table_default',
				field:"['YEAR','datefr','dateto','remark','resourcecode']",
				table_name:'hisdb.apptleave',
				table_id:'idno',
				filterCol:['resourcecode'],
				filterVal:[''],
			}

			var saveParamleave={
				action:'save_table_default',
				field:['YEAR','datefr','dateto','remark','resourcecode'],
				oper:oper,
				table_name:'hisdb.apptleave',
				table_id:'idno'
				
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
					$("#jqGridPager td[title='Edit Selected Row']").click();
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
}).jqGrid('navButtonAdd', "#gridleavepager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#gridleave").jqGrid('getGridParam', 'selrow');
			populateFormdata("#gridleave", "#dialogForm2", "#formdata2", selRowId, 'edit');
			$("#formdata2 :input[name='YEAR']").val($("#YEAR").val());
			refreshGrid("#gridleave", urlParamleave);
		},

}).jqGrid('navButtonAdd', "#gridleavepager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm2").dialog("open");
			$("#formdata2 :input[name='YEAR']").val($("#YEAR").val());
		},
	});
   
  
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#gridtime',true,urlParamtime);
			addParamField('#gridph',true,urlParamph);
			addParamField('#gridleave',true,urlParamleave);
				///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
	YEAR(urlParam)
	function YEAR(urlParam) {
		var param = {
			action: 'get_value_default',
			field: ['YEAR'],
			table_name: 'hisdb.apptph',
			filterCol: ['YEAR'],
			filterVal: ['']
		}
		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data)) {
				$.each(data.rows, function (index, value) {
					if (value.YEAR.toUpperCase() == $("#YEAR").val().toUpperCase()) {
						$("#searchForm [id=YEAR]").append("<option selected value='" + value.YEAR + "'>" + value.YEAR + "</option>");
					} else {
						$("#searchForm [id=YEAR]").append(" <option value='" + value.YEAR + "'>" + value.YEAR + "</option>");
					}
				});
			}
		});
	}
	
	// $('#YEAR').on('change', searchChange);
			
		});
		