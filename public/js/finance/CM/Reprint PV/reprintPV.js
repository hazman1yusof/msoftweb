
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
						toggleFormData('#jqGrid','#formdata');
					}
				},
				close: function( event, ui ) {
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
				table_name:'finance.apacthdr',
				table_id:'auditno',
				sort_idno:true,
				filterCol: ['recstatus'],
				filterVal: ['P'],
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'finance.glmasref',
				table_id:'glaccount'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					//{label: 'compcode', name: 'compcode', width: 90 , hidden: true,  classes: 'wrap'},
				 	{label: 'source', name: 'source', width: 90, hidden: true, classes: 'wrap'},
				 	{label: 'trantype', name: 'trantype', width: 90, hidden: true, classes: 'wrap'},
					{label: 'Audit No', name: 'auditno', width: 50, classes: 'wrap', hidden:true,},
					{ label: 'System Auto No', name: 'auditno', width: 35, classes: 'wrap',
						formatter: function (cellValue, options, rowObject) {
									return rowObject[0] + ' - ' + rowObject[1] + ' - ' + cellValue;
                        	}
					},
					{label: 'Pv No', name: 'pvno', width: 20, classes: 'wrap', canSearch: true, checked: true},
					{label: 'Date', name: 'actdate', width: 25, canSearch:true, classes: 'wrap'},
					{label: 'Bank Code', name: 'bankcode', width: 30, classes: 'wrap'},
					{label: 'Cheque No', name: 'cheqno', width: 30, canSearch: true, classes: 'wrap'},
					{label: 'Pay To', name: 'payto', width: 45, classes: 'wrap', canSearch: true,},
					{label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency' }, 
					{label: 'Paymode', name: 'paymode', width: 25, hidden:true, classes: 'wrap'},
					{label: 'Cheque Date', name: 'cheqdate', width: 40, classes: 'wrap', hidden:true},	
					{label: 'Remarks', name: 'remarks', width: 40, classes: 'wrap',hidden:true,},
					{label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', hidden:true,},//formatter: formatter
					{label: 'Entered By', name: 'adduser', width: 50, classes: 'wrap',hidden:true,},
					{label: 'Entered Date', name: 'adddate', width: 90, classes: 'wrap',hidden:true,},
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

					var jg=$("#jqGrid").jqGrid('getRowData',rowid);
					auditno=rowid;
					trantype=jg.trantype;
					source = jg.source;
					openForm();
					
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				onSelectRow:function(rowid, selected){
					var jg=$("#jqGrid").jqGrid('getRowData',rowid);
					auditno=rowid;
					trantype=jg.trantype;
				},
				
			});

			/////////////////////////////////formater ////////////////////////////////////////////////////////

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
					openForm();
				},
			}
			).jqGrid('navButtonAdd', '#jqGridPager', {
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-print", 
				title:"Print Selected Row", 
				onClickButton: function(){
					var genpdf = new generatePDF('#testreprintPVPDF','#formdata','#jqGrid');
					genpdf.printEvent();
					/*$.alert({
					    title: 'Alert!',
					    content: 'Print PV',
					});*/
					////window.open("http://localhost/msoftweb290716/page/finance/CM/Reprint PV/reprintPVPDF.php", "_self");
				}
			});

			function openForm(){
				if (source == 'CM' && trantype == 'FT') {

					loadurl="../../CM/bankTransfer/bankTransfer.php #dialogForm";
					var urlParam={
							action:'get_value_default',
							field: ['auditno','pvno','actdate','paymode','bankcode','cheqno','cheqdate','amount','payto','remarks'],
							table_name:'finance.apacthdr',
							table_id:'auditno',
							filterCol: ['source', 'trantype','auditno'],
							filterVal: ['CM', 'FT',auditno],
						}

					$.get( "../../../../assets/php/entry.php?"+$.param(urlParam), function( data ) {
							
						},'json').done(function(data) {
							if(!$.isEmptyObject(data.rows)){
								$( "#dialogForm" ).load(loadurl, function(){
									populatePage(data.rows[0],'#formdata');
									$("#dialogForm").dialog( "option", "title", "View Bank Transaction" );
									disableForm('#formdata');
									$("#dialogForm").dialog("open");
								});
							}
						});
				}

				if(source == 'CM' && trantype == 'DP') {

					loadurl="../../CM/Direct%20Payment/DirectPayment.php #dialogForm";

					var urlParam={
							action:'get_value_default',
							field: ['*'],
							table_name:'finance.apacthdr',
							table_id:'auditno',
							filterCol: ['source', 'trantype','auditno'],
							filterVal: ['CM', 'DP',auditno],
						}

					jqgrid=[ 
							{
								id:'#jqGrid2',
								urlParam:{
									action:'get_table_default',
									field:['deptcode','category','document', 'AmtB4GST', 'GSTCode', 'amount'],
									table_name:'finance.apactdtl',
									table_id:'deptcode',
									filterCol:['auditno', 'recstatus'],
									filterVal:[auditno, 'A'],
								}
							}
						]

					urlParam.filterVal[2] = auditno;

					$.get( "../../../../assets/php/entry.php?"+$.param(urlParam), function( data ) {
							
						},'json').done(function(data) {
							if(!$.isEmptyObject(data.rows)){
								$( "#dialogForm" ).load(loadurl, function(){
									populatePage(data.rows[0],'#formdata');
									$("#dialogForm").dialog( "option", "title", "View Direct Payment" );
									disableForm('#formdata');
									$("#dialogForm").dialog("open");
									jqgrid[0].urlParam.filterVal[0] = auditno;
										jqgrid_inpage(
											jqgrid[0].id,
											populate_colmodel(jqgrid[0].urlParam.field),
											jqgrid[0].urlParam
										);
								});
							}
						});
				}

				function populate_colmodel(field){
					var colmodel = [];
					field.forEach(function(element){
						colmodel.push({label:element,name:element});
					});
					return colmodel;
				}

				function jqgrid_inpage(jqgrid,colmodel,urlParam){
					var jqgrid = $("#dialogForm "+jqgrid).jqGrid({
						datatype: "local",
						colModel: colmodel,
						autowidth:true,
						viewrecords: true,
						loadonce:false,
						width: 200,
						height: 200,
						rowNum: 300,
					});

					addParamField(jqgrid,true,urlParam);
				}

				function populatePage(obj,form){
					$.each(obj, function( index, value ) {
						var input=$(form+" [name='"+index+"']");
						if(input.is("[type=radio]")){
							$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
						}else{
							input.val(value);
						}
					});
				}
			}

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam);
		});
		