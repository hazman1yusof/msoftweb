
$(document).ready(function (){
	var dialog_alloinvno_spec = new ordialog(
		'Alloinvno_spec2', 'debtor.dbacthdr', '#Alloinvno_spec2', errorField,
		{
			colModel: [
				{ label: 'MRN', name: 'mrn', width: 50, classes: 'pointer', canSearch: true, or_search: true, checked: true  },
				{ label: 'Name', name: 'patname', width: 100, classes: 'pointer' , canSearch: true, or_search: true},
				{ label: 'Episno', name: 'episno', width: 30, classes: 'pointer' },
				{ label: 'Invoice', name: 'invno', width: 50, classes: 'pointer', canSearch: true},
				{ label: 'Payer', name: 'payercode', width: 50, classes: 'pointer', canSearch: true },
				{ label: 'Payer Name', name: 'payername', width: 100, classes: 'pointer' },
				{ label: 'Line', name: 'lineno_', width: 20, classes: 'pointer' },
				{ label: 'Amount', name: 'amount', width: 50, classes: 'pointer' },
				{ label: 'O/S Amount', name: 'outamount', width: 50, classes: 'pointer' },
				{ label: 'idno', name: 'idno', width: 50, classes: 'pointer', hidden: true, key:true  },
			],
			sortname:'idno',
			sortorder:'desc',
			urlParam: {
				url:"./arenquiry/table",
				action: 'get_invno',
				url_chk: "./arenquiry/table",
				action_chk: "get_invno_check",
				filterCol:[],
				filterVal:[],
			},
			ondblClickRow: function (){
				let data = selrowData('#'+dialog_alloinvno_spec.gridname);
				$('#ALLoidnoIN_spec').val(data.idno);
				$('#Alloinvno_spec2').val(data.mrn);
				$('#Alloinvno_spec').val(data.invno);
				$('#Allolineno_spec').val(data.lineno_);
				$('#AlloMRN_spec').val(data.mrn);
				$('#AlloEpisode_spec').val(data.episno);
				$('#AlloPayer_spec').val(data.payercode);
				$('#AlloPayerName_spec').val(data.payername);
				urlParamManAlloc_spec.idnoIN = data.idno;
				refreshGrid("#gridManAlloc_spec",urlParamManAlloc_spec);
				myallocation_spec.renewAllo($('#AlloOutamt_spec').val());
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#apacthdr_actdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Invoice No",
			open: function (){
				dialog_alloinvno_spec.urlParam.url = "./arenquiry/table";
				dialog_alloinvno_spec.urlParam.action = 'get_invno';
				dialog_alloinvno_spec.urlParam.url_chk = "./arenquiry/table";
				dialog_alloinvno_spec.urlParam.action_chk = "get_invno_check";
				dialog_alloinvno_spec.urlParam.filterCol=[];
				dialog_alloinvno_spec.urlParam.filterVal=[];
			},
			close:function(){
				$('div.nohassuccess').removeClass( "has-success" );
			}
		},'urlParam','radio','tab'
	);
	dialog_alloinvno_spec.makedialog(false);

	var myallocation_spec = new Allocation_spec();
	var allocurrency_spec = new currencymode(["#AlloBalance_spec","#AlloTotal_spec"]);

	$( "#allocateDialog_spec" ).dialog({
		autoOpen: false,
		width: 9/10 * $(window).width(),
		modal: true,
		open: function (){
			$('button[classes=allocateDialog_spec_save_btn]').show();
			dialog_alloinvno_spec.on();
			$("#gridManAlloc_spec").jqGrid ('setGridWidth', Math.floor($("#gridManAlloc_spec_c")[0].offsetWidth-$("#gridManAlloc_spec_c")[0].offsetLeft));
			grid = '#jqGrid';
			$('#ALLoidnoRC_spec').val(selrowData(grid).db_idno);
			$('#AlloDtype_spec').val(selrowData(grid).db_trantype);
			$('#AlloDtype2_spec').html(selrowData(grid).db_PymtDescription);
			$('#AlloDno_spec').val(selrowData(grid).db_recptno);
			$('#AlloDebtor_spec').val(selrowData(grid).db_debtorcode);
			$('#AlloDebtorName_spec').val(selrowData(grid).dm_name);
			$('#AlloAmt_spec').val(selrowData(grid).db_amount);
			$('#AlloOutamt_spec').val(selrowData(grid).db_outamount);
			$('#AlloBalance_spec').val(selrowData(grid).db_outamount);
			$('#AlloTotal_spec').val(0);
			$('#AlloAuditno_spec').val(selrowData(grid).db_auditno);
			urlParamManAlloc_spec.filterVal[0] = selrowData(grid).db_payercode;
			urlParamManAlloc_spec.idnoRC = selrowData(grid).db_idno;
			parent_close_disabled(true);
		},
		close: function (event, ui){
			$('#ALLoidnoIN_spec').val('');
			$('#Alloinvno_spec').val('');
			$('#Alloinvno_spec2').val('');
			$('#AlloMRN_spec').val('');
			$('#AlloEpisode_spec').val('');
			$('#Allolineno_spec').val('');
			$('#AlloPayer_spec').val('');
			$('#AlloPayerName_spec').val('');
			$('#AlloBalance_spec').val('');
			$('#AlloTotal_spec').val('');
			refreshGrid("#gridManAlloc_spec",null,"kosongkan");
			dialog_alloinvno_spec.off();
			parent_close_disabled(false);
		},
		buttons:
			[{
				text: "Save", click: function (){
					$('button[classes=allocateDialog_spec_save_btn],button[classes=allocateDialog_spec_save_btn]').hide();
					if(parseFloat($("#AlloBalance_spec").val()) < 0){
						alert("Balance cannot be less than 0");
					}else{
						var obj = {
							allo: myallocation_spec.arrayAllo_spec
						}
						
						var saveParam = {
							action: 'spec_allocate_form',
							url: 'arenquiry/form',
							oper: 'spec_allocate_form',
							idnoRC: $('#ALLoidnoRC_spec').val(),
							idnoIN: $('#ALLoidnoIN_spec').val(),
							debtorcode: $('#AlloDebtor_spec').val(),
							payercode: $('#AlloPayer_spec').val(),
							_token: $('#csrf_token').val(),
							auditno: $('#AlloAuditno_spec').val(),
							trantype: $('#AlloDtype_spec').val(),
						}
						
						$.post(saveParam.url+'?'+$.param(saveParam), obj, function (data){
							
						}).fail(function (data){
							alert('error');
							$('button[classes=allocateDialog_spec_save_btn]').show();
						}).success(function (data){
							$('button[classes=allocateDialog_spec_save_btn]').show();
							$('#refresh_jqGrid').click();
							$('#allocateDialog_spec').dialog('close');
						});
					}
					$('button[classes=allocateDialog_spec_save_btn],button[classes=allocateDialog_spec_save_btn]').show();
				},classes: "allocateDialog_spec_save_btn"
			},{
				text: "Cancel", click: function (){
					$(this).dialog('close');
				},classes: "allocateDialog_spec_btn"
			}],
	});

	var urlParamManAlloc_spec = {
		action: 'specific_allocate',
		url: './arenquiry/table',
		field: '',
		table_name: 'debtor.dbacthdr',
		table_id: 'idno',
		sort_idno: true,
		filterCol: ['payercode','source','recstatus','outamount'],
		filterVal: ['','PB','POSTED','>.0'],
		WhereInCol: ['trantype'],
		WhereInVal: [['IN']]
	}

	$("#gridManAlloc_spec").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true, key:true },
			{ label: 'Chg Class', name: 'chgclass', width: 20, classes: 'wrap' },
			{ label: 'Invoice Code', name: 'invcode', width: 20, classes: 'wrap' },
			{ label: 'Description', name: 'description', width: 100 },
			{ label: 'Doctor Code', name: 'doctorcode', width: 100, classes: 'wrap', formatter: showdetail},
			{ label: 'Amount', name: 'amount', formatter: 'currency', width: 40 },
			{ label: 'O/S Amount', name: 'outamt', formatter: 'currency', width: 40  },
			{ label: ' ', name: 'tick', width: 20, editable: true, edittype: "checkbox", align: 'center' },
			{ label: 'Amount Paid', name: 'amtpaid', width: 40, editable: true },
			{ label: 'Balance', name: 'amtbal', width: 40, formatter: 'currency', formatoptions: { prefix: "" } },
		],
		autowidth: true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		height: 400,
		scroll: false,
		rowNum: 100,
		pager: "#pagerManAlloc_spec",
		onSelectRow: function (rowid){
		},
		onPaging: function (button){
		},
		gridComplete: function (rowid){
			startEdit_spec();
			$("#gridManAlloc_spec_c input[type='checkbox']").off('click');
			
			$("#gridManAlloc_spec_c input[type='checkbox']").on('click', function (){
				var idno = $(this).attr("rowid");
				var rowdata = $("#gridManAlloc_spec").jqGrid ('getRowData', idno);
				if($(this).prop("checked") == true){
					if(!myallocation_spec.alloInArray(idno)){
						if(myallocation_spec.alloBalance_spec < rowdata.outamt){
							$("#"+idno+"_amtpaid").val(myallocation_spec.alloBalance_spec).addClass( "valid" ).removeClass( "error" );
							setbal_spec(idno,rowdata.outamt - myallocation_spec.alloBalance_spec);
							myallocation_spec.addAllo(idno,myallocation_spec.alloBalance_spec,0);
						}else{
							$("#"+idno+"_amtpaid").val(rowdata.outamt).addClass( "valid" ).removeClass( "error" );
							setbal_spec(idno,0);
							myallocation_spec.addAllo(idno,rowdata.outamt,0);
						}
					}else{
						$("#"+idno+"_amtpaid").trigger("change");
					}
				}else{
					$("#"+idno+"_amtpaid").val(0).addClass( "valid" ).removeClass( "error" );
					setbal_spec(idno,rowdata.outamt);
					$("#"+idno+"_amtpaid").trigger("change");
				}
			});
			// $("#gridManAlloc_spec_c input[type='text'][rowid]").off('click');

			// $("#gridManAlloc_spec_c input[type='text'][rowid]").on('click', function (){
			// 	var idno = $(this).attr("rowid");
			// 	if(!myallocation_spec.alloInArray(idno)){
			// 		myallocation_spec.addAllo(idno,' ',0);
			// 	}
			// });
			
			delay(function (){
				// $("#alloText_spec").focus(); // AlloTotal_spec
				myallocation_spec.retickallotogrid();
			}, 100);
			
			calc_jq_height_onchange("gridManAlloc_spec");
			fdl.set_array().reset();

			$("#gridManAlloc_spec").setSelection($("#gridManAlloc_spec").getDataIDs()[0]).focus();
		},
	});

	AlloSearch_spec("#gridManAlloc_spec",urlParamManAlloc_spec);
	function AlloSearch_spec(grid,urlParam){
		$("#alloText_spec").on("keyup", function (){
			delay(function (){
				search(grid,$("#alloText_spec").val(),$("#alloCol_spec").val(),urlParam);
			}, 500);
		});
		
		$("#alloCol_spec").on("change", function (){
			search(grid,$("#alloText_spec").val(),$("#alloCol_spec").val(),urlParam);
		});
	}

	function startEdit_spec(){
		var ids = $("#gridManAlloc_spec").jqGrid('getDataIDs');
		
		for(var i = 0; i < ids.length; i++){
			var entrydate = $("#gridManAlloc_spec").jqGrid('getRowData', ids[i]).entrydate;
			$("#gridManAlloc_spec").jqGrid('setCell', ids[i], 'NULL', moment(entrydate).format("DD-MMM"));
			$("#gridManAlloc_spec").jqGrid('editRow', ids[i]);
		}
	};

	addParamField('#gridManAlloc_spec',false,urlParamManAlloc_spec,['tick','amtpaid','amtbal']);

	function Allocation_spec(){
		this.arrayAllo_spec = [];
		this.alloBalance_spec = 0;
		this.alloTotal_spec = 0;
		this.outamt_spec = 0;
		this.allo_error_spec = [];
		
		this.renewAllo = function (os){
			this.arrayAllo_spec.length = 0;
			this.alloTotal_spec = 0;
			this.alloBalance_spec = parseFloat(os);
			this.outamt_spec = parseFloat(os);
			
			this.updateAlloField();
		}
		this.addAllo = function (idno,paid,bal){
			var obj = getlAlloFromGrid_spec(idno);
			obj.amtpaid = paid;
			obj.amtbal = bal;
			var fieldID = "#"+idno+"_amtpaid";
			var self = this;
			
			this.arrayAllo_spec.push({idno:idno,obj:obj});
			
			$(fieldID).on('change',[idno,self.arrayAllo_spec,self.allo_error_spec],onchangeField_spec);
			
			this.updateAlloField();
		}
		function onchangeField_spec(obj){
			var idno = obj.handleObj.data[0];
			var arrayAllo_spec = obj.handleObj.data[1];
			var allo_error_spec = obj.handleObj.data[2];
			
			var alloIndex = getIndex(arrayAllo_spec,idno);
			var outamt_spec = $("#gridManAlloc_spec").jqGrid('getRowData', idno).outamt;
			var newamtpaid = parseFloat(obj.target.value);
			newamtpaid = isNaN(Number(newamtpaid)) ? 0 : parseFloat(obj.target.value);
			if(parseFloat(newamtpaid) > parseFloat(outamt_spec)){
				alert("Amount paid exceed O/S amount");
				$("#"+idno+"_amtpaid").addClass( "error" ).removeClass( "valid" );
				adderror_allo_spec(allo_error_spec,idno);
				obj.target.focus();
				return false;
			}
			$("#"+idno+"_amtpaid").removeClass( "error" ).addClass( "valid" );
			delerror_allo_spec(allo_error_spec,idno);
			var balance = outamt_spec - newamtpaid;
			
			obj.target.value = numeral(newamtpaid).format('0,0.00');;
			arrayAllo_spec[alloIndex].obj.amtpaid = newamtpaid;
			arrayAllo_spec[alloIndex].obj.amtbal = balance;
			setbal_spec(idno,balance);
			
			myallocation_spec.updateAlloField();
		}
		function getIndex(array,idno){
			var retval = 0;
			$.each(array, function (index, obj){
				if(obj.idno == idno){
					retval = index;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.deleteAllo = function (idno){
			var self = this;
			$.each(self.arrayAllo_spec, function (index, obj){
				if(obj.idno == idno){
					self.arrayAllo_spec.splice(index, 1);
					return false;
				}
			});
		}
		this.alloInArray = function (idno){
			var retval = false;
			$.each(this.arrayAllo_spec, function (index, obj){
				if(obj.idno == idno){
					retval = true;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.retickallotogrid = function (){
			var self = this;
			$.each(this.arrayAllo_spec, function (index, obj){
				$("#"+obj.idno+"_amtpaid").on('change',[obj.idno,self.arrayAllo_spec],onchangeField_spec);
				if(obj.obj.amtpaid != " "){
					$("#"+obj.idno+"_amtpaid").val(obj.obj.amtpaid).removeClass( "error" ).addClass( "valid" );
					setbal_spec(obj.idno,obj.obj.amtbal);
				}
			});
		}
		this.updateAlloField = function (){
			var self = this;
			this.alloTotal_spec = 0;
			console.log(this.arrayAllo_spec);
			$.each(this.arrayAllo_spec, function (index, obj){
				if(obj.obj.amtpaid != " "){
					self.alloTotal_spec += parseFloat(obj.obj.amtpaid);
				}
			});
			this.alloBalance_spec = this.outamt_spec - this.alloTotal_spec;
			
			$("#AlloTotal_spec").val(this.alloTotal_spec);
			$("#AlloBalance_spec").val(this.alloBalance_spec);
			if(this.alloBalance_spec < 0){
				$("#AlloBalance_spec").addClass( "error" ).removeClass( "valid" );
				// alert("Balance cannot in negative values");
			}else{
				$("#AlloBalance_spec").addClass( "valid" ).removeClass( "error" );
			}
			allocurrency_spec.formatOn();
		}
		function updateAllo(idno,amtpaid,arrayAllo_spec){
			$.each(arrayAllo_spec, function (index, obj){
				if(obj.idno == idno){
					obj.obj.amtpaid = amtpaid;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
		}
		function getlAlloFromGrid_spec(idno){
			var temp = $("#gridManAlloc_spec").jqGrid('getRowData', idno);
			return {idno:temp.idno,auditno:temp.auditno,amtbal:temp.amtbal,amtpaid:temp.amount};
		}
		function adderror_allo_spec(array,idno){
			if($.inArray(idno,array) === -1){ // xjumpa
				array.push(idno);
			}
		}
		function delerror_allo_spec(array,idno){
			if($.inArray(idno,array) !== -1){ // jumpa
				array.splice($.inArray(idno,array), 1);
			}
		}
	}

	function setbal_spec(idno,balance){
		console.log(balance)
		$("#gridManAlloc_spec").jqGrid('setCell', idno, 'amtbal', balance);
	}

	$("#gridManAlloc_spec").jqGrid('navGrid', '#pagerManAlloc_spec', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function (){
			refreshGrid("#gridManAlloc_spec",urlParamManAlloc_spec);
		},
	})

	$('#allocate_spec').click(function (){
		$( "#allocateDialog_spec" ).dialog( "open" );
	});

});