
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
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
			//////////////////////////////////////////////////////////////


			////////////////////object for dialog handler//////////////////

			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,null,
							{
								programmenu:arraybtngrp[arraybtngrp.length-1]
							});
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

			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 6/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
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
					}
					if(oper!='view'){
					}
					if(oper!='add'){
					}
				},
				close: function( event, ui ) {
					resetwhereatID();
					emptyFormdata(errorField,'#formdata');
					$('.alert').detach();
					$("#formdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",butt1);
					}
				},
				buttons :butt1,
			  });
			////////////////////////////////////////end dialog///////////////////////////////////////////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'sysdb.programtab',
				table_id:'idno',
				filterCol:['programmenu'],
				filterVal:['main']
			}

			var saveParam={
				action:'menu_maintenance_save',
				field:'',
				oper: oper,
				table_name:'sysdb.programtab',
				table_id:'idno'
			};

			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{label:'Program Id',name:'programid', width:80},
                    {label:'Program Name',name:'programname', width:300},
                    {label:'Program Type',name:'programtype', width:80,formatter:programtype,unformat:de_programtype},  
                    {label:'lineno',name:'lineno', hidden:true},
                    {label:'url',name:'url', hidden:true},   
                    {label:'remarks',name:'remarks', hidden:true},
                    {label:'condition1',name:'condition1', hidden:true},
                    {label:'condition2',name:'condition2', hidden:true},
                    {label:'condition3',name:'condition3', hidden:true},
                    {label:'bmpid',name:'bmpid', hidden:true},
                    {label:'programmenu',name:'programmenu', hidden:true},
					{label:'idno',name:'idno', hidden:true}
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				sortname:'lineno',
				sortorder:'asc',
				width: 900,
				height: 300,
				rowNum: 30,
				pager: "#jqGridPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					if($("#jqGrid").jqGrid ('getRowData', rowid).programtype!='P'){
						apenddbtngroup($("#jqGrid").jqGrid ('getRowData', rowid));
					}else{
						$("#jqGridPager td[title='Edit Selected Row']").click();
					}
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				
			});
			function programtype(cellvalue, options, rowObject){
				if(cellvalue == 'M'){return 'Menu';}else{return 'Program';}
			}

			function de_programtype(cellvalue, options, rowObject){
				if(cellvalue == 'Menu'){return 'M';}else{return 'P';}
			}


			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					oper='del';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,null,
							{
								programmenu:arraybtngrp[arraybtngrp.length-1],
								lineno:selrowData("#jqGrid").lineno,
								programid:selrowData("#jqGrid").programid,
							});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					$('#formdata .well').hide()
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					$('#formdata .well').hide()
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$('#formdata .well').show()
					$( "#dialogForm" ).dialog( "open" );
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);


			function resetwhereatID(){
				$('#idAfter').html("");
				$('#idAfter').closest('label').hide();
			}
			$('#idAfter').closest('label').hide();
			$('#whereat').change(function(){
				if($(this).val()=='after'){
					var param={
							action:'get_value_default',
							field:['lineno','programname'],
							table_name:'sysdb.programtab',
							filterCol:['programmenu'],
							filterVal:[arraybtngrp[arraybtngrp.length-1]]
						}
					$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data.rows)){
							$.each(data.rows, function( index, value ) {
								$('#idAfter').append("<option value='"+value.lineno+"'>"+value.programname+"</option>");
							});
						}
					});
					$('#idAfter').closest('label').show();
				}else{
					resetwhereatID()
				}
			});

			var arraybtngrp = ['main'];
			function apenddbtngroup(rowdata){
				urlParam.filterVal = [rowdata.programid];
				$("<div class='btn-group' role='group'><button type='button' class='btn btn-default' programid='"+rowdata.programid+"'>"+rowdata.programname+"</button></div>").hide().appendTo('#btngroup').fadeIn(500);

				$( "button[programid = '"+rowdata.programid+"']" ).on( "click",gotobreadcrumb);
				arraybtngrp.push(rowdata.programid);

				refreshGrid("#jqGrid",urlParam);
			}

			$( "button[programid = 'main'" ).on( "click",gotobreadcrumb);
			function gotobreadcrumb(){
				urlParam.filterVal = [$(this).attr('programid')];
				refreshGrid("#jqGrid",urlParam);
				var arrytodel = arraybtngrp.splice(arraybtngrp.indexOf($(this).attr('programid'))+1,arraybtngrp.length);
				arrytodel.forEach(function(element) {
				    $( "button[programid = '"+element+"']" ).closest('div').fadeOut(300, function() { $(this).remove(); });
				});
			}

		});
		