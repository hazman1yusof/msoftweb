
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();

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

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:['finance.bank fb', 'finance.bankdtl fd'],
				table_id:'fd_bankcode',
				join_type:['LEFT JOIN'],
				fixPost: true,
				join_onCol:['fb.bankcode'],
				join_onVal:['fd.bankcode'],
				////filterCol:['fb.compcode', 'fd.compcode'],
				//filterVal:['session.company', 'session.company'],
				sort_idno:true,
			}
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{label: 'idno', name: 'fd_idno', width: 90 , hidden: true},
				 	{label: 'compcode', name: 'fd_compcode', width: 90 , hidden: true},
					{label: 'Year', name: 'fd_year', width: 45 },
					{label: 'Bank Code', name: 'fd_bankcode', width: 60, canSearch:true },
					{label: 'Name', name: 'fb_bankname', width: 90 },
					{label: 'Bank Account No', name: 'fb_bankaccount', width: 90 },
					{label: 'Open Balance', name: 'fd_openbal', width: 90 },
					//{label: 'Balance', name: 'fd_balance', width: 90 },
					{label: 'actamount1', name: 'fd_actamount1', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount2', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount3', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount4', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount5', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount6', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount7', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount8', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount9', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount10', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount11', width: 90 , hidden: true},
					{label: 'actamount1', name: 'fd_actamount12', width: 90 , hidden: true},
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
					/*if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();*/
				},
				
			});

			////////////////////////////function//////////////////////////////////////////////////////////
			dialog_bankcode=new makeDialog('finance.bankdtl','#bankcode',['bankcode'], 'Bank Code');
			dialog_bankcode.handler(errorField);	


			$('#search').click(function(){
				/*urlParam.filterCol = ['bankcode','year'];
				urlParam.filterVal = [$('#bankcode').val(),$('#year').val()];*/
				//table_name = ['finance.bank fb', 'finance.bankdtl fd'];
				//table_id = 'fd_bankcode';
				//join_type=['LEFT JOIN'];
				//fixPost = true;
				//join_onCol=['fb.bankcode'];
				//join_onVal=['fd.bankcode'];
				urlParam.filterCol=['fb.compcode', 'fd.compcode', 'fd.bankcode','fd.year'];
				urlParam.filterVal=['session.company', 'session.company',$('#bankcode').val(),$('#year').val()];

				refreshGrid("#jqGrid",urlParam);
				console.log(urlParam);
			});

			/*
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:['finance.bank fb', 'finance.bankdtl fd'],
				table_id:'fd_bankcode',
				join_type:['LEFT JOIN'],
				fixPost: true,
				join_onCol:['fb.bankcode'],
				join_onVal:['fd.bankcode'],
				filterCol:['fb.compcode', 'fd.compcode'],//['glaccount','year']
				filterVal:['session.company', 'session.company'],
				sort_idno:true,
			}*/


			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			/*}).jqGrid('navButtonAdd',"#jqGridPager",{
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'glaccno':selRowId});
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
				},*/
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			//toogleSearch('#sbut1','#searchForm','on');
			//populateSelect('#jqGrid','#searchForm');
			//searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
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
					paramD.searchCol=null;
					paramD.searchVal=null;
				},
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 200,  classes: 'pointer', canSearch:true,checked:true}, 
					//{ label: 'Description', name: 'desc', width: 400, canSearch:true, classes: 'pointer'},
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

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
		});
		