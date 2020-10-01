
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']","input[name='si_lastcomputerid']","input[name='si_lastipaddress']","input[name='si_computerid']","input[name='si_ipaddress']","input[name='sb_lastcomputerid']","input[name='sb_lastipaddress']","input[name='sb_computerid']","input[name='sb_ipaddress']");
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

	var fdl = new faster_detail_load();

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.bank',
		table_id:'bankcode',
		filterCol:['recstatus'],
		filterVal:['ACTIVE'],
		sort_idno:true,
	}
	
	//////////////////////////start grid/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/cheqlist/form",
		 colModel: [
			{ label: 'Bank Code', name: 'bankcode', width: 5,canSearch:true,checked:true},
			{ label: 'Bank Name', name: 'bankname', width: 8, canSearch:true},
			{ label: 'Address', name: 'address1', width: 17, classes: 'wrap', formatter:formatterAddress, unformat: unformatAddress},
			{ label: 'address2', name: 'address2', width: 17, classes: 'wrap', hidden:true},
			{ label: 'address3', name: 'address3', width: 17, classes: 'wrap',  hidden:true},
			{ label: 'postcode', name: 'postcode', width: 17, classes: 'wrap',  hidden:true},
			{ label: 'statecode', name: 'statecode', width: 17, classes: 'wrap',  hidden:true},
			{ label: 'Tel No', name: 'telno', width: 5},	
			{ label: 'idno', name: 'idno', hidden: true},
		],
		autowidth:true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 250,
		//rowNum: 30,
		pager: "#jqGridPager",
		gridComplete: function () {
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			fdl.set_array().reset();
			empty_form();

		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				urlParam_cheqlistdtl.filterVal[0]=selrowData("#jqGrid").bankcode;
				populate_form(selrowData("#jqGrid"));
				refreshGrid('#gridCheqListDetail',urlParam_cheqlistdtl);
				$("#pg_jqGridPager2 table").show();
			}
		},

		
	});

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',
		{	
			edit:false,view:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam);
			},
			
		}	
	);


	/////////////////formatter address///////////////////////////
	function formatterAddress (cellvalue, options, rowObject){
		add1 = rowObject.address1;
		add2 = rowObject.address2;
		add3 = rowObject.address3;
		postcode = rowObject.postcode;
		state = rowObject.statecode;
		fulladdress =  add1 + '</br> ' + add2 + '</br> ' + add3 + ' </br>' + postcode + ' </br>' + state;
		return fulladdress;
	}

	function unformatAddress (cellvalue, options, rowObject){
		return null;
	}



	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);

	////////////////////////////cheq register detail/////////////////////////////////////////////////
	
	var urlParam_cheqlistdtl={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.chqtran',
		table_id:'idno',
		filterCol:['bankcode'],
		filterVal:[$("#bankcode").val()],
		sort_idno: true,
	}
	
	$("#gridCheqListDetail").jqGrid({
		datatype: "local",
		editurl: "/cheqlistDetail/form",
		colModel: [
			{ label: 'Comp Code', name: 'compcode', width: 50, hidden:true},	
			{ label: 'Bank Code', name: 'bankcode', width: 30, hidden: true,},
			{ label: 'Cheque No', name: 'cheqno', width: 20, classes: 'wrap',canSearch:true,checked:true},
			{ label: 'Remarks', name: 'remarks', width: 30, hidden:true},
			{ label: 'Pay To', name: 'payto', width: 30, hidden:false, canSearch:true},
			{ label: 'Status', name: 'recstatus', width: 30, hidden:false, canSearch:true},
			{ label: 'idno', name: 'idno', hidden: true,editable: true, key:true},
			 
		],
		autowidth:true,
		viewrecords: true,
        multiSort: true,
		loadonce: false,
		rownumbers: true,
		sortname: 'idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		caption: caption_cheqlist('searchForm2','Search By'),
		pager: "#jqGridPager2",
		loadComplete: function(){
			$("#gridCheqListDetail").setSelection($("#gridCheqListDetail").getDataIDs()[0]);
		},
		gridComplete: function () {
			fdl.set_array().reset();

			$("#searchForm2 input[type='radio'][name='dcolr']").off();
			$("#searchForm2 input[type='radio'][name='dcolr']").on('change',function(){
				if($(this).val() == "recstatus"){
					$("#searchForm2 input[type='text'][name='Stext']").hide();
					$("#searchForm2 #Ssel").show();
				}else{
					$("#searchForm2 input[type='text'][name='Stext']").show();
					$("#searchForm2 #Ssel").hide();
				}
			});
		},

	});

	function caption_cheqlist(form,caption){
		if(caption==undefined)caption='Search';
		return `<form id='`+form+`'>
					<div class='input-group input-group-sm pull-left'>
						<span class='input-group-addon'>
							<strong>`+caption+` :</strong>
						</span>
							<input type='text' name='Stext' class='form-control' placeholder='Search here...'>
							<select id="Ssel" class='form-control' style="display:none">
								<option value="">--Select Status--</option>
							    <option value="OPEN">OPEN</option>
							    <option value="ISSUED">ISSUED</option>
							    <option value="CANCELLED">CANCELLED</option>
							</select>
							<span class='input-group-addon' name='Scol'>
							</span>
					</div>
				</form>
				<div class='clearfix'></div>`;
	}


	function searchClick_cheqlist(grid,form,urlParam){
		$(form+' #Ssel').on( "change", function() {
			delay(function(){
				search(grid,$(form+' #Ssel').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
			}, 500 );
		});

		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			$(form+' #Ssel').val("")
			search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
		});
	}
	
	//////////add field into param, refresh grid if needed////////////////////////////////////////////////

	populateSelect('#gridCheqListDetail','#searchForm2');
	searchClick_cheqlist('#gridCheqListDetail','#searchForm2',urlParam_cheqlistdtl);

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#gridCheqListDetail").jqGrid ('setGridWidth', Math.floor($("#gridCheqListDetail_c")[0].offsetWidth-$("#gridCheqListDetail_c")[0].offsetLeft-28));
		refreshGrid('#gridCheqListDetail',urlParam_cheqlistdtl);
	});

	function populate_form(obj){

		//panel header
		$('#bankname').text(obj.bankname);	
	}

	function empty_form(){

		$('#bankname_show').text('');
		

	}
	
});