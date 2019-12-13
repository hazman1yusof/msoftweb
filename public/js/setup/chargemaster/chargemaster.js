
	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';

	$(document).ready(function () {
		$("body").show();
		check_compid_exist("input[name='cm_lastcomputerid']", "input[name='cm_lastipaddress']", "input[name='cm_computerid']", "input[name='cm_ipaddress']");
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
		
		var mycurrency =new currencymode(['#dtl_amt1','#dtl_amt2','#dtl_amt3','#dtl_costprice']);	
		var mycurrency2 =new currencymode([]);
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
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				switch(oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", false);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						break;
					case state = 'edit':
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						frozeOnEdit("#formdata");
						recstatusDisable("cm_recstatus");
						break;
					case state = 'view':
						disableForm('#formdata');
						$("#pg_jqGridPager2 table").hide();
						break;
				}
				if(oper!='view'){
					set_compid_from_storage("input[name='cm_lastcomputerid']", "input[name='cm_lastipaddress']", "input[name='cm_computerid']", "input[name='cm_ipaddress']");
					check_chgclass_on_open();
					dialog_chggroup.on();
					dialog_chgclass.on();
					dialog_chgtype.on();
					dialog_doctorcode.on();
					dialog_deptcode.on();
				}
				if(oper!='add'){
					///toggleFormData('#jqGrid','#formdata');
					dialog_chggroup.check(errorField);
					dialog_chgclass.check(errorField);
					dialog_chgtype.check(errorField);
					dialog_doctorcode.check(errorField);
					dialog_deptcode.check(errorField);
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata');
				emptyFormdata(errorField,'#formdata2');
				//$('.alert').detach();
				$('.my-alert').detach();
				dialog_chggroup.off();
				dialog_chgclass.off();
				dialog_chgtype.off();
				dialog_doctorcode.off();
				dialog_deptcode.off();
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
			url:'/util/get_table_default',
			field:'',
			fixPost:'true',
			table_name:['hisdb.chgmast AS CM', 'hisdb.chgclass AS CC', 'hisdb.chggroup AS CG', 'hisdb.chgtype AS CT'],
			table_id:'cm_chgcode',
			join_type:['LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN'],
			join_onCol:['cm.chgclass', 'cm.chggroup', 'cm.chgtype'],
			join_onVal:['cc.classcode', 'cg.grpcode', 'ct.chgtype'],
			filterCol:['cm.compcode'],
			filterVal:['session.compcode']
		}

		/////////////////////parameter for saving url////////////////////////////////////////////////
		var saveParam={
			action:'save_table_default',
			url:'chargemaster/form',
			fixPost:'true',
			field:'',
			idnoUse:'cm_idno',
			oper:oper,
			table_name:'hisdb.chgmast',
			table_id:'chgcode',
			saveip:'true',
			checkduplicate:'true'
		};
			
		/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
		$("#jqGrid").jqGrid({
			datatype: "local",
			  colModel: [
				{ label: 'idno', name: 'cm_idno', sorttype: 'number', hidden:true },
				{ label: 'Compcode', name: 'cm_compcode', hidden:true},
				{ label: 'Charge Code', name: 'cm_chgcode', classes: 'wrap', width: 30, canSearch: true},
				{ label: 'Description', name: 'cm_description', classes: 'wrap', width: 60, canSearch: true},
				{ label: 'Class', name: 'cm_chgclass', classes: 'wrap', width: 20,checked:true},
				{ label: 'Class Name', name: 'cc_description', classes: 'wrap', width: 30,checked:true},
				{ label: 'Group', name: 'cm_chggroup', classes: 'wrap', width: 20, canSearch: true},
				{ label: 'Description', name: 'cg_description', classes: 'wrap', width: 40},
				{ label: 'Charge Type', name: 'cm_chgtype', classes: 'wrap', width: 30, canSearch: true},
				{ label: 'Description', name: 'ct_description', classes: 'wrap', width: 30},
				{ label: 'UOM', name: 'cm_uom', width: 30,hidden:false },
				{ label: 'Generic Name', name: 'cm_brandname', width: 60},
				{ label: 'cm_barcode', name: 'cm_barcode', hidden:true},
				{ label: 'cm_constype', name: 'cm_constype', hidden:true},
				{ label: 'cm_invflag', name: 'cm_invflag', hidden:true},
				{ label: 'cm_packqty', name: 'cm_packqty', hidden:true},
				{ label: 'cm_druggrcode', name: 'cm_druggrcode', hidden:true},
				{ label: 'cm_subgroup', name: 'cm_subgroup', hidden:true},
				{ label: 'cm_stockcode', name: 'cm_stockcode', hidden:true},
				{ label: 'cm_invgroup', name: 'cm_invgroup', hidden:true},
				{ label: 'cm_costcode', name: 'cm_costcode', hidden:true},
				{ label: 'cm_revcode', name: 'cm_revcode', hidden:true},
				{ label: 'cm_seqno', name: 'cm_seqno', hidden:true},
				{ label: 'cm_overwrite', name: 'cm_overwrite', hidden:true},
				{ label: 'cm_doctorstat', name: 'cm_doctorstat', hidden:true},
				{ label: 'Upd User', name: 'cm_upduser', width: 80,hidden:true}, 
				{ label: 'Upd Date', name: 'cm_upddate', width: 90,hidden:true},
				{ label: 'Status', name:'cm_recstatus', width:30, classes:'wrap', hidden:false,
				formatter: formatterstatus, unformat: unformatstatus, cellattr: function (rowid, cellvalue)
				{ return cellvalue == 'Deactive' ? 'class="alert alert-danger"' : '' },},
				{ label: 'computerid', name: 'cm_computerid', width: 90, hidden: true, classes: 'wrap' },
				{ label: 'ipaddress', name: 'cm_ipaddress', width: 90, hidden: true, classes: 'wrap' },
				{ label: 'lastcomputerid', name: 'cm_lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
				{ label: 'lastipaddress', name: 'cm_lastipaddress', width: 90, hidden: true, classes: 'wrap' },
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
				urlParam2.filterVal[1]=selrowData("#jqGrid").cm_chgcode;
				refreshGrid("#jqGrid3",urlParam2);
			},
			ondblClickRow: function(rowid, iRow, iCol, e){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			},
			gridComplete: function(){
				if(oper == 'add'){
					$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				}

				// $('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			},
		});

		/////////////////////////////populate data for dropdown search By////////////////////////////
		searchBy();
		function searchBy(){
			$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
				if(value['canSearch']){
					if(value['selected']){
						$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
					}else{
						$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
					}
				}
			});
		}

		$('#Scol').on('change', scolChange);

		function scolChange() {
			if($('#Scol').val()=='cm_chggroup'){
				$("#div_chgtype").hide();
				$("#div_chggroup").show();
			} else if($('#Scol').val() == 'cm_chgtype'){
				$("#div_chggroup").hide();
				$("#div_chgtype").show();
			} else {
				$("#div_chgtype,#div_chggroup").hide();
			}
		}

		//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
		// populateSelect('#jqGrid','#searchForm');
		searchClick_('#jqGrid','#searchForm',urlParam);

		function searchClick_(grid,form,urlParam){
			$(form+' [name=Stext]').on( "keyup", function() {
				delay(function(){
					if($(form+' [name=Scol] option:selected').val() == 'cm_description'){
						search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'cm_brandname');
					}else{
						search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
					}
				}, 500 );
				refreshGrid("#jqGrid3",null,"kosongkan");
			});

			$(form+' [name=Scol]').on( "change", function() {
				if($(form+' [name=Scol] option:selected').val() == 'cm_description'){
					search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'cm_brandname');
				}else{
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				}
				refreshGrid("#jqGrid3",null,"kosongkan");
			});
		}

		$('#searchForm [name=Stext]').on( "keyup", function() {
			$("#chgtype,#chggroup").val($(this).val());
		});

		//////////add field into param, refresh grid if needed////////////////////////////////////////////////
		addParamField('#jqGrid',true,urlParam);
		addParamField('#jqGrid',false,saveParam,['cm_idno','ct_description', 'cc_description','cg_description', 'cm_compcode', 'cm_ipaddress', 'cm_computerid', 'cm_adddate', 'cm_adduser','cm_upduser','cm_upddate','cm_recstatus']);

		////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
		function hideatdialogForm(hide,saveallrow){
			if(saveallrow == 'saveallrow'){
				$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
				$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
			}else if(hide){
				$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
				$("#saveDetailLabel").show();
			}else{
				$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
				$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
			}
		}

		/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
		function saveHeader(form,selfoper,saveParam,obj){
			if(obj==null){
				obj={};
			}
			saveParam.oper=selfoper;

			$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {

			},'json').fail(function (data) {
				alert(data.responseText);
			}).done(function (data) {
				unsaved = false;
				hideatdialogForm(false);

				if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
					addmore_jqgrid2.state = true;
					$('#jqGrid2_iladd').click();
				}
				if(selfoper=='add'){
					oper='edit';//sekali dia add terus jadi edit lepas tu
					$('#idno').val(data.idno);
					
					urlParam2.filterVal[1]=$('#cm_chgcode').val();
				}else if(selfoper=='edit'){
					//doesnt need to do anything
				}
				disableForm('#formdata');

			});
		}
		
		$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
			unsaved = true; //kalu dia change apa2 bagi prompt
		});

		$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
			unsaved = true; //kalu dia change apa2 bagi prompt
		});

		/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
		var urlParam2={
			action:'get_table_default',
			url:'/util/get_table_default',
			field:['cp.effdate','cp.amt1','cp.amt2','cp.amt3','cp.costprice','cp.iptax','cp.optax','cp.adduser','cp.adddate', 'cp.chgcode','cm.chgcode','cp.idno'],
			table_name:['hisdb.chgprice AS cp', 'hisdb.chgmast AS cm'],
			table_id:'lineno_',
			join_type:['LEFT JOIN'],
			join_onCol:['cp.chgcode'],
			join_onVal:['cm.chgcode'],
			filterCol:['cp.compcode','cp.chgcode'],
			filterVal:['session.compcode','']
		};

		var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
		
		////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
		$("#jqGrid2").jqGrid({
			datatype: "local",
			editurl: "/chargemasterDetail/form",
			colModel: [
				{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
				{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
				{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editoptions: {
	                    dataInit: function (element) {
	                        $(element).datepicker({
	                            id: 'expdate_datePicker',
	                            dateFormat: 'dd/mm/yy',
	                            minDate: "dateToday",
	                            showOn: 'focus',
	                            changeMonth: true,
			  					changeYear: true,
	                        });
	                    }
	                }
				},
				{ label: 'Price 1', name: 'amt1', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Price 2', name: 'amt2', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Price 3', name: 'amt3', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Cost Price', name: 'costprice', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Inpatient Tax', name: 'iptax', width: 150,align: 'right' , classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:iptaxCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				},
				{ label: 'Outpatient Tax', name: 'optax', width: 150,align: 'right' , classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:optaxCustomEdit,
						       custom_value:galGridCustomValue 	
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
			pager: "#jqGridPager2",
			loadComplete: function(){
				if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
				else{
					$('#jqGrid2').jqGrid ('setSelection', "1");
				}

				addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			},
			gridComplete: function(){
				fdl.set_array().reset();
				
			},
			beforeSubmit: function(postdata, rowid){ 
				// dialog_deptcodedtl.check(errorField);
		 	}
		});

		///////////////////////////////////////cust_rules//////////////////////////////////////////////
		function cust_rules(value,name){
			var temp;
			switch(name){
				case 'Inpatient Tax':temp=$('#iptax');break;
				case 'Outpatient Tax':temp=$('#optax');break;
					break;
			}
			return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
		}

		function showdetail(cellvalue, options, rowObject){
			var field,table,case_;
			switch(options.colModel.name){
				case 'iptax':field=['taxcode','description'];table="hisdb.taxmast";case_='iptax';break;
				case 'optax': field = ['taxcode', 'description']; table = "hisdb.taxmast";case_='optax';break;
			}
			var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

			fdl.get_array('chargemaster',options,param,case_,cellvalue);
			
			return cellvalue;
		}

		function iptaxCustomEdit(val, opt) {
			val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
			return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="iptax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}

		function optaxCustomEdit(val, opt) {
			val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
			return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="optax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}
		function galGridCustomValue (elem, operation, value){
			if(operation == 'get') {
				return $(elem).find("input").val();
			} 
			else if(operation == 'set') {
				$('input',elem).val(value);
			}
		}


		/////////////////////////start grid pager/////////////////////////////////////////////////////////

		$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
			view:false,edit:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam,oper);
			},
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer",position: "first", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				oper='del';
				let cm_idno = selrowData('#jqGrid').cm_idno;
				if(!cm_idno){
					alert('Please select row');
					return emptyFormdata(errorField,'#formdata');
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':cm_idno});
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
				refreshGrid("#jqGrid2",urlParam2);
			},
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
			buttonicon:"glyphicon glyphicon-edit",
			title:"Edit Selected Row",  
			onClickButton: function(){
				oper='edit';
				selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				refreshGrid("#jqGrid2",urlParam2);
			}, 
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer",position: "first",  
			buttonicon:"glyphicon glyphicon-plus", 
			id: 'glyphicon-plus',
			title:"Add New Row", 
			onClickButton: function(){
				oper='add';
				$( "#dialogForm" ).dialog( "open" );
			},
		});

		//////////////////////////////////////end grid/////////////////////////////////////////////////////////
		//////////////////////////////////////////myEditOptions/////////////////////////////////////////////

		var myEditOptions = {
	        keys: true,
	        extraparam:{
			    "_token": $("#_token").val()
	        },
	        oneditfunc: function (rowid) {

	        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

	        	dialog_optax.on();
				dialog_iptax.on();
				// dialog_dtliptax.on();
				// dialog_dtloptax.on();


	        	unsaved = false;
				mycurrency2.array.length = 0;
				Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amt1']","#jqGrid2 input[name='amt2']","#jqGrid2 input[name='amt3']","#jqGrid2 input[name='costprice']"]);

				mycurrency2.formatOnBlur();//make field to currency on leave cursor

	   //      	$("input[name='dtl_maxlimit']").keydown(function(e) {//when click tab at document, auto save
				// 	var code = e.keyCode || e.which;
				// 	if (code == '9')$('#jqGrid2_ilsave').click();
				// })
	        },
	        aftersavefunc: function (rowid, response, options) {
	        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
	        	refreshGrid('#jqGrid2',urlParam2,'add');
		    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
	        }, 
	        errorfunc: function(rowid,response){
	        	alert(response.responseText);
	        	refreshGrid('#jqGrid2',urlParam2,'add');
		    	$("#jqGridPager2Delete").show();
	        },
	        beforeSaveRow: function(options, rowid) {

	        	//if(errorField.length>0)return false;  

				let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
				let editurl = "/chargemasterDetail/form?"+
					$.param({
						action: 'chargemasterDetail_save',
						oper: 'add',
						chgcode: $('#cm_chgcode').val(),
						uom: $('#cm_uom').val(),
						// authorid:$('#authorid').val()
					});
				$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
	        },
	        afterrestorefunc : function( response ) {
				hideatdialogForm(false);
		    }
	    };

		//////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////

		$("#jqGrid2").inlineNav('#jqGridPager2',{	
			add:true,
			edit:true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: { 
				addRowParams: myEditOptions
			},
			editParams: myEditOptions
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2Delete",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
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
					    			action: 'chargemasterDetail_save',
									idno: selrowData('#jqGrid2').idno,

					    		}
					    		$.post( "/chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									refreshGrid("#jqGrid2",urlParam2);
								});
					    	}else{
	        					$("#jqGridPager2EditAll").show();
					    	}
					    }
					});
				}
			},
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2EditAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-th-list",
			title:"Edit All Row",
			onClickButton: function(){
				mycurrency2.array.length = 0;
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
			    for (var i = 0; i < ids.length; i++) {

			        $("#jqGrid2").jqGrid('editRow',ids[i]);

			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			    }
			    mycurrency2.formatOnBlur();
		    	onall_editfunc();
				hideatdialogForm(true,'saveallrow');
			},
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2SaveAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-download-alt",
			title:"Save All Row",
			onClickButton: function(){
				var ids = $("#jqGrid2").jqGrid('getDataIDs');

				var jqgrid2_data = [];
				mycurrency2.formatOff();
			    for (var i = 0; i < ids.length; i++) {

					var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
			    	var obj = 
			    	{
			    		'idno' : data.idno,
			    		'effdate' : $("#jqGrid2 input#"+ids[i]+"_effdate").val(),
						'amt1' : $("#jqGrid2 input#"+ids[i]+"_amt1").val(),
						'amt2' : $("#jqGrid2 input#"+ids[i]+"_amt2").val(),
						'amt3' : $("#jqGrid2 input#"+ids[i]+"_amt3").val(),
						'costprice' : $("#jqGrid2 input#"+ids[i]+"_costprice").val(),
						'iptax' : $("#jqGrid2 input#"+ids[i]+"_iptax").val(),
						'optax' : $("#jqGrid2 input#"+ids[i]+"_optax").val()
			    	}

			    	jqgrid2_data.push(obj);
			    }

				var param={
	    			action: 'chargemasterDetail_save',
					_token: $("#_token").val()
	    		}

	    		$.post( "/chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
				}).fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).done(function(data){
					hideatdialogForm(false);
					refreshGrid("#jqGrid2",urlParam2);
				});
			},	
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2CancelAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-remove-circle",
			title:"Cancel",
			onClickButton: function(){
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2);
			},	
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "saveHeaderLabel",
			caption:"Header",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Header"
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "saveDetailLabel",
			caption:"Detail",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Detail"
		});

		//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
		$("#saveDetailLabel").click(function () {
			unsaved = false;
			// dialog_authorid.off();
			// dialog_deptcodehd.off();
			//radbuts.check();
			errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
				saveHeader("#formdata",oper,saveParam);
				unsaved = false;
			} else {
				// dialog_authorid.on();
				// dialog_deptcodehd.on();
			}
		});

		//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
		$("#saveHeaderLabel").click(function () {
			emptyFormdata(errorField, '#formdata2');
			hideatdialogForm(true);
			// dialog_authorid.on();
			// dialog_deptcodehd.on();
			enableForm('#formdata');
			rdonly('#formdata');
			$(".noti").empty();
			refreshGrid("#jqGrid2", urlParam2);
		});

		function onall_editfunc(){
	        dialog_optax.on();
			dialog_iptax.on();
			dialog_dtliptax.on();
			dialog_dtloptax.on();

			
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
		}

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////Chg Price Detail//////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////

		var buttItem1=[{
			text: "Save",click: function() {
				mycurrency.formatOff();
				// mycurrency.check0value(errorField);
				if( $('#FChgPriceDtl').isValid({requiredFields: ''}, {}, true) ) {
					saveFormdata("#jqGrid3","#ChgPriceDtl","#FChgPriceDtl",oper_chgpricedtl,saveParam3,urlParam2,{
							chgcode: selrowData("#jqGrid").cm_chgcode,
							uom: selrowData("#jqGrid").cm_uom,
						});
				}else{
					mycurrency.formatOn();
				}
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}];

		var oper_chgpricedtl;
		$("#ChgPriceDtl")
		  .dialog({ 
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				parent_close_disabled(true);
				switch(oper_chgpricedtl) {
					case state = 'add':
						mycurrency.formatOnBlur();
						$( this ).dialog( "option", "title", "Add" );
						enableForm('#FChgPriceDtl');
						hideOne('#FChgPriceDtl');
						rdonly('#FChgPriceDtl');
						$(this).dialog("option", "buttons",buttItem1);
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$( this ).dialog( "option", "title", "Edit" );
						enableForm('#FChgPriceDtl');
						frozeOnEdit("#ChgPriceDtl");
						$('#FChgPriceDtl :input[hideOne]').show();
						rdonly('#FChgPriceDtl');
						$(this).dialog("option", "buttons",buttItem1);
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$( this ).dialog( "option", "title", "View" );
						disableForm('#FChgPriceDtl');
						$('#FChgPriceDtl :input[hideOne]').show();
						$(this).dialog("option", "buttons",butt2);
						break;
				}
				
				if(oper_chgpricedtl == 'edit'){
					dialog_dtliptax.on();
					dialog_dtloptax.on();
				}
				
				if(oper_chgpricedtl!='add'){
					dialog_dtliptax.check(errorField);
					dialog_dtloptax.check(errorField);
				}
				if (oper_chgpricedtl != 'view') {
					$("#d_authorid").val(selrowData('#jqGrid').authorid);
					dialog_dtliptax.on();
					dialog_dtloptax.on();
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#FChgPriceDtl');
				$('.my-alert').detach();
				dialog_dtliptax.off();
				dialog_dtloptax.off();
				if(oper=='view'){
					$(this).dialog("option", "buttons",buttItem1);
				}
			},
			buttons :buttItem1,
		});

		/////////////////////parameter for jqgrid3/////////////////////////////////////////////////////////////////////
		// var urlParam3={
		// 	action:'get_table_default',
		// 	url:'/util/get_table_default',
		// 	field:['cp.effdate','cp.amt1','cp.amt2','cp.amt3','cp.costprice','cp.iptax','cp.optax','cp.adduser','cp.adddate', 'cp.chgcode','cm.chgcode'],
		// 	table_name:['hisdb.chgprice AS cp', 'hisdb.chgmast AS cm'],
		// 	table_id:'lineno_',
		// 	join_type:['LEFT JOIN'],
		// 	join_onCol:['cp.chgcode'],
		// 	join_onVal:['cm.chgcode'],
		// 	filterCol:['cp.compcode','cp.chgcode'],
		// 	filterVal:['session.compcode','']
		// };

		var saveParam3={
			action:'save_table_default',
			url:'chargemasterDetail/form',
			field:'',
			oper:oper_chgpricedtl,
			table_id:'d_idno',
			saveip:'true'
		};

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// $("#jqGrid3").jqGrid({
		// 	datatype: "local",
		// 	  colModel: [
		// 		// { label: 'compcode', name: 'd_compcode', width: 20, classes: 'wrap', hidden:true},
		// 		// { label: 'authorid', name: 'authorid', width: 20, classes: 'wrap', hidden:true},
		// 		{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true, editable:true},
		// 		{ label: 'Effective date', name: 'effdate', width: 200, classes: 'wrap', editable: true,
		// 			edittype:"date",
		// 		},
		// 		{ label: 'Price 1', name: 'amt1', width: 200, classes: 'wrap', editable: true,
		// 			edittype:"text",
		// 		},
		// 		{ label: 'Price 2', name: 'amt2', width: 200, classes: 'wrap', editable: true,
		// 			edittype:"text",
		// 		},
		// 		{ label: 'Price 3', name: 'amt3', width: 200, classes: 'wrap', editable: true,
		// 			edittype:"text",
		// 		},
		// 		{ label: 'Cost Price', name: 'costprice', width: 200, classes: 'wrap', editable: true,
		// 			edittype:"text",
		// 		},
		// 		{ label: 'Inpatient Tax', name: 'iptax', width: 200, classes: 'wrap', editable: true,
		// 			edittype:"text",
		// 		},
		// 		{ label: 'Outpatient Tax', name: 'optax', width: 200, classes: 'wrap', editable: true,
		// 			edittype:"text",
		// 		},
		// 		{ label: 'User ID', name: 'lastuser', width: 200, classes: 'wrap', editable: false,
		// 			edittype:"text",
		// 		},
		// 		{ label: 'Last Updated', name: 'lastupdate', width: 200, classes: 'wrap', editable: false,
		// 			edittype:"text",
		// 		},
		// 	],
		// 	viewrecords: true,
		// 	//shrinkToFit: true,
		// 	autowidth:true,
		// 	multiSort: true,
		// 	loadonce:false,
		// 	width: 900,
		// 	height: 200,
		// 	rowNum: 30,
		// 	hidegrid: false,
		// 	pager: "#jqGridPager3",
		// 	onPaging: function(pgButton){
		// 	},
		// 	ondblClickRow: function(rowid, iRow, iCol, e){
		// 		$('#cp_idno').val(selrowData('#jqGrid3').cp_idno);
		// 		// $('#d_authorid').val(selrowData('#gridAuthdtl').dtl_authorid);
		// 		$("#jqGridPager3 td[title='Edit Selected Row']").click();
		// 	},
		// 	gridComplete: function(){
		// 		if(oper == 'add'){
		// 			$("#jqGrid3").setSelection($("#jqGrid").getDataIDs()[0]);
		// 		}
	
		// 		$('#jqGrid3 #'+$("#jqGrid3").jqGrid ('getGridParam', 'selrow')).focus();
	
		// 		/////////////////////////////// reccount ////////////////////////////
				
		// 		if($("#jqGrid3").getGridParam("reccount") >= 1){
		// 			$("#jqGridPagerglyphicon-trash").hide();
		// 		} 
	
		// 		if($("#jqGrid3").getGridParam("reccount") < 1){
		// 			$("#jqGridPagerglyphicon-trash").show()
		// 		}
		// 	},
		// 	onSelectRow:function(rowid, selected){
		// 		/*if(rowid != null) {
		// 			rowData = $('#gridAuthdtl').jqGrid ('getRowData', rowid);
		// 			//console.log(rowData.svc_billtype);
		// 			urlParam_suppbonus.filterVal[0]=selrowData("#gridAuthdtl").si_itemcode; 
	
		// 			$("#Fsuppbonus :input[name*='sb_suppcode']").val(selrowData("#gridAuthdtl").si_suppcode);
		// 			$("#Fsuppbonus :input[name*='sb_pricecode']").val(selrowData("#gridAuthdtl").si_pricecode);
		// 			$("#Fsuppbonus :input[name*='sb_itemcode']").val(selrowData("#gridAuthdtl").si_itemcode);
		// 			$("#Fsuppbonus :input[name*='sb_uomcode']").val(selrowData("#gridAuthdtl").si_uomcode);
		// 			$("#Fsuppbonus :input[name*='sb_purqty']").val(selrowData("#gridAuthdtl").si_purqty);
		// 			refreshGrid('#gridSuppBonus',urlParam_suppbonus);
		// 			$("#pg_jqGridPager3 table").show();
		// 		}*/
		// 	},	
		// });

		// $("#jqGrid3").jqGrid('navGrid','#jqGridPager3',{	
		// 	view:false,edit:false,add:false,del:false,search:false,
		// 	beforeRefresh: function(){
		// 		refreshGrid("#jqGrid3",urlParam2);
		// 	},
		// }).jqGrid('navButtonAdd',"#jqGridPager3",{
		// 	caption:"", 
		// 	buttonicon:"glyphicon glyphicon-trash", 
		// 	id:"jqGridPager3glyphicon-trash",
		// 	onClickButton: function(){
		// 		oper_chgpricedtl='del';
		// 		var selRowId = $("#jqGrid3").jqGrid ('getGridParam', 'selrow');
		// 		if(!selRowId){
		// 			alert('Please select row');
		// 			return emptyFormdata(errorField,'#FChgPriceDtl');
		// 		}else{
		// 			saveFormdata("#jqGrid3","#ChgPriceDtl","#FChgPriceDtl",'del',saveParam3,urlParam2,{'idno':selrowData('#jqGrid3').idno});
		// 		}
		// 	}, 
		// 	position: "first", 
		// 	title:"Delete Selected Row", 
		// 	cursor: "pointer"
		// }).jqGrid('navButtonAdd',"#jqGridPager3",{
		// 	caption:"", 
		// 	buttonicon:"glyphicon glyphicon-info-sign", 
		// 	onClickButton: function(){
		// 		oper_chgpricedtl='view';
		// 		selRowId = $("#jqGrid3").jqGrid ('getGridParam', 'selrow');
		// 		populateFormdata("#jqGrid3","#ChgPriceDtl","#FChgPriceDtl",selRowId,'view');
		// 	}, 
		// 	position: "first", 
		// 	title:"View Selected Row", 
		// 	cursor: "pointer"
		// }).jqGrid('navButtonAdd',"#jqGridPager3",{
		// 	caption:"", 
		// 	buttonicon:"glyphicon glyphicon-plus", 
		// 	onClickButton: function(){
		// 		oper_chgpricedtl='add';
		// 		var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		// 		if(!selRowId){
		// 			alert('Please select row');
		// 			return emptyFormdata(errorField,'#FChgPriceDtl');
		// 		}else{
		// 			$( "#ChgPriceDtl" ).dialog( "open" );
		// 		}
		// 		//$('#FChgPriceDtl :input[name=d_lineno_]').hide();
		// 		//$("#Fsuppitems :input[name*='SuppCode']").val(selrowData('#jqGrid').SuppCode);
		// 	}, 
		// 	position: "first", 
		// 	title:"Add New Row", 
		// 	cursor: "pointer"
		// });

		////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////

		$("#jqGrid3").jqGrid({
			datatype: "local",
			editurl: "/chargemasterDetail/form",
			colModel: [
				{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
				{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
				{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editoptions: {
	                    dataInit: function (element) {
	                        $(element).datepicker({
	                            id: 'expdate_datePicker',
	                            dateFormat: 'dd/mm/yy',
	                            minDate: "dateToday",
	                            showOn: 'focus',
	                            changeMonth: true,
			  					changeYear: true,
	                        });
	                    }
	                }
				},
				{ label: 'Price 1', name: 'amt1', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Price 2', name: 'amt2', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Price 3', name: 'amt3', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Cost Price', name: 'costprice', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Inpatient Tax', name: 'iptax', width: 150,align: 'right' , classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:iptaxCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				},
				{ label: 'Outpatient Tax', name: 'optax', width: 150,align: 'right' , classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:optaxCustomEdit,
						       custom_value:galGridCustomValue 	
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
				if(addmore_jqgrid2.more == true){$('#jqGrid3_iladd').click();}
				else{
					$('#jqGrid3').jqGrid ('setSelection', "1");
				}

				addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			},
			gridComplete: function(){
				fdl.set_array().reset();
				
			},
			beforeSubmit: function(postdata, rowid){ 
				// dialog_deptcodedtl.check(errorField);
		 	}
		});

		//////////////////////////////////////////myEditOptions2/////////////////////////////////////////////

		var myEditOptions2 = {
	        keys: true,
	        extraparam:{
			    "_token": $("#_token").val()
	        },
	        oneditfunc: function (rowid) {

	        	$("#jqGridPager3EditAll,#jqGridPager3Delete").hide();

				dialog_dtliptax.on();
				dialog_dtloptax.on();

	        	unsaved = false;
				mycurrency2.array.length = 0;
				Array.prototype.push.apply(mycurrency2.array, ["#jqGrid3 input[name='amt1']","#jqGrid3 input[name='amt2']","#jqGrid3 input[name='amt3']","#jqGrid3 input[name='costprice']"]);

				mycurrency2.formatOnBlur();//make field to currency on leave cursor

	   //      	$("input[name='dtl_maxlimit']").keydown(function(e) {//when click tab at document, auto save
				// 	var code = e.keyCode || e.which;
				// 	if (code == '9')$('#jqGrid2_ilsave').click();
				// })
	        },
	        aftersavefunc: function (rowid, response, options) {
	        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
	        	refreshGrid('#jqGrid3',urlParam2,'add');
		    	$("#jqGridPager3EditAll,#jqGridPager3Delete").show();
	        }, 
	        errorfunc: function(rowid,response){
	        	alert(response.responseText);
	        	refreshGrid('#jqGrid3',urlParam2,'add');
		    	$("#jqGridPager3Delete").show();
	        },
	        beforeSaveRow: function(options, rowid) {

	        	//if(errorField.length>0)return false;  

				let data = $('#jqGrid3').jqGrid ('getRowData', rowid);
				let editurl = "/chargemasterDetail/form?"+
					$.param({
						action: 'chargemasterDetail_save',
						oper: 'add',
						chgcode: $('#cm_chgcode').val(),
						uom: $('#cm_uom').val(),
						// authorid:$('#authorid').val()
					});
				$("#jqGrid3").jqGrid('setGridParam',{editurl:editurl});
	        },
	        afterrestorefunc : function( response ) {
				hideatdialogForm(false);
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
					    			action: 'chargemasterDetail_save',
									idno: selrowData('#jqGrid3').idno,

					    		}
					    		$.post( "/chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
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

			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			    }
			    mycurrency2.formatOnBlur();
		    	onall_editfunc();
				hideatdialogForm(true,'saveallrow');
			},
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "jqGridPager3SaveAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-download-alt",
			title:"Save All Row",
			onClickButton: function(){
				var ids = $("#jqGrid3").jqGrid('getDataIDs');

				var jqgrid2_data = [];
				mycurrency2.formatOff();
			    for (var i = 0; i < ids.length; i++) {

					var data = $('#jqGrid3').jqGrid('getRowData',ids[i]);
			    	var obj = 
			    	{
			    		'idno' : data.idno,
			    		'effdate' : $("#jqGrid3 input#"+ids[i]+"_effdate").val(),
						'amt1' : $("#jqGrid3 input#"+ids[i]+"_amt1").val(),
						'amt2' : $("#jqGrid3 input#"+ids[i]+"_amt2").val(),
						'amt3' : $("#jqGrid3 input#"+ids[i]+"_amt3").val(),
						'costprice' : $("#jqGrid3 input#"+ids[i]+"_costprice").val(),
						'iptax' : $("#jqGrid3 input#"+ids[i]+"_iptax").val(),
						'optax' : $("#jqGrid3 input#"+ids[i]+"_optax").val()
			    	}

			    	jqgrid2_data.push(obj);
			    }

				var param={
	    			action: 'chargemasterDetail_save',
					_token: $("#_token").val()
	    		}

	    		$.post( "/chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
				}).fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).done(function(data){
					hideatdialogForm(false);
					refreshGrid("#jqGrid3",urlParam2);
				});
			},	
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "jqGridPager3CancelAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-remove-circle",
			title:"Cancel",
			onClickButton: function(){
				hideatdialogForm(false);
				refreshGrid("#jqGrid3",urlParam2);
			},	
		});

		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#btn_chggroup').on( "click", function() {
			$('#chggroup ~ a').click();
		});
		var chggroup = new ordialog(
			'chggroup', 'hisdb.chggroup', '#chggroup', 'errorField',
			{
				colModel: [
					{ label: 'Group Code', name: 'grpcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
					{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				],
				urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','A']
				},
				ondblClickRow: function () {
					let data = selrowData('#' + chggroup.gridname).grpcode;
					$("#searchForm input[name='Stext']").val($('#chggroup').val());

					urlParam.searchCol=["cm_chggroup"];
					urlParam.searchVal=[data];
					refreshGrid("#jqGrid3",null,"kosongkan");
					refreshGrid('#jqGrid', urlParam);
				}
			},{
				title: "Select Group Code",
				open: function () {
					chggroup.urlParam.filterCol=['compcode', 'recstatus'];
					chggroup.urlParam.filterVal=['session.compcode', 'A'];
				}
			},'urlParam','radio','tab'
		);
		chggroup.makedialog();
		chggroup.on();

		$('#btn_chgtype').on( "click", function() {
			$('#chgtype ~ a').click();
		});
		var chgtype = new ordialog(
			'chgtype', 'hisdb.chgtype', '#chgtype', 'errorField',
			{
				colModel: [
					{ label: 'Charge Type', name: 'chgtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
					{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true,  or_search: true },
				],
				urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','A']
				},
				ondblClickRow: function () {
					let data = selrowData('#' + chgtype.gridname).chgtype;
					$("#searchForm input[name='Stext']").val($('#chgtype').val());

					urlParam.searchCol=["cm_chgtype"];
					urlParam.searchVal=[data];
					refreshGrid("#jqGrid3",null,"kosongkan");
					refreshGrid('#jqGrid', urlParam);
				}
			},{
				title: "Select Charge Type",
				open: function () {
					chgtype.urlParam.filterCol=['compcode', 'recstatus'];
					chgtype.urlParam.filterVal=['session.compcode', 'A'];
				}
			},'urlParam','radio','tab'
		);
		chgtype.makedialog();
		chgtype.on();

		var dialog_chgclass= new ordialog(
			'cm_chgclass','hisdb.chgclass','#cm_chgclass',errorField,
			{	colModel:[
					{label:'Class Code',name:'classcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','A']
				},
				ondblClickRow: function () {
					let data=selrowData('#'+dialog_chgclass.gridname);
					if(data.classcode == 'C'){
						$('#cm_constype').data('validation','required')
						$('#cm_constype').attr('disabled',false)
						$('#cm_constype').val('A')
						$('#cm_constype option[value=""]').hide()
						dialog_doctorcode.required = true;
						$('#cm_constype').focus();
					}else{
						$('#cm_constype').data('validation','')
						$('#cm_constype').attr('disabled',true)
						$('#cm_constype').val('')
						$('#cm_constype option[value=""]').show()
						dialog_doctorcode.required = false;
						$('#cm_chggroup').focus();
					}
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#cm_constype').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
			},
			{
				title:"Select Class Code",
				open: function(){
					dialog_chgclass.urlParam.filterCol=['compcode', 'recstatus'];
					dialog_chgclass.urlParam.filterVal=['session.compcode', 'A'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_chgclass.makedialog(true);
		$('#cm_chgclass').blur(function(){
			let textval = $(dialog_chgclass.textfield).val();
			if(textval == 'C'){
				$('#cm_constype').data('validation','required')
				$('#cm_constype').attr('disabled',false)
				$('#cm_constype').val('A')
				$('#cm_constype option[value=""]').hide()
				dialog_doctorcode.required = true;
				$('#cm_constype').focus();
				text_error1('#cm_constype');
			}else{
				$('#cm_constype').data('validation','')
				$('#cm_constype').attr('disabled',true)
				$('#cm_constype').val('')
				$('#cm_constype option[value=""]').show()
				dialog_doctorcode.required = false;
				$('#cm_chggroup').focus();
			}
		});
		function check_chgclass_on_open(){
			let textval = $(dialog_chgclass.textfield).val();
			if(textval == 'C'){
				$('#cm_constype').data('validation','required')
				$('#cm_constype').attr('disabled',false)
				$('#cm_constype').val('A')
				$('#cm_constype option[value=""]').hide()
				dialog_doctorcode.required = true;
			}else{
				$('#cm_constype').data('validation','')
				$('#cm_constype').attr('disabled',true)
				$('#cm_constype').val('')
				$('#cm_constype option[value=""]').show()
				dialog_doctorcode.required = false;
			}
		}

		var dialog_chggroup= new ordialog(
			'cm_chggroup','hisdb.chggroup','#cm_chggroup',errorField,
			{	colModel:[
					{label:'Group Code',name:'grpcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','A']
				},
				ondblClickRow: function () {
					$('#cm_chgtype').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#cm_chgtype').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
			},
			{
				title:"Select Group Code",
				open: function(){
					dialog_chggroup.urlParam.filterCol=['compcode', 'recstatus'];
					dialog_chggroup.urlParam.filterVal=['session.compcode', 'A'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_chggroup.makedialog(true);

		var dialog_chgtype= new ordialog(
			'cm_chgtype','hisdb.chgtype','#cm_chgtype',errorField,
			{	colModel:[
					{label:'Charge Type',name:'chgtype',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','A']
				},
				ondblClickRow: function () {
					$('#cm_invgroup').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#cm_invgroup').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
			},
			{
				title:"Select Charge Type",
				open: function(){
					dialog_chgtype.urlParam.filterCol=['compcode', 'recstatus'];
					dialog_chgtype.urlParam.filterVal=['session.compcode', 'A'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_chgtype.makedialog(true);

		var dialog_doctorcode= new ordialog(
			'cm_costcode','hisdb.doctor','#cm_costcode',errorField,
			{	colModel:[
					{label:'Doctor Code',name:'doctorcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Doctor Name',name:'doctorname',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				urlParam: {
					filterCol:['compcode', 'recstatus'],
					filterVal:['session.compcode', 'A']
				},
				ondblClickRow: function () {
					$('#cm_revcode').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#cm_revcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
			},
			{
				title:"Select Doctor Code",
				open: function(){
					dialog_doctorcode.urlParam.filterCol=['compcode', 'recstatus'];
					dialog_doctorcode.urlParam.filterVal=['session.compcode', 'A'];
					
				}
			},'urlParam','radio','tab',false
		);
		dialog_doctorcode.makedialog(true);

		var dialog_deptcode= new ordialog(
			'cm_revcode','sysdb.department','#cm_revcode',errorField,
			{	colModel:[
					{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				urlParam: {
					filterCol:['compcode','chgdept', 'recstatus'],
					filterVal:['session.compcode','1', 'A']
				},
				ondblClickRow: function () {
					$('#cm_seqno').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#cm_seqno').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
			},
			{
				title:"Select Department Code",
				open: function(){
					dialog_deptcode.urlParam.filterCol=['compcode','chgdept', 'recstatus'];
					dialog_deptcode.urlParam.filterVal=['session.compcode','1', 'A'];
					
				}
			},'urlParam','radio','tab',false
		);
		dialog_deptcode.makedialog(true);

		var dialog_iptax = new ordialog(
			'iptax','hisdb.taxmast',"#jqGrid2 input[name='iptax']",errorField,
			{	colModel:[
					{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
					{label:'Rate',name:'rate',width:200,classes:'pointer'},
				],
				urlParam: {
					filterCol:['recstatus','compcode','taxtype'],
					filterVal:['A', 'session.compcode','Input']
						},
				ondblClickRow:function(){
					$('#optax').focus();
				},
				gridComplete: function(obj){
							var gridname = '#'+obj.gridname;
							if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
								$(gridname+' tr#1').click();
								$(gridname+' tr#1').dblclick();
								$('#optax').focus();
							}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
								$('#'+obj.dialogname).dialog('close');
							}
						}
			},{
				title:"Select Receiver Department",
				open: function(){
					dialog_iptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
					dialog_iptax.urlParam.filterVal = ['A', 'session.compcode','Input'];
				}
			},'urlParam','radio','tab'
		);
		dialog_iptax.makedialog();

		var dialog_optax = new ordialog(
			'optax','hisdb.taxmast',"#jqGrid2 input[name='optax']",errorField,
			{	colModel:[
					{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
					{label:'Rate',name:'rate',width:200,classes:'pointer'},
				],
				urlParam: {
					filterCol:['recstatus','compcode','taxtype'],
					filterVal:['A', 'session.compcode','Input']
						},
				ondblClickRow:function(){
					$('#delordhd_credcode').focus();
				},
				gridComplete: function(obj){
							var gridname = '#'+obj.gridname;
							if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
								$(gridname+' tr#1').click();
								$(gridname+' tr#1').dblclick();
								$('#delordhd_credcode').focus();
							}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
								$('#'+obj.dialogname).dialog('close');
							}
						}
			},{
				title:"Select Receiver Department",
				open: function(){
					dialog_optax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
					dialog_optax.urlParam.filterVal = ['A', 'session.compcode','Input'];
				}
			},'urlParam','radio','tab'
		);
		dialog_optax.makedialog();

		var dialog_dtliptax = new ordialog(
			'dtl_iptax','hisdb.taxmast',"#jqGrid3 input[name='iptax']",errorField,
			{	colModel:[
					{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
					{label:'Rate',name:'rate',width:200,classes:'pointer'},
				],
				urlParam: {
					filterCol:['recstatus','compcode','taxtype'],
					filterVal:['A', 'session.compcode','Input']
						},
				ondblClickRow:function(){
					$('#dtl_optax').focus();
				},
				gridComplete: function(obj){
							var gridname = '#'+obj.gridname;
							if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
								$(gridname+' tr#1').click();
								$(gridname+' tr#1').dblclick();
								$('#dtl_optax').focus();
							}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
								$('#'+obj.dialogname).dialog('close');
							}
						}
			},{
				title:"Select Receiver Department",
				open: function(){
					dialog_dtliptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
					dialog_dtliptax.urlParam.filterVal = ['A', 'session.compcode','Input'];
				}
			},'urlParam','radio','tab'
		);
		dialog_dtliptax.makedialog();

		var dialog_dtloptax = new ordialog(
			'dtl_optax','hisdb.taxmast',"#jqGrid3 input[name='optax']",errorField,
			{	colModel:[
					{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
					{label:'Rate',name:'rate',width:200,classes:'pointer'},
				],
				urlParam: {
					filterCol:['recstatus','compcode','taxtype'],
					filterVal:['A', 'session.compcode','Input']
						},
				ondblClickRow:function(){
					$('#lastuser').focus();
				},
				gridComplete: function(obj){
							var gridname = '#'+obj.gridname;
							if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
								$(gridname+' tr#1').click();
								$(gridname+' tr#1').dblclick();
								$('#lastuser').focus();
							}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
								$('#'+obj.dialogname).dialog('close');
							}
						}
			},{
				title:"Select Receiver Department",
				open: function(){
					dialog_dtloptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
					dialog_dtloptax.urlParam.filterVal = ['A', 'session.compcode','Input'];
				}
			},'urlParam','radio','tab'
		);
		dialog_dtloptax.makedialog();

		//////////////////////////////////////////////////////////////////////////////////////////////////////

		$("#jqGrid3_panel").on("show.bs.collapse", function(){
			$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
		});

	});