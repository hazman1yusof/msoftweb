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
			
		/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
		var urlParam={
			action:'get_table_default',
			url: '/util/get_table_default',				
			field:'',
			table_name:'finance.fatype',
			//table_id:'assettype', 
			table_id:'idno',
		}
	
		/////////////////////parameter for saving url////////////////////////////////////////////////
		var addmore_jqgrid={more:false,state:false,edit:false}	
		$("#jqGrid").jqGrid({
			datatype: "local",
			editurl: '/assettype/form',
			 colModel: [
				{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
				{ label: 'compcode', name: 'compcode', width: 40, hidden:true},						
				{ label: 'Asset Type', name: 'assettype', width: 15, canSearch: true, checked: true, editable: true, 
						editrules: { required: true }, 
						editoptions: {style: "text-transform: uppercase", maxlength:4 }
					},
				{ label: 'Description', name: 'description', width: 15, canSearch: true, checked: true, editable: true, 
						editrules: { required: true }, 
						editoptions: {style: "text-transform: uppercase", maxlength:100 }
					},
					{ label: 'Record Status', name: 'recstatus', width: 30, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
					editoptions:{
						value:"A:ACTIVE;D:DEACTIVE"
					}},
					
				{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			    { label: 'adddate', name: 'adddate', width: 90, hidden: true },
			    { label: 'upduser', name: 'upduser', width: 90, hidden: true },
			    { label: 'upddate', name: 'upddate', width: 90, hidden: true },
			    { label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			    { label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
			],
			autowidth:true,
    	    multiSort: true,
			viewrecords: true,
			loadonce:false,
			sortname: 'idno',
			sortorder:'desc',
			width: 900,
			height: 350,
			rowNum: 30,
			pager: "#jqGridPager",
			loadComplete: function(){
				if(addmore_jqgrid.more == true){$('#jqGrid2_iladd').click();}
				else{
					$('#jqGrid2').jqGrid ('setSelection', "1");
				}
				addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			},
			ondblClickRow: function (rowid, iRow, iCol, e) {
				$("#jqGrid_iledit").click();
		    },
	    });

		var myEditOptions = {
			keys: true,
			extraparam:{
				"_token": $("#_token").val()
			},
			oneditfunc: function (rowid) {
				$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
				$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid_ilsave').click();
					/*addmore_jqgrid.state = true;
					$('#jqGrid_ilsave').click();*/
				});
	
			},

			aftersavefunc: function (rowid, response, options) {
				if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
				//state true maksudnyer ada isi, tak kosong
				refreshGrid('#jqGrid',urlParam,'add');
				errorField.length=0;
				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
			},
			errorfunc: function(rowid,response){
				alert(response.responseText);
				refreshGrid('#jqGrid',urlParam,'add');
			},
			beforeSaveRow: function (options, rowid) {
				if(errorField.length>0)return false;
	
				let data = $('#jqGrid').jqGrid ('getRowData', rowid);
				console.log(data);
	
				let editurl = "/assettype/form?"+
					$.param({
						action: 'assettype_save',
					});
				$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
			},
			afterrestorefunc : function( response ) {
				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
			},
			errorTextFormat: function (data) {
				alert(data);
			}
		};

		var myEditOptions_edit = {
			keys: true,
			extraparam:{
				"_token": $("#_token").val()
			},
			oneditfunc: function (rowid) {
				$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
				$("input[name='assettype']").attr('disabled','disabled');
				$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid_ilsave').click();
					/*addmore_jqgrid.state = true;
					$('#jqGrid_ilsave').click();*/
				});
	
			},

			aftersavefunc: function (rowid, response, options) {
				if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
				//state true maksudnyer ada isi, tak kosong
				refreshGrid('#jqGrid',urlParam,'add');
				errorField.length=0;
				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
			},
			errorfunc: function(rowid,response){
				alert(response.responseText);
				refreshGrid('#jqGrid',urlParam2,'add');
			},
			beforeSaveRow: function (options, rowid) {
				console.log(errorField)
				if(errorField.length>0)return false;
	
				let data = $('#jqGrid').jqGrid ('getRowData', rowid);
				// console.log(data);
	
				let editurl = "/assettype/form?"+
					$.param({
						action: 'assettype_save',
					});
				$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
			},
			afterrestorefunc : function( response ) {
				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
			},
			errorTextFormat: function (data) {
				alert(data);
			}
		};

		$("#jqGrid").inlineNav('#jqGridPager', {
			add: true,
			edit: true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: {
				addRowParams: myEditOptions
			},
			editParams: myEditOptions_edit
		}).jqGrid('navButtonAdd', "#jqGridPager", {
			id: "jqGridPagerDelete",
			caption: "", cursor: "pointer", position: "last",
			buttonicon: "glyphicon glyphicon-trash",
			title: "Delete Selected Row",
			onClickButton: function () {
				selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
				if (!selRowId) {
					bootbox.alert('Please select row');
				} else {
					bootbox.confirm({
						message: "Are you sure you want to delete this row?",
						buttons: {
							confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
						},
						callback: function (result) {
							if (result == true) {
								param = {
									_token: $("#_token").val(),
									action: 'assettype_save',
									assettype: $('#assettype').val(),
									idno: selrowData('#jqGrid').idno,
								}
								$.post( "/assettype/form?"+$.param(param),{oper:'del'}, function( data ){
								}).fail(function (data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function (data) {
									refreshGrid("#jqGrid", urlParam);
								});
							}else{
								$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
							}
						}
					});
				}
			},
		}).jqGrid('navButtonAdd', "#jqGridPager", {
			id: "jqGridPagerRefresh",
			caption: "", cursor: "pointer", position: "last",
			buttonicon: "glyphicon glyphicon-refresh",
			title: "Refresh Table",
			onClickButton: function () {
				refreshGrid("#jqGrid", urlParam);
			},
		});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
		//toogleSearch('#sbut1', '#searchForm', 'on');
	    populateSelect2('#jqGrid', '#searchForm');
	    searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	    addParamField('#jqGrid', true, urlParam);
	    //addParamField('#jqGrid',false,saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);
 
	});
		