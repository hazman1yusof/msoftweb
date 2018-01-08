
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			/////////////////////////validation//////////////////////////
			var errorField=[];
			//////////////////////////////////////////////////////////////

			////////////////////object for dialog handler//////////////////

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

			//////////////////////////////////billsum grid////////////////////////////////////////////////

			var billsumParam={
				action:'get_table_default',
				field:'',
				table_id:'idno',
				table_name:'debtor.billsum',
				filterCol:['mrn','episno','billno','lineno_'],
				filterVal:[]
			}

			function setBillsumParam(mrn,episno,billno,lineno_,refresh){
				billsumParam.filterVal=[mrn,episno,billno,lineno_];
				if(refresh)refreshGrid("#billsumGrid",billsumParam);
			}

			$("#billsumGrid").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'TT', name: 'trantype', width: 20, hidden: false},
					{ label: 'chggroup', name: 'chggroup', width: 30, hidden: false},
					{ label: 'description', name: 'description', width: 100, hidden: false},
					{ label: 'CC', name: 'chgclass', width: 20, hidden: false},
					{ label: 'amount', name: 'amount', width: 90, hidden: false},
					{ label: 'outamt', name: 'outamt', width: 90, hidden: false},
					{ label: 'mrn', name: 'mrn', width: 20, hidden: true},
					{ label: 'episno', name: 'episno', width: 20, hidden: true},
					{ label: 'billno', name: 'billno', width: 20, hidden: true},
					{ label: 'lineno_', name: 'lineno_', width: 20, hidden: true},
				],
				width: 500,
				autowidth: true,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#billsumGridPager"
			});
			addParamField('#billsumGrid',false,billsumParam);



			//////////////////////////////////////////////////////////////////////////////////////////////


			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_id:'idno',
				table_name:'debtor.dbacthdr',
				filterCol:['source'],
				filterVal:['pb'],
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{label: 'Source', name: 'source', width: 20, canSearch:true, checked:true},
					{label: 'Trantype', name: 'trantype', width: 30, canSearch:true, checked:true},
					{label: 'Auditno', name: 'auditno', width: 30, canSearch:true },
					{label: 'Line No', name: 'lineno_', width: 30 },
					{label: 'Amount', name: 'amount', width: 90 , hidden: false},
					{label: 'O/S amount', name: 'outamount', width: 90 , hidden: false},
					{label: 'actamount1', name: 'recstatus', width: 90 , hidden: true},
					{label: 'actamount1', name: 'entrydate', width: 90 , hidden: true},
					{label: 'actamount1', name: 'entrytime', width: 90 , hidden: true},
					{label: 'actamount1', name: 'entryuser', width: 90 , hidden: true},
					{label: 'actamount1', name: 'reference', width: 90 , hidden: true},
					{label: 'actamount1', name: 'recptno', width: 90 , hidden: true},
					{label: 'Payment Mode', name: 'paymode', width: 50},
					{label: 'actamount1', name: 'tillcode', width: 90 , hidden: true},
					{label: 'actamount1', name: 'tillno', width: 90 , hidden: true},
					{label: 'actamount1', name: 'debtortype', width: 90 , hidden: true},
					{label: 'Debtor', name: 'debtorcode', width: 90 , hidden: false},
					{label: 'actamount1', name: 'payercode', width: 90 , hidden: true},
					{label: 'actamount1', name: 'billdebtor', width: 90 , hidden: true},
					{label: 'actamount1', name: 'remark', width: 90 , hidden: true},
					{label: 'MRN', name: 'mrn', width: 30 , hidden: false},
					{label: 'actamount1', name: 'episno', width: 90 , hidden: true},
					{label: 'actamount1', name: 'authno', width: 90 , hidden: true},
					{label: 'actamount1', name: 'expdate', width: 90 , hidden: true},
					{label: 'actamount1', name: 'epistype', width: 90 , hidden: true},
					{label: 'actamount1', name: 'cbflag', width: 90 , hidden: true},
					{label: 'actamount1', name: 'conversion', width: 90 , hidden: true},
					{label: 'actamount1', name: 'payername', width: 90 , hidden: true},
					{label: 'actamount1', name: 'hdrtype', width: 90 , hidden: true},
					{label: 'actamount1', name: 'currency', width: 90 , hidden: true},
					{label: 'actamount1', name: 'rate', width: 90 , hidden: true},
					{label: 'actamount1', name: 'units', width: 90 , hidden: true},
					{label: 'actamount1', name: 'invno', width: 90 , hidden: true},
					{label: 'actamount1', name: 'paytype', width: 90 , hidden: true},
					{label: 'actamount1', name: 'bankcharges', width: 90 , hidden: true},
					{label: 'actamount1', name: 'RCCASHbalance', width: 90 , hidden: true},
					{label: 'actamount1', name: 'RCOSbalance', width: 90 , hidden: true},
					{label: 'actamount1', name: 'RCFinalbalance', width: 90 , hidden: true},
					{label: 'actamount1', name: 'PymtDescription', width: 90 , hidden: true},
					{label: 'actamount1', name: 'idno', width: 90 , hidden: true},
				],
				autowidth:true,
				multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 250,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					if(toggle_TT(selrowData('#jqGrid').trantype)){
						populateSummary('#referenceForm',selrowData('#jqGrid').debtorcode);
						populateComment((curhref=='#comment'));
						setBillsumParam(
							selrowData('#jqGrid').mrn,
							selrowData('#jqGrid').episno,
							selrowData('#jqGrid').auditno,
							selrowData('#jqGrid').lineno_,
							(curhref=='#summary')
						);
					}else{
						DataTable.clear().draw();
						populateSummary('#referenceFormIN',selrowData('#jqGrid').debtorcode);
						getdatagltran();
					}
					
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
			})

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			$('#notpbin').hide();
			function toggle_TT(tt){
				var notpbin = true;
				if(tt == 'CN' || tt == 'DN' || tt == 'RC' || tt == 'RD'){
					$('#notpbin').show();$('#ispbin').hide();
					notpbin = false;
				}else{
					$('#notpbin').hide();$('#ispbin').show();
				}
				return notpbin;
			}

			var DataTable = $('#tableTran').DataTable({
			    responsive: true,
				scrollY: 200,
				paging: false,
			    columns: [
					{ data: 'account'},
					{ data: 'description'},
					{ data: 'amount'}
				],
			});

			$('#tableTran_wrapper').children().first().hide();

			function getdatagltran(){
				var param={
							action:'get_value_default',
							field:['auditno','dracc','cracc','amount'],
							table_name:'finance.gltran',
							table_id:'auditno',
							filterCol:['source','trantype','auditno','lineno_'],
							filterVal:[
								selrowData("#jqGrid").source,
								selrowData("#jqGrid").trantype,
								selrowData("#jqGrid").auditno,
								selrowData("#jqGrid").lineno_
							]
						}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						console.log(data.rows)
						data.rows = data.rows.reduce(function(acc,curr){
							acc.push({account:curr.cracc,description:"<span id='"+curr.cracc+"'></span>",amount:curr.amount+" CR"});
							acc.push({account:curr.dracc,description:"<span id='"+curr.dracc+"'></span>",amount:curr.amount+" DR"});
							(curr.cracc!="")?showDesc(curr.cracc):"";
							(curr.dracc!="")?showDesc(curr.dracc):"";
							return acc;
						},[]);
						DataTable.rows.add(data.rows).draw();
					}
				});
			}

			function showDesc(val){
				var param={action:'input_check',table:"finance.glmasref",field:['glaccno','description'],value:val};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.row)){
						$("#tableTran #"+val).html(data.row.description);
					}
				});
			}

            $('#searchText').keyup(function() {
				delay(function(){
					searchMain($('#searchText').val(),$('#filter').val());
				}, 500 );
			});

			$('#filter').change(function(){
				searchMain($('#searchText').val(),$('#filter').val());
			});

			function populateSummary(form,debtorcode){
				emptyFormdata([],form);
				$(form+' #payername').val(selrowData('#jqGrid').payername);
				$(form+' #reference').val(selrowData('#jqGrid').reference);
				$(form+' #remark').val(selrowData('#jqGrid').remark);
				$(form+' #status').val(selrowData('#jqGrid').recstatus);
				$(form+' #lastuser').val(selrowData('#jqGrid').entryuser);
				$(form+' #lastupdate').val(selrowData('#jqGrid').entrydate);

				let param={
					action:'get_value_default',
					field: ['name'],
					table_name:'debtor.debtormast',
					table_id:'name',
					filterCol:['debtorcode'],
					filterVal:[debtorcode]
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
							
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						$(form+' #debtorname').val(data.rows[0].name);
					}
				});
			}

			function populateComment(refresh){
				if(refresh){
					let param={
						action:'get_value_default',
						field: ['comment_'],
						table_name:'debtor.debtcomm',
						table_id:'name',
						filterCol:['debtorcode'],
						filterVal:[selrowData('#jqGrid').debtorcode]
					}
					$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
							
					},'json').done(function(data) {
						if(!$.isEmptyObject(data.rows)){
							$('#comment').val(data.rows[0].name);
						}
					});	
				}
			}

			function searchMain(Stext,Scol){

				if(Scol == 'billno'){
					$('#searchText').prop('disabled',true);
					urlParam.searchCol=null;
					urlParam.searchVal=null;
					urlParam.filterCol=['source','trantype'];
					urlParam.filterVal=['PB','IN'];
				}else{
					$('#searchText').prop('disabled',false);
					urlParam.filterCol=['source'];
					urlParam.filterVal=['PB'];

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


			var paramDB={
				action:'get_table_default',
				table_name:'debtor.debtormast',
				field:['debtorcode','debtorname'],
				table_id:'debtorcode'
			};

			$("#DBgridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'debtorcode', width: 200,  classes: 'pointer', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'name', width: 400, canSearch:true, classes: 'pointer'},
				],
				width: 500,
				autowidth: true,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#DBgridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$('#searchText').val(selrowData('#DBgridDialog').debtorcode);
					$("#dialogbox").dialog('close');
					searchMain($('#searchText').val(),$('#filter').val());
				},
			});

			$.each($("#DBgridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
				if(value['canSearch']){
					if(value['checked']){
						$( "#dbcol" ).append( "<label class='radio-inline'><input type='radio' name='dbcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
					}else{
						$("#dbcol" ).append( "<label class='radio-inline'><input type='radio' name='dbcolr' value='"+value['name']+"' >"+value['label']+"</input></label>" );
					}
				}
			});

			addParamField('#DBgridDialog',false,paramDB);
			
			$("#dialogbox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 7/10 * $(window).width(),
				open: function( event, ui ) {
					$("#DBgridDialog").jqGrid ('setGridWidth', Math.floor($("#DBgridDialog_c")[0].offsetWidth-$("#DBgridDialog_c")[0].offsetLeft));
					
					refreshGrid("#DBgridDialog",paramDB);

				},
				close: function( event, ui ) {
					$('#dbtext').val('')
					paramDB.searchCol=null;
					paramDB.searchVal=null;
				}
            });

            $('#dbtext').keyup(function() {
				delay(function(){
					DBsearch($('#dbtext').val(),$('#DBcheckForm input:radio[name=dbcolr]:checked').val());
				}, 500 );
			});
			
			$('#dbcol').change(function(){
				DBsearch($('#dbtext').val(),$('#DBcheckForm input:radio[name=dbcolr]:checked').val());
			});
			
			function DBsearch(Dtext,Dcol){
				paramDB.searchCol=null;
				paramDB.searchVal=null;
				Dtext=Dtext.trim();
				if(Dtext != ''){
					var split = Dtext.split(" "),searchCol=[],searchVal=[];
					$.each(split, function( index, value ) {
						searchCol.push(Dcol);
						searchVal.push('%'+value+'%');
					});
					paramDB.searchCol=searchCol;
					paramDB.searchVal=searchVal;
				}
				refreshGrid("#DBgridDialog",paramDB);
			}


			$('#filter').change(function(){
				if($(this).val() == 'debtorcode' || $(this).val() == 'payercode' ){
					$('#search').prop('disabled',false);
				}else{
					$('#search').prop('disabled',true);
				}
			});

			$('#search').click(function(){
				$( "#dialogbox" ).dialog( "open" );
			});

			var curhref;
			$('.nav-tabs a').on('shown.bs.tab', function(e){
				curhref=$(this).attr('href');

				switch(curhref){
					case "#summary":
						$("#billsumGrid").jqGrid('setGridWidth', Math.floor($("#billsumGrid_c")[0].offsetWidth-$("#billsumGrid_c")[0].offsetLeft));
						refreshGrid("#billsumGrid",billsumParam);
						break;
					case "#comment":
						populateComment(true);
						break;
				}

			});
		});
		