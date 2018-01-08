<script>
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';

		var bc;
		
		$(document).ready(function () {


		////////////// jqGrid /////////////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				url: 'chqregTbl.php',
				datatype: "json",
				 colModel: [
					{ label: 'Bank Code', name: 'bankcode', sorttype: 'number', width: 8, classes: 'wrap'},
					{ label: 'Bank Name', name: 'bankname', width: 20, classes: 'wrap'},
					{ label: 'Address', name: 'address1', width: 17, classes: 'wrap'},
					{ label: 'Tel No', name: 'telno', width: 10, classes: 'wrap'},						

				],
				autowidth:true,
				viewrecords: false,
                multiSort: true,
				loadonce: true,
				rownumbers: true,
				height: 168,
				rowNum: 30,
				
				pager: "#jqGridPager",
				onPaging: function(pgButton){ 
				},
				gridComplete: function(){
				},
				onSelectRow:function(rowid, selected)
				{
					bc=rowid;
					if(rowid != null) {
						$("#detail").jqGrid().setGridParam({url : 'chqregDetailTbl.php?bankcode='+bc,datatype:'json'}).trigger("reloadGrid");
						$("#pg_jqGridPager2 table").show();
					}
				},
			});
			
			jQuery("#jqGrid").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'bankcode', numberOfColumns: 4, titleText: 'Bank'},
			  ]
			});
			
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
					},
					
				}	
			);
			
			////////////// detail /////////////////////////////////////////////////////////////////
			
			$("#detail").jqGrid({
				editurl: 'chqregDetailSave.php',
				datatype: "json",
				 colModel: [
				 	{ label: 'Comp Code', name: 'compcode', width: 50, hidden:true},	
					{ label: 'Bank Code', name: 'bankcode', width: 30, hidden:true, editable: true,
					},
					{ label: 'Start Number', name: 'startno', width: 20, classes: 'wrap', sorttype: 'number', editable: true,
								editrules:{required: true},
								edittype:"text", editoptions:{
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
						editrules:{required: true},
						edittype:"text", editoptions:{
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
					{ label: 'Cheq Qty', name: 'cheqqty', width: 30, hidden:true,
					},
					{ label: 'Stat', name: 'stat', width: 30, hidden:true,
					},
					{ label: 'Created By', name: 'adduser', width: 30, hidden:true},
					{ label: 'Created Date', name: 'adddate', width: 30, hidden:true},
					{ label: 'lastuser', name: 'lastuser', width: 30, hidden:true},
					{ label: 'lastupdate', name: 'lastupdate', width: 30, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 30, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 30, hidden:true},
					{ label: 'Action', name: 'action', width :10,  formatoptions: { keys: false, editbutton: true, delbutton: true }, formatter: 'actions'},
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: true,
				rownumbers: true,
				height: 133,
				rowNum: 30,
				pager: "#jqGridPager2",
				onPaging: function(pgButton){ 
				},
				gridComplete: function(){
				},		
			});
			
				
			jQuery("#detail").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'startno', numberOfColumns: 11, titleText: 'Cheque Detail'},
			  ]
			});
											
			$("#detail").jqGrid('inlineNav','#jqGridPager2',{	
				edit:false,add:true,//del:true,
/*				errorTextFormat: function (data) {
						console.log(data);
						return 'Error: ' + data.responseText;
				},	*/
			});
			
/*			$("#detail").jqGrid('inlineNav','#jqGridPager2',
				{	
					edit:true,add:true,
					beforeRefresh: function(){
						//$("#detail").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
					},
					
				},
				
				// options for the Edit Dialog
				{},
				
				// options for the Add Dialog
				{	afterSubmit : function( data, postdata, oper){
						//$("#detail").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						return [true,'',''];

					},
					errorTextFormat: function (data) {
						console.log(data);
						return 'Error: ' + data.responseText;
					},
				}				
			);*/
			
			$("#detail_iladd").click(function(){
				$("input[id*='startno']").focus();
				$("input[id*='_bankcode']").val(bc);
				$("input[id*='_bankcode']").attr('readonly','readonly');
				$("input[id*='_endno']").keydown(function(e) {
					console.log('keydown called');
					var code = e.keyCode || e.which;
						if (code == '9') { // -->for tab
							$('#detail_ilsave').click();
							$("#detail").jqGrid().setGridParam({url : 'chqregDetailTbl.php?bankcode='+bc,datatype:'json'}).trigger("reloadGrid");
							//alert('Tab pressed');
							//$("#detail").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
							//return [true,'',''];
							//$("nextCell",iRow,iCol);
							//$("#detail").
							//$('#detail_iladd').click();
							//$("#detail_ilsave").show();
							
							
							delay(function(){
								$('#detail_iladd').click();
							}, 1500 );
						}
				 });
			});
			//$("#detail_iladd").show();
			//detail_iledit detail_ilsave
/*			$("#detail_iledit").click(function(){
				$("input[id*='_bankcode']").val(bc);
				$("input[id*='_bankcode']").attr('readonly','readonly');
				$("input[id*='_endno']").keydown(function(e) {
					console.log('keydown called');
					var code = e.keyCode || e.which;
						if (code == '9') { // -->for tab
							$('#detail_ilsave').click();
							$("#detail").jqGrid().setGridParam({url : 'chqregDetialTbl.php?bankcode='+bc,datatype:'json'}).trigger("reloadGrid");
						}
				 });
			});*/
			
			var delay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				};
			})();
			
			
			$("#pg_jqGridPager2 table").hide();
			//$("#detail_ilsave").hide();
			
			
			////////////// menu /////////////////////////////////////////////////////////////////
			
			$('#menu').metisMenu();
	
		});
 </script>