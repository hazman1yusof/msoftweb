
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			/////////////////////////validation//////////////////////////
			$.validate({
			    modules : 'sanitize',
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
			dialog_paymode=new makeDialog('debtor.paymode','#paymode',['paymode','description'],'Pay Mode','Description', 'Pay Mode');
			dialog_bankcode=new makeDialog('finance.bank','#bankcode',['bankcode','bankname'], 'Bank Code','Bank Name', 'Bank Code');
			dialog_cheqno=new makeDialog('finance.chqtran','#cheqno',['cheqno'],'Cheque No', 'hide', 'Cheque No');
			dialog_payto=new makeDialog('material.supplier','#payto',['SuppCode','Name'] ,'Pay To','Description', 'Pay To');

			////////////////////////////////////start dialog///////////////////////////////////////
			/*var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
						//saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,'#searchForm');//,{dtl:test});
					}
				}
			},{
				text: "Cancel",click: function() {
					$('#jqGrid2_ilcancel').click();
					//////$("#jqGrid2").jqGrid("clearGridData", true).trigger("reloadGrid");
					$(this).dialog('close');
				}
			}];

			var butt2=[{
				text: "Close",click: function() {
					$(this).dialog('close');
					//jqGrid2_ilcancel
				}
			}];*/

			var oper;
			var unsaved = false;

			$("#dialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add Direct Payment" );				
							/*var trf = $("#jqGrid2 tbody:first tr:first")[0];
							$("#jqGrid2 tbody:first").empty().append(trf);*/
							$("#jqGrid2").jqGrid("clearGridData", true);
							//refreshGrid("#jqGrid2");
							$("#pg_jqGridPager2 table").hide();
							$("#button_D").show();
							$("#button_H").hide();
							enableForm('#formdata');
							rdonly('#formdata');
							hideOne('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit Direct Payment" );
							$("#pg_jqGridPager2 table").hide();
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly('#formdata');
							$('#formdata :input[hideOne]').show();
							$("#button_D").show();
							$("#button_H").hide();
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View Direct Payment" );
							disableForm('#formdata');
							$("#button_H").hide();
							$("#button_D").hide();
							//$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						dialog_paymode.handler(errorField);
						dialog_bankcode.handler(errorField);
						dialog_cheqno.handler(errorField);
						dialog_payto.handler(errorField);

					}
					if(oper!='add'){
						//toggleFormData('#jqGrid','#formdata');
						dialog_paymode.check(errorField);
						dialog_bankcode.check(errorField);
						dialog_cheqno.check(errorField);
						dialog_payto.check(errorField);
					}
				},
				beforeClose: function(event, ui){
					if(unsaved){
						var r = confirm("Are you sure want to leaving without save?");
						if (r == true) {
						        return true;
						    } else {
						    unsaved = false
						       return false;
						    }
					}
					
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#formdata');
					//emptyFormdata(errorField,'#formdata2');
					$('.alert').detach();
					$("#formdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",butt1);
					}
					
				},
				//buttons :butt1,
			  });
			////////////////////////////////////////end dialog///////////////////////////////////////////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'finance.apacthdr',
				table_id:'auditno',
				filterCol:['trantype'],
				filterVal:['DP']
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'directPayment_save',
				field:'',
				oper:oper,
				table_name:'finance.apacthdr',
				table_id:'auditno',
				//filterCol:['compcode','auditno'],
				//filterVal:['session.company','']
				sysparam:{source:'CM',trantype:'DP',useOn:'auditno'},
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Audit No', name: 'auditno', width: 40, classes: 'wrap', canSearch: true, checked: true},
					{ label: 'Bank Code', name: 'bankcode', width: 40, classes: 'wrap', canSearch: true},
					{ label: 'Pay To', name: 'payto', width: 40, classes: 'wrap',},
					{ label: 'Post Date', name: 'actdate', width: 40, classes: 'wrap', 
						//formatter : 'date', formatoptions : {newformat : 'd/m/Y'}
					},
					{ label: 'Amount', name: 'amount', width: 40, classes: 'wrap', formatter:'currency'} ,//unformat:unformat2}
					{ label: 'Remarks', name: 'remarks', width: 40, classes: 'wrap',},
					{ label: 'Status', name: 'recstatus', width: 40, classes: 'wrap',},
					{ label: 'Entered By', name: 'adduser', width: 40, classes: 'wrap',},
					{ label: 'Entered Date', name: 'adddate', width: 40, classes: 'wrap',},
				 	{ label: 'source', name: 'source', width: 40, hidden:'true'},
				 	{ label: 'trantype', name: 'trantype', width: 40, hidden:'true'},
					{ label: 'Pv No', name: 'pvno', width: 40, hidden:'true'},
					{ label: 'Payment Mode', name: 'paymode', width: 40, hidden:'true'},
					{ label: 'Cheq No', name: 'cheqno', width: 40, hidden:'true'},
					{ label: 'Cheq Date', name: 'cheqdate', width: 40, hidden:'true'},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					adtNo=rowid;
					urlParam2.filterVal[0]=rowid;
					if(rowid != null) {
						refreshGrid("#jqGrid2",urlParam2);
						///urlParam2.filterVal[0]=selrowData("#jqGrid").auditno; 
						///urlParam2.filterVal[0]=rowid;
						//saveParam2.filterVal[0]=selrowData("#jqGrid").auditno; 
						///refreshGrid('#jqGrid2',urlParam2);
					}
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				
			});
			
			/////////////////////////formatter & unformat/////////////////////////////////////////////////////////
			/*function formatter2(cellvalue, option, rowObject){
				return "RM "+cellvalue;
			}

			function  unformat2(cellvalue, options){
				return cellvalue.replace("RM","");
			}*/
			//var totalAmount=0;

			/////////////////////////////// for Button /////////////////////////////////////////////////////////
			var adtNo
			$("#button_D").click(function(){
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata2("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
						//$( "#dialogForm" ).load(populateFormdata2("#jqGrid","#dialogForm","#formdata"));
						//alert( "Load was performed." );
						unsaved = false;
						//refreshGrid('#jqGrid2',urlParam2);
						$("#pg_jqGridPager2 table").show();
						$("#button_D").hide();
						$("#button_H").show();
						$('#formdata  textarea').prop("readonly",true);
						$('#formdata input').prop("readonly",true);
						$('#formdata  input[type=radio]').prop("disabled",true);					
						$('#formdata :input[hideOne]').show();
						$("#jqGrid2_iladd").click();
					}
				});

			$("#button_H").click(function(){
				$("#pg_jqGridPager2 table").hide();
				$("#button_D").show();
				$("#button_H").hide();
				$('#formdata  textarea').prop("readonly",false);
				$('#formdata input').prop("readonly",false);
				$('#formdata  input[type=radio]').prop("disabled",false);

			});

			function populateFormdata2(grid,dialog,form,selRowId,state){
				selRowId=  $("#jqGrid").jqGrid ('getGridParam', '#auditno');
				rowData = $(grid).jqGrid ('getRowData', selRowId);
				$.each(rowData, function( index, value ) {
					var input=$(form+" [name='"+index+"']");
					if(input.is("[type=radio]")){
						$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
					}else{
						input.val(value);
					}
				});
			}

			/*$(document).ready(function() {
			    $('#formdata input').change(function() {
			        $(this).closest('#formdata').addClass('form-dirty');
			    });

			    $(window).bind('beforeunload', function() {
			        if($('#formdata:not(.ignore-changes).form-dirty').length > 0) {
			            return 'You have unsaved changes, are you sure you want to discard them?';
			        }
			    });

			    $('#formdata').bind('submit',function() {
			        $(this).closest('#formdata').removeClass('form-dirty');
			        return true;
			    });
			});

			$(window).bind('beforeunload', function() {
			    if(unsaved){
			        return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
			    }
			});

			// Monitor dynamic inputs
			$(document).on('change', '#formdata input', function(){ //triggers change in all input fields including text type
			    unsaved = true;
			});

			var unsaved = false;

			$("#dialogForm").bind('dialogclose', function(event, ui) {
				alert("qwerty");
					    if(unsaved){
					        alert("You have unsaved changes on this form.");
					        //return "You have unsaved changes on this page.";
					    }

			});
*/
			$("#dialogForm").on('keypress', '#formdata :input', '#formdata :textarea',  function(){
			    unsaved = true;
			});

			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			})/*.jqGrid('navButtonAdd',"#jqGridPager",{
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'itemcode':selRowId});
					}
				},
			})*/.jqGrid('navButtonAdd',"#jqGridPager",{
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
				id: 'glyphicon-plus',
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					$("#formdata :input[name='source']").val("CM");
					$("#formdata :input[name='trantype']").val("DP");
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam);

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			

			//////////////////////////////////////grid2/////////////////////////////////////////////////////////
			var operDetail;

			var urlParam2={
				action:'get_table_default',
				field:['compcode','source','trantype','auditno','lineno_','deptcode','category','document', 'amount'],
				table_name:'finance.apactdtl',
				table_id:'lineno_',
				filterCol:['auditno'],
				filterVal:[''],
			}

			/*var saveParam2={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'finance.apactdtl',
				table_id:'lineno_',
				lineno:{useOn:'lineno_'},
			};*/

			$("#jqGrid2").jqGrid({
				datatype: "local",
				editurl: "../../../../assets/php/entry.php?action=directPayment_save",
				colModel: [
				 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
				 	{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
				 	{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
				 	{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:false, editable:true},
					{ label: 'Line No', name: 'lineno_', width: 20, classes: 'wrap', hidden:true, editable:true}, //canSearch: true, checked: true},
					{ label: 'Department', name: 'deptcode', width: 30, classes: 'wrap', canSearch: true, editable: true,
								editrules:{required: true},
								edittype:'custom',	editoptions:
								    {  custom_element:deptcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Category', name: 'category', width: 30, classes: 'wrap', editable: true,
								editrules:{required: true},
								edittype:'custom',	editoptions:
								    {  custom_element:categoryCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Document', name: 'document', width: 60, classes: 'wrap', editable: true,
								editrules:{required: true},
								edittype:"text",
					},
					{ label: 'Amount', name: 'amount', width: 30, classes: 'wrap', 
								formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
								editable: true,
								editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									$(element).keypress(function(e){
										 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					//{ label: 'Action', name: 'action', width :10,  formatoptions: { keys: true, delbutton: true }, formatter: 'actions'},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 200,
				rowNum: 30,
				//rownumbers: true,
				pager: "#jqGridPager2",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				footerrow: true,
                loadComplete: function () {
                    var $self = $(this),
                        sum = $self.jqGrid("getCol", "amount", false, "sum");

                    $self.jqGrid("footerData", "set", {invdate: "Total:", amount: sum});
                },				
			});

			///////custom input/////
			function deptcodeCustomEdit(val,opt){  		
				return $('<div class="input-group"><input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function categoryCustomEdit(val,opt){  		
				return $('<div class="input-group"><input id="category" name="category" type="text" class="form-control input-sm" data-validation="required" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}


			function galGridCustomValue (elem, operation, value){	
				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}

			//$( "#jqGrid2 input[name='amount']" ).clone().prependTo( "#jqGrid input[name='amount']" );

			$("#jqGrid2").inlineNav('#jqGridPager2',{	
				add:true,
				edit:true,
				del:true,
			});
			/*$('#jqGrid2').navGrid("#jqGridPager2", {edit: false, add: false, del: false, refresh: false, view: false});
            $('#jqGrid2').inlineNav('#jqGridPager2',
                // the buttons to appear on the toolbar of the grid
                { 
                    edit: true, 
                    add: true, 
                    del: true, 
                    cancel: true,
                    editParams: {
                        keys: true,
                    },
                    addParams: {
                        keys: true
                    }
                });*/

			$("#jqGrid2_iladd").click(function(){
				$("input[id*='_auditno']").val(adtNo);
				$("input[id*='_auditno']").attr('readonly','readonly');
				$("input[id*='_source']").val($("#source").val());
				$("input[id*='_trantype']").val($("#trantype").val());
				$("input[id*='_lineno_']").val($("#lineno_").val());
				console.log(adtNo);

				dialog_deptcode=new makeDialog('sysdb.department',"#jqGrid2 input[name='deptcode']",['deptcode','description'],'Department Code','Description', 'Department');
				dialog_category=new makeDialog('material.category',"#jqGrid2 input[name='category']",['catcode','description'],'Category Code','Description', 'Category');

				dialog_deptcode.handler(errorField);
				dialog_category.handler(errorField);

				$("input[id*='_amount']").keydown(function(e) {
					//console.log('keydown called');
					var code = e.keyCode || e.which;
						if (code == '9') { // -->for tab
							$('#jqGrid2_ilsave').click();
							refreshGrid("#jqGrid2",urlParam2);
							delay(function(){
								$('#jqGrid2_iladd').click();
							}, 1500 );
						}
				 });
				/*if(oper=='add'){
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						$("#formdata2 :input[id*='_source']").val(selrowData('#jqGrid').source);
						$("#formdata2 :input[id*='_trantype']").val(selrowData('#jqGrid').trantype);
						$("#formdata2 :input[id*='_auditno']").val(selrowData('#jqGrid').auditno);
						saveFormdata2("#jqGrid2","#dialogForm","#formdata2",oper,saveParam2,urlParam2);
						//saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);						
					}
					else{
						//$("#gridSuppitems_ilcancel").click();					
						return false;
					}
				}
				else{
					//$("#gridSuppitems_ilcancel").click();					
					return false;
				}*/
			});

			$("#jqGrid2_iledit").click(function(){
				dialog_deptcode=new makeDialog('sysdb.department',"#jqGrid2 input[name='deptcode']",['deptcode','description'],'Department Code','Description', 'Department');
				dialog_category=new makeDialog('material.category',"#jqGrid2 input[name='category']",['catcode','description'],'Category Code','Description', 'Category');

				dialog_deptcode.handler(errorField);
				dialog_category.handler(errorField);
			});


			/*function append(inp){ 
				$(inp).wrapAll( "<div class='input-group'></div><span class='help-block'></span>" ); ///"<span class='help-block'></span>"
				$(inp).css( "width", "100%" );
				$( "<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>"  ).insertAfter(inp);

			}


			$("#jqGrid2_iladd").click(function(){
				//$("#jqGrid2_ilsave").hide();

				append("#jqGrid2 input[name='deptcode']");
				dialog_deptcode=new makeDialog('sysdb.department',"#jqGrid2 input[name='deptcode']",['deptcode','description'],'Department Code','Description', 'Department');
				dialog_deptcode.handler(errorField);
				//dialog_deptcode.check(errorField);

				append("#jqGrid2 input[name='category']");
				dialog_category=new makeDialog('material.category',"#jqGrid2 input[name='category']",['catcode','description'],'Category Code','Description', 'Category');
				dialog_category.handler(errorField);
				//dialog_category.check(errorField);

				/*("input[id*='_deptcode']").keydown(function(e) {
					var code = e.keyCode || e.which;
						if (code == '112') { // -->for F1

						}
				});

				$("input[id*='_category']").keydown(function(e) {
					var code = e.keyCode || e.which;
						if (code == '112') { // -->for F1
							alert("Hello! I am Six");
						}
				});

				$("input[id*='_amount']").keydown(function(e) {
					var code = e.keyCode || e.which;
						if (code == '9') { // -->for tab
							$('#jqGrid2_ilsave').click();
							delay(function(){
								$('#jqGrid2_iladd').click();
							}, 1500 );
						}
				 });
			});*/

		//////////////////////////////////////end grid2/////////////////////////////////////////////////////////

		///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
			function makeDialog(table,id,cols,setLabel1,setLabel2,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.setLabel1=setLabel1;
				this.setLabel2=setLabel2;
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
					if(selText=='#paymode'){
						paramD.filterCol=['source'];
						paramD.filterVal=['cm'];
					}else if(selText=='#cheqno'){ 
						paramD.filterCol=['bankcode', 'stat'];
						paramD.filterVal=[$("#formdata :input[name='bankcode']").val(), 'A'];
					}else if(selText=='#payto'){
						paramD.filterCol=['recstatus'];
						paramD.filterVal=['A'];
					}else{
						paramD.filterCol=null;
						paramD.filterVal=null;
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
					{ label: 'Description', name: 'desc', width: 400, canSearch:true, classes: 'pointer',},
				],
				width: 500,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				shrinkToFit: true,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['desc']);
					/*if(selText=='#payto'){
						$('#payto2').val(data['desc']);
					}else{
						$(selText).parent().next().html(data['desc']);
					}*/
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(){
				var table=this.table,id=this.id,cols=this.cols,setLabel1=this.setLabel1,setLabel2=this.setLabel2,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);
				
					$( "#gridDialog" ).jqGrid( "setLabel", "code", setLabel1);
					$( "#gridDialog" ).jqGrid( "setLabel", "desc", setLabel2);
					$( "#dialog" ).dialog( "option", "title", title );
					if(selText=='#cheqno')	{	
						$( "#dialog" ).dialog({
								autoOpen: false,
								width: 5/10 * $(window).width(),
								modal: true,
						});	
						$("#gridDialog").css( "width", "30%" );
						$("#gridDialog").jqGrid('hideCol', 'desc');
					}
					else{
						$( "#dialog" ).dialog({
								autoOpen: false,
								width: 7/10 * $(window).width(),
								modal: true,
						});	
						$("#gridDialog").jqGrid('showCol', 'desc');
					}
					$( "#dialog" ).dialog( "open" );

					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];

					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {

						if(selText=='#cheqno')	{
							if(value['canSearch']){
								if(value['checked']){
									$( "#Dcol" ).append("<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+setLabel1+"</input></label>" );
								}
							}
						}else{
							if(value['canSearch']){
								if(value['checked']){
									$( "#Dcol" ).append("<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+setLabel1+"</input></label>" );
								}else{
									$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+setLabel2+"</input></label>" );
								}
							}
						}

						
					});
				});
				$(id).on("blur", function(){
					/*if(id!="#jqGrid2 input[name='deptcode']"){
						self.check();
					}*/
					self.check();
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
						$( id ).parent().siblings( ".help-block" ).show();
					}else if(data.msg=='fail'){
						if(id=='#payto'){
							//$( id ).parent().siblings( ".help-block" ).html(value);
							$( id ).parent().siblings( ".help-block" ).hide();
						}else{
							$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
							$( id ).removeClass( "valid" ).addClass( "error" );
							$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
							if($.inArray(id,errorField)===-1){
								errorField.push(id);
							}
						}
					}
				});
			}

			/*function checkInput(){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						var index = errorField.indexOf(id);
						if (index > -1) {
							errorField.splice(index, 1);
						}
						if(id!='#payto'){
							$( id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
						}
						if(id=='#payto'){
							$( '#payto2' ).val(data.row[field[1]]);
						}
					}else if(data.msg=='fail'&&id!='#payto'){
						errorField.push(id);
						$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( id ).removeClass( "valid" ).addClass( "error" );
						$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
					}
				});
			}*/
			
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