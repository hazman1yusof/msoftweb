		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var bc;

		$(document).ready(function () {

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'finance.bank',
				table_id:'bankcode',
				sort_idno: true,
			}
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Bank Code', name: 'bankcode', width: 8,canSearch:true,checked:true},
					{ label: 'Bank Name', name: 'bankname', width: 20, canSearch:true},
					{ label: 'Address', name: 'address1', width: 17},
					{ label: 'Tel No', name: 'telno', width: 10},	
				 	{ label: 'idno', name: 'idno', hidden: true},
					
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				height: 124,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					var jg=$("#jqGrid").jqGrid('getRowData',rowid);
					idno=rowid;
					bankcode=jg.bankcode;
					//bc=rowid;
					//alert(bc);
					urlParam2.filterVal[0]=bankcode;
					console.log(urlParam2);
					if(rowid != null) {
						refreshGrid("#detail",urlParam2);
						$("#pg_jqGridPager2 table").show();
					}
				},
			});
			
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refreshGrid("#jqGrid",urlParam);
					},
					
				}	
			);

		/*	jQuery("#jqGrid").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'bankcode', numberOfColumns: 4, titleText: 'Bank'},
			  ]
			});*/

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam2={
				action:'get_table_default',
				field:'',
				table_name:'finance.chqreg',
				table_id:'startno',
				//sord:'desc',
				filterCol:['bankcode'],
				filterVal:[''],
				sort_idno: true,
			}

			$("#detail").jqGrid({
				editurl: "../../../../assets/php/entry.php?action=chqreg_save",
				datatype: "local",
				colModel: [
				 	{ label: 'Comp Code', name: 'compcode', width: 50, hidden:true},	
					{ label: 'Bank Code', name: 'bankcode', width: 30, hidden: true, editable: true,},
					{ label: 'Start Number', name: 'startno', width: 20, classes: 'wrap', sorttype: 'number', editable: true,
							editrules:{required: true},edittype:"text",canSearch:true,checked:true,
							editoptions:{
								maxlength: 11,
								dataInit: function(element) {
									$(element).keypress(function(e){
										 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'End Number', name: 'endno', width: 20, classes: 'wrap', editable: true,
							editrules:{required: true},edittype:"text",canSearch:true,
							editoptions:{
								maxlength: 11,
								dataInit: function(element) {
									$(element).keypress(function(e){
										 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Cheq Qty', name: 'cheqqty', width: 30, hidden:true,},
					{ label: 'Stat', name: 'stat', width: 30, hidden:true,},
					{ label: 'Action', name: 'action', width :10,  formatoptions: { keys: false, editbutton: true, delbutton: true }, formatter: 'actions'},
					{label: 'idno', name: 'idno', hidden: true},
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: false,
				rownumbers: true,
				//sortname: 'startno',
				//sortorder:'desc',
				height: 124,
				rowNum: 30,
				sord: "desc",
				pager: "#jqGridPager2",
			});

		/*	jQuery("#detail").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'startno', numberOfColumns: 11, titleText: 'Cheque Detail'},
			  ]
			});*/
			
			$("#detail").jqGrid('inlineNav','#jqGridPager2',{	
				edit:false,
				add:true,

			});

			$("#detail_iladd").click(function(){
				$("input[id*='startno']").focus();
				//console.log(bankcode);
				$("input[id*='_bankcode']").val(bankcode);
				$("input[id*='_bankcode']").attr('readonly','readonly');
				$("input[id*='_endno']").keydown(function(e) {
					//console.log('keydown called');
					var code = e.keyCode || e.which;
						if (code == '9') { // -->for tab
							$('#detail_ilsave').click();
							delay(function(){
								$('#detail_iladd').click();
							}, 1500 );
						}
				 });
			});

			$("#detail_ilsave").click(function(){
					delay(function(){
						$('#detail_iladd').click();
					}, 1500 );
			});

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			toogleSearch('#sbut2','#searchForm2','off');
			populateSelect('#detail','#searchForm2');
			searchClick('#detail','#searchForm2',urlParam2);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#detail',false,urlParam2,['rn','action']);

			$("#pg_jqGridPager2 table").hide();
		});
		