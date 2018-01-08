		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
			
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
			//var Class2 = $('#Class2').val();
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
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
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
							$('#formdata :input[hideOne]').show();
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
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('#formdata .alert').detach();
					$("#formdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",butt1);
					}
				},
				buttons :butt1,
			  });



			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			
			var urlParam={
		        action:'get_table_default',
				field:['s.deptcode','s.itemcode'],
				fixPost: true,
				table_name:['material.stockloc s','material.product p'],
				table_id:'s_idno',
				join_type:['LEFT JOIN'],
				join_onCol:['s.itemcode'],
				join_onVal:['p.itemcode'],
				filterCol:['s.compcode', 's.uomcode'],
				filterVal:['skip.p.compcode', 'skip.p.uomcode'],
				sort_idno:true,
				
			}
			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
                    {label: 'idno', name: 's_idno', hidden: true},
                    { label: 'Department Code', name: 's_deptcode', width: 20, classes: 'wrap', canSearch: true,selected:true},
					{ label: 'Item code', name: 's_itemcode', width: 20, classes: 'wrap', canSearch: true,selected:true},		
					{ label: 'Description', name: 'p_description', width: 30, classes: 'wrap', canSearch: true,selected:true},				
					{ label: 'UOM Code', name: 's_uomcode', width: 20, classes: 'wrap'},
					{ label: 'Quantity on Hand', name: 's_qtyonhand', width: 20,classes: 'wrap',align: 'right'},
					{ label: 'Stock TrxType', name: 's_stocktxntype', width: 20, classes: 'wrap'},
				 	{ label: 'Min Stock Qty', name: 's_minqty', width: 15, classes: 'wrap',align: 'right'},
					{ label: 'Max Stock Qty', name: 's_maxqty', width: 15, classes: 'wrap',align: 'right'},
					{ label: 'Reorder Level', name: 's_reordlevel', width: 15, classes: 'wrap'},
					{ label: 'Reorder Qty', name: 's_reordqty', width: 15, classes: 'wrap',align: 'right'},
					{ label: 'DisType', name: 's_disptype', width: 10, classes: 'wrap'},
					{label: 'openbalqty', name: 's_openbalqty', width: 90 , hidden: true},
					{label: 'openbalval', name: 's_openbalval', width: 90 , hidden: true},
					{label: 'netmvval1', name: 's_netmvval1', width: 90 , hidden: true},
					{label: 'netmvqty1', name: 's_netmvqty2', width: 90 , hidden: true},
					{label: 'netmvval3', name: 's_netmvval3', width: 90 , hidden: true},
					{label: 'netmvqty3', name: 's_netmvqty3', width: 90 , hidden: true},
					



					
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
					if(rowid != null) {
						

						populateSummary('#details',selrowData('#jqGrid').s_itemcode, selrowData('#jqGrid').s_uomcode, selrowData('#jqGrid').s_deptcode);
					    

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

		     $("#jqGrid").jqGrid('setLabel', 'qtyonhand', 'Quantity on Hand', {'text-align':'right'});
             $("#jqGrid").jqGrid('setLabel', 'avgcost', 'Average Cost', {'text-align':'right'});
             $("#jqGrid").jqGrid('setLabel', 'currprice', 'Current Price', {'text-align':'right'});
             $("#jqGrid").jqGrid('setLabel', 'minqty', 'Min Stock Qty', {'text-align':'right'});
             $("#jqGrid").jqGrid('setLabel', 'maxqty', 'Max Stock Qty', {'text-align':'right'});
             $("#jqGrid").jqGrid('setLabel', 'reordqty', 'Reorder Qty', {'text-align':'right'});
            

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			
               
              
             
			
                var urlParam3={
				action:'get_table_default',
				field:'',
				table_name:'material.stockexp',
				table_id:'idno',
				sort_idno:true,
				filterCol:['itemcode', 'uomcode','year'],
				filterVal:['', '',$("#getYear").val()],
			}

			
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

                /*function getaccumval(){
				selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
				var accumvqty=0;
				var netmvqty=0;

				$.each(rowdata, function( index, value ) {
					if(!isNaN(parseFloat(value)) && index.indexOf('netmvqty') !== -1){
						netmvqty+=parseFloat(value);
					}
				});
				$('#accumvqty').html(numeral(accumvqty).format('0,0.00'));
			}
			*/

			function populateSummary(form,itemcode,uomcode,deptcode){

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



						
						
                      /* var netmvqty=0;
						
                       $.each(data.rows[0], function( index, value ) {
							
							
							
							
							if(!isNaN(parseInt(value)) && index.indexOf('netmvqty') !== -1){
								
								var totalQty=0;
								console.log(totalQty)
								console.log(index +"-"+ parseInt(value));
								console.log(totalQty+=parseInt(value));
								totalQty+=parseInt(value);
								
								console.log(totalQty);
							}
							$("#accumqty").val(totalQty);
							
							 console.log(value);
							netmvqty3=parseInt(data.rows[0].netmvqty3);
							netmvqty11=parseInt(data.rows[0].netmvqty11);
							console.log(totalQty=netmvqty3+netmvqty11);
							$("#accumqty").val(totalQty);
							

						});		
						*/



                     var accumqty=0;
				     var netmvqty=0;
				     $.each(data.rows[0], function( index, value ) {
					    if(!isNaN(parseInt(value)) && index.indexOf('netmvqty') !== -1){
						accumqty+=parseInt(value);
		
						console.log(accumqty);
					}
				});
				//$('#accumqty').html(numeral(accumqty).format('0,0.00'));
				$("#accumqty").val(accumqty);


                     var accumval=0;
				     var netmvval=0;
				      $.each(data.rows[0], function( index, value ) {
					     if(!isNaN(parseInt(value)) && index.indexOf('netmvval') !== -1){
						 accumval+=parseInt(value);

						 console.log(accumval);
					}
				});
				//$('#accumqty').html(numeral(accumqty).format('0,0.00'));
				$("#accumval").val(accumval);


					}
				});
			}
                 

			
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

function populateTable(){
				selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				rowData = $("#jqGrid").jqGrid ('getRowData', selrow);
				$.each(rowData, function( index, value ) {
					if(value)$('#TableBankEnquiry #'+index+' span').text(numeral(value).format('0,0.00'));
					
				});
			}
			/*console.log($('#Scol option:selected').val()); ///get selected yg first
			$('#Scol').change(function(){
				console.log($('#Scol option:selected').val());
			});*/

			
			/*$("#itemExpiry").jqGrid('navGrid','#jqGridPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refreshGrid("#itemExpiry",urlParam);
					},
					
				}	
			);*/

			/*jQuery("#itemExpiry").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'itemcode', numberOfColumns: 6, titleText: 'Bank'},
			  ]
			  });*/
            $("#itemExpiry").jqGrid('setLabel', 'balqty', 'Balance Quantity', {'text-align':'right'});
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			/*toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			toogleSearch('#sbut2','#searchForm2','off');
			populateSelect('#detail','#searchForm2');
			searchClick('#detail','#searchForm2',urlParam2);*/

			

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			//addParamField('#jqGrid',false,saveParam,['idno']);
			//addParamField('#detail',false,urlParam2,['idno']);

			$("#pg_jqGridPager2 table").hide();
			$("#pg_jqGridPager3 table").hide();
		});
		