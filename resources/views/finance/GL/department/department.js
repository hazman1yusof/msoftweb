
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

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

			//////////////////////////////////////////////////////////////////////////////////////////////////


			////////////////////object for dialog handler//////////////////
			
			dialog_costcode=new makeDialog('finance.costcenter','#costcode',['costcode','description'], 'Cost Center');
			dialog_sector=new makeDialog('sysdb.sector','#sector',['sectorcode','description'], 'Sector');
			dialog_region=new makeDialog('sysdb.region','#region',['regioncode','description'], 'Region');
			
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					radbuts.check();
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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
							rdonly('#formdata');
							hideOne('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							$('#formdata :input[hideOne]').show();
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_costcode.handler(errorField);
						dialog_sector.handler(errorField);
						dialog_region.handler(errorField);
					}
					if(oper!='add'){
						dialog_costcode.check(errorField);
						dialog_sector.check(errorField);
						dialog_region.check(errorField);
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

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'sysdb.department',
				table_id:'deptcode',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'sysdb.department',
				table_id:'deptcode',
				saveip:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					//{ label: 'compcode', name: 'compcode', width: 40, hidden:true},						
					{ label: 'Department', name: 'deptcode', width: 20, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Description', name: 'description', width: 80, classes: 'wrap', canSearch: true},
					{ label: 'Cost Code', name: 'costcode', width: 50, classes: 'wrap'},
					{ label: 'Purchase Dept', name: 'purdept', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Register Dept', name: 'regdept', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Charge Dept', name: 'chgdept', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Ward Dept', name: 'warddept', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Admit Dept', name: 'admdept', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Dispense Dept', name: 'dispdept', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Sector', name: 'sector', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Region', name: 'region', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Store Dept', name: 'storedept', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Category', name: 'category', width: 90, hidden:true, classes: 'wrap'},

					{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', 
						formatter:formatter, unformat:unformat, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
					{label: 'idno', name: 'idno', hidden: true},
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
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

			////////////////////////////formatter//////////////////////////////////////////////////////////
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
					return "Active";
				}
				if(cellvalue == 'Deactive') { 
					return "Deactive";
				}
			}

			function checkradiobutton(radiobuttons){
				this.radiobuttons=radiobuttons;
				this.check = function(){
					$.each(this.radiobuttons, function( index, value ) {
						var checked = $("input[name="+value+"]:checked").val();
						//alert(itemtype);
					    if(!checked){
					     	$("label[for="+value+"]").css('color', 'red');
					     	$(":radio[name='"+value+"']").parent('label').css('color', 'red');
						}else{
							$("label[for="+value+"]").css('color', '#444444');
							$(":radio[name='"+value+"']").parent('label').css('color', '#444444');
						}
					});
				}
			}

			var radbuts=new checkradiobutton(['category','chgdept','purdept','admdept','warddept','regdept', 'dispdept', 'storedept']);

			/*function textcolourradio(textcolour){
				this.textcolour=textcolour;
				this.check = function(){
					$.each(this.textcolour, function( index, value ) {
						$("label[for="+value+"]").css('color', '#444444');
						$(":radio[name="+value+"]").parent('label').css('color', '#444444');
					});
				}
			}

			var textCol=new textcolourradio(['category','chgdept','purdept','admdept','warddept','regdept', 'dispdept', 'storedept']);*/


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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'deptcode':selRowId});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno', 'computerid', 'ipaddress']);

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
					if(selText=='#sector'){ 
						paramD.filterCol=['regioncode', 'recstatus'];
						paramD.filterVal=[$("#formdata :input[name='region']").val(), 'A'];
					}else{
						paramD.filterCol=['recstatus'];
						paramD.filterVal=['A'];
					}
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
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['desc']);
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);
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
		