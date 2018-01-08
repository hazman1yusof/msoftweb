
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

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


			/////////////////////////////////////////////////////////object for dialog handler//////////////////
			
			dialog_itemcode=new makeDialog('material.product','#itemcode',['itemcode','description', 'uomcode'], 'Item');
			dialog_itemcode.handler(errorField);

			dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Department');
			dialog_uomcode=new makeDialog('material.uom','#uomcode',['uomcode','description'], 'UOM');
			
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						enableRadioButton();
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
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							disableRadioButton();
							hideOne('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							$('#formdata :input[hideOne]').show();
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						dialog_deptcode.handler(errorField);
						dialog_uomcode.handler(errorField);
					}
					if(oper!='add'){
						dialog_deptcode.check(errorField);
						dialog_uomcode.check(errorField);
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					//$('.alert').detach();
					$('#formdata .alert').detach();
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
				table_name:'material.stockloc',
				field:'',
				table_id:'idno',
				sort_idno:true,
				//filterCol:['itemcode', 'uomcode'],
				//filterVal:[itemcode, ],
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.stockloc',
				table_id:'idno'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{label: 'idno', name: 'idno', hidden: true},
					{label: 'compcode', name: 'compcode', width: 10 , hidden: true},
					{label: 'Department Code', name: 'deptcode', width: 100, classes: 'wrap'}, 
					{label: 'Item Code', name: 'itemcode', width: 90 , classes: 'wrap', hidden: true,},
					{label: 'UOM Code', name: 'uomcode', width: 90 , classes: 'wrap'}, 
					{label: 'Bin Code', name: 'bincode', width: 50 , classes: 'wrap', hidden: true},
					{label: 'Rack No', name: 'rackno', width: 50 , classes: 'wrap', hidden: true},
					{label: 'year', name: 'year', width: 90 , hidden: true},
					{label: 'openbalqty', name: 'openbalqty', width: 90 , hidden: true},
					{label: 'openbalval', name: 'openbalval', width: 90 , hidden: true,},
					{label: 'netmvqty1', name: 'netmvqty1', width: 90 , hidden: true,},
					{label: 'netmvval1', name: 'netmvval1', width: 90 , hidden: true,},
					{label: 'Tran Type', name: 'stocktxntype', width: 50 , classes: 'wrap',},
					{label: 'Disp Type', name: 'disptype', width: 50 , classes: 'wrap',}, 
					{label: 'qtyonhand', name: 'qtyonhand', width: 90 , hidden: true},
					{label: 'Min Stock Qty', name: 'minqty', width: 60 , classes: 'wrap'},
					{label: 'Max Stock Qty', name: 'maxqty', width: 60 , classes: 'wrap',},
					{label: 'Reorder Level', name: 'reordlevel', width: 60 , classes: 'wrap',},
					{label: 'Reorder Quantity', name: 'reordqty', width: 60 , classes: 'wrap',},
					{label: 'lastissdate', name: 'lastissdate', width: 90 , hidden: true,},
					{label: 'frozen', name: 'frozen', width: 90 , hidden: true,},
					{label: 'adduser', name: 'adduser', width: 90 , hidden: true,},
					{label: 'adddate', name: 'adddate', width: 90 , hidden: true,},
					{label: 'upduser', name: 'upduser', width: 90 , hidden: true,},
					{label: 'upddate', name: 'upddate', width: 90 , hidden: true,},
					{label: 'cntdocno', name: 'cntdocno', width: 90 , hidden: true,},
					{label: 'fix_uom', name: 'fix_uom', width: 90 , hidden: true,},
					{label: 'locavgcs', name: 'locavgcs', width: 90 , hidden: true,},
					{label: 'lstfrzdt', name: 'lstfrzdt', width: 90 , hidden: true, },
					{label: 'lstfrztm', name: 'lstfrztm', width: 90 , hidden: true,},
					{label: 'frzqty', name: 'frzqty', width: 90 , hidden: true,},
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

			////////////////////////////formatter//////////////////////////////////////////////////////////
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
					return "A";
				}
				if(cellvalue == 'Deactive') { 
					return "D";
				}
			}

			$("#pg_jqGridPager table").hide();
			$('#searchForm input[rdonly]').prop("readonly",true);
			$('#search').hide();

			function disableRadioButton(){
				$('#formdata input[name=disptype]:radio').prop("disabled",true);
			}

			function enableRadioButton(){
				$('#formdata input[name=disptype]:radio').prop("disabled",false);
			}

			$("input[name=stocktxntype]:radio").on('change click',  function(){
					stocktxntype = $("input[name=stocktxntype]:checked").val();
					//alert(stocktxntype);

					if(stocktxntype == 'Transfer') {
						$("#TRDS").prop("checked", true); 
						//alert($('input[id=TRDS]:checked').val());
					}
					if(stocktxntype == 'Issue') {
						$("#ISDS1").prop("checked", true); 
					}

			});

			/*function getYear(gYear){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var year={action:'get_year',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(year), function( data ) {
					
				},'json').done(function(data) {
					alert(year = data.year);
				});
			}*/

			$("#itemcode").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						console.log($('#uomcode').val())
						$("#searchForm :input[name='uomcode']").val($('#uomcode').val());
					}
			});

			$("#search").click(function(){
				var currentDate = $("#datetoday").val();
				
				urlParam.filterCol = ['itemcode','uomcode','year'];
				urlParam.filterVal = [$('#itemcode').val(),$('#uomcode').val(), currentDate];

				refreshGrid('#jqGrid',urlParam);
				$("#pg_jqGridPager table").show();
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
						return emptyFormdata(errorField,'#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'idno':selRowId});
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
					//i = $("#itemcode").val();
					$("#formdata :input[name='itemcode']").val($("#itemcode").val());
					$("#formdata :input[name='uomcode']").val($("#uomcode").val());
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',false,urlParam);
			addParamField('#jqGrid',false,saveParam);
			//addParamField('#jqGrid',false,saveParam,['idno']);


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
					paramD.searchCol=['recstatus'];
					paramD.searchVal=['A'];
				},
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 200,  classes: 'pointer', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'desc', width: 400, canSearch:true, classes: 'pointer'},
					{ label: 'UOM Code', name: 'uomcode', width: 400, classes: 'pointer'},
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
					if(selText=="#itemcode"){
						itemcode=data.itemcode;
						uomcode=data.uomcode;

						$("#uomcode").focus();	
					}
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);

					if(selText == "#itemcode"){
						$("#gridDialog").jqGrid('showCol', 'uomcode');
					}else{
						$("#gridDialog").jqGrid('hideCol', 'uomcode');
					}

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
						if(id== "#itemcode") {
							itemcode=data.row.itemcode;
							uomcode=data.row.uomcode;
							console.log(uomcode);
							$('#uomcode').val(uomcode);

							$('#search').show();
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

						if(id== "#itemcode") {
							$('#search').hide();
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
		