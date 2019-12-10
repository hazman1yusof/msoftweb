	
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
			dialog_paymode=new makeDialog('debtor.paymode','#paymode',['paymode','description'],'Pay Mode','Description','--', 'Pay Mode');
			dialog_bankcodefrom=new makeDialog('finance.bank','#bankcode',['bankcode','bankname'], 'Bank Code','Bank Name','--', 'Bank Code');
			dialog_bankcodeto=new makeDialog('finance.bank','#payto',['bankcode','bankname'], 'Bank Code','Bank Name','Pay To');
			dialog_cheqno=new makeDialog('finance.chqtran','#cheqno',['cheqno'],'Cheque No', '--','--', 'Cheque No');

			var mycurrency =new currencymode(['#amount']);
			
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				 id: "saveBut", 
				text: "Save",click: function() {
					mycurrency.formatOff();
					mycurrency.check0value(errorField);
						if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
							if ($("#formdata :input[name='payto']").val() === $("#formdata :input[name='bankcode']").val()) {
									
										bootbox.alert("Bank Code Credit cannot be same with Bank Code Debit");
									
									}
							else {
								saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
							}
						}else{
							mycurrency.formatOn();
						}
					}
				},{
				id: "canBut",	
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
			$("#dialogForm").dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
					case state = 'add':
					mycurrency.formatOnBlur();
						$( this ).dialog( "option", "title", "Add Bank Transaction" );
						enableForm("#formdata");
						rdonly("#formdata");
						hideOne("#formdata");
						$("#saveBut").show();
						$("#canBut").show();
						var paymode = $("#paymode").val();
						if(paymode == "CHEQUE"){
								$("#cheqno").prop("readonly",true);
								$("#saveBut").show();
								$("#canBut").show();
								enableChequeD();
							}
						else if(paymode == "CASH"){
							disableChequeD();
							disableFiledCash();
						} else{
							disableFiledCheqNo();
						}


						break;

					case state = 'edit':
					mycurrency.formatOn();
						$( this ).dialog( "option", "title", "Edit Bank Transaction" );
						enableForm("#formdata");
						frozeOnEdit("#dialogForm");
						rdonly("#formdata");
						$('#formdata :input[hideOne]').show();
						
						var paymode = $("#paymode").val()
							if(paymode == "CHEQUE"){
								$("#cheqno").prop("readonly",true);
								enableChequeD();
							}
							else if (paymode == "CASH"){
								disableChequeD();
								disableFiledCash();

							}
							else {
								$("label[for=cheqno]").text(paymode+" No");
								$("#cheqno").prop("readonly",false);
								disableChequeD();
							} 
							break;

					case state = 'view':
					mycurrency.formatOn();
						$( this ).dialog( "option", "title", "View Bank Transaction" );
						disableForm("#formdata");
						$( this ).dialog("option", "buttons",butt2);
						paymode = $("#paymode").val()
						if(paymode == "CHEQUE"){
								$("#cheqno").prop("readonly",true);
								enableChequeD();
							} 
							else if (paymode == "CASH"){
								disableChequeD();
								disableFiledCash();

							}
						else {
						$("label[for=cheqno]").text(paymode+" No");}
						break;
					
				}
					if(oper!='view'){
						dialog_paymode.handler(errorField);
						dialog_bankcodefrom.handler(errorField);
						dialog_bankcodeto.handler(errorField);
						dialog_cheqno.handler(errorField);
					}
					if(oper!='add'){
						dialog_paymode.check(errorField);
						dialog_bankcodefrom.check(errorField);
						dialog_bankcodeto.check(errorField);
						dialog_cheqno.check(errorField);
					}
					if(oper=='edit'){
						if (recstatus == 'Posted'){
							disableForm('#formdata');
							$('#formdata a').off();
						}

						else if (recstatus == 'Cancel'){
							disableForm('#formdata');
							$('#formdata a').off();
						}
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


			var actdateObj = new setactdate(["#actdate"]);
			actdateObj.getdata().set();
			function setactdate(target){
				this.actdateopen=[];
				this.lowestdate;
				this.highestdate;
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
							self.lowestdate = data.rows[0]["datefr1"];
							self.highestdate = data.rows[data.rows.length-1]["dateto12"];
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
					this.target.forEach(function(element){
						$(element).on('change',validate_actdate);
					});
				}

				function validate_actdate(obj){
					var permission = false;
					actdateObj.actdateopen.forEach(function(element){
					 	if(moment(obj.target.value).isBetween(element.from,element.to, null, '[]')) {
							permission=true
						}else{
							(permission)?permission=true:permission=false;
						}
					});
					if(!moment(obj.target.value).isBetween(actdateObj.lowestdate,actdateObj.highestdate)){
						bootbox.alert('Date not in accounting period setup');
						$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
					}else if(!permission){
						bootbox.alert('Accounting Period Has been Closed');
						$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
					} //Accounting Period Has been Closed
						//Date not in accounting period setup
					
				}
			}

			///format currency///

			function currencymode(arraycurrency){
			this.array = arraycurrency;
			this.formatOn = function(){
				$.each(this.array, function( index, value ) {
					$(value).val(numeral($(value).val()).format('0,0.00'));
				});
			}
			this.formatOnBlur = function(){
				$.each(this.array, function( index, value ) {
					currencyBlur(value);
				});
			}
			this.formatOff = function(){
				$.each(this.array, function( index, value ) {
					$(value).val(currencyRealval(value));
				});
			}

			this.check0value = function(errorField){
				$.each(this.array, function( index, value ) {
					if($(value).val()=='0' || $(value).val()=='0.00'){
						$(value).val('');
					}
				});
			}

			function currencyBlur(v){
					$(v).on( "blur", function(){
						$(v).val(numeral($(v).val()).format('0,0.00'));
					});
				}

			function currencyRealval(v){
					return numeral().unformat($(v).val());
				}
			}

			/////////////////////

			 
			////////////////////////////////////////end dialog///////////////////////////////////////////

			//////change label///////


			function disableChequeD() {
				dialog_cheqno.offHandler();
				$("#cheqno_a").hide();
			}

			function enableChequeD() {
				dialog_cheqno.updateField('finance.chqtran','#cheqno',['cheqno'],'Cheque No', '--','--', 'Cheque No');
				dialog_cheqno.offHandler();
				dialog_cheqno.handler(errorField);
				$("#cheqno_a").show();
			}

			function disableFiledCheqNo() {
				$("label[for=cheqno]").hide();
				$("#cheqno_parent").hide();

				$("label[for=bankcode]").hide();
				$("#bankcode_parent").hide();
			}

			function disableFiledCash() {
				$("label[for=cheqno]").hide();
				$("#cheqno_parent").hide();

				$("label[for=bankcode]").show();
				$("#bankcode_parent").show();
			}

			function enableFiledCheqNo() {
				$("label[for=cheqno]").show();
				$("#cheqno_parent").show();

				$("label[for=bankcode]").show();
				$("#bankcode_parent").show();
				
			}

			$('#dialog').on('dblclick',function(){
				unsaved = true;
				if(selText == "#paymode"){
					enableFiledCheqNo();
					var paymode = $('#paymode').val();
					if(paymode == "CHEQUE"){
						$("label[for=cheqno]").text(paymode+" No");
						$("#cheqno").prop("readonly",true);
						enableChequeD();
					}
					else if(paymode == "CASH"){
						disableChequeD();
						disableFiledCash();
					}
					else {
						$("label[for=cheqno]").text(paymode+" No");
						$("#cheqno").prop("readonly",false);
						disableChequeD();
					}

					$('#bankcode').val('');
					$('#bc').html('');
					$('#cheqno').val('');
					$('#cn').html('');
				}

				if(selText == "#bankcode") {
					$('#cheqno').val('');
					$('#cn').html('');
				}
			});
				/////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field: '',
				table_name:'finance.apacthdr',
				table_id:'auditno',
				filterCol: ['source', 'trantype'],
				filterVal: ['CM', 'FT'],
				sort_idno: true
				
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'ftHeaderSave',
				field:'',
				oper:oper,
				table_name:'finance.apacthdr',
				table_id:'auditno',
				sysparam: {source: 'CM', trantype: 'FT', useOn: 'auditno'},
				sysparam2: {source: 'HIS', trantype: 'PV', useOn: 'pvno'}
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{label: 'compcode', name: 'compcode', width: 10 , hidden: true,  classes: 'wrap'},
				 	{label: 'idno', name: 'idno', width: 10 , hidden: true,  classes: 'wrap'},
				 	{label: 'source', name: 'source', width: 10, hidden: true, classes: 'wrap'},
				 	{label: 'trantype', name: 'trantype', width: 10, hidden: true, classes: 'wrap'},
					{label: 'Audit No', name: 'auditno', width: 27, classes: 'wrap'},
					{label: 'Payment No', name: 'pvno', width: 40, hidden: true, classes: 'wrap'},
					{label: 'Transfer Date', name: 'actdate', width: 25, canSearch:true, checked:true, classes: 'wrap'},
					{label: 'Bank Code From', name: 'bankcode', width: 35, classes: 'wrap'},
					{label: 'Bank Code To', name: 'payto', width: 35, classes: 'wrap'},
					{label: 'Cheque Date', name: 'cheqdate', width: 90, classes: 'wrap', hidden:true},
					{label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency'},
					{label: 'Remarks', name: 'remarks', width: 40, classes: 'wrap'},
					{label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', formatter:formatterPost,},
					{label: 'Entered By', name: 'adduser', width: 30, classes: 'wrap'},
					{label: 'Entered Date', name: 'adddate', width: 30, classes: 'wrap'},
					{label: 'Paymode', name: 'paymode', width: 30, classes: 'wrap'},
					{label: 'Cheq No', name: 'cheqno', width: 30, classes: 'wrap', formatter:formatterCheqnno, unformat:unformatterCheqnno},
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
						$("#saveBut").show();
						$("#canBut").show();
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				},
				

				onSelectRow: function(rowid, selected) {
					var buts = $('#jqGrid').jqGrid('getRowData', rowid);
					auditno=rowid;
					recstatus=buts.recstatus;


					if (recstatus=='Open'){
						$("#postedBut").show();
						$("#cancelBut").show();
						$("#saveBut").show();
						$("#canBut").show();
						$("#glyphicon-edit").show();

					}

					else if (recstatus==='Posted'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#saveBut").hide();
						$("#canBut").hide();
						$("#glyphicon-edit").hide();
						//$("#glyphicon-plus").hide();
					} 

					else if (recstatus==='Cancel'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#saveBut").hide();
						$("#canBut").hide();
						$("#glyphicon-edit").hide();
						//$("#glyphicon-plus").hide();
					} 
					else {
						$("#postedBut").hide();
						$("#cancelBut").hide();
					}
				}
				
			});

				$("#postedBut").hide();
				$("#cancelBut").hide();


				$("#postedBut").click(function(){
					var param={
						action:'bankreg_save',
						oper:'add',
						field:'',
						table_name:'finance.cbtran',
						table_id:'auditno',
						skipduplicate: true,
						returnVal:true,
						sysparam:{source:'PB',trantype:'TN',useOn:'auditno'}
					};

					$.post( "../../../../assets/php/entry.php?"+$.param(param),
						{seldata:selrowData("#jqGrid")}, 
						function( data ) {
							
						}
					).fail(function(data) {
						bootbox.alert('error');
					}).success(function(data){
						refreshGrid("#jqGrid",urlParam);
						$("#postedBut").hide();
						$("#cancelBut").hide();

					});
				});


				$("#cancelBut").click(function(){
					var param={
						action:'cancel_save',
						oper:'add',
						field:'',
						table_name:'finance.cbtran',
						table_id:'auditno',
						skipduplicate: true,
						returnVal:true,
						sysparam:{source:'PB',trantype:'TN',useOn:'auditno'}
					};

					$.post( "../../../../assets/php/entry.php?"+$.param(param),
						{seldata:selrowData("#jqGrid")}, 
						function( data ) {
							
						}
					).fail(function(data) {
						bootbox.alert('error');
					}).success(function(data){
						refreshGrid("#jqGrid",urlParam);
						$("#postedBut").hide();
						$("#cancelBut").hide();

					});
				});

				////////////////////formatter status////////////////////////////////////////
				function formatterPost(cellvalue, option, rowObject){
					if (cellvalue == 'O'){
						return 'Open';
					}

					else if (cellvalue == 'P'){
						return 'Posted';
					}

					else if (cellvalue == 'C'){
						return 'Cancel';
					}
				}


				function formatterCheqnno  (cellValue, options, rowObject) {
					//return rowObject[9] != "CHEQUE" ? "&nbsp;" : $.jgrid.htmlEncode(cellValue);
					return rowObject[15] != "CHEQUE" ? "<span cheqno='"+cellValue+"'></span>" : "<span cheqno='"+cellValue+"'>"+cellValue+"</span>";

				}

				function unformatterCheqnno (cellValue, options, rowObject) {
					return $(rowObject).find('span').attr('cheqno');
				}


			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
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
				caption:"",cursor: "pointer", id: "glyphicon-edit", position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer", id: "glyphicon-plus", position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					$( "#formdata :input[name='source']" ).val( "CM" );
					$( "#formdata :input[name='trantype']" ).val( "FT" );
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////
			
			///////////////////////////////start->dialogHandler part////////////////////////////////////////////
			function makeDialog(table,id,cols,setLabel1, setLabel2,setLabel3, title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.setLabel1=setLabel1;
				this.setLabel2=setLabel2;
				this.setLabel3=setLabel3;
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
				}
			}

			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
				open: function() {
				$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));	
					
					if (selText=="#paymode") {
						paramD.filterCol=['source', 'recstatus'];
						paramD.filterVal=['CM','A'];
					} 
					
					else if(selText=="#cheqno") {
						paramD.filterCol=['bankcode', 'stat'];
						paramD.filterVal=[$("#formdata :input[name='bankcode']").val(), 'A'];
					}

					else if(selText=='#payto'){
						paramD.filterCol=['recstatus'];
						paramD.filterVal=['A'];
					}

					else {
						paramD.filterCol=['recstatus'];
						paramD.filterVal=['A'];
					}
				},
				close: function( event, ui ){
					paramD.filterCol=null;
					paramD.filterVal=null;
				},
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 40,  classes: 'pointer', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'desc', width: 70, canSearch:true, classes: 'pointer'},
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
				setLabel1=this.setLabel1, 
				setLabel2=this.setLabel2,
				setLabel3=this.setLabel3, 
				title=this.title,
				self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);

					$( "#gridDialog").jqGrid( "setLabel", "code", setLabel1 );
					$( "#gridDialog").jqGrid( "setLabel", "desc", setLabel2 );
					
					$( "#dialog" ).dialog( "option", "title", title );
					if(selText=='#cheqno')	{	
						$( "#dialog" ).dialog({
								autoOpen: false,
								width: 6/10 * $(window).width(),
								modal: true,
						});	
						$("#gridDialog").css( "width", "30%" );
						$("#gridDialog").jqGrid('hideCol', 'desc');
						$("#gridDialog").jqGrid('hideCol', 'rate');
					} else{
						$( "#dialog" ).dialog({
								autoOpen: false,
								width: 7/10 * $(window).width(),
								modal: true,
						});	
						$("#gridDialog").jqGrid('showCol', 'desc');
						$("#gridDialog").jqGrid('hideCol', 'rate');
					}
					$( "#dialog" ).dialog( "open" );
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
						if(selText=="#cheqno") {
							if(value['canSearch']){
							if(value['checked']){
								$( "#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+setLabel1+"</input></label>" );
							}
						}


						} else {
							if(value['canSearch']){
							if(value['checked']){
								$( "#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+setLabel1+"</input></label>" );
							}else{
								$( "#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+setLabel2+"</input></label>" );
							}
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
						/*if((id== "#cheqno") && ($("#paymode") == 'CHEQUE')) {
							dialog_cheqno.handler(errorField);
						}*/
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
						}
						else if((id == '#cheqno') && ($('#paymode').val() != "CHEQUE")) {
							console.log((id == '#cheqno') && ($('#paymode').val() == "CHEQUE"))
							//alert("ppp");
							$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
							$( id ).removeClass( "error" ).addClass( "valid" );
							
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

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['adduser', 'adddate', 'idno']);
		});
		