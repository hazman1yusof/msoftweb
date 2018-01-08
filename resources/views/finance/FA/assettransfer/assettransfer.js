		
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
		//////////////////////////////////////////////////////////////

		/////////////////Object for Dialog Handler////////////////////////////////////////////////////

				//department
				dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Department');
				//location
				dialog_loccode=new makeDialog('sysdb.location','#loccode',['loccode','description'],'Location');
		
		////////////////////////////////////start dialog///////////////////////////////////////
		
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,null,{idno:selrowData('#jqGrid').idno});

						saveOnly(saveParam2,
						{
							'deptcode' : $("#deptcode").val(),
							'olddeptcode' : $("#currdeptcode").val(),
							'trantype' : 'TRF',
							'trandate' : $("#trandate").val(),
							'curloccode' : $("#loccode").val(),
							'oldloccode' : $("#currloccode").val(),
							'assetno' : $("#assetno").val(),
							'assetcode' : $("#assetcode").val(),
							'assettype' : $("#assettype").val()

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
							$("#loccode").val('');
							$("#deptcode").val('');
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						dialog_deptcode.handler(errorField);
						dialog_loccode.handler(errorField);
					}
					if(oper!='add'){
						//dialog_deptcode.check(errorField);
						//dialog_loccode.check(errorField);
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					//$('.alert').detach();
					$('#formdata .alert').detach();
					$("#formdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",butt1);
					}
				},
				buttons :butt1,
			  });
			////////////////////////////////////////end dialog///////////////////////////////////////////
			var actdateObj = new setactdate(["#trandate"]);
			actdateObj.getdata().set();
			function setactdate(target){
				this.actdateopen=[];
				this.target=target;
				this.param={
					action:'get_value_default',
					field: ['*'],
					table_name:'sysdb.period',
					table_id:'idno'
				}

				this.getdata = function(){
					var self=this;
					$.get( "../../../../assets/php/entry.php?"+$.param(this.param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data.rows)){
							data.rows.forEach(function(element){	
								$.each(element, function( index, value ) {
									if(index.match('periodstatus') && value == 'O'){
										self.actdateopen.push({
											from:element["datefr"+index.match(/\d+/)[0]],
											to:element["dateto"+index.match(/\d+/)[0]]
										})
									}
								});
							});
						}
					});
					return this;
				}

				this.set = function(){
					console.log(this.actdateopen);
					this.target.forEach(function(element){
						$(element).on('change',validate_actdate);
					});
				}

				function validate_actdate(obj){
					var permission = false;
					actdateObj.actdateopen.forEach(function(element){
						if(moment(obj.target.value).isBetween(element.from,element.to)){
							permission=true
						}else{
							(permission)?permission=true:permission=false;
						}
					});
					if(!permission){
						bootbox.alert('Transfer Date Has been Closed');
						$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
					}
				}
			}
			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'finance.faregister',
				table_id:'idno',
				sort_idno:true,
				filterCol:['recstatus'],
				filterVal:['A'],
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:['deptcode','loccode','trandate'],
				oper:oper,
				table_name:'finance.faregister',
				table_id:'idno'
			};

			var saveParam2={
				action:'save_table_default',
				field:['auditno','deptcode','olddeptcode','trantype','trandate','curloccode','oldloccode','assetno','assetcode','assettype'],
				oper:'add',
				table_name:'finance.fatran',
				table_id:'auditno',
				sysparam: {source: 'FA', trantype: 'TRF', useOn: 'auditno'},
			};

			$("#jqGrid").jqGrid({
				datatype: "local",	
				 colModel: [
				 	{label: 'Tagging No', name: 'assetno', width: 10, canSearch: true, checked: true},
                    {label: 'Item Code', name:'itemcode', width: 20, },
                    {label: 'Category', name: 'assetcode', width: 20, classes: 'wrap', canSearch: true,checked:true},
                    {label: 'Type', name:'assettype', width: 20, classes: 'wrap', canSearch: true, checked:true},
                    {label: 'Department', name:'deptcode', width: 20, },
                    {label: 'Location', name:'loccode', width: 20, classes: 'wrap',},
                    {label: 'Description', name:'description', width: 40, classes: 'wrap'},
                    {label: 'idno', name: 'idno', hidden: true},
                    {label: 'Transfer Date', name:'trandate', formatter:dateFormatter, hidden:true},
                    { label: 'Add User', name:'adduser', width:20, classes:'wrap',  hidden:true},
					{ label: 'Add Date', name:'adddate', width:20, classes:'wrap',  hidden:true},
                    ],
                autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
		        multiselect:false,
				pager: "#jqGridPager",
				onSelectRow: function(){
					$('#currdeptcode').val(selrowData('#jqGrid').deptcode);
					$('#currloccode').val(selrowData('#jqGrid').loccode);
					$('#assetno').val(selrowData('#jqGrid').assetno);
					$('#description').val(selrowData('#jqGrid').description);
					$('#assetcode').val(selrowData('#jqGrid').assetcode);
					$('#assettype').val(selrowData('#jqGrid').assettype);
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

			////////////////////////////////////////////////////////////////////////////////////////


 			//////////////////////formatter//////////////////////////////////////////////////////////
			function formatter(cellvalue, options, rowObject){
				if(cellvalue == 'A'){
					return "Active";
				}
				if(cellvalue == 'D') { 
					return "Deactive";
				}
			}

			function  unformat(cellvalue, options){
				if(cellvalue == 'Active'){
					return "A";
				}
				if(cellvalue == 'Deactive') { 
					return "D";
				}
			}

			////////////////////////////// DATE FORMATTER ////////////////////////////////////////

			function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("DD-MM-YYYY");
			}

			////////////////////////////////////////////////////////////////////////////////////////

            ////////////////////////////////////////////////////////////////////////////////////////
            $("#msgBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 3/10 * $(window).width(),
            	buttons: [{
					text: "OK",click: function() {
						$(this).dialog('close');
						oper='edit';
	    				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
	    				$('#trandateNew').attr('max', moment().format('D-M-YYYY'));
						populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
					}
				},{
					text: "Cancel",click: function() {
						$(this).dialog('close');
					}
				}]
            });

            $("#transferButn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select row');
            	}else{
	            	$("span[name='itemcode']").text(selrowData('#jqGrid').itemcode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
	            	
					$("#msgBox").dialog("open");
            	}
            });
            /////////////////////////////////////////////////////////////////////////////////////////////////
    //        
			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
				
			//}).jqGrid('navButtonAdd',"#jqGridPager",{
			//	caption:"",cursor: "pointer",position: "first", 
				//buttonicon:"glyphicon glyphicon-trash",
				//title:"Delete Selected Row",
				//onClickButton: function(){
				//	oper='del';
					//selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					//if(!selRowId){
						//alert('Please select row');
						//return emptyFormdata(errorField,'#formdata');
					//}else{
						//saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'':selRowId});
					//}
				//},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
				},

				
			//}).jqGrid('navButtonAdd',"#jqGridPager",{
				//caption:"",cursor: "pointer",position: "first",  
				//buttonicon:"glyphicon glyphicon-edit",
				//title:"Edit Selected Row",  
				//onClickButton: function(){
					//oper='edit';
					//selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					//populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				//}, 
			//}).jqGrid('navButtonAdd',"#jqGridPager",{
			//	caption:"",cursor: "pointer",position: "first",  
				//buttonicon:"glyphicon glyphicon-plus", 
				//title:"Add New Row", 
				//onClickButton: function(){
					//oper='add';
					//$( "#dialogForm" ).dialog( "open" );
				//},

				
			});

  

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);

			function saveOnly(saveParam,obj){
				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $.param(obj));
			}

			///////////////////////////////start->dialogHandler part////////////////////////////////////////////
			function makeDialog(table,id,cols,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.title=title;
				this.handler=dialogHandler;
				this.check=checkInput;
			}

			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
				open: function(){
					$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));
				},
				close: function( event, ui ){
					paramD.searchCol=null;
					paramD.searchVal=null;
				},
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 200,  classes: 'pointer', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'desc', width: 400, canSearch:true, classes: 'pointer'},
				],
				width: 500,
				autowidth: true,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );

					if(selText == "#deptcode" && data['code'] == $("#currdeptcode").val()){
						bootbox.alert('Department Code cannot be the same as current department code');
					}else if(selText == "#loccode" && data['code'] == $("#currloccode").val()){
						bootbox.alert('Location Code cannot be the same as current location code');
					}else{

						$(selText).val(rowid);
						$(selText).focus();
						$(selText).parent().next().html(data['desc']);
					}
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
						if(value['canSearch']){
							if(value['checked']){
								$( "#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+value['label']+"</input></label>" );
							}else{
								$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+value['label']+"</input></label>" );
							}
						}
					});
				});
				$(id).on("blur", function(){
					self.check(errorField);
				});
			}
			
			function checkInput(errorField){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						if($.inArray(id,errorField)!==-1){
							errorField.splice($.inArray(id,errorField), 1);
						}
						$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
						$( id ).removeClass( "error" ).addClass( "valid" );
						$( id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
					}else if(data.msg=='fail'){
						$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( id ).removeClass( "valid" ).addClass( "error" );
						$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
						if($.inArray(id,errorField)===-1){
							errorField.push(id);
						}
					}
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
			});
			
			function Dsearch(Dtext,Dcol){
				paramD.searchCol=null;
				paramD.searchVal=null;
				Dtext=Dtext.trim();
				if(Dtext != ''){
					var split = Dtext.split(" "),searchCol=[],searchVal=[];
					$.each(split, function( index, value ) {
						searchCol.push(Dcol);
						searchVal.push('%'+value+'%');
					});
					paramD.searchCol=searchCol;
					paramD.searchVal=searchVal;
				}
				refreshGrid("#gridDialog",paramD);
			}
			///////////////////////////////finish->dialogHandler///part////////////////////////////////////////////

		});
		
