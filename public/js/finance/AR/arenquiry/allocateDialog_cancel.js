
$(document).ready(function (){
	var dialog_allodebtor_cancel = new ordialog(
		'AlloDebtor_cancel', 'debtor.debtormast', '#AlloDebtor_cancel', errorField,
		{
			colModel: [
				{ label: 'Code', name: 'debtorcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				let data = selrowData('#'+dialog_allodebtor_cancel.gridname);
				$('#AlloDebtor_cancel').val(data.debtorcode);
				myallocation_cancel.renewAllo($('#AlloOutamt_cancel').val());
				urlParamManAlloc_cancel.filterVal[0] = data.debtorcode;
				refreshGrid("#gridManAlloc_cancel",urlParamManAlloc_cancel);
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
			title: "Select MRN",
			open: function (){
				dialog_allodebtor_cancel.urlParam.filterCol = ['compcode','recstatus'],
				dialog_allodebtor_cancel.urlParam.filterVal = ['session.compcode','ACTIVE']
			},
			close:function(){
			}
		},'urlParam','radio','tab'
	);
	dialog_allodebtor_cancel.makedialog(false);

	var myallocation_cancel = new Allocation_cancel();
	var allocurrency_cancel = new currencymode(["#AlloBalance_cancel","#AlloTotal_cancel"]);

	$( "#allocateDialog_cancel" ).dialog({
		autoOpen: false,
		width: 9/10 * $(window).width(),
		modal: true,
		open: function (){
			$('button[classes=allocateDialog_cancel_save_btn]').show();
			// dialog_allodebtor_cancel.off();
			$("#gridManAlloc_cancel").jqGrid ('setGridWidth', Math.floor($("#gridManAlloc_cancel_c")[0].offsetWidth-$("#gridManAlloc_cancel_c")[0].offsetLeft));
			grid = '#jqGrid';
			$('#ALLoidno_cancel').val(selrowData(grid).db_idno);
			$('#AlloDtype_cancel').val(selrowData(grid).db_trantype);
			$('#AlloDtype2_cancel').html(selrowData(grid).db_PymtDescription);
			$('#AlloDno_cancel').val(selrowData(grid).db_recptno);
			$('#AlloDebtor_cancel').val(selrowData(grid).db_payercode);
			$('#AlloDebtor2_cancel').html(selrowData(grid).db_payername);
			$('#AlloPayer_cancel').val(selrowData(grid).db_payercode);
			$('#AlloPayer2_cancel').html(selrowData(grid).db_payername);
			$('#AlloAmt_cancel').val(selrowData(grid).db_amount);
			$('#AlloOutamt_cancel').val(selrowData(grid).db_outamount);
			$('#AlloBalance_cancel').val(selrowData(grid).db_outamount);
			$('#AlloTotal_cancel').val(0);
			$('#AlloAuditno_cancel').val(selrowData(grid).db_auditno);
			urlParamManAlloc_cancel.filterVal[0] = selrowData(grid).db_payercode;
			urlParamManAlloc_cancel.idno = selrowData(grid).db_idno;
			refreshGrid("#gridManAlloc_cancel",urlParamManAlloc_cancel);
			parent_close_disabled(true);
			myallocation_cancel.renewAllo(selrowData(grid).db_outamount);
		},
		close: function (event, ui){
			// dialog_allodebtor_cancel.off();
			parent_close_disabled(false);
		},
		buttons:
			[{
				text: "Save", click: function (){
					$('button[classes=allocateDialog_cancel_save_btn],button[classes=allocateDialog_cancel_save_btn]').hide();
					if(parseFloat($("#AlloBalance_cancel").val()) == 0){
						alert("Balance cannot zero");
					}else{
						var obj = {
							allo: myallocation_cancel.arrayAllo_cancel
						}
						
						var saveParam = {
							action: 'cancel_allocate_form',
							url: 'arenquiry/form',
							oper: 'cancel_allocate_form',
							idno: $('#ALLoidno_cancel').val(),
							debtorcode: $('#AlloDebtor_cancel').val(),
							payercode: $('#AlloPayer_cancel').val(),
							_token: $('#csrf_token').val(),
							auditno: $('#AlloAuditno_cancel').val(),
							trantype: $('#AlloDtype_cancel').val(),
						}
						
						$.post(saveParam.url+'?'+$.param(saveParam), obj, function (data){
							
						}).fail(function (data){
							alert('error');
							// $('button[classes=allocateDialog_cancel_save_btn]').show();
						}).success(function (data){
							// $('button[classes=allocateDialog_cancel_save_btn]').show();
							$('#refresh_jqGrid').click();
							$('#allocateDialog_cancel').dialog('close');
						});
					}
				},classes: "allocateDialog_cancel_save_btn"
			},{
				text: "Cancel", click: function (){
					$(this).dialog('close');
				},classes: "allocateDialog_cancel_btn"
			}],
	});

	var urlParamManAlloc_cancel = {
		action: 'cancel_allocate',
		url: './arenquiry/table',
		field: '',
		table_name: 'debtor.dbacthdr',
		table_id: 'idno',
		sort_idno: true,
		filterCol: ['payercode','source','recstatus','outamount'],
		filterVal: ['','PB','POSTED','>.0'],
		WhereInCol: ['trantype'],
		WhereInVal: [['DN','IN']]
	}

	$("#gridManAlloc_cancel").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true, key:true },
			{ label: 'System Auto No.', name: 'sysAutoNo', width: 50, classes: 'wrap' },
			{ label: 'Document No', name: 'auditno', width: 40 },
			{ label: 'MRN', name: 'mrn', width: 50 },
			{ label: 'EpisNo', name: 'episno', width: 50 },
			{ label: 'Src', name: 'source', width: 20, hidden: true },
			{ label: 'Type', name: 'trantype', width: 20, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 20, hidden: true },
			// { label: 'Batchno', name: 'NULL', width: 40 },
			{ label: 'Amount', name: 'amount', formatter: 'currency', width: 50 },
			{ label: 'Document No', name: 'recptno', width: 50, align: 'right' },
			{ label: 'Debtor', name: 'debtorcode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Alloc Date', name: 'allocdate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Paymode', name: 'paymode', width: 50, classes: 'wrap text-uppercase' },
			{ label: 'O/S Amount', name: 'outamount', formatter: 'currency', width: 50, hidden: true  },
			{ label: ' ', name: 'tick', width: 20, editable: true, edittype: "checkbox", align: 'center' },
			{ label: 'Amount Paid', name: 'amtpaid', width: 50, hidden: true},
			{ label: 'Balance', name: 'amtbal', width: 50, formatter: 'currency', formatoptions: { prefix: "" }, hidden: true },
		],
		autowidth: true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		height: 400,
		scroll: false,
		rowNum: 100,
		pager: "#pagerManAlloc_cancel",
		onSelectRow: function (rowid){
		},
		onPaging: function (button){
		},
		gridComplete: function (rowid){
			startEdit_cancel();
			$("#gridManAlloc_cancel_c input[type='checkbox']").off('click');
			$("#gridAlloc_c input[type='text'][rowid]").off('click');
			
			$("#gridManAlloc_cancel_c input[type='checkbox']").on('click', function (){
				var idno = $(this).attr("rowid");
				var rowdata = $("#gridManAlloc_cancel").jqGrid ('getRowData', idno);
				if($(this).prop("checked") == true){
					$("#"+idno+"_amtpaid").val(rowdata.outamount).addClass( "valid" ).removeClass( "error" );
					// setbal_cancel(idno,0);
					if(!myallocation_cancel.alloInArray(idno)){
						myallocation_cancel.addAllo(idno,rowdata.amount,0);
					}
				}else{
					$("#"+idno+"_amtpaid").val(0).addClass( "valid" ).removeClass( "error" );
					if(myallocation_cancel.alloInArray(idno)){
						myallocation_cancel.delAllo(idno,rowdata.amount,0);
					}
					// setbal_cancel(idno,rowdata.outamount);
					// $("#"+idno+"_amtpaid").trigger("change");
				}
			});
			
			delay(function (){
				// $("#alloText_cancel").focus(); // AlloTotal_cancel
				myallocation_cancel.retickallotogrid();
			}, 100);
			
			calc_jq_height_onchange("gridManAlloc_cancel");
			fdl.set_array().reset();
		},
	});

	AlloSearch_cancel("#gridManAlloc_cancel",urlParamManAlloc_cancel);
	function AlloSearch_cancel(grid,urlParam){
		$("#alloText_cancel").on("keyup", function (){
			delay(function (){
				search(grid,$("#alloText_cancel").val(),$("#alloCol_cancel").val(),urlParam);
			}, 500);
		});
		
		$("#alloCol_cancel").on("change", function (){
			search(grid,$("#alloText_cancel").val(),$("#alloCol_cancel").val(),urlParam);
		});
	}

	function startEdit_cancel(){
		var ids = $("#gridManAlloc_cancel").jqGrid('getDataIDs');
		
		for(var i = 0; i < ids.length; i++){
			var entrydate = $("#gridManAlloc_cancel").jqGrid('getRowData', ids[i]).entrydate;
			$("#gridManAlloc_cancel").jqGrid('setCell', ids[i], 'NULL', moment(entrydate).format("DD-MMM"));
			$("#gridManAlloc_cancel").jqGrid('editRow', ids[i]);
		}
	};

	addParamField('#gridManAlloc_cancel',false,urlParamManAlloc_cancel,['tick','amtpaid','amtbal']);

	function Allocation_cancel(){
		this.arrayAllo_cancel = [];
		this.alloBalance_cancel = 0;
		this.alloTotal_cancel = 0;
		this.outamt_cancel = 0;
		this.allo_error_cancel = [];
		
		this.renewAllo = function (os){
			this.arrayAllo_cancel.length = 0;
			this.alloTotal_cancel = 0;
			this.alloBalance_cancel = parseFloat(os);
			this.outamt_cancel = parseFloat(os);
			
			this.updateAlloField();
		}
		this.addAllo = function (idno,paid,bal){
			var obj = getlAlloFromGrid_cancel(idno);
			obj.amtpaid = paid;
			obj.amtbal = bal;
			var fieldID = "#"+idno+"_amtpaid";
			var self = this;
			
			this.arrayAllo_cancel.push({idno:idno,obj:obj});
			console.log(this.arrayAllo_cancel);
			
			// $(fieldID).on('change',[idno,self.arrayAllo_cancel,self.allo_error_cancel],onchangeField_cancel);
			
			this.updateAlloField();
		}
		this.delAllo = function (idno,paid,bal){
			// var obj = getlAlloFromGrid_cancel(idno);
			// obj.amtpaid = paid;
			// obj.amtbal = bal;
			// var fieldID = "#"+idno+"_amtpaid";
			// var self = this;
			
			// this.arrayAllo_cancel.push({idno:idno,obj:obj});
			let self = this
			this.arrayAllo_cancel.forEach(function (e,index){
				if(e.idno == idno){
					self.arrayAllo_cancel.splice(index, 1);
					return false;
				}
			});

			console.log(this.arrayAllo_cancel);
			
			// $(fieldID).on('change',[idno,self.arrayAllo_cancel,self.allo_error_cancel],onchangeField_cancel);
			
			this.updateAlloField();
		}
		function onchangeField_cancel(obj){
			var idno = obj.handleObj.data[0];
			var arrayAllo_cancel = obj.handleObj.data[1];
			var allo_error_cancel = obj.handleObj.data[2];
			
			var alloIndex = getIndex(arrayAllo_cancel,idno);
			var outamt_cancel = $("#gridManAlloc_cancel").jqGrid('getRowData', idno).outamount;
			var newamtpaid = parseFloat(obj.target.value);
			newamtpaid = isNaN(Number(newamtpaid)) ? 0 : parseFloat(obj.target.value);
			if(parseFloat(newamtpaid) > parseFloat(outamt_cancel)){
				alert("Amount paid exceed O/S amount");
				$("#"+idno+"_amtpaid").addClass( "error" ).removeClass( "valid" );
				adderror_allo_cancel(allo_error_cancel,idno);
				obj.target.focus();
				return false;
			}
			$("#"+idno+"_amtpaid").removeClass( "error" ).addClass( "valid" );
			delerror_allo_cancel(allo_error_cancel,idno);
			var balance = outamt_cancel - newamtpaid;
			
			obj.target.value = numeral(newamtpaid).format('0,0.00');;
			arrayAllo_cancel[alloIndex].obj.amtpaid = newamtpaid;
			arrayAllo_cancel[alloIndex].obj.amtbal = balance;
			setbal_cancel(idno,balance);
			
			myallocation_cancel.updateAlloField();
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
			$.each(self.arrayAllo_cancel, function (index, obj){
				if(obj.idno == idno){
					self.arrayAllo_cancel.splice(index, 1);
					return false;
				}
			});
		}
		this.alloInArray = function (idno){
			var retval = false;
			$.each(this.arrayAllo_cancel, function (index, obj){
				if(obj.idno == idno){
					retval = true;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.retickallotogrid = function (){
			var self = this;
			$.each(this.arrayAllo_cancel, function (index, obj){
				$("#"+obj.idno+"_amtpaid").on('change',[obj.idno,self.arrayAllo_cancel],onchangeField_cancel);
				if(obj.obj.amtpaid != " "){
					$("#"+obj.idno+"_amtpaid").val(obj.obj.amtpaid).removeClass( "error" ).addClass( "valid" );
					setbal_cancel(obj.idno,obj.obj.amtbal);
				}
			});
		}
		this.updateAlloField = function (){
			var self = this;
			this.alloTotal_cancel = 0;
			$.each(this.arrayAllo_cancel, function (index, obj){
				if(obj.obj.amtpaid != " "){
					self.alloTotal_cancel += parseFloat(obj.obj.amtpaid);
				}
			});
			// this.alloBalance_cancel = this.outamt_cancel - this.alloTotal_cancel;
			
			// $("#AlloTotal_cancel").val(this.alloTotal_cancel);
			$("#AlloBalance_cancel").val(this.alloTotal_cancel);
			if(this.alloTotal_cancel == 0){
				$("#AlloBalance_cancel").addClass( "error" ).removeClass( "valid" );
				// alert("Balance cannot in negative values");
			}else{
				$("#AlloBalance_cancel").addClass( "valid" ).removeClass( "error" );
			}
			allocurrency_cancel.formatOn();
		}
		function updateAllo(idno,amtpaid,arrayAllo_cancel){
			$.each(arrayAllo_cancel, function (index, obj){
				if(obj.idno == idno){
					obj.obj.amtpaid = amtpaid;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
		}
		function getlAlloFromGrid_cancel(idno){
			var temp = $("#gridManAlloc_cancel").jqGrid('getRowData', idno);
			return {idno:temp.idno,auditno:temp.auditno,amtbal:temp.amtbal,amtpaid:temp.amount};
		}
		function adderror_allo_cancel(array,idno){
			if($.inArray(idno,array) === -1){ // xjumpa
				array.push(idno);
			}
		}
		function delerror_allo_cancel(array,idno){
			if($.inArray(idno,array) !== -1){ // jumpa
				array.splice($.inArray(idno,array), 1);
			}
		}
	}

	function setbal_cancel(idno,balance){
		$("#gridManAlloc_cancel").jqGrid('setCell', idno, 'amtbal', balance);
	}

	$("#gridManAlloc_cancel").jqGrid('navGrid', '#pagerManAlloc_cancel', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function (){
			refreshGrid("#gridManAlloc_cancel",urlParamManAlloc_cancel);
		},
	})

	$('#allocate_cancel').click(function (){
		$( "#allocateDialog_cancel" ).dialog( "open" );
	});

});