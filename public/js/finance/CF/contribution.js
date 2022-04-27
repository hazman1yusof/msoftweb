	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';

	$(document).ready(function () {
		$("body").show();
		check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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
		
		var mycurrency =new currencymode(['#stfamount','#amount', '#drprcnt', '#stfpercent']);	
		var mycurrency2 =new currencymode(['#stfamount','#amount', '#drprcnt', '#stfpercent']);
		var fdl = new faster_detail_load();
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

		var oper='add';
		$("#dialogForm")
		  .dialog({ 
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				
				parent_close_disabled(true);

				switch(oper) {
					case state = 'add':
					case state = 'edit':
					case state = 'view':
				}
				if(oper!='view'){
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata');
				emptyFormdata(errorField,'#formdata3');
				//$('.alert').detach();
				$(".noti").empty();
				$('.my-alert').detach();
				if(oper=='view'){
					$(this).dialog("option", "buttons",butt1);
				}
			},
			// buttons :butt1,
		});
		////////////////////////////////////////end dialog///////////////////////////////////////////

		/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

		var urlParam={
			action:'get_table_default',
            url:'util/get_table_default',
            field: '',
            table_name:'hisdb.doctor',
            table_id:'doctorcode',
            sort_idno: true,
		}
		
		/////////////////////////////////// Doctor Header //////////////////////////////////////////////////////////
		///////////////////////////////////////// jqgrid //////////////////////////////////////////////////////////////////
		$("#jqGrid").jqGrid({
			datatype: "local",
			colModel: [
				{label: 'idno', name: 'idno', key: true, hidden:true},
				{label: 'compcode', name: 'compcode', width: 90 , hidden: true, classes: 'wrap'},
				{label: 'Doctor Code', name: 'doctorcode', width: 10, canSearch:true, classes: 'wrap'},
				{label: 'Doctor Name', name: 'doctorname', width: 90, canSearch:true , classes: 'wrap', checked:true},
				{label: 'Login ID', name: 'loginid', width: 30, classes: 'wrap', hidden:true},
				{label: 'Costcenter', name: 'department', width: 90 , hidden: true, classes: 'wrap'},
				{label: 'Discipline Code', name: 'disciplinecode', width: 30, classes: 'wrap', hidden:true},
				{label: 'Speciality Code', name: 'specialitycode', width: 30, classes: 'wrap', hidden:true},
				{label: 'Doctor Type', name: 'doctype', width: 30, classes: 'wrap', hidden:true},
				{label: 'Creditor', name: 'creditorcode', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Resign Date', name: 'resigndate', width: 90 , classes: 'wrap', hidden: true},
				{label: 'idno', name: 'idno', width: 90, classes: 'wrap', hidden: true},
				{label: 'Class', name: 'classcode', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Admission Right', name: 'admright', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Appointment', name: 'appointment', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Company', name: 'company', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Address', name: 'address1', width: 90 , classes: 'wrap', hidden: true},
				{label: 'address2', name: 'address2', width: 90 , classes: 'wrap', hidden: true},
				{label: 'address3', name: 'address3', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Postcode', name: 'postcode', width: 90 , classes: 'wrap', hidden: true},
				{label: 'State', name: 'statecode', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Country', name: 'countrycode', width: 90 , classes: 'wrap', hidden: true},
				{label: 'GST No', name: 'gstno', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Home', name: 'res_tel', width: 90 , classes: 'wrap', hidden: true},
				{label: 'H/Phone', name: 'tel_hp', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Office', name: 'off_tel', width: 90 , classes: 'wrap', hidden: true},	
				{label: 'Operation Theatre (OT)', name: 'operationtheatre', width: 90 , classes: 'wrap', hidden: true},
				{label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', hidden:true},
				{label: 'Interval Time', name: 'intervaltime', width: 90 , classes: 'wrap', hidden: true},
				{label: 'mmcid', name: 'mmcid', width: 90 , classes: 'wrap', hidden: true},
				{label: 'apcid', name: 'apcid', width: 90 , classes: 'wrap', hidden: true},
				{label: 'adduser', name: 'adduser', width: 90, hidden:true},
				{label: 'adddate', name: 'adddate', width: 90, hidden:true},
				{label: 'upduser', name: 'upduser', width: 90, hidden:true},
				{label: 'upddate', name: 'upddate', width: 90, hidden:true},
				{label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
				{label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
			],

			autowidth:true,
			multiSort: true,
			viewrecords: true,
			loadonce:false,
			width: 900,
			height: 250,
			rowNum: 30,
			pager: "#jqGridPager",
			onSelectRow:function(rowid, selected){
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
			
				urlParam2.filterVal[2]=selrowData("#jqGrid").doctorcode;
				refreshGrid("#jqGrid3",urlParam2);
				
			},
			ondblClickRow: function(rowid, iRow, iCol, e){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			},
			gridComplete: function(){
				if(oper == 'add'){
					$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				}

				$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				$("#searchForm input[name=Stext]").focus();
				fdl.set_array().reset();

			},
		});

		addParamField('#jqGrid',true,urlParam);

		/////////////////////////pager///////////////////
		$("#jqGrid").jqGrid('navGrid','#jqGridPager',
			{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
				
			}	
		);

		/////////////////////////////populate data for dropdown search By////////////////////////////
		searchBy();
		function searchBy(){
			$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
				if(value['canSearch']){
					if(value['checked']){
						$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
					}else{
						$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
					}
				}
				searchClick2('#jqGrid', '#searchForm', urlParam);
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

		//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
		// populateSelect('#jqGrid','#searchForm');
		searchClick2('#jqGrid','#searchForm',urlParam);

		function searchClick2(grid,form,urlParam){
			$(form+' [name=Stext]').on( "keyup", function() {
				delay(function(){
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
					refreshGrid("#jqGrid3",null,"kosongkan");
				}, 500 );
			});
	
			$(form+' [name=Scol]').on( "change", function() {
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				refreshGrid("#jqGrid3",null,"kosongkan");
			});
		}

		////////////////////////////////hide at dialogForm//////////////////////////////////////////////////

		function hideatdialogForm_jqGrid3(hide,saveallrow){
			if(saveallrow == 'saveallrow'){

				$("#jqGrid3_iledit,#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3Refresh").hide();
				$("#jqGridPager3SaveAll,#jqGridPager3CancelAll").show();
			}else if(hide){

				$("#jqGrid3_iledit,#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3SaveAll,#jqGridPager3CancelAll,#jqGridPager3Refresh").hide();
			}else{

				$("#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3Refresh").show();
				$("#jqGridPager3SaveAll,#jqGrid3_iledit,#jqGridPager3CancelAll").hide();
			}
			
		}

		///////////////////////////////////////cust_rules//////////////////////////////////////////////
		function cust_rules(value,name){
			var temp;
			switch(name){
				case 'Charge Code':temp=$("input[name='chgcode']");break;
			}
			return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
		}

		function showdetail(cellvalue, options, rowObject){
			var field, table, case_;
			switch(options.colModel.name){
				case 'chgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
			}
			var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

			fdl.get_array('contribution',options,param,case_,cellvalue);
			if(cellvalue == null)cellvalue = " ";
			
			return cellvalue;
		}

		function chgcodeCustomEdit(val, opt) {
			val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
			return $('<div class="input-group"><input jqgrid="jqGrid3" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}

		function galGridCustomValue (elem, operation, value){
			if(operation == 'get') {
				return $(elem).find("input").val();
			} 
			else if(operation == 'set') {
				$('input',elem).val(value);
			}
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////contribution percentage/////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////

		var urlParam2={
			action:'get_table_default',
			url:'util/get_table_default',
			field:['dc.compcode', 'dc.lineno_', 'dc.chgcode', 'dc.effdate', 'dc.epistype', 'dc.drprcnt', 'dc.amount', 'dc.stfamount', 'dc.stfpercent', 'dc.idno', 'd.doctorcode'],
			table_name:['debtor.drcontrib AS dc', 'hisdb.doctor AS d'],
			table_id:'lineno_',
			join_type:['LEFT JOIN'],
			join_onCol:['dc.drcode'],
			join_onVal:['d.doctorcode'],
			filterCol:['dc.compcode', 'dc.unit','d.doctorcode'],
			filterVal:['session.compcode', 'session.unit','']
		};

		var addmore_jqgrid3={more:false,state:true,edit:false}
		////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////

		$("#jqGrid3").jqGrid({
			datatype: "local",
			editurl: "./contribution/form",
			colModel: [
				{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
				{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
				{ label: 'Charge Code', name: 'chgcode', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:chgcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				},
				{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editoptions: {
						dataInit: function (element) {
							$(element).datepicker({
								id: 'expdate_datePicker',
								dateFormat: 'dd/mm/yy',
								minDate: "dateToday",
								//showOn: 'focus',
								changeMonth: true,
								changeYear: true,
								onSelect : function(){
									$(this).focus();
								}
							});
						}
					}
				},
				{ label: 'Type', name: 'epistype', width: 100, align: 'right', classes: 'wrap', editable:true,
					edittype: "select",
					editoptions: {
						value: "IP:IP;OP:OP",
					}
				},
				{ label: 'Patient %', name: 'drprcnt', width: 100, align: 'right', classes: 'wrap', 
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
				{ label: 'Amount', name: 'amount', width: 100, align: 'right', classes: 'wrap', 
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
				{ label: 'Staff %', name: 'stfpercent', width: 100, align: 'right', classes: 'wrap', 
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
				{ label: 'Amount', name: 'stfamount', width: 100, align: 'right', classes: 'wrap', 	
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
				{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
			],
			autowidth: true,
			shrinkToFit: true,
			multiSort: true,
			viewrecords: true,
			loadonce:false,
			width: 1150,
			height: 200,
			rowNum: 30,
			sortname: 'idno',
			sortorder: "desc",
			pager: "#jqGridPager3",
			loadComplete: function(){
				if(addmore_jqgrid3.more == true){$('#jqGrid3_iladd').click();}
				else{
					$('#jqGrid3').jqGrid ('setSelection', "1");
				}

				addmore_jqgrid3.edit = addmore_jqgrid3.more = false; //reset
				
			},
			gridComplete: function(){

				fdl.set_array().reset();
				if(!hide_init){
					hide_init=1;
					hideatdialogForm_jqGrid3(false);
				}
			}
		});
		var hide_init=0;

		//////////////////////////////////////////myEditOptions2 for doctor contribution/////////////////////////////////////////////
		var myEditOptions2 = {
	        keys: true,
	        extraparam:{
			    "_token": $("#_token").val()
	        },
	        oneditfunc: function (rowid) {

	        	$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").hide();

				dialog_chgcode.on();

	        	unsaved = false;
				mycurrency2.array.length = 0;
				Array.prototype.push.apply(mycurrency2.array, ["#jqGrid3 input[name='stfamount']","#jqGrid3 input[name='amount']"]);

				mycurrency2.formatOnBlur();//make field to currency on leave cursor

	        	$("input[name='stfamount']").keydown(function(e) {//when click tab at document, auto save
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid3_ilsave').click();
				});
	        },
	        aftersavefunc: function (rowid, response, options) {
	        	if(addmore_jqgrid3.state==true)addmore_jqgrid3.more=true; //only addmore after save inline
	        	refreshGrid('#jqGrid3',urlParam2,'add');
		    	$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").show();
	        }, 
	        errorfunc: function(rowid,response){
				$(".noti").text(response.responseText);
	        	// alert(response.responseText);
	        	refreshGrid('#jqGrid3',urlParam2,'add');
		    	$("#jqGridPager3Delete,#jqGridPager3Refresh").show();
	        },
	        beforeSaveRow: function(options, rowid) {

	        	//if(errorField.length>0)return false; 

				mycurrency2.formatOff();
				let data = $('#jqGrid3').jqGrid ('getRowData', rowid);
				let editurl = "./contribution/form?"+
					$.param({
						action: 'contribution_save',
						oper: 'add',
						drcode: selrowData('#jqGrid').doctorcode,
					});
				$("#jqGrid3").jqGrid('setGridParam',{editurl:editurl});
	        },
	        afterrestorefunc : function( response ) {
				hideatdialogForm_jqGrid3(false);
		    }
	    };

		//////////////////////////////////////////pager jqgrid3/////////////////////////////////////////////

		$("#jqGrid3").inlineNav('#jqGridPager3',{	
			add:true,
			edit:true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: { 
				addRowParams: myEditOptions2
			},
			editParams: myEditOptions2
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "jqGridPager3Delete",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				selRowId = $("#jqGrid3").jqGrid ('getGridParam', 'selrow');
				if(!selRowId){
					bootbox.alert('Please select row');
				}else{
					bootbox.confirm({
					    message: "Are you sure you want to delete this row?",
					    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					    },
					    callback: function (result) {
					    	if(result == true){
					    		param={
					    			action: 'contribution_save',
									idno: selrowData('#jqGrid3').idno,

					    		}
					    		$.post( "./contribution/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									refreshGrid("#jqGrid3",urlParam2);
								});
					    	}else{
	        					$("#jqGridPager3EditAll").show();
					    	}
					    }
					});
				}
			},
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "jqGridPager3EditAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-th-list",
			title:"Edit All Row",
			onClickButton: function(){
				mycurrency2.array.length = 0;
				var ids = $("#jqGrid3").jqGrid('getDataIDs');
			    for (var i = 0; i < ids.length; i++) {

			        $("#jqGrid3").jqGrid('editRow',ids[i]);

			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_stfamount","#"+ids[i]+"_amount"]);
			    }
			    mycurrency2.formatOnBlur();
		    	onall_editfunc();
				hideatdialogForm_jqGrid3(true,'saveallrow');
			},
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "jqGridPager3SaveAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-download-alt",
			title:"Save All Row",
			onClickButton: function(){
				var ids = $("#jqGrid3").jqGrid('getDataIDs');

				var jqgrid3_data = [];
				mycurrency2.formatOff();
			    for (var i = 0; i < ids.length; i++) {

					var data = $('#jqGrid3').jqGrid('getRowData',ids[i]);
			    	var obj = 
			    	{
			    		'idno' : data.idno,
						'lineno_' : ids[i],
						'chgcode' : $("#jqGrid3 input#"+ids[i]+"_chgcode").val(),
			    		'effdate' : $("#jqGrid3 input#"+ids[i]+"_effdate").val(),
						'epistype' : $("#jqGrid3 input#"+ids[i]+"_epistype").val(),
						'stfamount' : $("#jqGrid3 input#"+ids[i]+"_stfamount").val(),
						'stfpercent' : $("#jqGrid3 input#"+ids[i]+"_stfpercent").val(),
						'drprcnt' : $("#jqGrid3 input#"+ids[i]+"_drprcnt").val(),
						'amount' : $("#jqGrid3 input#"+ids[i]+"_amount").val(),
			    	}

			    	jqgrid3_data.push(obj);
			    }

				var param={
	    			action: 'contribution_save',
					_token: $("#_token").val(),
					idno: $('#idno').val()
	    		}

	    		$.post( "./contribution/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid3_data}, function( data ){
				}).fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).done(function(data){
					hideatdialogForm_jqGrid3(false);
					refreshGrid("#jqGrid3",urlParam2);
				});
			},	
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "jqGridPager3CancelAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-remove-circle",
			title:"Cancel",
			onClickButton: function(){
				hideatdialogForm_jqGrid3(false);
				refreshGrid("#jqGrid3",urlParam2);
			},	
		}).jqGrid('navButtonAdd', "#jqGridPager3", {
			id: "jqGridPager3Refresh",
			caption: "", cursor: "pointer", position: "last",
			buttonicon: "glyphicon glyphicon-refresh",
			title: "Refresh Table",
			onClickButton: function () {
				refreshGrid("#jqGrid3", urlParam2);
			},
		});

		function onall_editfunc(jqgrid="none"){
	        dialog_chgcode.on();

			mycurrency2.formatOnBlur();//make field to currency on leave cursor
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////

		var dialog_chgcode = new ordialog(
			'chgcode','hisdb.chgmast',"#jqGrid3 input[name='chgcode']",errorField,
			{	colModel:[
					{label:'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				],
				urlParam: {
					filterCol:['recstatus','compcode'],
					filterVal:['ACTIVE', 'session.compcode']
				},
				ondblClickRow:function(event){
					
					if(event.type == 'keydown'){

						var optid = $(event.currentTarget).get(0).getAttribute("optid");
						var id_optid = optid.substring(0,optid.search("_"));

						$(event.currentTarget).parent().next().html('');
					}else{

						var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
						var id_optid = optid.substring(0,optid.search("_"));

						$(event.currentTarget).parent().next().html('');
					}

				},
				gridComplete: function(obj){
							var gridname = '#'+obj.gridname;
							if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
								$(gridname+' tr#1').click();
								$(gridname+' tr#1').dblclick();
								// $('#lastuser').focus();
							}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
								$('#'+obj.dialogname).dialog('close');
							}
						}
			},{
				title:"Select Charge Code",
				open: function(){
					dialog_chgcode.urlParam.filterCol = ['recstatus','compcode'];
					dialog_chgcode.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
				},
				close: function(){
					//$("#jqGrid3 input[name='quantity']").focus();
				}
			},'urlParam','radio','tab'
		);
		dialog_chgcode.makedialog();

		//////////////////////////////////////////////////////////////////////////////////////////////////////

		$("#jqGrid3_panel").on("show.bs.collapse", function(){
			$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
		});

	});