
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
			
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata2("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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
						//dialog_dept.handler(errorField);
					}
					if(oper!='add'){
						//toggleFormData('#jqGrid','#formdata');
						//dialog_dept.check(errorField);
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
				table_name:'sysdb.period',
				table_id:'year',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				//skipduplicate: true,
				oper:oper,
				table_name:'sysdb.period',
				table_id:'year'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Year', name: 'year', width: 30},
					{ label: 'd', name: 'datefr1', width: 30, hidden:true},
					{ label: 'd', name: 'datefr2', width: 30, hidden:true},
					{ label: 'd', name: 'datefr3', width: 30, hidden:true},
					{ label: 'd', name: 'datefr4', width: 30, hidden:true},
					{ label: 'd', name: 'datefr5', width: 30, hidden:true},
					{ label: 'd', name: 'datefr6', width: 30, hidden:true},
					{ label: 'd', name: 'datefr7', width: 30, hidden:true},
					{ label: 'd', name: 'datefr8', width: 30, hidden:true},
					{ label: 'd', name: 'datefr9', width: 30, hidden:true},
					{ label: 'd', name: 'datefr10', width: 30, hidden:true},
					{ label: 'd', name: 'datefr11', width: 30, hidden:true},
					{ label: 'd', name: 'datefr12', width: 30, hidden:true},
					{ label: 'd', name: 'dateto1', width: 30, hidden:true},
					{ label: 'd', name: 'dateto2', width: 30, hidden:true},
					{ label: 'd', name: 'dateto3', width: 30, hidden:true},
					{ label: 'd', name: 'dateto4', width: 30, hidden:true},
					{ label: 'd', name: 'dateto5', width: 30, hidden:true},
					{ label: 'd', name: 'dateto6', width: 30, hidden:true},
					{ label: 'd', name: 'dateto7', width: 30, hidden:true},
					{ label: 'd', name: 'dateto8', width: 30, hidden:true},
					{ label: 'd', name: 'dateto9', width: 30, hidden:true},
					{ label: 'd', name: 'dateto10', width: 30, hidden:true},
					{ label: 'd', name: 'dateto11', width: 30, hidden:true},
					{ label: 'd', name: 'dateto12', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus1', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus2', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus3', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus4', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus5', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus6', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus7', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus8', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus9', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus10', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus11', width: 30, hidden:true},
					{ label: 'd', name: 'periodstatus12', width: 30, hidden:true},
					{ label: 'idno', name: 'idno', hidden: true},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				//sortname: 'year',
        		//sortorder: "desc",
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					$('#formdata :input[rdonly]').prop("readonly",true);
					$('#formdata select').prop("disabled",true);
					selectYear();
					$("#saveyear").hide();
					$("#cancelyear").hide();
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
                    var ids = $("#jqGrid").jqGrid('getDataIDs');
                    var cl = ids[0];
                    $("#jqGrid").jqGrid('setSelection', cl);
				},
				
			});

			$("#saveyear").hide();
			$("#cancelyear").hide();

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
						alert('Please select year');
						return emptyFormdata(errorField,'#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'year':selRowId});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Year",  
				onClickButton: function(){
					oper='view';
					selectYear()
					$('#formdata :input[rdonly]').prop("readonly",true);
					sdate.check();
					$('#formdata select').prop("disabled",true);
					$("#saveyear").hide();

				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Year",  
				onClickButton: function(){
					oper='edit';
					$("#saveyear").show();
					$("#cancelyear").hide();
					$('#formdata :input[rdonly]').prop("readonly",false);
					$('#formdata :input[frozeOnEdit]').prop("readonly",true);
					$('#formdata select').prop("disabled",false);
					sdate.check();
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$("#year").focus();
					$('#formdata select').prop("disabled",false);
					$("#saveyear").show();
					$("#cancelyear").show();
					addYear();
					hdate.check();
				},
			});

			function selectYear(){
				$.each(selrowData("#jqGrid"), function( index, value ) {
					var input=$("#formdata [name='"+index+"']");
					if(input.is("[type=radio]")){
						$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
					}else{
						input.val(value);
					}
				});
			}

			function addYear(){
				emptyFormdata(errorField,'#formdata');
				$('#formdata :input[rdonly]').prop("readonly",false);
				
			}

			$('#saveyear').click(function(){
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					saveForm("#formdata",oper,saveParam,urlParam);
					emptyFormdata(errorField,'#formdata');
					$('.alert').detach();
					$("#jqGrid").trigger('reloadGrid');
				}
			});

			$('#cancelyear').click(function(){			
				emptyFormdata(errorField,'#formdata');
				datefr1.min=null; dateto1.min=null;
				datefr2.min=null; dateto2.min=null;
				datefr3.min=null; dateto3.min=null;
				datefr4.min=null; dateto4.min=null;
				datefr5.min=null; dateto5.min=null;
				datefr6.min=null; dateto6.min=null;
				datefr7.min=null; dateto7.min=null;
				datefr8.min=null; dateto8.min=null;
				datefr9.min=null; dateto9.min=null;
				datefr10.min=null; dateto10.min=null;
				datefr11.min=null; dateto11.min=null;
				datefr12.min=null; dateto12.min=null;
				hdate.check();
			});

			$('#r').click(function(){
				location.reload()
			});

			function saveForm(form,oper,saveParam,urlParam){
				saveParam.oper=oper;
				
				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize() , function( data ) {
					
				}).fail(function(data) {
					errorText(dialog,data.responseText);
				}).success(function(data){
				});
			}

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			//toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno']);


			////////////////////////////////////////////////////////////////////////////////////////////////////
			$("#jqGridPager_center").hide();
			$("#jqGridPager_right").hide();

			/////////////////// FUNCTION HIDE /////////////////////////////////////////////////////////////////////

			function hideDateAll(hdt){
				this.hdt=hdt;
				this.check = function(){
					$.each(this.hdt, function( index, value ) {
						$("#"+value).hide();
					});
				}
			}

			var hdate=new hideDateAll(['1','2','3','4','5','6','7','8','9','10','11','12']);

			function showDateAll(sdt){
				this.sdt=sdt;
				this.check = function(){
					$.each(this.sdt, function( index, value ) {
						$("#"+value).show();
					});
				}
			}

			var sdate=new showDateAll(['1','2','3','4','5','6','7','8','9','10','11','12']);


			///////////////////////////////////////////////////////////////////////////////////////////////////////

			/////////////////// FUNCTION ON CLICK  ////////////////////////////////////////////////////////////////

			function addClickTrigger(row){
				$("#save"+row).on('click',function(){
					var datefr=$("#datefr"+row);
					var dateto=$("#dateto"+row);

					if(datefr.val()==''){
						haveerror(datefr)
					}else if(dateto.val()==''){
						haveerror(dateto)
					}else{
						noerror(datefr);noerror(dateto);
						datefr.prop('disabled',true);
						dateto.prop('disabled',true);
						addSaveColumn($("#addPd tbody tr#"+(parseInt(row)+1)),parseInt(row)+1,false);
					}
				});

				$("#del"+row).on('click',function(){
					clearDate(row);
					hideBelow(parseInt(row)+1);
				});
			}

			function haveerror(obj){
				obj.addClass( "error" ).removeClass( "success" );
			}

			function noerror(obj){
				obj.addClass( "success" ).removeClass( "error" );
			}

			function hideBelow(index){
				for(x=index;x<=12;x++){
					clearDate(x);
					$("#"+x).hide();
				}
			}

			function clearDate(row){
				$("#datefr"+row).prop('disabled',false);
				$("#dateto"+row).prop('disabled',false);
				$("#datefr"+row).val('');
				$("#dateto"+row).val('');
			}


			function addSaveColumn(obj,row,isth){
				$("#"+row).show();
				if(obj.children('.tdSave').length == 0 && row<=12){
					if(isth)$("#addPd thead tr").append("<td><b>Action</b></td>");
					obj.append("<td class='tdSave'><button id='save"+row+"' type='button' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-ok'></span></button> <button type='button' class='btn btn-default btn-sm' id='del"+row+"'><span class='glyphicon glyphicon-remove'></span></button></td>");
					addClickTrigger(row);
				}
			}

			$("#year").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						addSaveColumn($("#addPd tbody tr#1"),'1',true);
					}
			});



			

			///////////////////////////////////////////////////////////////////////////////////////////////////////


			/////////////////// FUNCTION TAB /////////////////////////////////////////////////////////////////////

			/*$("#dateto1").keydown(function(e) {//<br> <span class='glyphicon glyphicon-remove'></span></td>
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#2").show();
							$("#addPd tbody tr#2").append("<span class='11 glyphicon glyphicon-ok'></span> <br> <span class='glyphicon glyphicon-remove'></span>");
						}, 1500 );
					}
			});

			$("#dateto2").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#3").show();	
							$("#addPd tbody tr#3").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");
						}, 1500 );
					}
			});

			$("#dateto3").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#4").show();
							$("#addPd tbody tr#4").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");	
						}, 1500 );
					}
			});

			$("#dateto4").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#5").show();	
							$("#addPd tbody tr#5").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");
						}, 1500 );
					}
			});

			$("#dateto5").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#6").show();	
							$("#addPd tbody tr#6").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");
						}, 1500 );
					}
			});

			$("#dateto6").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#7").show();	
							$("#addPd tbody tr#7").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");
						}, 1500 );
					}
			});

			$("#dateto7").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#8").show();	
							$("#addPd tbody tr#8").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");
						}, 1500 );
					}
			});

			$("#dateto8").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#9").show();	
							$("#addPd tbody tr#9").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");
						}, 1500 );
					}
			});

			$("#dateto9").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#10").show();	
							$("#addPd tbody tr#10").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");
						}, 1500 );
					}
			});

			$("#dateto10").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#11").show();
							$("#addPd tbody tr#11").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");	
						}, 1500 );
					}
			});

			$("#dateto11").keydown(function(e) {
				var code = e.keyCode || e.which;
					if (code == '9') { // -->for tab
						delay(function(){
							$("#12").show();
							$("#addPd tbody tr#12").append("<td><span class='glyphicon glyphicon-ok'>   <span class='glyphicon glyphicon-remove'></td>");	
						}, 1500 );
					}
			});*/

			/*$("#picon1").on('click',function(){
				alert("s");
			});*/

			///////////////////////////////////////////////////////////////////////////////////////////////////////

			/////////////////// FUNCTION DATE /////////////////////////////////////////////////////////////////////

			$('#datefr1').on('change', function() {
			    var df1 = $("#datefr1").val();
			    var datefr1 = new Date(df1);
				datefr1.setDate( datefr1.getDate() + 1 ); 
				var m = datefr1.getUTCMonth() + 1;
					if (m < 10) m='0'+m;
				var d = datefr1.getUTCDate();
				    if (d < 10) d='0'+d;
				var ndf1 = datefr1.getUTCFullYear()+"-"+m+"-"+d;
				dateto1.min = ndf1;  
			});

            $('#dateto1').on('change', function() {
		    	var dt1 = $("#dateto1").val();
		    	var dateto1 = new Date(dt1);
		        dateto1.setDate( dateto1.getDate() + 1 ); 
				var m = dateto1.getUTCMonth() + 1;
					if (m < 10) m='0'+m;
				var d = dateto1.getUTCDate();
				    if (d < 10) d='0'+d;
				var ndt1 = dateto1.getUTCFullYear()+"-"+m+"-"+d;
				datefr2.min = ndt1;
			});


		    $('#datefr2').on('change', function() {
		    	var df2 = $("#datefr2").val();
			    var datefr2 = new Date(df2);
				datefr2.setDate( datefr2.getDate() + 1 );
				var m = datefr2.getUTCMonth() + 1;
				    if (m < 10) m='0'+m;
				var d = datefr2.getUTCDate();
				    if (d < 10) d='0'+d;
				var ndf2 = datefr2.getUTCFullYear()+"-"+m+"-"+d;
				dateto2.min = ndf2;  
			});

            $('#dateto2').on('change', function() {
		    	var dt2 = $("#dateto2").val();
				var dateto2 = new Date(dt2);
			    dateto2.setDate( dateto2.getDate() + 1 );
			    var m = dateto2.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto2.getUTCDate();
					if (d < 10) d='0'+d;
				var ndt2 = dateto2.getUTCFullYear()+"-"+m+"-"+d;
				datefr3.min = ndt2;  
			});

			$('#datefr3').on('change', function() {
				var df3 = $("#datefr3").val();
				var datefr3 = new Date(df3);
				datefr3.setDate( datefr3.getDate() + 1 );
				var m = datefr3.getUTCMonth() + 1;
					if (m < 10) m='0'+m;
			    var d = datefr3.getUTCDate();
			    	if (d < 10) d='0'+d;
				var ndf3 = datefr3.getUTCFullYear()+"-"+m+"-"+d;
				dateto3.min = ndf3;  
			});

			$('#dateto3').on('change', function() {
				var dt3 = $("#dateto3").val();
				var dateto3 = new Date(dt3);
				dateto3.setDate( dateto3.getDate() + 1 );
				var m = dateto3.getUTCMonth() + 1;
					if (m < 10) m='0'+m;
			    var d = dateto3.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt3 = dateto3.getUTCFullYear()+"-"+m+"-"+d;
			    datefr4.min = ndt3;  
			});

			$('#datefr4').on('change', function() {
				var df4 = $("#datefr4").val();
				var datefr4 = new Date(df4);
				datefr4.setDate( datefr4.getDate() + 1 );
			    var m = datefr4.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr4.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf4 = datefr4.getUTCFullYear()+"-"+m+"-"+d;
			    dateto4.min = ndf4;
			});

			$('#dateto4').on('change', function() {
			    var dt4 = $("#dateto4").val();
			    var dateto4 = new Date(dt4);
			    dateto4.setDate( dateto4.getDate() + 1 );
			    var m = dateto4.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto4.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt4 = dateto4.getUTCFullYear()+"-"+m+"-"+d;
			    datefr5.min = ndt4;
			});

			$('#datefr5').on('change', function() {
				var df5 = $("#datefr5").val();
				var datefr5 = new Date(df5);
				datefr5.setDate( datefr5.getDate() + 1 );
			    var m = datefr5.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr5.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf5 = datefr5.getUTCFullYear()+"-"+m+"-"+d;
			    dateto5.min = ndf5;
			});

			$('#dateto5').on('change', function() {
				var dt5 = $("#dateto5").val();
			    var dateto5 = new Date(dt5);
			    dateto5.setDate( dateto5.getDate() + 1 );
			    var m = dateto5.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto5.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt5 = dateto5.getUTCFullYear()+"-"+m+"-"+d;
			    datefr6.min = ndt5;
			});

			$('#datefr6').on('change', function() {
			    var df6 = $("#datefr6").val();
				var datefr6 = new Date(df6);
				datefr6.setDate( datefr6.getDate() + 1 );
			    var m = datefr6.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr6.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf6 = datefr6.getUTCFullYear()+"-"+m+"-"+d;
			    dateto6.min = ndf6;
			});

			$('#dateto6').on('change', function() {
				var dt6 = $("#dateto6").val();
			    var dateto6 = new Date(dt6);
			    dateto6.setDate( dateto6.getDate() + 1 );
			    var m = dateto6.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto6.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt6 = dateto6.getUTCFullYear()+"-"+m+"-"+d;
			    datefr7.min = ndt6;
			});

			$('#datefr7').on('change', function() {
				var df7 = $("#datefr7").val();
				var datefr7 = new Date(df7);
				datefr7.setDate( datefr7.getDate() + 1 );
			    var m = datefr7.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr7.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf7 = datefr7.getUTCFullYear()+"-"+m+"-"+d;
			    dateto7.min = ndf7;
			});

			$('#dateto7').on('change', function() {
				var dt7 = $("#dateto7").val();
			    var dateto7 = new Date(dt7);
			    dateto7.setDate( dateto7.getDate() + 1 );
			    var m = dateto7.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto7.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt7 = dateto7.getUTCFullYear()+"-"+m+"-"+d;
			    datefr8.min = ndt7;
			});

			$('#datefr8').on('change', function() {
			    var df8 = $("#datefr8").val();
				var datefr8 = new Date(df8);
				datefr8.setDate( datefr8.getDate() + 1 );
			    var m = datefr8.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr8.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf8 = datefr8.getUTCFullYear()+"-"+m+"-"+d;
			    dateto8.min = ndf8;
			});

			$('#dateto8').on('change', function() {
				var dt8 = $("#dateto8").val();
			    var dateto8 = new Date(dt8);
			    dateto8.setDate( dateto8.getDate() + 1 );
			    var m = dateto8.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto8.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt8 = dateto8.getUTCFullYear()+"-"+m+"-"+d;
			    datefr9.min = ndt8;
			});

			$('#datefr9').on('change', function() {
				var df9 = $("#datefr9").val();
				var datefr9 = new Date(df9);
				datefr9.setDate( datefr9.getDate() + 1 );
			    var m = datefr9.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr9.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf9 = datefr9.getUTCFullYear()+"-"+m+"-"+d;
			    dateto9.min = ndf9;
			});

			$('#dateto9').on('change', function() {
				var dt9 = $("#dateto9").val();
			    var dateto9 = new Date(dt9);
			    dateto9.setDate( dateto9.getDate() + 1 );
			    var m = dateto9.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto9.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt9 = dateto9.getUTCFullYear()+"-"+m+"-"+d;
			    datefr10.min = ndt9;
			});

			$('#datefr10').on('change', function() {
				var df10 = $("#datefr10").val();
				var datefr10 = new Date(df10);
				datefr10.setDate( datefr10.getDate() + 1 );
			    var m = datefr10.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr10.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf10 = datefr10.getUTCFullYear()+"-"+m+"-"+d;
			    dateto10.min = ndf10;
			});

			$('#dateto10').on('change', function() {
				var dt10 = $("#dateto10").val();
			    var dateto10 = new Date(dt10);
			    dateto10.setDate( dateto10.getDate() + 1 );
			    var m = dateto10.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto10.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt10 = dateto10.getUTCFullYear()+"-"+m+"-"+d;
			    datefr11.min = ndt10;
			});

			$('#datefr11').on('change', function() {
				var df11 = $("#datefr11").val();
				var datefr11 = new Date(df11);
				datefr11.setDate( datefr11.getDate() + 1 );
			    var m = datefr11.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr11.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf11 = datefr11.getUTCFullYear()+"-"+m+"-"+d;
			    dateto11.min = ndf11;
			});

			$('#dateto11').on('change', function() {
				var dt11 = $("#dateto11").val();
			    var dateto11 = new Date(dt11);
			    dateto11.setDate( dateto11.getDate() + 1 );
			    var m = dateto11.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto11.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt11 = dateto11.getUTCFullYear()+"-"+m+"-"+d;
			    datefr12.min = ndt11;
			});

			$('#datefr12').on('change', function() {
				var df12 = $("#datefr12").val();
				var datefr12 = new Date(df12);
				datefr12.setDate( datefr12.getDate() + 1 );
			    var m = datefr12.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = datefr12.getUTCDate();
			     	if (d < 10) d='0'+d;
			    var ndf12 = datefr12.getUTCFullYear()+"-"+m+"-"+d;
			    dateto12.min = ndf12;
			});

			$('#dateto12').on('change', function() {
				var dt12 = $("#dateto11").val();
			    var dateto12 = new Date(dt12);
			    dateto12.setDate( dateto12.getDate() + 1 );
			    var m = dateto12.getUTCMonth() + 1;
			    	if (m < 10) m='0'+m;
			    var d = dateto12.getUTCDate();
			    	if (d < 10) d='0'+d;
			    var ndt12 = dateto12.getUTCFullYear()+"-"+m+"-"+d;
			    datefr12.max = ndt12;
			});

});
		