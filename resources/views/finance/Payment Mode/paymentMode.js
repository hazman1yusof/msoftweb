
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
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
							element : $('#'+errorField[0]),
							message : ' '
						}
					}
				},
			};
			//////////////////////////////////////////////////////////////


			////////////////////object for dialog handler//////////////////
			dialog_costcode=new makeDialog('finance.costcenter','#ccode',['costcode','description'], 'Cost Code');
			dialog_glaccount=new makeDialog('finance.glmasref','#glaccno',['glaccno','description'],'GL Account');
			dialog_cardbank=new makeDialog('finance.bank','#cardcent',['bankcode','bankname'],'Bank');
			
			
			//// to hide bank/card handler////

			function disableCardBank() {
				$("#cardcent").hide();
				$("#2").addClass("hidden");
				$("#3").removeClass("hidden");
				$("#4").hide();
			}

			function enableCardBank() {
				$("label[for=cardcent]").show();
				$("#cardcent").show();
				$("#4").show();
				$("#2").removeClass("hidden");
				$("#3").addClass("hidden");
				
			}

			///////////////end hide bank/card handler/////////

			$("input[name=paytype]:radio").on('change',  function(){
					paytype = $("input[name=paytype]:checked").val();			 
				
					if(paytype == 'Bank') {
						$("label[for=cardcent]").text(paytype);
						enableCardBank();
						dialog_cardbank.updateField('finance.bank','#cardcent',['bankcode','bankname'],'Bank');
						dialog_cardbank.offHandler();
						dialog_cardbank.handler(errorField);
						
					}else if(paytype == 'Card') {
						$("label[for=cardcent]").text(paytype);
						enableCardBank();
						dialog_cardbank.updateField('finance.cardcent','#cardcent',['cardcode','name'],'Card');
						dialog_cardbank.offHandler();
						dialog_cardbank.handler(errorField);

					} else  {
						$("label[for=cardcent]").hide();
						disableCardBank();
					}


			});


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
					parent_close_disabled(true);
					toggleFormData('#jqGrid','#formdata');
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							$("label[for=cardcent]").hide();
							disableCardBank();
							enableForm('#formdata');
							rdonly("#formdata");
							hideOne("#formdata");
							break;

						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#formdata");
							$('#formdata :input[hideOne]').show();
							paytype = $("input[name=paytype]:checked").val();			 

							if(paytype == 'Bank') {
								$("label[for=cardcent]").text(paytype);
								enableCardBank();
								dialog_cardbank.updateField('finance.bank','#cardcent',['bankcode','bankname'],'Bank');
								dialog_cardbank.offHandler();
								dialog_cardbank.handler(errorField);
								
							}else if(paytype == 'Card') {
								$("label[for=cardcent]").text(paytype);
								enableCardBank();
								dialog_cardbank.updateField('finance.cardcent','#cardcent',['cardcode','name'],'Card');
								dialog_cardbank.offHandler();
								dialog_cardbank.handler(errorField);

							} else  {
								$("label[for=cardcent]").hide();
								disableCardBank();
							}
							break;
							
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_glaccount.handler(errorField);
						dialog_costcode.handler(errorField);
						
					}
					if(oper!='add'){
						dialog_glaccount.check(errorField);
						dialog_costcode.check(errorField);

					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
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

			///////////////////////////////start->dialogHandler part////////////////////////////////////////////
			function makeDialog(table,id,cols, title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.title=title;
				this.handler=dialogHandler;
				this.offHandler=function(){
					$( this.id+" ~ a" ).off();
				}
				this.check=checkInput;
				this.updateField=function(table,id,cols,title){
					this.table=table;
					this.id=id;
					this.cols=cols;
					this.title=title;
					console.log(this);
				}
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
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['desc']);
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,
				id=this.id,
				cols=this.cols,
				title=this.title,
				self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#dialog" ).dialog( "open" );
					$("#gridDialog").jqGrid("clearGridData", true);

					
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

			
			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'debtor.paymode',
				table_id:'paymode',
				sort_idno: true,
				filterCol:['source'],
				filterVal:[$('#source2').val()]
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'debtor.paymode',
				table_id:'paymode',
				filterCol:['source'],
				filterVal:[$('#source2').val()],
				saveip:'true'

			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{label: 'compcode', name: 'compcode', width: 90 , hidden: true},
					{label: 'source', name: 'source', width: 90, hidden: true},
					{label: 'PayMode', name: 'paymode', width: 90, classes: 'wrap', canSearch: true, checked:true,},
					{label: 'PayType', name: 'paytype', width: 90, checked:true,  classes: 'wrap'},
					{label: 'Description', name: 'description', width: 100, canSearch: true, classes: 'wrap'}, 
					{label: 'Cost Code', name: 'ccode', width: 90, hidden: true, classes: 'wrap'}, 
					{label: 'GL Account', name: 'glaccno', width: 90, hidden: true, classes: 'wrap'},
					{label: 'Dr. Payment', name: 'drpayment', width: 90, hidden: true, classes: 'wrap'},
					//{label: 'Record Status', name: 'recstatus', width: 90, hidden: true, classes: 'wrap', formatter: formatterstatus, unformat: unformat},
					{label: 'Card Flag', name: 'cardflag', width: 90, hidden: true,classes: 'wrap'},
					{label: 'ValExpDate', name: 'valexpdate', width: 90, hidden: true, classes: 'wrap'},
					{label: 'Card Cent', name: 'cardcent', width: 90, hidden: true, classes: 'wrap'},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{ label: 'deluser', name: 'deluser', width: 90, hidden:true},
					{ label: 'deldate', name: 'deldate', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true},
					{label: 'idno', name: 'idno', width: 200, hidden: true, classes: 'wrap'}, 
					{ label: 'Record Status', name: 'recstatus', width: 25, classes: 'wrap', 
						formatter:formatter, unformat:unformat, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
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

			///////////////////////////formatter//////////////////////////////////////////////////////////
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'paymode':selRowId});
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

		});
		