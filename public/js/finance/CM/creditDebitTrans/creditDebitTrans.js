
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

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
			
	//////////////////////////////////////////////////////////////

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount']);
	var radbuts=new checkradiobutton(['TaxClaimable']);
	var fdl = new faster_detail_load();

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#actdate"]);
	actdateObj.getdata().set();


	////////////////////////////////////start dialog//////////////////////////////////////
	var oper;
	var unsaved = false;

	$("#dialogForm").dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			mycurrency.formatOnBlur();
			mycurrency.formatOn();
			switch(oper) {
				case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly("#formdata");
					hideOne("#formdata");
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly("#formdata");
					$('#formdata :input[hideOne]').show();
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					$('#formdata :input[hideOne]').show();
					break;
			}
			if(oper!='view'){
				dialog_bankcode.on();
			}

			if(oper!='add'){
				dialog_bankcode.check(errorField);
			}
		},

		close: function( event, ui ) {
			addmore_jqgrid2.state = false;
			addmore_jqgrid2.more = false;
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			$('.my-alert').detach();
			$("#formdata a").off();
			dialog_bankcode.off();
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
			radbuts.reset();
			errorField.length=0;
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.apacthdr',
		table_id:'auditno',
		filterCol: ['trantype'],
		filterVal: [$('#adjustment').val()],
		sort_idno: 'true'
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'cdHeader_Save',
		url:'creditDebitTrans/form',
		field:'',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'idno',
		//saveip:'true',
		checkduplicate:'false',
	};
		
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Audit No', name: 'auditno', width: 16, classes: 'wrap', canSearch: true, checked: true},
			{ label: 'Trantype', name: 'trantype', width: 13},
			{ label: 'Bank Code', name: 'bankcode', width: 35, classes: 'wrap', canSearch: true},
			{ label: 'Reference', name: 'refsource', width: 43, classes: 'wrap',},
			{ label: 'Post Date', name: 'actdate', width: 25, classes: 'wrap'},
			{ label: 'Amount', name: 'amount', width: 28, classes: 'wrap', formatter:'currency'} ,//unformat:unformat2}
			{ label: 'Remarks', name: 'remarks', width: 43, classes: 'wrap',},
			{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap'},
			{ label: 'Entered By', name: 'adduser', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Entered Date', name: 'adddate', width: 40, classes: 'wrap', hidden:true},
			{ label: 'GST', name: 'TaxClaimable', width: 40, hidden:true},
			{ label: 'PV No', name: 'pvno', width: 40, hidden:true},
			{ label: 'source', name: 'source', width: 40, hidden:true},
		 	{ label: 'idno', name: 'idno', width: 40, hidden:'true'},
		 	{ label: 'upduser', name: 'upduser', width: 35, classes: 'wrap', hidden:true},
			{ label: 'upddate', name: 'upddate', width: 40, classes: 'wrap', hidden:true},
		 	
		],
		autowidth:true,
		shrinkToFit: true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function(rowid, selected) {
			populate_formCDT(selrowData("#jqGrid"));
			let recstatus = selrowData("#jqGrid").recstatus;
			if(recstatus=='OPEN'){
				$('#but_cancel_jq,#but_post_jq').show();
				$("#jqGridplus2").show();			
			}else if(recstatus=="POSTED"){
				$('#but_post_jq').hide();
				$('#but_cancel_jq').show();
				$("#jqGridplus2").hide();
			}else if (recstatus == "CANCELLED"){
				$('#but_cancel_jq,#but_post_jq').hide();	
				$("#jqGridplus2").hide();		
			}

			urlParam2.filterVal[1]=selrowData("#jqGrid").auditno;
			refreshGrid("#jqGrid3",urlParam2);
		},

		ondblClickRow: function(rowid, iRow, iCol, e){
		let stat = selrowData("#jqGrid").recstatus;
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
				$('#save').hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}
		},
		gridComplete: function(){
			empty_formCDT();
			$('#but_cancel_jq,#but_post_jq').hide();
				if (oper == 'add' || oper == null) {
					$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				}
				$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();

				/*adj = $("#adjustment option:selected" ).val();

					if (adj == "CA" && adj == "DA") {
						$("#jqGridplus").hide();
					} else {
						$("#jqGridplus").show();
					}

					if(oper == 'edit'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					adj = $("#adjustment option:selected" ).val();

					if (adj == "CA" && adj == "DA") {
						$("#jqGridplus2").hide();
					} else {
						$("#jqGridplus2").show();
					}	*/
		},
			
	});
	
	$('#adjustment').on('change', function() {
		let adjustment1  = $("#adjustment option:selected" ).val();
		$("#jqGridplus").show();
		urlParam.filterCol = ['trantype'];
		urlParam.filterVal = [$('#adjustment').val()];
		urlParam2.filterVal[4] = $('#adjustment').val();
		refreshGrid('#jqGrid',urlParam);
	})

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");	

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id:"jqGridplus2", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id:"jqGridplus", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$( "#dialogForm" ).dialog( "open" );
			let adjustment1  = $("#adjustment option:selected" ).val();
			$('#recstatus').text("");
			if(adjustment1 == 'CA') {
				$( "#dialogForm" ).dialog( "option", "title", "Credit Transaction" );
				$("#formdata :input[name='source']").val("CM");
				$("#formdata :input[name='trantype']").val("CA");
			}else if(adjustment1 == 'DA') {
				$( "#dialogForm" ).dialog( "option", "title", "Debit Transaction" );
				$("#formdata :input[name='source']").val("CM");
			    $("#formdata :input[name='trantype']").val("DA");
			}
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	
	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	// populateSelect('#jqGrid','#searchForm');
	//searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

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

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq").click(function(){
		saveParam.oper = $(this).data('oper');
		let obj={
			auditno:selrowData('#jqGrid').auditno,
			_token:$('#_token').val(),
		};
		$.post("creditDebitTrans/form?" + $.param(saveParam),obj,function (data) { 
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj,needrefresh){
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
				
				$('#auditno').val(data.auditno);
				$('#idno').val(data.idno);
				$('#pvno').val(data.pvno);
				$('#amount').val(data.amount);//just save idno for edit later
				
				urlParam2.filterVal[1]=data.auditno;
			}else if(selfoper=='edit'){
				urlParam2.filterVal[1]=$('#auditno').val();
				//doesnt need to do anything
			}
			disableForm('#formdata');

			if(needrefresh === 'refreshGrid'){
				refreshGrid("#jqGrid", urlParam);
			}
			
		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});
	
	////////////////////////////populate data for dropdown search By////////////////////////////
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
			searchClick2('#jqGrid','#searchForm',urlParam);
		});
	}

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:['apactdtl.compcode','apactdtl.source','apactdtl.trantype','apactdtl.auditno','apactdtl.lineno_','apactdtl.deptcode','apactdtl.category','apactdtl.document', 'apactdtl.AmtB4GST', 'apactdtl.GSTCode', 'apactdtl.amount', 'apactdtl.dorecno', 'apactdtl.grnno'],
		table_name:['finance.apactdtl AS apactdtl'],
		table_id:'lineno_',
		filterCol:['apactdtl.compcode','apactdtl.auditno', 'apactdtl.recstatus','apactdtl.source','apactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'CM', $('#adjustment').val()]
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "/creditDebitTransDetail/form",
		colModel: [
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true}, //canSearch: true, checked: true},
			{ label: 'Department', name: 'deptcode', width: 100, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{  
						custom_element:deptcodeCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Category', name: 'category', width: 100, edittype:'text', classes: 'wrap', editable: true,
						editrules:{required: true,custom:true, custom_func:cust_rules},
						formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:categoryCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Document', name: 'document', width: 100, classes: 'wrap', editable: true,
						//editrules:{required: true},
						edittype:"text",
			},
			{ label: 'GST Code', name: 'GSTCode', width: 100, edittype:'text', classes: 'wrap', editable: true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
					formatter: showdetail,
					edittype:'custom',	editoptions:
						{
							custom_element:GSTCodeCustomEdit,
						    custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 80, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					//readonly: "readonly",
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
			
			{ label: 'Total GST Amount', name: 'tot_gst', width: 80, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules:{required: true},
				editoptions:{
					readonly: "readonly",
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
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Amount', name: 'amount', width: 80, classes: 'wrap', 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					readonly: "readonly",
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
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			setjqgridHeight(data,'jqGrid2');
			
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		gridComplete: function(){
		
			fdl.set_array().reset();
			
		},
		beforeSubmit: function(postdata, rowid){ 
			dialog_deptcode.check(errorField);
			dialog_category.check(errorField);
			dialog_GSTCode.check(errorField);
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	
	var myEditOptions = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {

        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_deptcode.on();//start binding event on jqgrid2
			dialog_category.on();
			dialog_GSTCode.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='AmtB4GST']"]);
			
			$("input[name='gstpercent']").val('0')//reset gst to 0
			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("#jqGrid2 input[name='amount'], #jqGrid2 input[name='AmtB4GST']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

        	$("input[name='amount']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
        	$('#amount').val(response.responseText);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
	    	errorField.length=0;
        }, 
        errorfunc: function(rowid,response){
        	alert(response.responseText);
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
        beforeSaveRow: function(options, rowid) {

        	if(errorField.length>0)return false;

			mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "/creditDebitTransDetail/form?"+
				$.param({
					action: 'creditDebitTransDetail_save',
					auditno:$('#auditno').val(),
					trantype:$('#trantype').val(),
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
				    			action: 'creditDebitTransDetail_save',
								auditno: $('#auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
								trantype: $('#trantype').val(),

				    		}
				    		$.post( "/creditDebitTransDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#amount').val(data);
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

		        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst"]);

		        cari_gstpercent(ids[i]);
		    }
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
		    		'lineno_' : ids[i],
		    		'idno' : data.idno,
		    		'deptcode' : $("#jqGrid2 input#"+ids[i]+"_deptcode").val(),
		    		'category' : $("#jqGrid2 input#"+ids[i]+"_category").val(),
		    		'document' : $('#'+ids[i]+"_document").val(),
		    		'GSTCode' : $("#jqGrid2 input#"+ids[i]+"_GSTCode").val(),
		    		'AmtB4GST' : $('#'+ids[i]+"_AmtB4GST").val(),
		    		'tot_gst' : $('#'+ids[i]+"_tot_gst").val(),
		    		'amount' : ('#'+ids[i]+"_amount").val(),
                    'unit' : $("#"+ids[i]+"_unit").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'creditDebitTransDetail_save',
				_token: $("#_token").val(),
				auditno: $('#auditno').val(),
    			trantype: $('#trantype').val(),
    		}

    		$.post( "/creditDebitTransDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				// $('#amount').val(data);
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

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
			case 'category':field=['catcode','description'];table="material.category";break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('creditDebitTrans',options,param,case_,cellvalue);
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Department':temp=$('#deptcode');break;
			case 'Category':temp=$('#category');break;
			case 'GST Code':temp=$('#GSTCode');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function deptcodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}

	function categoryCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="category" name="category" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}
	function GSTCodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	

		var id_optid = opt.id.substring(0,opt.id.search("_"));
		return $(`<div class="input-group">
			<input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="GSTCode" type="text" class="form-control input-sm" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
		</div>
		<span class="help-block"></span>
		<div class="input-group">
			<input id="`+id_optid+`_gstpercent" name="gstpercent" type="hidden">
		</div>`);
	}


	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function () {
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		dialog_bankcode.off();
		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		} else {
			mycurrency.formatOn();
			dialog_bankcode.on();
		}
	});


	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_bankcode.on();

		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});

	///////////////////////////////////////////////////////////////////////////////

	function onall_editfunc(){
		if($('#auditno').val()!=''){
    		$("#jqGrid2 input[name='deptcode'],#jqGrid2 input[name='category'],#jqGrid2 input[name='document'],#jqGrid2 input[name='AmtB4GST'],#jqGrid2 input[name='GSTCode'],#jqGrid2 input[name='amount']").attr('readonly','readonly');

		}else{
			dialog_deptcode.on();//start binding event on jqgrid2
			dialog_category.on();
			dialog_GSTCode.on();

		}
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid2 input[name='amount'], #jqGrid2 input[name='AmtB4GST']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

		//$("#jqGrid2 input[name='qtydelivered'],#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='expdate'],#jqGrid2 input[name='batchno']").on('focus',updwnkey_func);
	}

	/////////////bind shift + f to btm detail///////////
	$(document).bind('keypress', function(event) {
	    if( event.which === 70 && event.altKey ) {
	        $("#saveDetailLabel").click();
	    }
	});

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	
	function cari_gstpercent(id){
		let data = $('#jqGrid2').jqGrid ('getRowData', id);
		$("#jqGrid2 #"+id+"_gstpercent").val(data.rate);
	}

	var mycurrency2 =new currencymode([]);
	function calculate_line_totgst_and_totamt(event){

        mycurrency2.formatOff();
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let amntb4gst = parseFloat($("#"+id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_gstpercent").val());
		
		var tot_gst = amntb4gst * (gstpercent / 100);
		var amount = amntb4gst + tot_gst;

		$("#"+id_optid+"_tot_gst").val(tot_gst);

		$("#jqGrid2 #"+id_optid+"_amount").val(amount)

		
		event.data.currency.formatOn();//change format to currency on each calculation

	}


	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(data){

			setjqgridHeight(data,'jqGrid3');
		},
		gridComplete: function(){
			
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGrid3");


	////////////////////object for dialog handler/////////////////

	var dialog_bankcode = new ordialog(
		'bankcode','finance.bank','#bankcode',errorField,
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
				$('#cheqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cheqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcode.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcode.urlParam.filterVal=['session.compcode','A']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcode.makedialog(true);

	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department',"#jqGrid2 input[name='deptcode']",errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Department Code",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['compcode','recstatus'],
				dialog_deptcode.urlParam.filterVal=['session.compcode','A']
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(true);

	var dialog_category = new ordialog(
		'category','material.category',"#jqGrid2 input[name='category']",errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','source', 'cattype', 'recstatus'],
				filterVal:['session.compcode','CR', 'Other', 'A']
			},
			ondblClickRow: function () {
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Category",
			open: function(){
				dialog_category.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
				dialog_category.urlParam.filterVal=['session.compcode','CR', 'Other', 'A']
			}
		},'urlParam','radio','tab'
	);
	dialog_category.makedialog(true);

	var dialog_GSTCode = new ordialog(
		'GSTCode',['hisdb.taxmast'],"#jqGrid2 input[name='GSTCode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
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
				console.log(optid)
				let data=selrowData('#'+dialog_GSTCode.gridname);

				$("#jqGrid2 #"+id_optid+"_gstpercent").val(data['rate']);
				$(dialog_GSTCode.textfield).closest('td').next().has("input[type=text]").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_GSTCode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_GSTCode.urlParam.filterVal=['session.compcode','A', 'Input'];
			},
			close: function(){
				if($('#jqGridPager2SaveAll').css("display") == "none"){
					$(dialog_GSTCode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
				}
				
			}
		},'urlParam','radio','tab'
	);
	dialog_GSTCode.makedialog(false);

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});
	
	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}
});

function populate_formCDT(obj){

	//panel header
	$('#bankcode_show').text(obj.bankcode);
	$('#refsource_show').text(obj.refsource);	
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").show();
	
}

function empty_formCDT(){

	$('#bankcode_show').text('');
	$('#refsource_show').text('');
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").hide();
	// $("#cancel_ti, #cancel_ad, #cancel_tpa").click();

	// disableForm('#formCDT');
	// emptyFormdata(errorField_MMA,'#formCDT')

}