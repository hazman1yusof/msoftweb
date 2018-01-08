
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
							element : $('#'+errorField[0]),
							message : ' '
						}
					}
				},
			};
			//////////////////////////////////////////////////////////////


			////////////////////object for dialog handler//////////////////
			
			dialog_billtypeop=new makeDialog('material.category','#stockacct',['catcode','description'], 'Stock Account');
			dialog_billtypeop=new makeDialog('material.category','#cosacct',['catcode','description'], 'COS Account');
			dialog_billtypeop=new makeDialog('material.category','#adjacct',['catcode','description'], 'Adjusment Account');
			dialog_billtypeop=new makeDialog('material.category','#woffacct',['catcode','description'], 'Write Off Account');
			dialog_billtypeop=new makeDialog('material.category','#expacct',['catcode','description'], 'Expenses Account');
			dialog_billtypeop=new makeDialog('material.category','#loanacct',['catcode','description'], 'Loan Account');	
						
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
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
					toggleFormData('#jqGrid','#formdata');
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
						dialog_stockacct.handler();
						dialog_cosacct.handler();
						dialog_adjacct.handler();
						dialog_woffacct.handler();
						dialog_expacct.handler();
						dialog_loanacct.handler();
					}
					if(oper!='add'){
						dialog_stockacct.check();
						dialog_cosacct.check();
						dialog_adjacct.check();
						dialog_woffacct.check();
						dialog_expacct.check();
						dialog_loanacct.check();
					}
				},
				close: function( event, ui ) {
					emptyFormdata('#formdata');
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
				table_name:'material.category',
				table_id:'catcode'
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.category',
				table_id:'catcode',
				saveip:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [

				 	{label: 'Compcode', name: 'compcode', width: 90 , hidden: true},
					{label: 'Category Code', name: 'catcode', width: 100, checked:true, canSearch: true},
					{label: 'Description', name: 'description', width: 200, classes: 'wrap', canSearch: true},					
					{label: 'Category Type', name: 'cattype', width: 90 , hidden: true},					
					{label: 'Source', name: 'source', width: 90 , hidden: true},					
					{label: 'Stock Account', name: 'stockacct', width: 90 ,  hidden: true},					
					{label: 'COS Account', name: 'cosacct', width: 90, hidden: true,},					
					{label: 'Adjustment Account', name: 'adjacct', width: 90, hidden: true},					
					{label: 'Write Off Account', name: 'woffacct', width: 90, hidden: true},					
					{label: 'Expenses Account', name: 'expacct', width: 90, hidden: true},					
					{label: 'Loan Account', name: 'loanacct', width: 90, hidden: true},					
					{label: 'PO Validate', name: 'povalidate', width: 90, hidden: true},					
					{label: 'accrualacc', name: 'accrualacc', width: 90, hidden: true},					
					{label: 'stktakeadjacct', name: 'stktakeadjacct', width: 90, hidden: true},					
					{label: 'adduser', name: 'adduser', width: 90 , hidden: true},					
					{label: 'adddate', name: 'adddate', width: 90 , hidden: true},					
					{label: 'upduser', name: 'upduser', width: 90 , hidden: true},					
					{label: 'upddate', name: 'upddate', width: 90 , hidden: true},
					{label: 'deluser', name: 'deluser', width: 90 , hidden: true},					
					{label: 'deldate', name: 'deldate', width: 90 , hidden: true},					
					{label: 'recstatus', name: 'recstatus', width: 90, classes: 'wrap', hidden: true},

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
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				
			});

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
						return emptyFormdata('#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'catcode':selRowId});
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
			addParamField('#jqGrid',false,saveParam,['depamt']);

			///////////////////////start utility function/////////////////////////////////////////////////////////

			function toogleSearch(butID,formID,statenow){
				this.state=false;
				$(butID+' i').attr('class','fa fa-chevron-down');
				$(butID).on( "click", function() {
					$(formID).toggle("fast");
					$(butID+' i').toggleClass('fa fa-chevron-down', this.state );
					$(butID+' i').toggleClass('fa fa-chevron-up', !this.state );
				});
				if(statenow=='off'){
					this.state=true;
					$(formID).toggle();
					$(butID+' i').attr('class','fa fa-chevron-up');
				}
			}

			function toggleFormData(grid,formName){
				if(oper=='add'){
					$(formName+" .btn-group").hide();
				}else{
					$(formName+" .btn-group").show();
				}
				$(formName+" a[name='next']").on( "click", function() {
					var selrow = $(grid).jqGrid('getGridParam', 'selrow');
					if (selrow == null) return;

					var ids = $(grid).jqGrid('getDataIDs');
					if (ids.length < 2) return;

  					console.log(ids);

					var index = $(grid).jqGrid('getInd', selrow);index++;
					if (index > ids.length)index = 1;

  					$(grid).jqGrid('setSelection', ids[index - 1]);
  					console.log(selrow)
					populateFormdata(grid,null,formName,ids[index - 1], oper);
				});
				$(formName+" a[name='prev']").on( "click", function() {
					var selrow = $(grid).jqGrid('getGridParam', 'selrow');
					if (selrow == null) return;

					var ids = $(grid).jqGrid('getDataIDs');
					if (ids.length < 2) return;

					var index = $(grid).jqGrid('getInd', selrow);index--;
					console.log(index);
					if (index == 0)index = ids.length;

  					$(grid).jqGrid('setSelection', ids[index - 1]);
  					console.log(selrow)
					populateFormdata(grid,'',formName,ids[index - 1],oper);
				});
			}

			function addParamField(grid,needRefresh,param,except){
				var temp=[];
				$.each($(grid).jqGrid('getGridParam','colModel'), function( index, value ) {
					if(except!=undefined && except.indexOf(value['name']) === -1){
						temp.push(value['name']);
					}else if(except==undefined){
						temp.push(value['name']);
					}
				});
				param.field=temp;
				if(needRefresh){
					refreshGrid(grid,param);
				}
			}

			function refreshGrid(grid,urlParam){
				$(grid).jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(urlParam)}).trigger('reloadGrid');
			}

			function disableForm(formName){
				$(formName+' texarea').prop("readonly",true);
				$(formName+' input').prop("readonly",true);
				$(formName+' input[type=radio]').prop("disabled",true);
			}
			
			function enableForm(formName){
				$(formName+' textarea').prop("readonly",false);
				$(formName+' input').prop("readonly",false);
				$(formName+' input[type=radio]').prop("disabled",false);
			}
			
			function populateFormdata(grid,dialog,form,selRowId,state){
				if(!selRowId){
					alert('Please select row');
					return emptyFormdata(form);
				}
				rowData = $(grid).jqGrid ('getRowData', selRowId);
				$.each(rowData, function( index, value ) {
					var input=$("[name='"+index+"']");
					if(input.is("[type=radio]")){
						$("[name='"+index+"'][value='"+value+"']").prop('checked', true);
					}else{
						input.val(value);
					}
				});
				if(dialog!=''){
					$(dialog).dialog( "open" );	
				}
			}
			
			function frozeOnEdit(form){
				$(form+' input[frozeOnEdit]').prop("readonly",true);
			}
			
			function emptyFormdata(form){
				errorField.length=0;
				$(form).trigger('reset');
				$(form+' .help-block').html('');
			}
			
			function saveFormdata(grid,dialog,form,oper,saveParam,urlParam,obj){
				if(obj==null){
					obj={null:'null'};
				}
				saveParam.oper=oper;
				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+$.param(obj) , function( data ) {
					
				}).fail(function(data) {
					errorText(dialog,data.responseText);
				}).success(function(data){
					$(dialog).dialog('close');
					editedRow = $(grid).jqGrid ('getGridParam', 'selrow');
					refreshGrid(grid,urlParam);
				});
			}
			
			function errorText(dialog,text){
				$(".ui-dialog-buttonpane" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
			}
			
			var delay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				};
			})();
			
			function populateSelect(grid,form){
				$.each($(grid).jqGrid('getGridParam','colModel'), function( index, value ) {
					if(value['canSearch']){
						if(value['checked']){
							$( form+" [name=Scol]" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
						}
						else{
							$( form+" [name=Scol]" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"'>"+value['label']+"</input></label>" );
						}
					}
				});
			}

			function searchClick(grid,form,urlParam){
				$(form+' [name=Stext]').on( "keyup", function() {
					delay(function(){
						search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
					}, 500 );
				});

				$(form+' [name=Stext]').on( "change", function() {
					search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
				});
			}
			
			function search(grid,Stext,Scol,urlParam){
				urlParam.searchCol=null;
				urlParam.searchVal=null;
				if(Stext.trim() != ''){
					var split = Stext.split(" "),searchCol=[],searchVal=[];
					$.each(split, function( index, value ) {
						searchCol.push(Scol);
						searchVal.push('%'+value+'%');
					});
					urlParam.searchCol=searchCol;
					urlParam.searchVal=searchVal;
				}
				refreshGrid(grid,urlParam);
			}
			/////////////////////////////////End utility function////////////////////////////////////////////////

			///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
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
				close: function( event, ui ){
					paramD.searchCol=null;
					paramD.searchVal=null;
				},
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Category Code', name: 'catcode', width: 200, classes: 'pointer', canSearch:true}, 
					{ label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch:true},
					{ label: 'Status', name: 'recstatus', width: 200,classes: 'pointer', hidden: true},
					{ label: 'Date Created', name: 'adddate', width: 200,classes: 'pointer', hidden: true},
					{ label: 'Created By', name: 'adduser', width: 200,classes: 'pointer', hidden: true},
				],
				width: 680,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
					if(selText=='catcode' ){
						$('#recstatus').val(data['recstatus']);
						$('#adddate').val(data['adddate']);
						$('#adduser').val(data['adduser']);
					}
					}
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['desc']);
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(){
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
					self.check();
				});
			}
			
			function checkInput(){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						var index = errorField.indexOf(id);
						if (index > -1) {
							errorField.splice(index, 1);
						}
						$( id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
					}else if(data.msg=='fail'){
						errorField.push(id);
						$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( id ).removeClass( "valid" ).addClass( "error" );
						$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
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
		