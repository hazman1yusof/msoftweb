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
	var mycurrency =new currencymode(['#minlimit','#maxlimit']);

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
		url:'/util/get_table_default',
		field:'',
		//fixPost:'true',
		table_name:'material.authdtl',
		table_id:'idno',
		filterCol:['compcode','cando'],
		filterVal:['session.compcode','A']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
           	{ label: 'Author ID', name: 'authorid', width: 200, classes: 'wrap', hidden:false},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'Trantype', name: 'trantype', width: 200, classes: 'wrap', canSearch: true, editable: true,
				 editable: true,
                     edittype: "select",
                     editoptions: {
                         value: "PR:Purchase Request;PO:Purchase Order"
                     }
			},
			{ label: 'Deptcode', name: 'deptcode', width: 200, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ custom_element:deptcodedtlCustomEdit,
					custom_value:galGridCustomValue },
			},
			{ label: 'Record Status', name: 'recstatus', width: 150, classes: 'wrap', canSearch: true, editable: true,
				 editable: true,
                     edittype: "select",
                     editoptions: {
                         value: "Request:Request;Support:Support;Verify:Verify;Approve:Approve"
                     }
			},
			{ label: 'CanDo', name: 'cando', width: 150, classes: 'wrap', canSearch: true, editable: true,
				 editable: true,
                     edittype: "select",
                     editoptions: {
                         value: "A:Active;D:Deactive"
                     }
			},
		
			{ label: 'Min Limit', name: 'minlimit', width: 200, classes: 'wrap',  align: 'right', editable: true,
				edittype:"text",
			},
			{ label: 'Max Limit', name: 'maxlimit', width: 200, classes: 'wrap', align: 'right',formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }
			},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		sortname: 'idno',
		sortorder: 'desc',
		loadonce:false,
		height: 124,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){

			/*var jg=$("#jqGrid").jqGrid('getRowData',rowid);
			if(rowid != null) {
				populateSummary('#details',selrowData('#jqGrid').s_itemcode, selrowData('#jqGrid').s_uomcode, selrowData('#jqGrid').s_deptcode);
			}
*/
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

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Deptcode':temp=$('#deptcode');break;
		
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input///////////////////////////////////////////

	function deptcodedtlCustomEdit(val,opt){
	val = (val=="undefined")? "" : val;	
	return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

   /*  $("#jqGrid").jqGrid('setLabel', 'qtyonhand', 'Quantity on Hand', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'avgcost', 'Average Cost', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'currprice', 'Current Price', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'minqty', 'Min Stock Qty', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'maxqty', 'Max Stock Qty', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'reordqty', 'Reorder Qty', {'text-align':'right'});
*/

	///////////////////utk dropdown search By/////////////////////////////////////////////////
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

	$('#searchText').keyup(function() {
		delay(function(){
			searchMain($('#searchText').val(),$('#Scol').val());
		}, 500 );
	});

	$('#Scol').change(function(){
		searchMain($('#searchText').val(),$('#Scol').val());
	});

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


	
	addParamField('#jqGrid',true,urlParam);
});
