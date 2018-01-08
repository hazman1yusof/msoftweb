
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			/////////////////////////validation//////////////////////////
			var errorField=[];
			var mymodal = new modal();
			var detbut = new detail_button();
			//////////////////////////////////////////////////////////////

			////////////////////object for dialog handler//////////////////
			dialog_bankcode=new makeDialog('finance.bank','#bankcode',['bankcode', 'bankname'], 'Bank Code', 'Bank Name');
			dialog_bankcode.handler(errorField);

			

			////////////////////////////////////start dialog///////////////////////////////////////
			$("#dialogForm").dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					
				},
				close: function( event, ui ) {
					
				},
				buttons :[{
					text: "Close",click: function() {
						$(this).dialog('close');
					}
				}],
			});
			
			////////////////////////////////////////end dialog///////////////////////////////////////////


			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				fixPost: true,
				table_name:['finance.bank fb', 'finance.bankdtl fd'],
				table_id:'fd_bankcode',
				join_type:['LEFT JOIN'],
				join_onCol:['fb.bankcode'],
				join_onVal:['fd.bankcode'],
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({

				datatype: "local",
				 colModel: [
				 	{label: 'idno', name: 'fd_idno', width: 90 , hidden: true},
				 	{label: 'compcode', name: 'fd_compcode', width: 90 , hidden: true},
					{label: 'Year', name: 'fd_year', width: 30 },
					{label: 'Bank Code', name: 'fd_bankcode', width: 60, canSearch:true },
					{label: 'Name', name: 'fb_bankname', width: 100 },
					{label: 'Bank Account No', name: 'fb_bankaccount', width: 90 },
					{label: 'Open Balance', name: 'fd_openbal',formatter:'currency', width: 60, readonly: true, align: 'right'},
					// {label: 'Balance', name: 'fd_balance', width: 90, readonly:true, hidden: true},
					{label: 'actamount1', name: 'fd_actamount1', width: 90 , hidden: true},
					{label: 'actamount2', name: 'fd_actamount2', width: 90 , hidden: true},
					{label: 'actamount3', name: 'fd_actamount3', width: 90 , hidden: true},
					{label: 'actamount4', name: 'fd_actamount4', width: 90 , hidden: true},
					{label: 'actamount5', name: 'fd_actamount5', width: 90 , hidden: true},
					{label: 'actamount6', name: 'fd_actamount6', width: 90 , hidden: true},
					{label: 'actamount7', name: 'fd_actamount7', width: 90 , hidden: true},
					{label: 'actamount8', name: 'fd_actamount8', width: 90 , hidden: true},
					{label: 'actamount9', name: 'fd_actamount9', width: 90 , hidden: true},
					{label: 'actamount10', name: 'fd_actamount10', width: 90 , hidden: true},
					{label: 'actamount11', name: 'fd_actamount11', width: 90 , hidden: true},
					{label: 'actamount12', name: 'fd_actamount12', width: 90 , hidden: true},
					{label: 'Total', name: 'fd_total', width: 90, readonly:true, hidden:true},
				],

				autowidth:true,
				multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 100,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					$("#TableBankEnquiry td[id^='fd_actamount']").removeClass('bg-primary');
					hidetbl(true);
					DataTable.clear().draw();
					populateTable();
					getTotal();
					getBalance();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				
			});
			$("#jqGrid").jqGrid('setLabel','fd_openbal','Open Balance',{'text-align':'right'});

		
			
			function getTotal(){
				selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
				var total=0;
				var fd_actamount=0;
				$.each(rowdata, function( index, value ) {
					if(!isNaN(parseFloat(value)) && index.indexOf('fd_actamount') !== -1){
						total+=parseFloat(value);
					}
				});
				$('#fd_total').html(numeral(total).format('0,0.00'));
			}

			function getBalance(){
				selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
				var openbal=rowdata.fd_openbal;
				var balance=0;
				var total=0;
				var fd_actamount=0;

				$.each(rowdata, function( index, value ) {
					if(!isNaN(parseFloat(value)) && (index.indexOf('fd_actamount') && index.indexOf('fd_openbal')) !== -1){
						balance+=parseFloat(value);
					}
				});
				balance = parseFloat(openbal) - parseFloat(balance)
				$('#fd_openbal').html(numeral(openbal).format('0,0.00'));
				$('#fd_balance').html(numeral(balance).format('0,0.00'));
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
					$(selText).val(rowid);
					$(selText).focus();
					//$(selText).parent().next().html(data['desc']);
					if(selText=="#bankcode"){
						bankcode=data.bankcode;

						$("#year").focus();	
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

			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			})

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',false,urlParam);

			function populateTable(){
				selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				rowData = $("#jqGrid").jqGrid ('getRowData', selrow);
				$.each(rowData, function( index, value ) {
					if(value)$('#TableBankEnquiry #'+index+' span').text(numeral(value).format('0,0.00'));
					
				});
			}


			$('#search').click(function(){
				urlParam.filterCol = ['fd.compcode','fb.compcode','fd.bankcode','fd.year'];
				urlParam.filterVal = ['session.company','session.company',$('#bankcode').val(),$('#year').val()];
				refreshGrid("#jqGrid",urlParam);
				hidetbl(true);
				$("#TableBankEnquiry td[id^='fd_actamount']").removeClass('bg-primary');
				$("#TableBankEnquiry td span").text("");
				DataTable.clear().draw();

			});

			var counter=20, moredr=true, DTscrollTop = 0;
			function scroll_next1000(){
				var scrolbody = $(".dataTables_scrollBody")[0];
				$('#but_det').hide();
				DTscrollTop = scrolbody.scrollTop;
				if (scrolbody.scrollHeight - scrolbody.scrollTop === scrolbody.clientHeight) {
					if(moredr){
						mymodal.show("#TableBankEnquiryTran_c");
						getdatadr(false,counter,20);
						counter+=20;
					}
				}
			}

			$("#TableBankEnquiry td[id^='fd_actamount']").click(function(){
				$("#TableBankEnquiry td[id^='fd_actamount']").removeClass('bg-primary');
				$(this).addClass("bg-primary");
				DataTable.clear().draw();
				$(".dataTables_scrollBody").unbind('scroll').scroll(scroll_next1000);
				hidetbl(false);
				if($(this).text().length>0){
					moredr=true;
					mymodal.show("#TableBankEnquiryTran_c");
					getdatadr(false,0,20);
				}else{
					hidetbl(true);
				}
			
			});

			var DataTable = $('#TableBankEnquiryTran').DataTable({
			    responsive: true,
			    scrollY: 400,
				paging: false,
			    columns: [
			   	 	{data: 'open',},
					{data: "source","width": "1%"},
					{data: "trantype","width": "1%"},
					{data: "auditno", "width": "1%" },
					{data: "postdate","width": "13%"},
					{data: "reference"},
					{data: "cheqno" },
					{data: "amountdr", "sClass": "numericCol","width": "11%",},
					{data: "amountcr","sClass": "numericCol","width": "11%"},
				],
				drawCallback: function( settings ) {
					$(".dataTables_scrollBody")[0].scrollTop = DTscrollTop;
				}
			});

			$('#TableBankEnquiryTran tbody').on( 'click', 'tr', function () {
				DataTable.$('tr.bg-info').removeClass('bg-info');
				$(this).addClass('bg-info');
			});
		/*	$('#TableBankEnquiryTran').on( 'dblclick', 'tr', function () {
				console.log($(this));
			});*/
			$('#TableBankEnquiryTran').on( 'click', 'i', function () {
				console.log($(this).closest( "tr" ));
				detbut.show($(this).closest( "tr" ));
			});

			hidetbl(true);
			function hidetbl(hide){
				$('#but_det').hide();
				counter=20
				if(hide){
					$('#TableBankEnquiryTran_wrapper').children().first().hide();
					$('#TableBankEnquiryTran_wrapper').children().last().hide();
				}else{
					$('#TableBankEnquiryTran_wrapper').children().first().show();
					$('#TableBankEnquiryTran_wrapper').children().last().show();
				}
			}

			function getdatadr(fetchall,start,limit){
				var param={
							action:'get_value_default',
							//field:'',
							field:['NULL as open','source','trantype','auditno','postdate','reference','cheqno','amount as amountdr','NULL as amountcr'],
							table_name:'finance.cbtran',
							table_id:'bankcode',
							filterCol:['bankcode','year','period'],
							filterVal:[
								selrowData("#jqGrid").fd_bankcode,
								$('#year').val(),
								$("td[class='bg-primary']").attr('period')
								],
								sidx: 'NULL', sord:'asc'
						}

						if(!fetchall){
						param.start=start;
						param.rows=limit;
					}

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
				},'json').done(function(data) {
					mymodal.hide();
					if(!$.isEmptyObject(data.rows)){
						data.rows.forEach(function(obj){
							obj.open="<i class='fa fa-folder-open-o fa-2x' </i>"
							obj.postdate = moment(obj.postdate).format("DD-MM-YYYY");
							if(obj.amountdr<0){
								obj.amountcr = obj.amountdr;
								obj.amountdr = null;
							}
							obj.amountcr = numeral(Math.abs(obj.amountcr)).format('0,0.00');
							obj.amountdr = numeral(obj.amountdr).format('0,0.00');
							obj.amountcr = (obj.amountcr == '0.00')?'':obj.amountcr;
							obj.amountdr = (obj.amountdr == '0.00')?'':obj.amountdr;
						});
						DataTable.rows.add(data.rows).draw();
					}else{
						moredr=false;
					}
				});
			}

			function detail_button(){
			
				this.pagesList = [
					{
						source:'CM',
						trantype:'FT',
						loadurl:"../../CM/bankTransfer/bankTransfer.php #dialogForm",
						urlParam:{
							action:'get_value_default',
							field: ['auditno','pvno','actdate','paymode','bankcode','cheqno','cheqdate','amount','payto','remarks'],
							table_name:'finance.apacthdr',
							table_id:'auditno',
							filterCol: ['source', 'trantype','auditno'],
							filterVal: ['CM', 'FT',''],
						}
					},{
						source:'CM',
						trantype:'DP',
						loadurl:"../../CM/Direct%20Payment/DirectPayment.php #dialogForm",
						urlParam:{
							action:'get_value_default',
							field:['*'],
							table_name:'finance.apacthdr',
							table_id:'auditno',
							filterCol: ['source', 'trantype','auditno'],
							filterVal: ['CM', 'DP', ''],
						},
						jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
							{
								id:'#jqGrid2',
								urlParam:{
									action:'get_table_default',
									field:[
										{label:'Department',name:'deptcode'},
										{label:'Category',name:'category'},
										{label:'Document',name:'document'},
										{label:'Amount Before GST',name:'AmtB4GST'},
										{label:'GST Code',name:'GSTCode'},
										{label:'Total Amount',name:'amount'}
									],
									table_name:'finance.apactdtl',
									table_id:'deptcode',
									filterCol:['auditno', 'recstatus'],
									filterVal:['', 'A'],
								}
							}
						]
					},{
						source:'PB',
						trantype:'RC',
						loadurl:"../../AR/receipt/receipt.php #dialogForm",
						urlParam:{
							action:'get_value_default',
							field:["*"],
							table_name:'debtor.dbacthdr',
							table_id:'auditno',
							filterCol:['source', 'trantype','auditno'],
							filterVal:['PB', 'RC','']
						}
					},{
						source:'CM',
						trantype:'CA',
						loadurl:"../../CM/Credit%20Debit%20Transaction/creditDebitTrans.php #dialogForm",
						urlParam:{
							action:'get_value_default',
							field:["*"],
							table_name:'finance.apacthdr',
							table_id:'auditno',
							filterCol:['source', 'trantype','auditno'],
							filterVal:['CM', 'CA','']
						},
						jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
							{
								id:'#jqGrid2',
								urlParam:{
									action:'get_table_default',
									field:[
										{label:'Department',name:'deptcode'},
										{label:'Category',name:'category'},
										{label:'Document',name:'document'},
										{label:'Amount Before GST',name:'AmtB4GST'},
										{label:'GST Code',name:'GSTCode'},
										{label:'Total Amount',name:'amount'}
									],
									table_name:'finance.apactdtl',
									table_id:'deptcode',
									filterCol:['auditno', 'recstatus','trantype'],
									filterVal:['', 'A',''],
								}
							}
						]
					},{
						source:'CM',
						trantype:'DA',
						loadurl:"../../CM/Credit%20Debit%20Transaction/creditDebitTrans.php #dialogForm",
						urlParam:{
							action:'get_value_default',
							field:["*"],
							table_name:'finance.apacthdr',
							table_id:'auditno',
							filterCol:['source', 'trantype','auditno'],
							filterVal:['CM', 'DA','']
						},
						jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
							{
								id:'#jqGrid2',
								urlParam:{
									action:'get_table_default',
									field:[
										{label:'Department',name:'deptcode'},
										{label:'Category',name:'category'},
										{label:'Document',name:'document'},
										{label:'Amount Before GST',name:'AmtB4GST'},
										{label:'GST Code',name:'GSTCode'},
										{label:'Total Amount',name:'amount'}
									],
									table_name:'finance.apactdtl',
									table_id:'deptcode',
									filterCol:['auditno', 'recstatus','trantype'],
									filterVal:['', 'A',''],
								}
							}
						]
					}
				];
				
				this.show=function(obj){
					
						mymodal.show("body");
						var source = obj.children("td:nth-child(2)").text();
						var trantype = obj.children("td:nth-child(3)").text();
						var auditno = obj.children("td:nth-child(4)").text();
						var pageUse = this.pagesList.find(function(obj){
							return (obj.source === source && obj.trantype === trantype);
						});
						if(pageUse == undefined){
							mymodal.hide();
							bootbox.alert('Unknown source: '+source+' | trantype: '+trantype+' or no selected row');
							return false;
						}
						pageUse.urlParam.filterVal[2] = auditno;

						$.get( "../../../../assets/php/entry.php?"+$.param(pageUse.urlParam), function( data ) {
							
						},'json').done(function(data) {
							mymodal.hide();
							if(!$.isEmptyObject(data.rows)){
								$( "#dialogForm" ).load( pageUse.loadurl, function(){
									populatePage(data.rows[0],'#formdata',source,trantype);
									disableForm('#formdata');

									if(source=="PB" && trantype=="RC"){
										$(".nav-tabs a[form='"+data.rows[0].paytype+"']").tab('show');
										populatePage(data.rows[0],data.rows[0].paytype,source,trantype);
										disableForm(data.rows[0].paytype);
									}

									$("#dialogForm").dialog("open");
									
									if(pageUse.hasOwnProperty('jqgrid')){
										pageUse.jqgrid[0].urlParam.filterVal[0] = auditno;

										if(source=="CM" && trantype=="DA"){
											pageUse.jqgrid[0].urlParam.filterVal[2] = "DA";
										}else if(source=="CM" && trantype=="CA"){
											pageUse.jqgrid[0].urlParam.filterVal[2] = "CA";
										}

										jqgrid_inpage(
											pageUse.jqgrid[0].id,
											populate_colmodel(pageUse.jqgrid[0].urlParam.field),
											pageUse.jqgrid[0].urlParam
										);//change here
									}

								});
							}
						});
					
				}

				function populate_colmodel(field){
					var colmodel = [];
					field.forEach(function(element){
						colmodel.push({label:element.label,name:element.name,formatter:showdetail,classes: 'wrap'});
					});
					return colmodel;
				}

				function showdetail(cellvalue, options, rowObject){
					var field,table;
					switch(options.colModel.name){
						case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
						case 'category':field=['catcode','description'];table="material.category";break;
						case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
						default: return cellvalue;
					}
					var param={action:'input_check',table:table,field:field,value:cellvalue};
					$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data.row)){
							console.log(options);
							$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.row.description+"</span>");
						}
					});
					return cellvalue;
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

				function populatePage(obj,form,source,trantype){
					$.each(obj, function( index, value ) {
						if(source=="PB" && trantype=="RC")index = "dbacthdr_"+index;
						var input=$(form+" [name='"+index+"']");
						if(input.is("[type=radio]")){
							$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
						}else{
							input.val(value);
						}
					});
				}
			}


			set_yearperiod();
			function set_yearperiod(){
				param={
					action:'get_value_default',
					field: ['year'],
					table_name:'sysdb.period',
					table_id:'idno'
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(this.param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data.rows)){
							data.rows.forEach(function(element){	
								$('#year').append("<option>"+element.year+"</option>")
							});
						}
					});
			}

		});
		