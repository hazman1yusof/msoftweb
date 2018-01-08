
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		//var editedRow=0;

		$(document).ready(function () {
			$("body").show();
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
			$('.sajanktry').click(function(){
				if($(this).css('width') == '400px'){
					$('.sajanktry div').hide();
					$('.sajanktry').velocity("reverse");
				}else{
					$('.sajanktry').velocity({
						width:"400px",
						height:"120px",
					},{
						display: "block",
						duration: 400,
						easing: "swing",
						complete: function(){$('.sajanktry div').show();},
					});
				}
			});
			//////////////////////////////////////////////////////////////

			////////////////////object for dialog handler//////////////////
			//dialog_prdept=new makeDialog('sysdb.department','#prdept',['deptcode','description'],"Dept Code","Description", 'Purchase Department');
			//dialog_suppcode=new makeDialog('material.supplier','#suppcode',['SuppCode', 'Name'],"Supplier Code","Name", 'Supplier Code');
			//dialog_deldept=new makeDialog('sysdb.department','#deldept',['deptcode','description'],"Department Code","Description", 'Delivery Department');
			

			var mycurrency =new currencymode(['#amount']);
			////////////////////////////////////start dialog//////////////////////////////////////
			var oper;
			var unsaved = false;

			$("#dialogForm")
			  .dialog({ 
				width: 9.5/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
					switch(oper) {
						case state = 'add':
						mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add Invoice AP" );	
							$("#jqGrid2").jqGrid("clearGridData", true);
							$("#jqGrid2").jqGrid('hideCol', 'action');
							$("#jqGrid2_iledit").hide();
							$("#jqGrid2_iladd").hide();
							$("#saveHeaderLabel").show();
							$("#saveDetailLabel").hide();
							$("#pg_jqGridPager2 table").show();
							enableForm('#formdata');
							rdonly('#formdata');
							hideOne('#formdata');
							break;
						case state = 'edit':
						mycurrency.formatOn();
							$( this ).dialog( "option", "title", "Edit Invoice AP" );
							$("#jqGrid2").jqGrid('hideCol', 'action');
							$("#jqGrid2_iledit").hide();
							$("#jqGrid2_iladd").hide();
							$("#saveHeaderLabel").show();
							$("#saveDetailLabel").hide();
							$("#pg_jqGridPager2 table").show();
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly('#formdata');
							$('#formdata :input[hideOne]').show();
							break;
						case state = 'view':
						mycurrency.formatOn();
							$( this ).dialog( "option", "title", "View Invoice AP" );
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							$("#jqGrid2").jqGrid('hideCol', 'action');
							break;
					}
					if(oper!='view'){
						dialog_prdept.handler(errorField);
						dialog_suppcode.handler(errorField);
						dialog_deldept.handler(errorField);
						

					}
					if(oper!='add'){
						dialog_prdept.check(errorField);
						dialog_suppcode.check(errorField);
						dialog_deldept.check(errorField);
						
					}
					if(oper =='edit'){
						if(recstatus == 'P') {
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							$("#formdata a").off();
						}

						else if(recstatus == 'C') {
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							$("#formdata a").off();
						}
					}
				},
				beforeClose: function(event, ui){
					if(unsaved){
						event.preventDefault();
						bootbox.confirm("Are you sure want to leave without save?", function(result){
							if (result == true) {
								unsaved = false
						        $("#dialogForm").dialog('close');
							}
						});
					}
					
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					emptyFormdata(errorField,'#formdata2');
					$('.alert').detach();
					$("#formdata a").off();
					//$('#jqGrid2_ilcancel').click();
					$("#refresh_jqGrid").click();
					
				},
			  });
			////////////////////////////////////////end dialog///////////////////////////////////////////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:['material.delordhd', 'material.supplier'],
				table_id:'delordhd_idno',
				sort_idno:true,
				join_type:['LEFT JOIN'],
				join_onCol:['supplier.suppcode'],
				join_onVal:['delordhd.suppcode'],
				fixPost:true,
				//filterCol: ['source'],
				//filterVal: ['AP'],
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'invoiceAP_save',
				field:'',
				oper:oper,
				table_name:'material.delordhd',
				table_id:'idno'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	//{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
					{ label: 'Record No', name: 'delordhd_recno', width: 10, classes: 'wrap', canSearch: true},
					{ label: 'Purchase Department', name: 'delordhd_prdept', width: 20, classes: 'wrap'},
					{ label: 'GRN No', name: 'delordhd_grnno', width: 15, classes: 'wrap', canSearch: true},
					{ label: 'Received Date', name: 'delordhd_trandate', width: 20, classes: 'wrap', canSearch: true},
					{ label: 'Supplier Name', name: 'supplier_name', width: 25, classes: 'wrap'},
					{ label: 'Purchase Order No', name: 'delordhd_delordno', width: 25, classes: 'wrap', canSearch: true},
					{ label: 'DO No', name: 'delordhd_delordno', width: 25, classes: 'wrap', canSearch: true},
					{ label: 'Invoice No', name: 'delordhd_invoiceno', width: 20, classes: 'wrap'},
					{ label: 'Trantype', name: 'delordhd_trantype', width: 20, classes: 'wrap', hidden: true},
					{ label: 'Document No', name: 'delordhd_docno', width: 20, classes: 'wrap'},
					{ label: 'Total Amount', name: 'delordhd_totamount', width: 20, classes: 'wrap'},
					{ label: 'Status', name: 'delordhd_recstatus', width: 20, hidden:'true'},
					
					{ label: 'Supplier Code', name: 'delordhd_suppcode', width: 25, classes: 'wrap',hidden:true, canSearch: true},
					{ label: 'srcdocno', name: 'delordhd_srcdocno', width: 25, classes: 'wrap',align: 'right',hidden:true},
					{ label: 'Delivery Department', name: 'delordhd_deldept', width: 25, classes: 'wrap',hidden:true},
					{ label: 'Sub Amount', name: 'delordhd_subamount', width: 50, classes: 'wrap', hidden:true},
					{ label: 'Amount Discount', name: 'delordhd_amtdisc', width: 25, classes: 'wrap', hidden:true},
					{ label: 'perdisc', name: 'delordhd_perdisc', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Delivery Date', name: 'delordhd_deldate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Time', name: 'delordhd_trantime', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'respersonid', name: 'delordhd_respersonid', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'checkpersonid', name: 'delordhd_checkpersonid', width: 40, hidden:'true'},
					{ label: 'checkdate', name: 'delordhd_checkdate', width: 40, hidden:'true'},
					{ label: 'suppperson', name: 'delordhd_suppperson', width: 40, hidden:'true'},
					{ label: 'Remarks', name: 'delordhd_remarks', width: 40, hidden:'true'},
					{ label: 'adduser', name: 'delordhd_adduser', width: 40, hidden:'true'},
					{ label: 'adddate', name: 'delordhd_adddate', width: 40, hidden:'true'},
					{ label: 'upduser', name: 'delordhd_upduser', width: 40, hidden:'true'},
					{ label: 'upddate', name: 'delordhd_upddate', width: 40, hidden:'true'},
					{ label: 'reason', name: 'delordhd_reason', width: 40, hidden:'true'},
					{ label: 'rtnflg', name: 'delordhd_rtnflg', width: 40, hidden:'true'},
					{ label: 'reqdept', name: 'delordhd_reqdept', width: 40, hidden:'true'},
					{ label: 'credcode', name: 'delordhd_credcode', width: 40, hidden:'true'},
					{ label: 'impflg', name: 'delordhd_impflg', width: 40, hidden:'true'},
					{ label: 'allocdate', name: 'delordhd_allocdate', width: 40, hidden:'true'},
					{ label: 'postdate', name: 'delordhd_postdate', width: 40, hidden:'true'},
					{ label: 'deluser', name: 'delordhd_deluser', width: 40, hidden:'true'},
					{ label: 'idno', name: 'delordhd_idno', width: 40, hidden:'true'},


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
					var jg=$("#jqGrid").jqGrid('getRowData',rowid);
					auditno=rowid;
					pvno=jg.pvno;
					amount=jg.delordhd_amount;
					recstatus=jg.delordhd_recstatus;
				
					

					// urlParam2.filterVal[0]=rowid;
					// if(rowid != null) {
					// 	refreshGrid("#jqGrid2",urlParam2);
					// }

					//////////////// hide/show button
					if (recstatus=='O'){
						$("#postedBut").show();
						$("#cancelBut").show();
					}

					else if (recstatus==='P'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#glyphicon-edit").hide();
					} 

					else if (recstatus==='C'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#glyphicon-edit").hide();
					} 

					else {
						$("#postedBut").hide();
						$("#cancelBut").hide();
					}
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					/*$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					if 
						(ttype1 = ($("#apacthdr_ttype option:selected" ).text()=='Supplier')){
							$("#jqGrid2").show();
						}
					else {
						$("#jqGrid2").hide();
					}

					if(oper == 'edit'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					if (ttype1 = ($("#apacthdr_ttype option:selected" ).text()=='Supplier')){
							$("#jqGrid2").show();
						}
					else {
						$("#jqGrid2").hide();
					}*/

				},
				
			});

		//	$("#jqGrid").jqGrid('setLabel','apacthdr_amount','Amount',{'text-align':'right'});
			
			/////////////////////////formatter & unformat/////////////////////////////////////////////////////////
			function formatterPost(cellvalue, options, rowObject){
				if(cellvalue == 'O'){
					return "Open";
				}else if(cellvalue == 'P') { 
					return "Posted";
				}else if (cellvalue == 'C'){
					return "Cancel";
				}
			}

			function  unformatterPost(cellvalue, options){
				if(cellvalue == 'Open'){
					return "O";
				}else if(cellvalue == 'Posted') { 
					return "P";
				}else if (cellvalue == 'Cancel'){
					return "C";
				}
			}


			/////////////////////////////// for Button /////////////////////////////////////////////////////////
			var adtNo
			function sometodo(){
				$("#jqGrid2_iledit").show();
				$("#jqGrid2").jqGrid('showCol', 'action');
				$('#formdata  textarea').prop("readonly",true);
				$('#formdata :input[hideOne]').show();
				$('#formdata input').prop("readonly",true);
				$('#formdata  input[type=radio]').prop("disabled",true);
				$("input[id*='_auditno']").val(auditno);
				$("#formdata a").off(); 
			}

			////////////////////selected///////////////

		/*	$('#apacthdr_ttype').on('change', function() {
				let ttype1 = $("#apacthdr_ttype option:selected" ).val();
				if(ttype1 == 'IN') {
					$("#formdata :input[name='apacthdr_source']").val("AP");
					$("#formdata :input[name='apacthdr_trantype']").val("IN");
				}else if(ttype1 == 'DN') {
					$("#formdata :input[name='apacthdr_source']").val("AP");
					$("#formdata :input[name='apacthdr_trantype']").val("DN");
				}

				if (($("#apacthdr_ttype option:selected" ).text()=='Supplier')){
					$('#save').hide();
					$('#ap_parent').show();
				}

				else {
					$('#save').show();
					$('#ap_parent').hide();
				}
				

			});*/

			$('#save').show();
			
			$("#save").click(function(){
				unsaved = false;
				mycurrency.formatOff();
				mycurrency.check0value(errorField);
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					saveHeader("#formdata", oper,saveParam,{idno:selrowData('#jqGrid').delordhd_idno});
					unsaved = false;
					$("#dialogForm").dialog('close');

					// $("#saveHeaderLabel").hide();
					// $("#saveDetailLabel").show();
					// $("#jqGrid2_iladd").show();
					// $("#jqGrid2_iladd").click();
				}else{
					mycurrency.formatOn();
				}
			});

			$("#postedBut").click(function(){
				var param={
					action:'invoiceAP_post',
					field:'',
					oper:'add',
					table_name:'material.delordhd',
					table_id:'idno'
				};
				$.post( "../../../../assets/php/entry.php?"+$.param(param),{seldata:selrowData('#jqGrid')},function(data) {},'json'
				).fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).success(function(data){
					
				});
			});

			////////////////////save Header////////////////////////////////////////

			function saveHeader(form,oper,saveParam,obj){//saveonly
				if(obj==null){
					obj={};
				}
				saveParam.oper=oper;

				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
				},'json').fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).success(function(data){
					
					if(oper=='add'){
						auditno = data.auditno;
						pvno = data.pvno;
						sometodo();
						$('#auditno').val(auditno);
						$('#pvno').val(pvno);
					}else if(oper=='edit'){
						$("#formdata :input[name*='auditno']").val(selrowData('#jqGrid').auditno);
						sometodo();
						$('#auditno').val(auditno);
						$('#pvno').val(pvno);
						$('#amount').val(amount);
					}
				}); 
			}

			$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea',  function(){
					unsaved = true;
			});

			///////////////////utk dropdown search By/////////////////////////////////////////////////
			searchBy();
			function searchBy(){
			$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
					if(value['canSearch']){
						if(value['selected']){
							$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
						}
						else{
						$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
						}
					}
				});
			}

			$('#searchText').keyup(function() {
				delay(function(){
					searchMain($('#searchText').val(),$('#Scol').val());
				}, 500 );
			});

			$('#Scol').change(function(){
				searchMain($('#searchText').val(),$('#Scol').val());
			});


	/*		function populateSummary(form,itemcode,uomcode,deptcode){

				emptyFormdata([],form);
				$(form+' #netmvval1').val(selrowData('#jqGrid').netmvval1);

				let param={
					action:'get_value_default',
					field: ['openbalval','openbalqty','netmvval1','netmvqty1','netmvval2','netmvqty2','netmvval3','netmvqty3','netmvval4','netmvqty4','netmvval5','netmvqty5', 'netmvval6','netmvqty6','netmvval7','netmvqty7','netmvval8','netmvqty8','netmvval9','netmvqty9','netmvval10','netmvqty10',
					'netmvval11','netmvqty11','netmvval2','netmvqty12'],
					table_name:'material.stockloc',
					table_id:'itemcode',
					filterCol:['itemcode', 'uomcode', 'deptcode', 'year'],
					filterVal:[itemcode, uomcode, deptcode, $("#getYear").val()]
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
							
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						$(form+' #openbalqty').val(data.rows[0].openbalqty);
						$(form+' #openbalval').val(data.rows[0].openbalval);
						$(form+' #netmvqty1').val(data.rows[0].netmvqty1);
						$(form+' #netmvval1').val(data.rows[0].netmvval1);
						$(form+' #netmvqty2').val(data.rows[0].netmvqty2);
						$(form+' #netmvval2').val(data.rows[0].netmvval2);
						$(form+' #netmvqty3').val(data.rows[0].netmvqty3);
						$(form+' #netmvval3').val(data.rows[0].netmvval3);
						$(form+' #netmvqty4').val(data.rows[0].netmvqty4);
						$(form+' #netmvval4').val(data.rows[0].netmvval4);
						$(form+' #netmvqty5').val(data.rows[0].netmvqty5);
						$(form+' #netmvval5').val(data.rows[0].netmvval5);
						$(form+' #netmvqty6').val(data.rows[0].netmvqty6);
						$(form+' #netmvval6').val(data.rows[0].netmvval6);
						$(form+' #netmvqty7').val(data.rows[0].netmvqty7);
						$(form+' #netmvval7').val(data.rows[0].netmvval7);
						$(form+' #netmvqty8').val(data.rows[0].netmvqty8);
						$(form+' #netmvval8').val(data.rows[0].netmvval8);
						$(form+' #netmvqty9').val(data.rows[0].netmvqty9);
						$(form+' #netmvval9').val(data.rows[0].netmvval9);
						$(form+' #netmvqty10').val(data.rows[0].netmvqty10);
						$(form+' #netmvval10').val(data.rows[0].netmvval10);
						$(form+' #netmvqty11').val(data.rows[0].netmvqty11);
						$(form+' #netmvval11').val(data.rows[0].netmvval11);
						$(form+' #netmvqty12').val(data.rows[0].netmvqty12);
						$(form+' #netmvval12').val(data.rows[0].netmvval12);
						$(form+' #accumqty').val(data.rows[0].accumqty);
						$(form+' #accumval').val(data.rows[0].accumval);
                       
						

					}
				});
			}*/


			
			function searchMain(Stext,Scol){

				if(Scol == 'itemcode'){
					$('#searchText').prop('disabled',true);
					urlParam.searchCol=null;
					urlParam.searchVal=null;
					//urlParam.filterCol=['source','trantype'];
					//urlParam.filterVal=['PB','IN'];
				}else{
					$('#searchText').prop('disabled',false);
					//urlParam.filterCol=['source'];
					//urlParam.filterVal=['PB'];

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
				}
             refreshGrid('#jqGrid',urlParam);
			}

              $('#filter').change(function(){
				if($(this).val() == 'itemcode' || $(this).val() == 'deptcode' ){
					$('#search').prop('disabled',false);
				}else{
					$('#search').prop('disabled',true);
				}
			});

              $('#search').click(function(){
				$( "#dialogbox" ).dialog( "open" );
			});





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
				caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
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
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['adduser','adddate','idno']);

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

		/*	//////////////////////////////////////grid2/////////////////////////////////////////////////////////
			var operDetail;

			var urlParam2={
				action:'get_table_default',
				field:['compcode','source','trantype','auditno','lineno_','deptcode','category','document', 'AmtB4GST', 'GSTCode', 'amount'],
				table_name:'finance.apactdtl',
				table_id:'lineno_',
				filterCol:['auditno', 'recstatus','source','trantype'],
				filterVal:['', 'A','AP',''],
			}

			var saveParam2={
				action:'save_table_default',
				field:'',
				table_name:'finance.apactdtl',
				table_id:'lineno_',
				skipduplicate:true,
				lineno:{useOn:'auditno',useVal:'',useBy:'lineno_'},
				filterCol:['auditno'],
				filterVal:[''],
			}

			$("#jqGrid2").jqGrid({
				datatype: "local",
				editurl: "../../../../assets/php/entry.php?action=APDetail_save",
				colModel: [
				 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
				 	{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
				 	{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
				 	{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:true},
					{ label: 'Line No', name: 'lineno_', width: 20, classes: 'wrap', hidden:true, editable:true}, //canSearch: true, checked: true},
					{ label: 'Delivery Order Number', name: 'deptcode', width: 25, classes: 'wrap', canSearch: true, editable: true,
								editrules:{required: true,custom:true, custom_func:cust_rules},
								formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:deptcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Purchase Order Number', name: 'category', width: 25, edittype:'text', classes: 'wrap', editable: true,
								editrules:{required: true,custom:true, custom_func:cust_rules},
								formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:categoryCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Record No', name: 'document', width: 29, classes: 'wrap', editable: true,
								//editrules:{required: true},
								edittype:"text",
					},
					{ label: 'Amount Before GST', name: 'AmtB4GST', width: 25, classes: 'wrap',
								formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
								editable: true,
								align: "right",
								editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'GST Code', name: 'GSTCode', width: 25, edittype:'text', classes: 'wrap', editable: true,
								editrules:{required: true,custom:true, custom_func:cust_rules},
								formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:GSTCodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Amount', name: 'amount', width: 25, classes: 'wrap', 
								formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
								editable: true,
								align: "right",
								editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'GRN No', name: 'grnno', width: 29, classes: 'wrap', editable: true,
								//editrules:{required: true},
								edittype:"text",
					},
					{ label: 'Action', name: 'action', width : 9,  formatter: "actions", editable:false,
											formatoptions: {
											    keys: true,
											    editbutton: false,
											    delbutton: true,
											    delOptions: {
											    	mtype: 'POST',
											    	onclickSubmit: function (options, rowid) {
											    			var detVa=$("#jqGrid2").jqGrid('getRowData',rowid);
											    			auditno=detVa.auditno;
                        									lineno_=detVa.lineno_;
													        options.url = '../../../../assets/php/entry.php?' + jQuery.param({
													            action: 'APDetail_save',
													            auditno: detVa.auditno,
													            lineno_: detVa.lineno_,
													            source: detVa.source,
													            trantype: detVa.trantype
													        });
													},
													afterSubmit: function (response, postdata) {
														return $('#amount').val(response.responseText);
													},
											    },
											}
					},
				],
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 300,
				height: 200,
				shrinkToFit: true,
				rowNum: 30,
				sortname: 'lineno_',
        		sortorder: "desc",

        		onClose: function () {
        			console.log('before show form');
				},
				pager: "#jqGridPager2",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					$( "#jqGrid2_ilcancel" ).unbind();
					$( "#jqGrid2_ilcancel" ).on( "click", function(event) {
						event.preventDefault();
						event.stopPropagation();
						bootbox.confirm("Are you sure want to cancel?", function(result){
							if (result == true) {
								refreshGrid("#jqGrid2",urlParam2);
							}
						});
					});
				},

				beforeSubmit: function(){
					
			
				},
				onSelectRow:function(rowid, selected){
					$("#jqGrid2 input[name='AmtB4GST']").on('keydown',  function() { 
						delay(function(){
							if($("#jqGrid2 input[name='GSTCode']").val() == '') {
								var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
								var amount = amntb4gst;
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
							}else{
								var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
								var amount = amntb4gst+(amntb4gst*(rate/100));//.toFixed(2);
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
							}
							}, 1000 );
					});

					$("#jqGrid2 input[name='GSTCode']").on('keydown',  function(e) { 
						var code = e.keyCode || e.which;
							if (code == '9') { // -->for tab
								delay(function(){
									var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
								var amount = amntb4gst+(amntb4gst*(rate/100));//.toFixed(2);
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
								}, 900 );
							}
					});

					$('#dialog').on('dblclick',function(){
						if(selText=="#jqGrid2 input[name='GSTCode']"){
							var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
							var amount = amntb4gst+(amntb4gst*(rate/100));//.toFixed(2);
							$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
						}
					});
				},		
			});
			
			$("#jqGrid2").jqGrid('setLabel','AmtB4GST','Amount Before GST',{'text-align':'right'});
			$("#jqGrid2").jqGrid('setLabel','amount','Amount',{'text-align':'right'});

			function cust_rules(value,name){
				var temp;
				switch(name){
					case 'Department':temp=$('#deptcode');break;
					case 'Category':temp=$('#category');break;
					case 'GST Code':temp=$('#GSTCode');break;
				}
				return(temp.parent().hasClass("has-error"))?[false,"Please enter valid "+name+" value"]:[true,''];
			}

			////////formatter checkdetail//////////
			function showdetail(cellvalue, options, rowObject){
				var field,table;
				switch(options.colModel.name){
					case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
					case 'category':field=['catcode','description'];table="material.category";break;
					case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
				}
				var param={action:'input_check',table:table,field:field,value:cellvalue};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.row)){
						$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.row.description+"</span>");
					}
				});
				return cellvalue;
			}

			///////custom input/////
			function deptcodeCustomEdit(val,opt){  	
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function categoryCustomEdit(val,opt){  
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));
				return $('<div class="input-group"><input id="category" name="category" type="text" class="form-control input-sm" data-validation="required" value="'+val+'"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function GSTCodeCustomEdit(val,opt){  
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));
				return $('<div class="input-group"><input id="GSTCode" name="GSTCode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}



			function galGridCustomValue (elem, operation, value){	
				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}

			var myEditOptions = {
		        keys: true,
		        oneditfunc: function (rowid) {
		        },
		        aftersavefunc: function (rowid, response, options) {
		           $('#amount').val(response.responseText);
		           //console.log(response);
		        },
		        afterRestore:function(rowid) {
                 
             },
		    };



			$("#jqGrid2").inlineNav('#jqGridPager2',{	
				add:true,
				edit:true,
				addParams: { 
        			addRowParams: myEditOptions
   				},
   				editParams: myEditOptions

			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "saveHeaderLabel",
				caption:"Detail",cursor: "pointer",position: "last", 
				buttonicon:"",
				title:"Detail"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "saveDetailLabel",
				caption:"Header",cursor: "pointer",position: "last", 
				buttonicon:"",
				title:"Header"
			});

			$("#saveHeaderLabel").click(function(){
				//radbuts.check();
				unsaved = false;
				mycurrency.formatOff();
					mycurrency.check0value(errorField);
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					saveHeader("#formdata", oper,saveParam);
					unsaved = false;

					$("#saveHeaderLabel").hide();
					$("#saveDetailLabel").show();
					$("#jqGrid2_iladd").show();
					$("#jqGrid2_iladd").click();
				}else{
					mycurrency.formatOn();
				}
			});

			$("#saveDetailLabel").click(function(){
				$("#formdata a").on('click',function(){
					dialog_paymode.handler(errorField);
					dialog_bankcode.handler(errorField);
					dialog_cheqno.handler(errorField);
					dialog_payto.handler(errorField);
				});

				//$('#jqGrid2_ilcancel').click();
				$("#jqGrid2_iladd").hide();
				$("#saveHeaderLabel").show();
				$("#saveDetailLabel").hide();
				$("#jqGrid2_iledit").hide();
				$('#formdata  textarea').prop("readonly",false);
				$('#formdata input').prop("readonly",false);
				$('#formdata  input[type=radio]').prop("disabled",false);
				$('#formdata  input[rdonly]').prop("readonly",true);
				$('#formdata  input[frozeOnEdit]').prop("readonly",true);
			});
			
			$("#jqGrid2_iladd").click(function(){
				unsaved = false;

				$("#formdata :input[name='amount']").val($("#amount").val());
				$("input[id*='_auditno']").val(auditno);
				$("input[id*='_auditno']").attr('readonly','readonly');
				$("input[id*='_source']").val($("#source").val());
				$("input[id*='_trantype']").val($("#trantype").val());
				$("input[id*='_lineno_']").val($("#lineno_").val());

				dialog_deptcode=new makeDialog('sysdb.department',"#jqGrid2 input[name='deptcode']",['deptcode','description'],'Department Code','Description', '--', 'Department');
				dialog_category=new makeDialog('material.category',"#jqGrid2 input[name='category']",['catcode','description'],'Category Code','Description', '--', 'Category');
				dialog_GSTCode=new makeDialog('hisdb.taxmast',"#jqGrid2 input[name='GSTCode']",['taxcode','description','rate'],'GST Code','Description', 'Rate', 'GST Code');
					
				dialog_deptcode.handler(errorField);
				dialog_category.handler(errorField);
				dialog_GSTCode.handler(errorField);

				

				$("input[id*='_amount']").keydown(function(e) {
					//console.log('keydown called');
						var code = e.keyCode || e.which;
							if (code == '9') { // -->for tab
								$('#jqGrid2_ilsave').click();
								//refreshGrid("#jqGrid2",urlParam2);
								delay(function(){
									$('#jqGrid2_iladd').click();
								}, 1500 );
							}
				});
			});

			$("#jqGrid2_iledit").click(function(){
				dialog_deptcode=new makeDialog('sysdb.department',"#jqGrid2 input[name='deptcode']",['deptcode','description'],'Department Code','Description', 'Department');
				dialog_category=new makeDialog('material.category',"#jqGrid2 input[name='category']",['catcode','description'],'Category Code','Description', 'Category');
				dialog_GSTCode=new makeDialog('hisdb.taxmast',"#jqGrid2 input[name='GSTCode']",['taxcode','description','rate'],'GST Code','Description', 'Rate', 'GST Code');

				dialog_deptcode.handler(errorField);
				dialog_category.handler(errorField);
				dialog_GSTCode.handler(errorField);
			});

			$("#jqGrid2_ilsave").click(function(){
				unsaved = false;
				
			});

			   $("#jqGrid2_ilcancel").click(function(event, ui){
			
				if(unsaved){
						event.preventDefault();
						bootbox.confirm("Are you sure want to cancel?", function(result){
							if (result == true) {
								unsaved == false
								//$("#dialogForm").dialog('close');
							}
						});
					}
				
			});*/

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////end grid2/////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		var creditorDialog2 = new ordialog('suppcode','material.supplier','#apacthdr_suppcode',[{label:'Supplier Code',name:'suppcode'},{label:'Supplier Name',name:'name'}]);
		creditorDialog2.makedialog();

		var paytoDialog2 = new ordialog('payto','material.supplier','#apacthdr_payto',[{label:'Supplier Code',name:'suppcode'},{label:'Supplier Name',name:'name'}]);
		paytoDialog2.makedialog();

		function ordialog(unique,table,id,cols){
			this.gridname = "othergrid_"+unique;
			this.dialogname = "otherdialog_"+unique;
			this.otherdialog = "<div id='"+this.dialogname+"' title='Select your choice'><div class='panel panel-default'><div class=panel-body><div id='"+this.gridname+"_c' class='col-xs-12' align='center'><table id='"+this.gridname+"' class='table table-striped'></table><div id='"+this.gridname+"Pager'></div></div></div></div></div>";
			this.button = null;
			this.field = cols;
			this.textfield = id;
			this.urlParam={
				action:'get_table_default',
				table_name:table,
				field:getfield(cols),
				table_id:getfield(cols)[0]
			};
			this.dialoguse;
			this.jqgriduse;

			this.makedialog = function(){
				$("body").append(this.otherdialog);
				makejqgrid(this);
				makedialog(this);
				$(this.textfield).on('keydown',{data:this},onTab);
			}

			function makedialog(obj){
				obj.dialoguse = $("#"+obj.dialogname).dialog({
					autoOpen: false,
					width: 7/10 * $(window).width(),
					modal: true,
					open: function(){
						$("#"+obj.gridname).jqGrid ('setGridWidth', Math.floor($("#"+obj.gridname+"_c")[0].offsetWidth-$("#"+obj.gridname+"_c")[0].offsetLeft));
					},
					close: function( event, ui ){
					},
				});
			}

			function onTab(event){
				if(event.key == "Tab"){
					event.preventDefault();
					let text = $(this).val();
					if(text != ' '){
						let split = text.split(" "),searchCol=[],searchVal=[],searchCol2=[],searchVal2=[];
						$.each(split, function( index, value ) {
							searchCol.push(getfield(cols)[0]);
							searchVal.push('%'+value+'%');
							searchCol2.push(getfield(cols)[1]);
							searchVal2.push('%'+value+'%');
						});
						event.data.data.urlParam.searchCol=searchCol;
						event.data.data.urlParam.searchVal=searchVal;
						event.data.data.urlParam.searchCol2=searchCol2;
						event.data.data.urlParam.searchVal2=searchVal2;
					}
					$("#"+event.data.data.dialogname).dialog("open");
					refreshGrid("#"+event.data.data.gridname,event.data.data.urlParam);
				}
			}

			function makejqgrid(obj){
				obj.jqgriduse = $("#"+obj.gridname).jqGrid({
					datatype: "local",
					colModel: obj.field,
					autowidth:true,
					viewrecords: true,
					loadonce:false,
					width: 200,
					height: 200,
					rowNum: 300,
					ondblClickRow: function(rowid, iRow, iCol, e){
						$("#"+obj.dialogname).dialog( "close" );
						$(obj.textfield).val(rowid);
						$(obj.textfield).parent().next().html(selrowData("#"+obj.gridname).name);//this
						$(obj.textfield).focus();
						$("#"+obj.gridname).jqGrid("clearGridData", true);
					},
				});

				addParamField("#"+obj.gridname,false,obj.urlParam);
			}

			function getfield(field){
				var fieldReturn = [];
				field.forEach(function(element){
					fieldReturn.push(element.name);
				});
				return fieldReturn;
			}
		}

		///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
			function makeDialog(table,id,cols,setLabel1,setLabel2,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.setLabel1=setLabel1;
				this.setLabel2=setLabel2;
				// this.setLabel3=setLabel3;
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
				open: function(){
					$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));
					// if(selText=='#trantype'){
					// 	paramD.filterCol=['source'];
					// 	paramD.filterVal=['PO'];
					// }
					
					// else{
					// 	paramD.filterCol=['recstatus'];
					// 	paramD.filterVal=['A'];
					// }
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
					{ label: 'Rate', name: 'rate', width: 400, classes: 'pointer', hidden: 'true'},
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
				},
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,setLabel1=this.setLabel1,setLabel2=this.setLabel2,setLabel3=this.setLabel3,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);
					$( "#gridDialog" ).jqGrid( "setLabel", "code", setLabel1);
					$( "#gridDialog" ).jqGrid( "setLabel", "desc", setLabel2);
					$( "#dialog" ).dialog( "option", "title", title );
					paramD.filterCol=null;
					paramD.filterVal=null;
					if(selText=='#prdept'){
							paramD.filterCol=['compcode', 'purdept'];
							paramD.filterVal=['9A','!=.0'];
						}else{
							paramD.filterCol=['compcode', 'purdept'];
							paramD.filterVal=['9A','=.0'];
						}
					if(selText=='#suppcode'){
							paramD.filterCol=['compcode', 'recstatus'];
							paramD.filterVal=['9A','A'];
						}
					if(selText=='#deldept'){
							paramD.filterCol=['compcode', 'storedept'];
							paramD.filterVal=['9A','!=.0'];
						}	
					//}
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
						$( id ).parent().siblings( ".help-block" ).show();

						if(id== "#jqGrid2 input[name='GSTCode']") {
							taxcode=data.row.taxcode;
							rate=data.row.rate;
						} else if (id == "#jqGrid2 input[name='deptcode']"){
							deptcode=data.row.deptcode;
						} else if (id == "#jqGrid2 input[name='catcode']"){
							catcode=data.row.catcode;
						} 

					}else if(data.msg=='fail'){
						if(id=='#payto'){
							$( id ).parent().siblings( ".help-block" ).hide();
						}
						else if((id == '#cheqno') && ($('#paymode').val() != "CHEQUE")) {
							console.log((id == '#cheqno') && ($('#paymode').val() == "CHEQUE"))
							$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
							$( id ).removeClass( "error" ).addClass( "valid" );
						}else{
							$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
							$( id ).removeClass( "valid" ).addClass( "error" );
							$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
							if($.inArray(id,errorField)===-1){
								errorField.push( id );
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
});