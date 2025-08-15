$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
    //////////////////////////////////validation//////////////////////////////////
    $.validate({
        modules: 'sanitize',
        language: {
            requiredFields: ''
        },
    });
    
    var errorField = [];
    conf = {
        onValidate: function ($form){
            if(errorField.length > 0){
                console.log(errorField);
                show_errors(errorField,'#formdata');
                return [{
                    element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
                    message: ' '
                }]
            }
        },
    };
    
    ////////////////////////////////////currency////////////////////////////////////
    var myfail_msg = new fail_msg_func();
    var mycurrency = new currencymode(['#amount']);
    var fdl = new faster_detail_load();
    var sequence = new Sequences('SO','#SL_entrydate');
    var cbselect = new checkbox_selection("#jqGrid","Checkbox","SL_idno","recstatus");
    
    /////////////////////////check date validate from period/////////////////////////
    var actdateObj = new setactdate(["#SL_entrydate"]);
    actdateObj.getdata().set();
    
    ///////////////////////////////////start dialog///////////////////////////////////
    var oper = null;
    var unsaved = false;
    page_to_view_only($('#viewonly').val());
    
    $("#dialogForm")
        .dialog({
            width: 9.5 / 10 * $(window).width(),
            modal: true,
            autoOpen: false,
            open: function (event, ui){
                $('#SL_deptcode').focus();
                errorField.length = 0;
                actdateObj.getdata().set();
                parent_close_disabled(true);
                $("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth - $("#jqGrid2_c")[0].offsetLeft));
                mycurrency.formatOnBlur();
                mycurrency.formatOn();
                switch(oper){
                    case state = 'add':
                        $("#jqGrid2").jqGrid("clearGridData", true);
                        $("#pg_jqGridPager2 table").show();
                        hideatdialogForm(true);
                        enableForm('#formdata');
                        rdonly('#formdata');
                        $("#SL_deptcode").val($("#deptcode").val());
                        dialog_deptcode.check(errorField);
                        // $("#purreqhd_reqdept").val($("#x").val());
                        $('#save').hide();
                        break;
                    case state = 'edit':
                        $("#pg_jqGridPager2 table").show();
                        hideatdialogForm(true);
                        enableForm('#formdata');
                        rdonly('#formdata');
                        break;
                    case state = 'view':
                        disableForm('#formdata');
                        $("#pg_jqGridPager2 table").hide();
                        break;
                }if(oper != 'add'){
                    dialog_deptcode.check(errorField);
                    dialog_billtypeSO.check(errorField);
                    dialog_mrn.check('errorField');
                    dialog_CustomerSO.check(errorField);
                    dialog_doctor.check('errorField');
                }if(oper != 'view'){
                    dialog_deptcode.on();
                    dialog_billtypeSO.on();
                    dialog_mrn.on();
                    dialog_CustomerSO.on();
                    dialog_doctor.on();
                }
            },
            beforeClose: function (event, ui){
                if(unsaved){
                    event.preventDefault();
                    bootbox.confirm("Are you sure want to leave without save?", function (result){
                        if(result == true){
                            unsaved = false
                            $("#dialogForm").dialog('close');
                        }
                    });
                }
            },
            close: function (event, ui){
                errorField.length = 0;
                addmore_jqgrid2.state = false; // reset balik
                addmore_jqgrid2.more = false;
                // reset balik
                parent_close_disabled(false);
                emptyFormdata(errorField, '#formdata');
                emptyFormdata(errorField, '#formdata2');
                $('.my-alert').detach();
                $("#formdata a").off();
                dialog_deptcode.off();
                dialog_billtypeSO.off();
                dialog_mrn.off();
                dialog_CustomerSO.off();
                dialog_doctor.off();
                $(".noti").empty();
                $("#refresh_jqGrid").click();
                refreshGrid("#jqGrid2",null,"kosongkan");
            },
        });
    ////////////////////////////////////end dialog////////////////////////////////////
    
    /////////////////////////////check postdate & docdate/////////////////////////////
    $("#posteddate,#SL_entrydate").blur(checkdate);
    
    function checkdate(nkreturn=false){
        var posteddate = $('#posteddate').val();
        var SL_entrydate = $('#SL_entrydate').val();
        
        text_success1('#posteddate')
        text_success1('#SL_entrydate')
        $("#dialogForm .noti2 ol").empty();
        var failmsg = [];
        
        if(moment(posteddate).isBefore(SL_entrydate)){
            failmsg.push("Post Date cannot be lower than Document date");
            text_error1('#posteddate')
            text_error1('#SL_entrydate')
        }
        
        if(moment(SL_entrydate).isAfter(moment())){
            failmsg.push("Doc Date cannot be higher than today");
            text_error1('#SL_entrydate')
        }
        
        if(failmsg.length){
            failmsg.forEach(function (element){
                $('#dialogForm .noti2 ol').prepend('<li>'+element+'</li>');
            });
            if(nkreturn)return false;
        }else{
            if(nkreturn)return true;
        }
    }
    
    var recstatus_filter = [['OPEN','REQUEST']];
    if($("#recstatus_use").val() == 'ALL'){
        recstatus_filter = [['OPEN','REQUEST','SUPPORT','INCOMPLETED','VERIFIED','APPROVED','CANCELLED']];
        filterCol_urlParam = ['purreqhd.compcode'];
        filterVal_urlParam = ['session.compcode'];
    }else if($("#recstatus_use").val() == 'SUPPORT'){
        recstatus_filter = [['REQUEST']];
        filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
        filterVal_urlParam = ['session.compcode','session.username'];
    }else if($("#recstatus_use").val() == 'VERIFIED'){
        recstatus_filter = [['SUPPORT']];
        filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
        filterVal_urlParam = ['session.compcode','session.username'];
    }else if($("#recstatus_use").val() == 'APPROVED'){
        recstatus_filter = [['VERIFIED']];
        filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
        filterVal_urlParam = ['session.compcode','session.username'];
    }
    
    ////////////////////////////////////searchClick2////////////////////////////////////
    function searchClick2(grid,form,urlParam){
        $(form+' [name=Stext]').on("keyup", function (){
            delay(function (){
                search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
                // $('#reqnodepan').text(""); // tukar kat depan tu
                // $('#reqdeptdepan').text("");
                refreshGrid("#jqGrid3",null,"kosongkan");
            }, 500);
        });
        
        $(form+' [name=Scol]').on("change", function (){
            search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
            // $('#reqnodepan').text(""); // tukar kat depan tu
            // $('#reqdeptdepan').text("");
            refreshGrid("#jqGrid3",null,"kosongkan");
        });
    }
    
    ////////////////////////////////////////jqgrid////////////////////////////////////////
    var urlParam = {
        action: 'maintable',
        url: './Quotation_SO/table',
        source: $('#SL_source').val(),
        trantype: $('#SL_trantype').val(),
    }
    
    var saveParam = {
        // action: 'Quotation_header_save',
        url: './Quotation_SO/form',
        field: '',
        oper: oper,
        table_name: 'finance.salehdr',
        table_id: 'idno',
        fixPost: true,
        // returnVal: true,
    }
    
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'SL_idno', width: 10, hidden: true, key: true },
			{ label: 'compcode', name: 'SL_compcode', hidden: true },
			{ label: 'source', name: 'SL_source', width: 10, hidden: true },
			{ label: 'lineno_', name: 'SL_lineno_', width: 20, hidden: true },
			{ label: 'hdrsts', name: 'SL_hdrsts', width: 20, hidden: true },
			{ label: 'Debtor Code', name: 'SL_debtorcode', width: 30, canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: 'Payber Code', name: 'SL_payercode', hidden: true },
			{ label: 'Customer', name: 'dm_name', width: 40, canSearch: false, classes: 'wrap', hidden: true },
			{ label: 'Document Date', name: 'SL_entrydate', width: 12, classes: 'wrap text-uppercase', formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Posted Date', name: 'SL_posteddate', width: 12, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Audit No', name: 'SL_auditno', width: 12, align: 'right', formatter: padzero, unformat: unpadzero },
			{ label: 'Quotation No', name: 'SL_quoteno', width: 10, align: 'right', canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'PO No', name: 'SL_pono', width: 10, align: 'right', formatter: padzero5, unformat: unpadzero, hidden: true },
			{ label: 'Amount', name: 'SL_amount', width: 10, align: 'right', formatter: 'currency' },
			{ label: 'O/S<br>Amount', name: 'SL_outamount', width: 10, align: 'right', formatter: 'currency' },
			{ label: 'Trantype', name: 'SL_trantype', width: 10 , hidden: true },
			{ label: 'PO Date', name: 'SL_podate', width: 12, formatter: dateFormatter, unformat: dateUNFormatter, hidden: true },
			{ label: 'Department Code', name: 'SL_deptcode', width: 18, canSearch: true, classes: 'wrap', formatter: showdetail, unformat: un_showdetail },
			{ label: 'HUKM MRN', name: 'SL_mrn', width: 25, formatter: showdetail, unformat: un_showdetail },
			{ label: 'Status', name: 'SL_recstatus', width: 10 },
			{ label: ' ', name: 'Checkbox', sortable: false, width: 8, align: "center", formatter: formatterCheckbox },
			// { label: 'SL_posteddate', name: 'SL_posteddate', hidden: true, formatter: dateFormatter },
			{ label: 'recptno', name: 'SL_recptno', width: 20, hidden: true },
			{ label: 'paymode', name: 'SL_paymode', width: 20, hidden: true },
			{ label: 'debtortype', name: 'SL_debtortype', hidden: true },
			{ label: 'billdebtor', name: 'SL_billdebtor', hidden: true },
			{ label: 'Remark', name: 'SL_remark', width: 10, hidden: true },
			{ label: 'mrn', name: 'SL_doctorcode', width: 10, hidden: true },
			{ label: 'adduser', name: 'SL_adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'SL_adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'SL_upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'SL_upddate', width: 10, hidden: true },
			{ label: 'paytype', name: 'SL_hdrtype', width: 10, hidden: true },
			{ label: 'termvalue', name: 'SL_termvalue', width: 10, hidden: true },
			{ label: 'termcode', name: 'SL_termcode', width: 10, hidden: true },
			// { label: 'Sector', name: 'SL_unit', width: 15, canSearch: true, classes: 'wrap', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		// sortname: 'SL_idno',
		// sortorder: 'desc',
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected){
			$('#save').hide();
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").SL_recstatus;
			let scope = $("#recstatus_use").val();
			
			urlParam2.source = selrowData("#jqGrid").SL_source;
			urlParam2.trantype = selrowData("#jqGrid").SL_trantype;
			urlParam2.auditno = selrowData("#jqGrid").SL_auditno;
			urlParam2.deptcode = selrowData("#jqGrid").SL_deptcode;
			
			$('#reqnodepan').text(selrowData("#jqGrid").purreqhd_purreqno); // tukar kat depan
			$('#reqdeptdepan').text(selrowData("#jqGrid").purreqhd_reqdept);
			refreshGrid("#jqGrid3", urlParam2);
			populate_form(selrowData("#jqGrid"));
			
			$("#pdfgen1").attr('href','./Quotation_SO/showpdf?idno='+selrowData("#jqGrid").SL_idno);
			$("#pdfgen2").attr('href','./Quotation_SO/showpdf?idno='+selrowData("#jqGrid").SL_idno);
			if_cancel_hide();
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").SL_recstatus;
			
			if(stat == 'POSTED' || stat == 'PARTIAL' || stat == 'COMPLETED'){
				$("#jqGridPager td[title='View Selected Row']").click();
			}else if (stat == 'OPEN'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
				
				if(rowid != null){
					rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				}
			}
			
			let SL_quoteno = selrowData("#jqGrid").SL_quoteno;
			let quoteno = SL_quoteno.toString().padStart(8, '0');
			
			let SL_auditno = selrowData("#jqGrid").SL_auditno;
			let auditno = SL_auditno.toString().padStart(8, '0');
			
			$('#SL_quoteno').val(quoteno);
			$('#SL_auditno').val(auditno);
		},
		gridComplete: function (){
			cbselect.show_hide_table();
			if(oper == 'add' || oper == null || $("#jqGrid").jqGrid('getGridParam', 'selrow') == null){ // highlight 1st record
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus().click();
			$("#searchForm input[name=Stext]").focus();
			populate_form(selrowData("#jqGrid"));
			fdl.set_array().reset();
			
			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
			
			// if($('#jqGrid').jqGrid('getGridParam', 'reccount') < 1){
			// 	$('#reqnodepan').text(''); // tukar kat depan tu
			// 	$('#reqdeptdepan').text('');
			// }else 
			if($('#jqGrid').data('inputfocus') == 'customer_search'){
				$("#customer_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#customer_search_hb').text('');
				removeValidationClass(['#customer_search']);
			}else if($('#jqGrid').data('inputfocus') == 'department_search'){
				$("#department_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#department_search_hb').text('');
				removeValidationClass(['#department_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			page_to_view_only($('#viewonly').val(),function (){
				$('#customer_text').hide();
			});
		},
		loadComplete: function (data){
			// console.log($('#deptcode').val());
			if($('#deptcode').val() !== 'IMP'){
				$("#jqGrid").jqGrid('hideCol', 'SL_mrn');
				$("#jqGrid").jqGrid('setGridWidth', 1320);
			}else{
				$("#jqGrid").jqGrid('showCol', 'SL_mrn');
			}

            $("#jqGrid").jqGrid('setGridWidth', Math.floor($("#jqGrid_c")[0].offsetWidth) - 2);
		},
		beforeRequest: function (){
			refreshGrid("#jqGrid2", urlParam, 'kosongkan')
		}
	});
    
    /////////////////////////////////set label jqGrid right/////////////////////////////////
    jqgrid_label_align_right("#jqGrid2");
    
    ////////////////////////////////////start grid pager////////////////////////////////////
    $("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
        view: false, edit: false, add: false, del: false, search: false,
        beforeRefresh: function (){
            refreshGrid("#jqGrid", urlParam, oper);
        },
    }).jqGrid('navButtonAdd', "#jqGridPager", {
        caption: "", cursor: "pointer", position: "first",
        buttonicon: "glyphicon glyphicon-info-sign",
        title: "View Selected Row",
        onClickButton: function (){
            oper = 'view';
            selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
            populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', ['SL_termcode']);
            refreshGrid("#jqGrid2", urlParam2);
        },
    }).jqGrid('navButtonAdd', "#jqGridPager", {
        caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
        buttonicon: "glyphicon glyphicon-edit",
        title: "Edit Selected Row",
        onClickButton: function (){
            oper = 'edit';
            // if(selrowData("#jqGrid").SL_recstatus != 'OPEN'){
            // 	return false;
            // }
            selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
            populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', ['SL_termcode']);
            refreshGrid("#jqGrid2", urlParam2);
            
            if(selrowData("#jqGrid").SL_recstatus == 'POSTED' || selrowData("#jqGrid").SL_recstatus == 'PARTIAL' || selrowData("#jqGrid").SL_recstatus == 'COMPLETED'){
                disableForm('#formdata');
                // $('#SL_orderno').prop('readonly',false);
                // $('#SL_podate').prop('readonly',false);
                // $('#SL_pono').prop('readonly',false);
                $("#pg_jqGridPager2 table").hide();
                $('#save').show();
                refreshGrid("#jqGrid2", urlParam2);
            }
            
            if(selrowData("#jqGrid").SL_posteddate == 'undefined' || selrowData("#jqGrid").SL_posteddate == 'Invalid date'){
                $('#SL_posteddate').val('');
            }
            
            let SL_quoteno = selrowData("#jqGrid").SL_quoteno;
            let quoteno = SL_quoteno.toString().padStart(8, '0');
            
            let SL_auditno = selrowData("#jqGrid").SL_auditno;
            let auditno = SL_auditno.toString().padStart(8, '0');
            
            $('#SL_quoteno').val(quoteno);
            $('#SL_auditno').val(auditno);
        },
    }).jqGrid('navButtonAdd', "#jqGridPager", {
        caption: "", cursor: "pointer", position: "first",
        buttonicon: "glyphicon glyphicon-plus",
        id: 'glyphicon-plus',
        title: "Add New Row",
        onClickButton: function (){
            oper = 'add';
            $("#dialogForm").dialog("open");
        },
    });
    
    //////////////////////handle searching, its radio button and toggle//////////////////////
    populateSelect('#jqGrid', '#searchForm');
    
    ///////////////////////add field into param, refresh grid if needed///////////////////////
    addParamField('#jqGrid', false, urlParam, ['Checkbox']);
    addParamField('#jqGrid', false, saveParam, ['SL_idno','SL_auditno','SL_adduser','SL_adddate','SL_mrn','dm_name','SL_upduser','SL_upddate','SL_recstatus','SL_unit','Checkbox','queuepr_AuthorisedID']);
    
    ////////////////////////////////////hide at dialogForm////////////////////////////////////
    function hideatdialogForm(hide,saveallrow){
        if(saveallrow == 'saveallrow'){
            $("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
            $("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
        }else if(hide){
            $("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
            $("#saveDetailLabel").show();
        }else{
            $("#jqGrid2_iladd,#jqGrid2_iledit,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
            $("#saveDetailLabel,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
        }
    }
    
    function padzero(cellvalue, options, rowObject){
        if(cellvalue == null || cellvalue.toString().trim() == ''){
            return ''
        }
        
        let padzero = 8, str = "";
        while(padzero > 0){
            str = str.concat("0");
            padzero--;
        }
        return pad(str, cellvalue, true);
    }
    
    /////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////
    $('#jqGrid2_ilcancel').click(function (){
        $(".noti").empty();
    });
    
    $("#but_post_jq,#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function (){
        $(this).attr('disabled',true);
        var self_ = this;
        var idno_array = [];
        
        idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
        var obj = {};
        obj.idno_array = idno_array;
        obj.oper = $(this).data('oper');
        obj._token = $('#_token').val();
        
        $.post('Quotation_SO/form', obj, function (data){
            refreshGrid('#jqGrid', urlParam);
            $(self_).attr('disabled',false);
            cbselect.empty_sel_tbl();
        }).fail(function (data){
            $('#error_infront').text(data.responseText);
            $(self_).attr('disabled',false);
        }).success(function (data){
            $(self_).attr('disabled',false);
        });
    });
    
    $("#but_post2_jq").click(function (){
        var obj = {};
        obj.idno = selrowData('#jqGrid').SL_idno;
        obj.oper = $(this).data('oper');
        obj._token = $('#_token').val();
        oper = null;
        
        $.post('./Quotation_SO/form', obj, function (data){
            cbselect.empty_sel_tbl();
            refreshGrid('#jqGrid', urlParam);
        }).fail(function (data){
            $('#error_infront').text(data.responseText);
        }).success(function (data){
            
        });
    });
    
    /////////////////////////////////////////saveHeader/////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj){
		if(obj == null){
			obj = {};
		}
		saveParam.oper = selfoper;
		
		$.post(saveParam.url+"?"+$.param(saveParam), $(form).serialize()+'&'+ $.param(obj), function (data){
			
		},'json').fail(function (data){
			$('#SL_hdrtype').focus();
			$('.noti').text(data.responseText);
			dialog_deptcode.on();
			dialog_billtypeSO.on();
			dialog_CustomerSO.on();
            dialog_doctor.on();
            dialog_mrn.on();
		}).done(function (data){
			$("#saveDetailLabel").attr('disabled',false);
			unsaved = false;
			hideatdialogForm(false);
			// $(".noti").empty();
			$('.noti').text(' ');
			
			addmore_jqgrid2.state = true;
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}
			
			if(selfoper == 'add'){
				oper = 'edit'; // sekali dia add terus jadi edit lepas tu
				$('#SL_auditno').val(data.auditno);
				$('#SL_idno').val(data.idno); // just save idno for edit later
				$('#SL_amount').val(data.totalAmount);
				
				urlParam2.source = 'SL';
				urlParam2.trantype = 'RECNO';
				urlParam2.auditno = data.auditno;
				urlParam2.deptcode = $('#SL_deptcode').val();
			}else if(selfoper == 'edit'){
				// doesnt need to do anything
			}
			disableForm('#formdata');
		})
	}
    
    $("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea', function (){
        unsaved = true; // kalu dia change apa2 bagi prompt
    });
    
    // $("#dialogForm").on('click','#formdata a.input-group-addon', function (){
    //     unsaved = true; // kalu dia change apa2 bagi prompt
    // });
    
    ////////////////////////////////////utk dropdown search By////////////////////////////////////
    searchBy();
    function searchBy(){
        $.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value){
            if(value['canSearch']){
                if(value['selected']){
                    $("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
                }else{
                    $("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
                }
            }
            searchClick2('#jqGrid', '#searchForm', urlParam);
        });
    }
    
    $('#Scol').on('change', whenchangetodate);
    $('#Status').on('change', searchChange);
    $('#storedept').on('change', searchChange);
    $('#docuDate_search').on('click', searchDate);
    
    function whenchangetodate(){
        customer_search.off();
        department_search.off();
        $('#customer_search,#docuDate_from,#docuDate_to,#department_search').val('');
        $('#customer_search_hb').text('');
        $('#department_search_hb').text('');
        urlParam.filterdate = null;
        removeValidationClass(['#customer_search,#department_search']);
        if($('#Scol').val()=='SL_posteddate'){
            $("input[name='Stext'], #customer_text, #department_text").hide("fast");
            $("#docuDate_text").show("fast");
        }else if($('#Scol').val() == 'SL_debtorcode'){
            $("input[name='Stext'],#docuDate_text,#department_text").hide("fast");
            $("#customer_text").show("fast");
            customer_search.on();
        }else if($('#Scol').val() == 'SL_deptcode'){
            $("input[name='Stext'],#docuDate_text,#customer_text").hide("fast");
            $("#department_text").show("fast");
            department_search.on();
        }else{
            $("#customer_text,#docuDate_text,#department_text").hide("fast");
            $("input[name='Stext']").show("fast");
            $("input[name='Stext']").velocity({ width: "100%" });
        }
    }
    
    function searchDate(){
        urlParam.filterdate = [$('#docuDate_from').val(),$('#docuDate_to').val()];
        refreshGrid('#jqGrid',urlParam);
    }
    
    searchChange(true);
    function searchChange(once=false){
        var arrtemp = [$('#Status option:selected').val(), $('#storedept option:selected').val()];
        var filter = arrtemp.reduce(function (a,b,c){
            if(b.toUpperCase() == 'ALL'){
                return a;
            }else{
                a.fc = a.fc.concat(a.fct[c]);
                a.fv = a.fv.concat(b);
                return a;
            }
        },{fct:['SL.recstatus', 'SL.deptcode'],fv:[],fc:[]});
        
        urlParam.filterCol = filter.fc;
        urlParam.filterVal = filter.fv;
        urlParam.WhereInCol = null;
        urlParam.WhereInVal = null;
        
        if(once){
            urlParam.searchCol = null;
            urlParam.searchVal = null;
            if($('#searchForm [name=Stext]').val().trim() != ''){
                let searchCol = ['auditno'];
                let searchVal = ['%'+$('#searchForm [name=Stext]').val().trim()+'%'];
                urlParam.searchCol = searchCol;
                urlParam.searchVal = searchVal;
            }
            once = false;
        }
        
        refreshGrid('#jqGrid',urlParam);
    }
    
    var customer_search = new ordialog(
        'customer_search', 'debtor.debtormast', '#customer_search', 'errorField',
        {
            colModel: [
                { label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
            ],
            urlParam: {
                filterCol: ['compcode','recstatus'],
                filterVal: ['session.compcode','ACTIVE']
            },
            ondblClickRow: function (){
                let data = selrowData('#' + customer_search.gridname).debtorcode;
                
                if($('#Scol').val() == 'SL_debtorcode'){
                    urlParam.searchCol = ["SL.debtorcode"];
                    urlParam.searchVal = [data];
                }
                // }else if($('#Scol').val() == 'SL_payercode'){
                // 	urlParam.searchCol = ["SL.payercode"];
                // 	urlParam.searchVal = [data];
                // }
                refreshGrid('#jqGrid', urlParam);
            },
            gridComplete: function (obj){
                var gridname = '#'+obj.gridname;
                if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                    $(gridname+' tr#1').click();
                    $(gridname+' tr#1').dblclick();
                }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                    // $('#'+obj.dialogname).dialog('close');
                }
            }
        },{
            title: "Select Customer",
            open: function (){
                customer_search.urlParam.filterCol = ['compcode','recstatus'];
                customer_search.urlParam.filterVal = ['session.compcode','ACTIVE'];
            }
        },'urlParam','radio','tab'
    );
    customer_search.makedialog(true);
    $('#customer_search').on('keyup',ifnullsearch);
    
    var department_search = new ordialog(
        'department_search', 'sysdb.department', '#department_search', 'errorField',
        {
            colModel: [
                { label: 'Department Code', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
            ],
            urlParam: {
                filterCol: ['compcode','recstatus'],
                filterVal: ['session.compcode','ACTIVE']
            },
            ondblClickRow: function (){
                let data = selrowData('#' + department_search.gridname).deptcode;
                
                if($('#Scol').val() == 'SL_deptcode'){
                    urlParam.searchCol = ["SL.deptcode"];
                    urlParam.searchVal = [data];
                }
                refreshGrid('#jqGrid', urlParam);
            },
            gridComplete: function (obj){
                var gridname = '#'+obj.gridname;
                if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                    $(gridname+' tr#1').click();
                    $(gridname+' tr#1').dblclick();
                }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                    // $('#'+obj.dialogname).dialog('close');
                }
            }
        },{
            title: "Select Creditor",
            open: function (){
                department_search.urlParam.filterCol = ['compcode','recstatus'];
                department_search.urlParam.filterVal = ['session.compcode','ACTIVE'];
            }
        },'urlParam','radio','tab'
    );
    department_search.makedialog(true);
	$('#department_search').on('keyup',ifnullsearch);
	
	function ifnullsearch(){
		if($(this).val() == ''){
			urlParam.searchCol = [];
			urlParam.searchVal = [];
			$('#jqGrid').data('inputfocus',$(this).attr('id'));
			refreshGrid('#jqGrid', urlParam);
		}
	}
	resizeColumnHeader = function (){
		var rowHight, resizeSpanHeight,
		// get the header row which contains
		headerRow = $(this).closest("div.ui-jqgrid-view")
			.find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");
		
		// reset column height
		headerRow.find("span.ui-jqgrid-resize").each(function (){
			this.style.height = "";
		});
		
		// increase the height of the resizing span
		resizeSpanHeight = "height: " + headerRow.height() + "px !important; cursor: col-resize;";
		headerRow.find("span.ui-jqgrid-resize").each(function (){
			this.style.cssText = resizeSpanHeight;
		});
		
		// set position of the dive with the column header text to the middle
		rowHight = headerRow.height();
		headerRow.find("div.ui-jqgrid-sortable").each(function (){
			var ts = $(this);
			ts.css("top", (rowHight - ts.outerHeight()) / 2 + "px");
		});
	},
	fixPositionsOfFrozenDivs = function (){
		var $rows;
		if(typeof this.grid.fbDiv !== "undefined"){
			$rows = $(">div>table.ui-jqgrid-btable>tbody>tr", this.grid.bDiv);
			$(">table.ui-jqgrid-btable>tbody>tr", this.grid.fbDiv).each(function (i){
				var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
				if($(this).hasClass("jqgrow")){
					$(this).height(rowHight);
					rowHightFrozen = $(this).height();
					if(rowHight !== rowHightFrozen){
						$(this).height(rowHight + (rowHight - rowHightFrozen));
					}
				}
			});
			$(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
			$(this.grid.fbDiv).css($(this.grid.bDiv).position());
		}
		if(typeof this.grid.fhDiv !== "undefined"){
			$rows = $(">div>table.ui-jqgrid-htable>thead>tr", this.grid.hDiv);
			$(">table.ui-jqgrid-htable>thead>tr", this.grid.fhDiv).each(function (i){
				var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
				$(this).height(rowHight);
				rowHightFrozen = $(this).height();
				if(rowHight !== rowHightFrozen){
					$(this).height(rowHight + (rowHight - rowHightFrozen));
				}
			});
			$(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
			$(this.grid.fhDiv).css($(this.grid.hDiv).position());
		}
	},
	fixGboxHeight = function (){
		var gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight(),
			pagerHeight = $(this.p.pager).outerHeight();
		
		$("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
		gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight();
		pagerHeight = $(this.p.pager).outerHeight();
		$("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
	}
	
	///////////////////////////////////parameter for jqgrid2 url///////////////////////////////////
	var urlParam2 = {
		action: 'get_table_dtl',
		url: 'Quotation_SO_Detail/table',
		source: '',
		trantype: '',
		auditno: '',
		deptcode: '',
	};
	var addmore_jqgrid2 = {more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	
	////////////////////////////////////////////jqgrid2////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "Quotation_SO_Detail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false, hidden: true },
			{ label: 'rowno', name: 'rowno', width: 50, classes: 'wrap', editable: false, hidden: true },
			{ label: 'Item Code', name: 'chggroup', width: 200, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				}
			},
            { label: 'chggroup_ori', name: 'chggroup_ori', hidden:true },
            { label: 'uom_ori', name: 'uom_ori', hidden:true },
			{ label: 'Item Description', name: 'description', width: 180, classes: 'wrap', editable: false, editoptions: { readonly: "readonly" }, hidden: true },
			{ label: 'UOM Code', name: 'uom', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				}
			},
			{ label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 150, classes: 'wrap', hidden:true, editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: uom_recvCustomEdit,
					custom_value: galGridCustomValue
				}
			},
			{ label: 'Tax', name: 'taxcode', width: 100, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				}
			},
			{ label: 'Unit Price', name: 'unitprice', width: 100, classes: 'wrap txnum', align: 'right', editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'Quantity', name: 'quantity', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }
			},
			{ label: 'Quantity on Hand', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }, editoptions: { readonly: "readonly" }, hidden: true
			},
			{ label: 'Quantity Delivered', name: 'qtydelivered', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'Outstanding Quantity', name: 'qty_outstanding', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'Total Amount <br>Before Tax', name: 'amount', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			// { label: 'Tax', name: 'taxcode', width: 100, align: 'right', classes: 'wrap', editable: true,
			// 	editrules: { required: false }, editoptions: { readonly: "readonly" }
			// },
			{ label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'Discount Amount', name: 'discamt', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'Tax Amount', name: 'taxamt', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'Total Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: "," },
				editrules: { required: true }, editoptions: { readonly: "readonly" }
			},
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
	    rowNum: 1000000,
	    pgbuttons: false,
	    pginput: false,
	    pgtext: "",
		sortname: 'idno',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function (data){
			data.rows.forEach(function (element){
				if(element.callback_param != null){
					$("#"+element.callback_param[2]).on('click', function (){
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2]
						)
					});
				}
			});
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
                let lastselrow = $('#jqGrid2').data('lastselrow');
                if(lastselrow == null || lastselrow == undefined){
                    $('#jqGrid2').jqGrid ('setSelection', "1");
                }else{
                    $('#jqGrid2 tr#'+lastselrow).focus().click();
                    $('#jqGrid2').data('lastselrow',null);
                }
			}
			
			// setjqgridHeight(data,'jqGrid2');
			
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; // reset
			
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		gridComplete: function (){
			$("#jqGrid2").find(".remarks_button").on("click", function (e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();
			myfail_msg.clear_fail();
			// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			
			// if(oper == 'edit'){
			// 	get_billtype();
			// }
			// calculate_quantity_outstanding('#jqGrid2');
		},
        ondblClickRow:function(rowid){
            if($('#jqGrid2_iledit').is(":visible")){
                $('#jqGrid2_iledit').click();
                $('#jqGrid2').data('lastselrow',rowid);
            }
        },
		afterShowForm: function (rowid){
			$("#expdate").datepicker();
		},
		beforeSubmit: function (postdata, rowid){
			dialog_deptcode.check(errorField);
			dialog_billtypeSO.check(errorField);
			dialog_mrn.check('errorField');
			dialog_CustomerSO.check(errorField);
			dialog_doctor.check('errorField');
		}
	});
	
	// $("#jqGrid2").jqGrid('setGroupHeaders', {
	// 	useColSpanStyle: false,
	// 	groupHeaders: [
	// 		{ startColumnName: 'description', numberOfColumns: 1, titleText: 'Item' },
	// 		// { startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item' },
	// 	]
	// });
	
	////////////////////////////////////set label jqGrid2 right////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	////////////////////////////////////all function for remarks////////////////////////////////////
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><i class='fa fa-file-text-o'></i> remark</button>";
	}
	
	function unformatRemarks(cellvalue, options, rowObject){
		return null;
	}
	
	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;

        if(options.gid != "jqGrid"){
            return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
        }

        if($('#recstatus_use').val() == 'CANCEL_PARTIAL'){
            if(rowObject.SL_recstatus == "PARTIAL"){
                return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
            }
        }else{
            if(rowObject.SL_recstatus == "OPEN"){
                return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
            }
        }

        return ' ';
	}
	
	var butt1_rem = 
		[{
			text: "Save", click: function (){
				let newval = $("#remarks2").val();
				let rowid = $('#remarks2').data('rowid');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{remarks:newval});
				if($("#jqGridPager2SaveAll").css('display') == 'none'){
					$("#jqGrid2_ilsave").click();
				}
				$(this).dialog('close');
			}
		},{
			text: "Cancel", click: function (){
				$(this).dialog('close');
			}
		}];
	
	var butt2_rem = 
		[{
			text: "Close", click: function (){
				$(this).dialog('close');
			}
		}];
	
	$("#dialog_remarks").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function (event, ui){
			let rowid = $('#remarks2').data('rowid');
			let grid = $('#remarks2').data('grid');
			$('#remarks2').val($(grid).jqGrid('getRowData', rowid).remarks);
			let exist = $("#jqGrid2 #"+rowid+"_pouom_convfactor_uom").length;
			if(grid == '#jqGrid3' || exist == 0){ // lepas ni letak or not edit mode
				$("#remarks2").prop('disabled',true);
				$( "#dialog_remarks").dialog("option", "buttons", butt2_rem);
			}else{
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks").dialog("option", "buttons", butt1_rem);
			}
		},
		buttons: butt2_rem
	});
	
	/////////////////////////////////////////myEditOptions/////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			myfail_msg.clear_fail();
			errorField.length = 0;
			$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			get_billtype(mycurrency2);
			
			dialog_chggroup.on();
			dialog_uomcode.on();
			// dialog_uom_recv.on();
			dialog_tax.on();
			
			unsaved = false;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			// Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='billtypeperct']","#jqGrid2 input[name='billtypeamt']","#jqGrid2 input[name='totamount']","#jqGrid2 input[name='amount']"]);
			// Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtydelivered']","#jqGrid2 input[name='quantity']"]);
			
			mycurrency2.formatOnBlur(); // make field to currency on leave cursor
			mycurrency_np.formatOnBlur(); // make field to currency on leave cursor
			
			// $("#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='quantity']").on('keyup',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);
			$("#jqGrid2 input[name='quantity']").on('blur',{currency: [mycurrency2]},calculate_line_totgst_and_totamt);
			
			// $("#jqGrid2 input[name='quantity']").on('blur',calculate_conversion_factor);
			$("#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='billtypeamt'],#jqGrid2 input[name='quantity'],#jqGrid2 input[name='chggroup']").on('focus',remove_noti);
			
			$("#jqGrid2 input[name='qtydelivered']").keydown(function (e){ // when click tab at qtydelivered, auto save
				var code = e.keyCode || e.which;
				if(code == '9'){
					delay(function (){
						$('#jqGrid2_ilsave').click();
						addmore_jqgrid2.state = true;
					}, 500);
				}
			});
		},
		aftersavefunc: function (rowid, response, options){
			$('#SL_amount').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length = 0;
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		errorfunc: function (rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid){
			if(errorField.length>0)return false;
			mycurrency2.formatOff();
			mycurrency_np.formatOff();
			
			if(parseInt($('#jqGrid2 input[name="quantity"]').val()) <= 0)return false;
			
			if(myfail_msg.fail_msg_array.length > 0){
				return false;
			}
			
			let editurl = "./Quotation_SO_Detail/form?"+
				$.param({
					action: 'quotation_detail_save',
					source: 'SL',
					trantype: 'RECNO',
					auditno: $('#SL_auditno').val(),
					// discamt: $("#jqGrid2 input[name='discamt']").val(),
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			myfail_msg.clear_fail();
			errorField.length = 0;
			// delay(function (){
			// 	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			// }, 500);
			hideatdialogForm(false);
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};

    var myEditOptions_edit = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            myfail_msg.clear_fail();
            errorField.length = 0;
            $("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();
            calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
            get_billtype(mycurrency2);
            
            dialog_chggroup.off();
            $(dialog_chggroup.textfield).prop('readonly',true);
            dialog_uomcode.off();
            $(dialog_uomcode.textfield).prop('readonly',true);
            // dialog_uom_recv.off();
            // $(dialog_uom_recv.textfield).prop('readonly',true);
            dialog_tax.off();
            $(dialog_tax.textfield).prop('readonly',true);
            
            unsaved = false;
            mycurrency2.array.length = 0;
            mycurrency_np.array.length = 0;
            // Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='billtypeperct']","#jqGrid2 input[name='billtypeamt']","#jqGrid2 input[name='totamount']","#jqGrid2 input[name='amount']"]);
            // Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtydelivered']","#jqGrid2 input[name='quantity']"]);
            
            mycurrency2.formatOnBlur(); // make field to currency on leave cursor
            mycurrency_np.formatOnBlur(); // make field to currency on leave cursor
            
            // $("#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='quantity']").on('keyup',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);
            $("#jqGrid2 input[name='quantity']").on('blur',{currency: [mycurrency2]},calculate_line_totgst_and_totamt);
            
            // $("#jqGrid2 input[name='quantity']").on('blur',calculate_conversion_factor);
            $("#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='billtypeamt'],#jqGrid2 input[name='quantity'],#jqGrid2 input[name='chggroup']").on('focus',remove_noti);
            
            $("#jqGrid2 input[name='qtydelivered']").keydown(function (e){ // when click tab at qtydelivered, auto save
                var code = e.keyCode || e.which;
                if(code == '9'){
                    delay(function (){
                        $('#jqGrid2_ilsave').click();
                        addmore_jqgrid2.state = true;
                    }, 500);
                }
            });
        },
        aftersavefunc: function (rowid, response, options){
            $('#SL_amount').val(response.responseText);
            // if(addmore_jqgrid2.state == true)addmore_jqgrid2.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid2',urlParam2,'add');
            $("#jqGridPager2EditAll,#jqGridPager2Delete").show();
            errorField.length = 0;
            calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
        },
        errorfunc: function (rowid,response){
            alert(response.responseText);
            refreshGrid('#jqGrid2',urlParam2,'add');
            $("#jqGridPager2Delete").show();
        },
        beforeSaveRow: function (options, rowid){
            if(errorField.length>0)return false;
            mycurrency2.formatOff();
            mycurrency_np.formatOff();
            
            if(parseInt($('#jqGrid2 input[name="quantity"]').val()) <= 0)return false;
            
            if(myfail_msg.fail_msg_array.length > 0){
                return false;
            }
            
            let editurl = "./Quotation_SO_Detail/form?"+
                $.param({
                    action: 'quotation_detail_save',
                    source: 'SL',
                    trantype: 'RECNO',
                    auditno: $('#SL_auditno').val(),
                    // discamt: $("#jqGrid2 input[name='discamt']").val(),
                });
            $("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            myfail_msg.clear_fail();
            errorField.length = 0;
            // delay(function (){
            //  fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
            // }, 500);
            hideatdialogForm(false);
            calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
	
	/////////////////////////////////////////pager jqgrid2/////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2', {
		add: true, edit: true, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Delete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function (){
			selRowId = $("#jqGrid2").jqGrid('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result){
						if(result == true){
							param = {
								_token: $("#_token").val(),
								source: 'SL',
								trantype: 'RECNO',
								auditno: $('#SL_auditno').val(),
								idno: selrowData('#jqGrid2').idno
							}
							$.post("./Quotation_SO_Detail/form?"+$.param(param),{oper:'del'}, function (data){
							},'json').fail(function (data){
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data){
								$('#SL_amount').val(data.totalAmount);
								refreshGrid("#jqGrid2", urlParam2);
							});
						}else{
							$("#jqGridPager2EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2EditAll",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-th-list",
		title: "Edit All Row",
		onClickButton: function (){
			errorField.length = 0;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for(var i = 0; i < ids.length; i++){
                
                let rowdata = $("#jqGrid2").jqGrid ('getRowData', ids[i]);
				$("#jqGrid2").jqGrid('editRow',ids[i]);
				
				// Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amtdisc","#"+ids[i]+"_unitprice","#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst", "#"+ids[i]+"_totamount"]);
				
				// Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_quantity"]);
				
				dialog_chggroup.id_optid = ids[i];
				dialog_chggroup.check(errorField,ids[i]+"_chggroup","jqGrid2",null,
					function (self){
						if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
                        self.urlParam.chgcode = rowdata.chggroup_ori;
                        self.urlParam.uom = rowdata.uom_ori;
					},function (self){
						// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
					}
				);
				
				dialog_uomcode.id_optid = ids[i];
				dialog_uomcode.check(errorField,ids[i]+"_uom","jqGrid2",null,
					function (self){
						if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
                        self.urlParam.chgcode = rowdata.chggroup_ori;
                        self.urlParam.uom = rowdata.uom_ori;
					},function (self){
						// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
					}
				);
				
				// dialog_uom_recv.id_optid = ids[i];
				// dialog_uom_recv.check(errorField,ids[i]+"_uom_recv","jqGrid2",null,
				// 	function (self){
				// 		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
				// 	},function (self){
				// 		// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
				// 	}
				// );
				
				dialog_tax.id_optid = ids[i];
				dialog_tax.check(errorField,ids[i]+"_taxcode","jqGrid2",null,
					function (self){
						if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
					},function (self){
						// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
					}
				);
				
				cari_gstpercent(ids[i]);
			}
		    onall_editfunc();
			hideatdialogForm(true,'saveallrow');
		    $("#jqGrid2 input#1_pricecode").focus();
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2SaveAll",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-download-alt",
		title: "Save All Row",
		onClickButton: function (){
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			
			var jqgrid2_data = [];
			mycurrency2.formatOff();
			mycurrency_np.formatOff();
			
			if(errorField.length > 0){
				console.log(errorField)
				return false;
			}
			
			for(var i = 0; i < ids.length; i++){
				if(parseInt($('#'+ids[i]+"_quantity").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid2",data);
				if(retval[0] != true){
					alert(retval[1]);
					return false;
				}
				
				let rowid = ids[i];
				var st_idno = $("#jqGrid2 #"+rowid+"_chggroup").data('st_idno');
				var invflag = $("#jqGrid2 #"+rowid+"_chggroup").data('invflag');
				var pt_idno = $("#jqGrid2 #"+rowid+"_chggroup").data('pt_idno');
				var qty = parseFloat($("#jqGrid2 #"+rowid+"_quantity").val());
				var qtydelivered = parseFloat($("#jqGrid2 #"+rowid+"_qtydelivered").val());
				
				if(myfail_msg.fail_msg_array.length > 0){
					return false;
				}
				
				var obj = 
				{
					'lineno_' : data.lineno_,
					'rowno' : data.rowno,
					'pricecode' : $("#jqGrid2 input#"+ids[i]+"_pricecode").val(),
					'chggroup' : $("#jqGrid2 input#"+ids[i]+"_chggroup").val(),
					'uom' : $("#jqGrid2 input#"+ids[i]+"_uom").val(),
					'uom_recv' : $("#jqGrid2 input#"+ids[i]+"_uom_recv").val(),
					'quantity' : $('#'+ids[i]+"_quantity").val(),
					'qtydelivered' : $('#'+ids[i]+"_qtydelivered").val(),
					'unitprice': $('#'+ids[i]+"_unitprice").val(),
					'taxcode' : $("#jqGrid2 input#"+ids[i]+"_taxcode").val(),
					'perdisc' : $('#'+ids[i]+"_perdisc").val(),
					'discamt' : $('#'+ids[i]+"_discamt").val(),
					'tot_gst' : $('#'+ids[i]+"_tot_gst").val(),
					'amount' : $('#'+ids[i]+"_amount").val(),
					'totamount' : $('#'+ids[i]+"_totamount").val(),
					'taxamt' : $("#"+ids[i]+"_taxamt").val(),
					// 'billtypeperct' : $('#'+ids[i]+"_billtypeperct").val(),
					// 'billtypeamt' : $("#"+ids[i]+"_billtypeamt").val(),
				}
				
				jqgrid2_data.push(obj);
			}
			
			var param = {
				_token: $("#_token").val(),
				source: 'SL',
				trantype: 'RECNO',
				auditno: $('#SL_auditno').val(),
			}
			
			$.post("./Quotation_SO_Detail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function (data){
			},'json').fail(function (data){
				// alert(data.responseText);
                myfail_msg.add_fail({
                    id:'after_save',
                    textfld:"",
                    msg:data.responseText,
                });
			}).done(function (data){
				$('#SL_amount').val(data.totalAmount);
				mycurrency.formatOn();
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2);
			});
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2CancelAll",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-remove-circle",
		title: "Cancel",
		onClickButton: function (){
			hideatdialogForm(false);
			refreshGrid("#jqGrid2",urlParam2);
		},				
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveHeaderLabel",
		caption: "Header", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Header"
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveDetailLabel",
		caption: "Detail", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Detail"
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'chggroup':field=['chgcode','description'];table="hisdb.chgmast";case_='chggroup';break;
			case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
			case 'uom_recv':field=['uomcode','description'];table="material.uom";case_='uom';break;
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
			case 'SL_deptcode':field=['deptcode','description'];table="sysdb.department";case_='SL_deptcode';break;
			case 'SL_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='SL_debtorcode';break;
			// case 'SL_mrn':field=['debtorcode','name'];table="debtor.debtormast";case_='SL_mrn';break;
			case 'SL_mrn':field=['NewMrn','Name'];table="hisdb.pat_mast";case_='SL_mrn';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('Quotation_SO',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		return cellvalue;
	}



	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			case 'Item Code': temp = $("#jqGrid2 input[name='chggroup']"); break;
			case 'UOM Code': temp = $("#jqGrid2 input[name='uom']"); break;
			case 'PO UOM': 
				temp = $("#jqGrid2 input[name='pouom']"); 
				var text = $( temp ).parent().siblings( ".help-block" ).text();
				if(text == 'Invalid Code'){
					return [false,"Please enter valid "+name+" value"];
				}

				break;
			case 'Price Code': temp = $("#jqGrid2 input[name='pricecode']"); break;
			case 'Tax Code': temp = $("#jqGrid2 input[name='taxcode']"); break;
			case 'Quantity Request': temp = $("#jqGrid2 input[name='quantity']");break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];

	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="chggroup" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	

		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span><span><input id="`+opt.id+`_discamt" name="discamt" type="hidden"></span><span><input id="`+opt.id+`_convfactor_uom" name="convfactor_uom" type="hidden"></span><span><input id="`+opt.id+`_convfactor_uom_recv" name="convfactor_uom_recv" type="hidden"></span><span><input id="`+opt.id+`_uom_rate" name="rate" type="hidden"></span>`);
	}
	function uom_recvCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
	}
	function taxcodeCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
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
	$("#saveDetailLabel").click(function (){
		$("#saveDetailLabel").attr('disabled',true)
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		dialog_deptcode.off();
		dialog_billtypeSO.off();
		dialog_mrn.off();
		dialog_CustomerSO.off();
		dialog_doctor.off();

		// errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			mycurrency.formatOn();
			unsaved = false;
		}else{
			mycurrency.formatOn();
			dialog_deptcode.on();
			dialog_billtypeSO.on();
			dialog_CustomerSO.on();
			dialog_doctor.on();
			dialog_mrn.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function (){
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_deptcode.on();
		dialog_billtypeSO.on();
		dialog_CustomerSO.on();
		dialog_doctor.on();
		dialog_mrn.on();


		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	$("#save").click(function(){
		unsaved = false;
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader("#formdata", oper,saveParam,{idno:$('#SL_idno').val()},'refreshGrid');
			unsaved = false;
			$("#dialogForm").dialog('close');
		}else{
			mycurrency.formatOn();
		}
	});
	/////////////calculate conv fac//////////////////////////////////

	function remove_noti(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		remove_error("#jqGrid2 #"+id_optid+"_pouom");
		remove_error("#jqGrid2 #"+id_optid+"_quantity");
		delay(function(){
			remove_error("#jqGrid2 #"+id_optid+"_pouom");
		}, 500 );

		$(".noti").empty();

	}

	/////////////////////////////edit all//////////////////////////////////////////////////

	function onall_editfunc(){
    	// $("#jqGrid2 input[name='chggroup'],#jqGrid2 input[name='uom'],#jqGrid2 input[name='taxcode']").attr('readonly','readonly');

		errorField.length=0;
		dialog_chggroup.off();
        $(dialog_chggroup.textfield).prop('readonly',true);
		dialog_uomcode.off();
        $(dialog_uomcode.textfield).prop('readonly',true);
		// dialog_uom_recv.off();
        // $(dialog_uom_recv.textfield).prop('readonly',true);
        dialog_tax.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor
        mycurrency2.array.length = 0;
        mycurrency_np.array.length = 0;
		
		$("#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='quantity']").on('blur',{currency: [mycurrency2,mycurrency_np],alledit:'true'},calculate_line_totgst_and_totamt);

		$("#jqGrid2 input[name='uom'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='pricecode'],#jqGrid2 input[name='chggroup']").on('focus',remove_noti);
	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	var mycurrency2 =new currencymode([]);
	var mycurrency_np =new currencymode([],true);
	function calculate_line_totgst_and_totamt(event) {

		event.data.currency.forEach(function(element){
			element.formatOff();
		});

        let alledit = event.data.alledit;
        console.log(alledit);
		// mycurrency_np.formatOff();

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
       
		let quantity = parseFloat($("#"+id_optid+"_quantity").val());

		if(quantity<=0 || quantity==''){
			myfail_msg.add_fail({
				id:'quantity',
				textfld:"#jqGrid2 #"+id_optid+"_quantity",
				msg:"Quantity Request must be greater than 0",
			});
		}else{
			myfail_msg.del_fail({
				id:'quantity',
				textfld:"#jqGrid2 #"+id_optid+"_quantity",
				msg:"Quantity Request must be greater than 0",
			});
		}

		let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
		let st_idno = $("#jqGrid2 #"+id_optid+"_chggroup").data('st_idno');

        // if(alledit != 'true'){
        //     if(qtyonhand<quantity && st_idno!=''){
        //         myfail_msg.add_fail({
        //             id:'qtyonhand',
        //             textfld:"#jqGrid2 #"+id_optid+"_quantity",
        //             msg:"Quantity Request must be greater than quantity on hand",
        //         });
        //     }else{
        //         myfail_msg.del_fail({
        //             id:'qtyonhand',
        //             textfld:"#jqGrid2 #"+id_optid+"_quantity",
        //             msg:"Quantity Request must be greater than quantity on hand",
        //         });
        //     }
        // }

		let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
		let billtypeperct = 100 - parseFloat($("#"+id_optid+"_billtypeperct").val());
		let billtypeamt = parseFloat($("#"+id_optid+"_billtypeamt").val());
		let rate =  parseFloat($("#"+id_optid+"_uom_rate").val());
		if(isNaN(rate)){
			rate = 0;
		}


		var amount = (unitprice*quantity);
		var discamt = ((unitprice*quantity) * billtypeperct / 100) + billtypeamt;

		let taxamt = amount * rate / 100;

		var totamount = amount - discamt + taxamt;

		$("#"+id_optid+"_taxamt").val(taxamt);
		$("#"+id_optid+"_discamt").val(discamt);
		$("#"+id_optid+"_totamount").val(totamount);
		$("#"+id_optid+"_amount").val(amount);
		
		var id="#jqGrid2 #"+id_optid+"_quantity";
		var name = "quantityrequest";
		var fail_msg = "Quantity Request must be greater than 0";

		event.data.currency.forEach(function(element){
			element.formatOn();
		});
		// event.data.currency.formatOn();//change format to currency on each calculation
		// mycurrency.formatOn();
		// mycurrency_np.formatOn();

		// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

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
			data.rows.forEach(function(element){
				if(element.callback_param != null){//ini baru
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2]
						)
					});
				}
			});

			setjqgridHeight(data,'jqGrid3');
			calc_jq_height_onchange("jqGrid3");
		},
	
		gridComplete: function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'))
				$("#remarks2").data('grid',$(this).data('grid'))
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();

			//calculate_quantity_outstanding('#jqGrid3');
		},
	});
	// fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	$("#jqGrid3").jqGrid("setFrozenColumns");
	jqgrid_label_align_right("#jqGrid3");


	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_deptcode = new ordialog(
		'SL_deptcode', 'sysdb.department', '#SL_deptcode', errorField,
		{
			colModel: [
				{ label: 'Sector Code', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
			],
			urlParam: {
				filterCol:['compcode','recstatus','chgdept','storedept'],
				filterVal:['session.compcode','ACTIVE','1','1']
			},
			ondblClickRow: function (event) {
				$('#SL_debtorcode').focus();

				let data=selrowData('#'+dialog_deptcode.gridname);
				
				sequence.set(data['deptcode']).get();
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
		}, {
			title: "Select Unit",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['recstatus', 'compcode','chgdept','storedept'];
				dialog_deptcode.urlParam.filterVal=['ACTIVE', 'session.compcode','1','1'];
			},
			close: function(obj_){
				$("#SL_debtorcode").focus().select();
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog();

	// var dialog_CustomerSO = new ordialog(
	// 	'customer', 'debtor.debtormast', '#SL_debtorcode', errorField,
	// 	{
	// 		colModel: [
	// 			{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
	// 			{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
	// 			{ label: 'Status', name: 'recstatus', width: 200, classes: 'pointer' },
	// 		],
	// 		urlParam: {
	// 			url: "./Quotation_SO/table",
	// 			action: 'get_debtorMaster',
	// 			filterCol: [],
	// 			filterVal: []
	// 		},
	// 		ondblClickRow: function (){
	// 			$('#SL_hdrtype').focus();
	// 		},
	// 		loadComplete: function (data,obj){
	// 			// var rows = data.rows;
	// 			// var gridname = '#'+obj.gridname; // #othergrid_customer
				
	// 			// if(rows.length > 1 && obj.ontabbing){
	// 				// rows.forEach(function(e,i){
	// 				// 	if(e.recstatus == 'ACTIVE'){
	// 				// 		// let id = parseInt(i)+1;
	// 				// 		// $(gridname+' tr#'+id).val('');
	// 				// 		// $(gridname+'_recstatus').val('');
	// 				// 	}
	// 				// });
	// 			// }
	// 		},
	// 		gridComplete: function (obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 				$('#'+obj.dialogname).dialog('close');
	// 			}
	// 		}
	// 	}, {
	// 		title: "Select Customer",
	// 		open: function (){
	// 			dialog_CustomerSO.urlParam.url = "./Quotation_SO/table";
	// 			dialog_CustomerSO.urlParam.action = 'get_debtorMaster';
	// 			// dialog_CustomerSO.urlParam.table_name = ['debtor.debtormast'];
	// 			// dialog_CustomerSO.urlParam.fixPost = "true";
	// 			// dialog_CustomerSO.urlParam.table_id = "none_";
	// 			dialog_CustomerSO.urlParam.filterCol = [];
	// 			dialog_CustomerSO.urlParam.filterVal = [];
	// 		},
	// 		close: function (obj_){
	// 			$("#SL_hdrtype").focus().select();
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// dialog_CustomerSO.makedialog();
    var dialog_CustomerSO = new ordialog(
        'customer', 'debtor.debtormast', '#SL_debtorcode', errorField,
        {
            colModel: [
                { label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
                { label: 'Bill Type', name: 'billtypeop', width: 100, classes: 'pointer'},
                { label: 'idno', name: 'idno',hidden:true},
                { label: 'name', name: 'name',hidden:true},
                { label: 'debtortype', name: 'debtortype',hidden:true},
                { label: 'address1', name: 'address1',hidden:true},
                { label: 'address2', name: 'address2',hidden:true},
                { label: 'address3', name: 'address3',hidden:true},
                { label: 'postcode', name: 'postcode',hidden:true},
            ],
            urlParam: {
                filterCol:['compcode','recstatus'],
                filterVal:['session.compcode','ACTIVE']
            },
            ondblClickRow: function () {
                let data = selrowData('#' + dialog_CustomerSO.gridname);
                $('#SL_hdrtype').val(data['billtypeop']);
                dialog_billtypeSO.check(errorField);
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
        }, {
            title: "Select Customer",
            open: function(){
                dialog_CustomerSO.urlParam.filterCol=['recstatus', 'compcode'];
                dialog_CustomerSO.urlParam.filterVal=['ACTIVE', 'session.compcode'];
            },
            close: function(obj_){
                $("#SL_hdrtype").focus().select();
            }
        },'urlParam','radio','tab'
    );
    dialog_CustomerSO.makedialog();
    $('#otherdialog_customer').append(`<p><button type='button' class='btn btn-default btn-sm' id='new_customer' style='float:right'>New Customer</button> <button type='button' class='btn btn-default btn-sm' id='edit_customer' style='float:right;margin-right: 10px'>Edit Customer</button></p>`);

	var dialog_billtypeSO = new ordialog(
		'billtype', 'hisdb.billtymst', '#SL_hdrtype', errorField,
		{
			colModel: [
				{ label: 'Bill type', name: 'billtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label: 'Effective Date<br/>From', name: 'effdatefrom',formatter: dateFormatter, unformat: dateUNFormatter, width: 150, classes: 'pointer' },
				{ label: 'Effective Date<br/>To', name: 'effdateto',formatter: dateFormatter, unformat: dateUNFormatter, width: 150, classes: 'pointer' },
			],
			urlParam: {
				url:"./Quotation_SO/table",
				action: 'get_hdrtype',
				url_chk: "./Quotation_SO/table",
				action_chk: "get_hdrtype_check",
				filterCol:[],
				filterVal:[]
			},
			// urlParam: {
			// 	filterCol:['compcode','recstatus','opprice'],
			// 	filterVal:['session.compcode','ACTIVE','1']
			// },
			ondblClickRow: function () {
				// $('#SL_mrn').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#SL_mrn').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Billtype",
			open: function(){
				dialog_billtypeSO.urlParam.url = "./Quotation_SO/table";
				dialog_billtypeSO.urlParam.action = 'get_hdrtype';
				dialog_billtypeSO.urlParam.url_chk = "./Quotation_SO/table";
				dialog_billtypeSO.urlParam.action_chk = "get_hdrtype_check";
				dialog_billtypeSO.urlParam.filterCol = [];
				dialog_billtypeSO.urlParam.filterVal = [];
			},
			close: function(obj_){
				$("#SL_podate").focus().select();
			}
		},'urlParam','radio','tab'
	);
	dialog_billtypeSO.makedialog();

    var dialog_mrn = new ordialog(
        'dialog_mrn', 'hisdb.pat_mast', '#SL_mrn', 'errorField',
        {
            colModel: [
                { label: 'HUKM MRN', name: 'NewMrn', width: 200, classes: 'pointer', canSearch: true},
                { label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
                { label: 'MRN', name: 'MRN', width: 200, classes: 'pointer', canSearch: true, or_search: true , formatter: padzero, unformat: unpadzero },
                { label: 'I/C', name: 'Newic', width: 400, classes: 'pointer', canSearch: true,},
                { label: 'idno', name: 'idno', hidden:true},
                { label: 'newmrn', name: 'newmrn', hidden:true},
                { label: 'address1', name: 'address1', hidden:true},
                { label: 'address2', name: 'address2', hidden:true},
                { label: 'address3', name: 'address3', hidden:true},
                { label: 'postcode', name: 'postcode', hidden:true},
            ],
            urlParam: {
                filterCol:['compcode','ACTIVE'],
                filterVal:['session.compcode','1']
            },
            ondblClickRow: function () {
                $('#SL_doctorcode').focus();
            },
            gridComplete: function(obj){
                var gridname = '#'+obj.gridname;
                if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                    $(gridname+' tr#1').click();
                    $(gridname+' tr#1').dblclick();
                    $('#db_termdays').focus();
                }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                    $('#'+obj.dialogname).dialog('close');
                }
            }
        }, {
            title: "Select MRN",
            open: function(){
                dialog_mrn.urlParam.filterCol=['compcode', 'ACTIVE'];
                dialog_mrn.urlParam.filterVal=['session.compcode', '1'];
            }
        },'none','radio','tab'
    );
    dialog_mrn.makedialog();
    $('#otherdialog_dialog_mrn').append(`<p><button type='button' class='btn btn-default btn-sm' id='new_patient' style='float:right'>New Patient</button> <button type='button' class='btn btn-default btn-sm' id='edit_patient' style='float:right;margin-right: 10px;'>Edit Patient</button></p>`);

    var dialog_doctor = new ordialog(
        'dialog_doctor', 'hisdb.doctor', '#SL_doctorcode', 'errorField',
        {
            colModel: [
                { label: 'Doctor Code', name: 'doctorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Doctor Name', name: 'doctorname', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
            ],
            urlParam: {
                filterCol:['compcode','recstatus'],
                filterVal:['session.compcode','ACTIVE']
            },
            ondblClickRow: function () {
                $('#db_termdays').focus();
            },
            gridComplete: function(obj){
                var gridname = '#'+obj.gridname;
                if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                    $(gridname+' tr#1').click();
                    $(gridname+' tr#1').dblclick();
                    $('#db_termdays').focus();
                }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                    $('#'+obj.dialogname).dialog('close');
                }
            }
        }, {
            title: "Select Doctor",
            open: function(){
                dialog_doctor.urlParam.filterCol=['compcode', 'recstatus'];
                dialog_doctor.urlParam.filterVal=['session.compcode', 'ACTIVE'];
            }
        },'none','radio','tab'
    );
    dialog_doctor.makedialog();

    $("#dialog_new_patient").dialog({
        autoOpen: false,
        width: 7/10 * $(window).width(),
        modal: true,
        open: function( event, ui ) {
            if($("#dialog_new_patient").data('oper') == 'edit'){
                if($('#'+dialog_mrn.gridname).jqGrid ('getGridParam', 'selrow') == null){
                    alert('select patient to edit!');
                    $("#dialog_new_patient").dialog('close');
                }else{
                    let rowdata = selrowData('#'+dialog_mrn.gridname);
                    $('#np_idno').val(rowdata.idno);
                    $('#np_newmrn').val(rowdata.newmrn);
                    $('#np_name').val(rowdata.name);
                    $('#np_newic').val(rowdata.Newic);
                    $('#np_address1').val(rowdata.address1);
                    $('#np_address2').val(rowdata.address2);
                    $('#np_address3').val(rowdata.address3);
                    $('#np_postcode').val(rowdata.postcode);
                }
            }
        },
        close: function( event, ui ) {
            emptyFormdata_div('#formdata_new_patient');
        },
        buttons : [{
            text: "Submit",click: function() {
                    if($('#formdata_new_patient').isValid({requiredFields:''},conf,true)){
                        let oper_ = $("#dialog_new_patient").data('oper');
                        $.post( "./SalesOrder/form?oper=new_patient&oper_="+oper_, $('#formdata_new_patient').serialize(), function( data ) {
                            },'json')
                        .fail(function (data) {
                            alert(data.responseText);
                        }).done(function (data) {
                            if(data.mrn == 'none'){
                                $("#dialog_new_patient").dialog('close');
                                refreshGrid("#"+dialog_mrn.gridname,dialog_mrn.urlParam);
                            }else{
                                emptyFormdata_div('#formdata_new_patient');
                                $('#SL_mrn').val(data.mrn);
                                dialog_mrn.check('errorField');
                                $("#dialog_new_patient").dialog('close');
                                $('#'+dialog_mrn.dialogname).dialog('close');
                            }
                        })
                    }
                }
            },{
            text: "Cancel",click: function() {
                $(this).dialog('close');
            }
        }]
    });

    $('#new_patient').click(function(){
        $("#dialog_new_patient").data('oper','add');
        $("#dialog_new_patient").dialog('open');
    });

    $('#edit_patient').click(function(){
        $("#dialog_new_patient").data('oper','edit');
        $("#dialog_new_patient").dialog('open');
    });

    $("#dialog_new_customer").dialog({
        autoOpen: false,
        width: 7/10 * $(window).width(),
        modal: true,
        open: function( event, ui ) {
            if($("#dialog_new_customer").data('oper') == 'edit'){
                if($('#'+dialog_CustomerSO.gridname).jqGrid ('getGridParam', 'selrow') == null){
                    alert('select customer to edit!');
                    $('#dialog_new_customer').dialog('close');
                }else{
                    let rowdata = selrowData('#'+dialog_CustomerSO.gridname);
                    $('#nc_idno').val(rowdata.idno);
                    $('#nc_name').val(rowdata.name);
                    $('#nc_debtortype').val(rowdata.debtortype);
                    $('#nc_debtorcode').val(rowdata.debtorcode);
                    $('#nc_address1').val(rowdata.address1);
                    $('#nc_address2').val(rowdata.address2);
                    $('#nc_address3').val(rowdata.address3);
                    $('#nc_postcode').val(rowdata.postcode);
                }
            }
        },
        close: function( event, ui ) {
            emptyFormdata_div('#formdata_new_customer');
        },
        buttons : [{
            text: "Submit",click: function() {
                    if($('#formdata_new_customer').isValid({requiredFields:''},conf,true)){
                        let oper_ = $("#dialog_new_customer").data('oper');
                        $.post( "./SalesOrder/form?oper=new_customer&oper_="+oper_, $('#formdata_new_customer').serialize(), function( data ) {
                            },'json')
                        .fail(function (data) {
                            alert(data.responseText);
                        }).done(function (data) {
                            if(data.debtorcode == 'none'){
                                $("#dialog_new_customer").dialog('close');
                                refreshGrid("#"+dialog_CustomerSO.gridname,dialog_CustomerSO.urlParam);
                            }else{
                                emptyFormdata_div('#formdata_new_customer');
                                $('#SL_debtorcode').val(data.debtorcode);
                                dialog_CustomerSO.check(errorField);
                                $('#SL_hdrtype').val('OP');
                                dialog_billtypeSO.check(errorField);
                                $("#dialog_new_customer").dialog('close');
                                $('#'+dialog_CustomerSO.dialogname).dialog('close');
                            }
                        })
                    }
                }
            },{
            text: "Cancel",click: function() {
                $(this).dialog('close');
            }
        }]
    });

    $('#new_customer').click(function(){
        $("#dialog_new_customer").data('oper','add');
        $("#dialog_new_customer").dialog('open');
    });

    $('#edit_customer').click(function(){
        $("#dialog_new_customer").data('oper','edit');
        $("#dialog_new_customer").dialog('open');
    });

	///dialog for itemcode for Quotation Detail//
	var dialog_chggroup = new ordialog(
		'chggroup',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid2 input[name='chggroup']",errorField,
		{	colModel:
			[
				{label: 'Charge Code',name:'chgcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'description',width:220,classes:'pointer',canSearch:true,checked:true,or_search:true},
                {label: 'Generic',name:'generic',width:220,classes:'pointer',canSearch:true},
				{label: 'Inventory',name:'invflag',width:100,formatter:formatterstatus_tick2, unformat:unformatstatus_tick2},
				{label: 'UOM',name:'uom',width:100,classes:'pointer',},
				{label: 'Quantity On Hand',name:'qtyonhand',width:100,classes:'pointer',},
				{label: 'Price',name:'price',width:100,classes:'pointer'},
				{label: 'Tax',name:'taxcode',width:100,classes:'pointer'},
                {label: 'overwrite',name:'overwrite',hidden:true},
				{label: 'rate',name:'rate',hidden:true},
				{label: 'st_idno',name:'st_idno',hidden:true},
				{label: 'pt_idno',name:'pt_idno',hidden:true},
				{label: 'billty_amount',name:'billty_amount',hidden:true},
				{label: 'billty_percent',name:'billty_percent',hidden:true},
				{label: 'convfactor',name:'convfactor',hidden:true},
				
			],
			urlParam: {
					url:"./SalesOrderDetail/table",
					action: 'get_itemcode_price',
					url_chk: './SalesOrderDetail/table',
					action_chk: 'get_itemcode_price_check',
					entrydate : $('#SL_entrydate').val(),
					billtype : $('#SL_hdrtype').val(),
					deptcode : $('#SL_deptcode').val(),
					price : $('#pricebilltype').val(),
					filterCol:[],
					filterVal:[]
				},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				myfail_msg.del_fail({id:'noprod_'+id_optid});
				myfail_msg.del_fail({id:'nostock_'+id_optid});

				let data=selrowData('#'+dialog_chggroup.gridname);

				$("#jqGrid2 #"+id_optid+"_chggroup").data('st_idno',data['st_idno']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('invflag',data['invflag']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('pt_idno',data['pt_idno']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('pt_idno',data['pt_idno']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('convfactor',data['convfactor']);
				$('#'+dialog_chggroup.gridname).data('fail_msg','');

                if(data.overwrite == '1'){
                    $("#jqGrid2 #"+id_optid+"_unitprice").prop('readonly',false);
                }

				if(data.invflag == '1' && data.pt_idno == ''){
					myerrorIt_only2('input#'+id_optid+'_chggroup',true);
					let name = 'noprod_'+id_optid;
					let fail_msg = 'Item not available in product master, please check';
					myfail_msg.add_fail({id:name,msg:fail_msg});
					$('span#'+id_optid+'_chggroup').text('');

					ordialog_buang_error_shj("#jqGrid2 #"+id_optid+"_taxcode",errorField);

					$("#jqGrid2 #"+id_optid+"_taxcode").val('');
					$("#jqGrid2 #"+id_optid+"_uom_rate").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_qtyonhand").val('');
					$("#jqGrid2 #"+id_optid+"_quantity").val('');
					$("#jqGrid2 #"+id_optid+"_uom").val('');
					$("#jqGrid2 #"+id_optid+"_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_unitprice").val('');
					$("#jqGrid2 #"+id_optid+"_billtypeperct").val(data['billty_percent']);
					$("#jqGrid2 #"+id_optid+"_billtypeamt").val(data['billty_amount']);

				}else if(data.invflag == '1' && data.st_idno == ""){
					myerrorIt_only2('input#'+id_optid+'_chggroup',true);
					let name = 'nostock_'+id_optid;
					let fail_msg = 'Item not available in store dept '+$('#SL_deptcode').parent().next('span.help-block').text()+', please check';	
					myfail_msg.add_fail({id:name,msg:fail_msg});
					$('span#'+id_optid+'_chggroup').text('');
					
					ordialog_buang_error_shj("#jqGrid2 #"+id_optid+"_taxcode",errorField);

					$("#jqGrid2 #"+id_optid+"_taxcode").val('');
					$("#jqGrid2 #"+id_optid+"_uom_rate").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_qtyonhand").val('');
					$("#jqGrid2 #"+id_optid+"_quantity").val('');
					$("#jqGrid2 #"+id_optid+"_uom").val('');
					$("#jqGrid2 #"+id_optid+"_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_unitprice").val('');
					$("#jqGrid2 #"+id_optid+"_billtypeperct").val('');
					$("#jqGrid2 #"+id_optid+"_billtypeamt").val('');
				}else{
					myfail_msg.del_fail({id:'noprod_'+id_optid});
					myfail_msg.del_fail({id:'nostock_'+id_optid});

					$("#jqGrid2 #"+id_optid+"_chggroup").val(data['chgcode']);
					$("#jqGrid2 #"+id_optid+"_taxcode").val(data['taxcode']);
					$("#jqGrid2 #"+id_optid+"_uom_rate").val(data['rate']);
					$("#jqGrid2 #"+id_optid+"_convfactor_uom").val(data['convfactor']);
					$("#jqGrid2 #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
					$("#jqGrid2 #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
					$("#jqGrid2 #"+id_optid+"_quantity").val('');
					$("#jqGrid2 #"+id_optid+"_uom").val(data['uom']);
					$("#jqGrid2 #"+id_optid+"_uom_recv").val(data['uom']);
					$("#jqGrid2 #"+id_optid+"_unitprice").val(data['price']);
					$("#jqGrid2 #"+id_optid+"_billtypeperct").val(data['billty_percent']);
					$("#jqGrid2 #"+id_optid+"_billtypeamt").val(data['billty_amount']);

					dialog_chggroup.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_chggroup").val();
					dialog_chggroup.urlParam.uom = $("#jqGrid2 #"+id_optid+"_uom").val();

					dialog_uomcode.urlParam.entrydate = $('#SL_entrydate').val();
					dialog_uomcode.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_chggroup").val();
					dialog_uomcode.urlParam.uom = $("#jqGrid2 #"+id_optid+"_uom").val();
					dialog_uomcode.urlParam.deptcode = $('#SL_deptcode').val();
					dialog_uomcode.urlParam.price = 'PRICE2';
					dialog_uomcode.urlParam.billtype = $('#SL_hdrtype').val();

					dialog_uomcode.check(errorField);
					// dialog_uom_recv.check(errorField);
					dialog_tax.check(errorField);
					mycurrency2.formatOn();
				}

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid2 input[name='quantity']").focus().select();
				}
			},
			loadComplete:function(data){

			}
		},{
			title:"Select Item",
			open:function(obj_){
				let id_optid = obj_.id_optid;

				dialog_chggroup.urlParam.url = "./SalesOrderDetail/table";
				dialog_chggroup.urlParam.action = 'get_itemcode_price';
				dialog_chggroup.urlParam.url_chk = "./SalesOrderDetail/table";
				dialog_chggroup.urlParam.action_chk = "get_itemcode_price_check";
				dialog_chggroup.urlParam.entrydate = $('#SL_entrydate').val();
				dialog_chggroup.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_chggroup").val();
				dialog_chggroup.urlParam.uom = $("#jqGrid2 #"+id_optid+"_uom").val();
				dialog_chggroup.urlParam.billtype = $('#SL_hdrtype').val();
				dialog_chggroup.urlParam.deptcode = $('#SL_deptcode').val();
				dialog_chggroup.urlParam.price = $('#pricebilltype').val();
				dialog_chggroup.urlParam.filterCol = [];
				dialog_chggroup.urlParam.filterVal = [];

			},
			close: function(obj){
				$("#jqGrid2 input[name='quantity']").focus().select();
			}
		},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
	);
	dialog_chggroup.makedialog(false);

	var dialog_uomcode = new ordialog(
		'uom',['material.uom AS u'],"#jqGrid2 input[name='uom']",errorField,
		{	colModel:
			[
				{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'Charge Code',name:'chgcode',hidden:true},
				{label: 'Inventory',name:'invflag',hidden:true},
				{label: 'Quantity On Hand',hidden:true},
				{label: 'Price',name:'price',hidden:true},
				{label: 'Tax',name:'taxcode',hidden:true},
				{label: 'rate',name:'rate',hidden:true},
				{label: 'st_idno',name:'st_idno',hidden:true},
				{label: 'pt_idno',name:'pt_idno',hidden:true},
				{label: 'avgcost',name:'avgcost',hidden:true},
				{label: 'billty_amount',name:'billty_amount',hidden:true},
				{label: 'billty_percent',name:'billty_percent',hidden:true},
				{label: 'convfactor',name:'convfactor',hidden:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE'],
						url:"./Quotation_SO_Detail/table",
						url_chk:"./Quotation_SO_Detail/table",
						action: 'get_itemcode_uom',
						action_chk: 'get_itemcode_uom_check',
						entrydate : $('#SL_entrydate').val(),
						billtype : $('#SL_hdrtype').val(),
						deptcode : $('#SL_deptcode').val(),
						price : 'PRICE2',
						filterCol : [],
						filterVal : [],
					},
			ondblClickRow:function(event){

				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				myfail_msg.del_fail({id:'noprod_'+id_optid});
				myfail_msg.del_fail({id:'nostock_'+id_optid});

				let data=selrowData('#'+dialog_uomcode.gridname);

				$("#jqGrid2 #"+id_optid+"_chggroup").data('st_idno',data['st_idno']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('invflag',data['invflag']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('pt_idno',data['pt_idno']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('pt_idno',data['pt_idno']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('avgcost',data['avgcost']);
				$("#jqGrid2 #"+id_optid+"_chggroup").data('convfactor',data['convfactor']);
				$('#'+dialog_uomcode.gridname).data('fail_msg','');

				if(data.invflag == '1' && (data.pt_idno == '' || data.pt_idno == null)){
					myerrorIt_only2('input#'+id_optid+'_chggroup',true);
					let name = 'noprod_'+id_optid;
					let fail_msg = 'Item not available in product master, please check';
					myfail_msg.add_fail({id:name,msg:fail_msg});
					$('span#'+id_optid+'_chggroup').text('');

					ordialog_buang_error_shj("#jqGrid2 #"+id_optid+"_taxcode",errorField);

					$("#jqGrid2 #"+id_optid+"_taxcode").val('');
					$("#jqGrid2 #"+id_optid+"_uom_rate").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_qtyonhand").val('');
					$("#jqGrid2 #"+id_optid+"_quantity").val('');
					$("#jqGrid2 #"+id_optid+"_uom").val('');
					$("#jqGrid2 #"+id_optid+"_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_unitprce").val('');
					$("#jqGrid2 #"+id_optid+"_cost_price").val('');
					$("#jqGrid2 #"+id_optid+"_billtypeperct").val(data['billty_percent']);
					$("#jqGrid2 #"+id_optid+"_billtypeamt").val(data['billty_amount']);

				}else if(data.invflag == '1' && (data.st_idno == "" || data.st_idno == null)){
					myerrorIt_only2('input#'+id_optid+'_chggroup',true);
					let name = 'nostock_'+id_optid;
					let fail_msg = 'Item not available in store dept '+$('#SL_deptcode').parent().next('span.help-block').text()+', please check';	
					myfail_msg.add_fail({id:name,msg:fail_msg});
					$('span#'+id_optid+'_chggroup').text('');
					
					ordialog_buang_error_shj("#jqGrid2 #"+id_optid+"_taxcode",errorField);

					$("#jqGrid2 #"+id_optid+"_taxcode").val('');
					$("#jqGrid2 #"+id_optid+"_uom_rate").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom").val('');
					$("#jqGrid2 #"+id_optid+"_convfactor_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_qtyonhand").val('');
					$("#jqGrid2 #"+id_optid+"_quantity").val('');
					$("#jqGrid2 #"+id_optid+"_uom").val('');
					$("#jqGrid2 #"+id_optid+"_uom_recv").val('');
					$("#jqGrid2 #"+id_optid+"_unitprce").val('');
					$("#jqGrid2 #"+id_optid+"_cost_price").val('');
					$("#jqGrid2 #"+id_optid+"_billtypeperct").val('');
					$("#jqGrid2 #"+id_optid+"_billtypeamt").val('');
				}else{
					myfail_msg.del_fail({id:'noprod_'+id_optid});
					myfail_msg.del_fail({id:'nostock_'+id_optid});

					$("#jqGrid2 #"+id_optid+"_chggroup").val(data['chgcode']);
					$("#jqGrid2 #"+id_optid+"_taxcode").val(data['taxcode']);
					$("#jqGrid2 #"+id_optid+"_uom_rate").val(data['rate']);
					$("#jqGrid2 #"+id_optid+"_convfactor_uom").val(data['convfactor']);
					$("#jqGrid2 #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
					$("#jqGrid2 #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
					// $("#jqGrid2 #"+id_optid+"_quantity").val('');
					$("#jqGrid2 #"+id_optid+"_uom").val(data['uomcode']);
					$("#jqGrid2 #"+id_optid+"_uom_recv").val(data['uomcode']);
					$("#jqGrid2 #"+id_optid+"_unitprce").val(data['price']);
					$("#jqGrid2 #"+id_optid+"_cost_price").val(data['avgcost']);
					$("#jqGrid2 #"+id_optid+"_billtypeperct").val(data['billty_percent']);
					$("#jqGrid2 #"+id_optid+"_billtypeamt").val(data['billty_amount']);

					dialog_uomcode.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_chggroup").val();
					dialog_uomcode.urlParam.uom = $("#jqGrid2 #"+id_optid+"_uom").val();

					// dialog_uomcode.check(errorField);
					// dialog_uom_recv.check(errorField);
					dialog_tax.check(errorField);
					mycurrency.formatOn();
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid2 input[name='qty']").focus();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
			
		},{
			title:"Select UOM Code For Item",
			open:function(obj_){
				let id_optid = obj_.id_optid;

				dialog_uomcode.urlParam.url = "./Quotation_SO_Detail/table";
				dialog_uomcode.urlParam.action = 'get_itemcode_uom';
				dialog_uomcode.urlParam.url_chk = "./Quotation_SO_Detail/table";
				dialog_uomcode.urlParam.action_chk = "get_itemcode_uom_check";
				dialog_uomcode.urlParam.entrydate = $('#SL_entrydate').val();
				dialog_uomcode.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_chggroup").val();
				dialog_uomcode.urlParam.uom = $("#jqGrid2 #"+id_optid+"_uom").val();
				dialog_uomcode.urlParam.deptcode = $('#SL_deptcode').val();
				dialog_uomcode.urlParam.price = 'PRICE2';
				dialog_uomcode.urlParam.billtype = $('#SL_hdrtype').val();
				dialog_uomcode.urlParam.filterCol = [];
				dialog_uomcode.urlParam.filterVal = [];
			},
			close: function(){
				// $(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
			}
		},'urlParam', 'radio', 'tab' 	
	);
	dialog_uomcode.makedialog(false);

	// var dialog_uomcode = new ordialog(
	// 	'uom',['material.uom AS u'],"#jqGrid2 input[name='uom']",errorField,
	// 	{	colModel:
	// 		[
	// 			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
	// 			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
	// 		],
	// 		urlParam: {
	// 					filterCol:['compcode','recstatus'],
	// 					filterVal:['session.compcode','ACTIVE'],
	// 					url:"./Quotation_SO_Detail/table",
	// 					action: 'get_itemcode_uom',
	// 					entrydate : $('#SL_entrydate').val()
	// 				},
	// 		ondblClickRow:function(event){

	// 			if(event.type == 'keydown'){

	// 				var optid = $(event.currentTarget).get(0).getAttribute("optid");
	// 				var id_optid = optid.substring(0,optid.search("_"));
	// 			}else{

	// 				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
	// 				var id_optid = optid.substring(0,optid.search("_"));
	// 			}

	// 			let data=selrowData('#'+dialog_uomcode.gridname);
	// 			$("#jqGrid2 input#"+id_optid+"_uom").val(data.uomcode);
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				$("#jqGrid2 input[name='qty']").focus();
	// 				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
	// 			}
	// 		}
			
	// 	},{
	// 		title:"Select UOM Code For Item",
	// 		open:function(obj_){
	// 			let id_optid = obj_.id_optid;

	// 			dialog_uomcode.urlParam.url = "./Quotation_SO_Detail/table";
	// 			dialog_uomcode.urlParam.action = 'get_itemcode_uom';
	// 			dialog_uomcode.urlParam.url_chk = "./Quotation_SO_Detail/table";
	// 			dialog_uomcode.urlParam.action_chk = "get_itemcode_uom_check";
	// 			dialog_uomcode.urlParam.filterCol = [];
	// 			dialog_uomcode.urlParam.filterVal = [];
	// 			dialog_uomcode.urlParam.entrydate = $('#SL_entrydate').val();
	// 			dialog_uomcode.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_chggroup").val();
	// 			dialog_uomcode.urlParam.deptcode = $('#SL_deptcode').val();
	// 			dialog_uomcode.urlParam.filterCol=['compcode','recstatus'];
	// 			dialog_uomcode.urlParam.filterVal=['session.compcode','ACTIVE'];
	// 		},
	// 		close: function(){
	// 			// $(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
	// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
	// 			// 	.next()
	// 			// 	.find("input[type=text]").focus();
	// 		}
	// 	},'urlParam', 'radio', 'tab' 	
	// );
	// dialog_uomcode.makedialog(false);

	// var dialog_uom_recv = new ordialog(
	// 	'uom_recv',['material.uom AS u'],"#jqGrid2 input[name='uom_recv']",errorField,
	// 	{	colModel:
	// 		[
	// 			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
	// 			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
	// 		],
	// 		urlParam: {
	// 					filterCol:['compcode','recstatus'],
	// 					filterVal:['session.compcode','ACTIVE']
	// 				},
	// 		ondblClickRow:function(event){

	// 			if(event.type == 'keydown'){

	// 				var optid = $(event.currentTarget).get(0).getAttribute("optid");
	// 				var id_optid = optid.substring(0,optid.search("_"));
	// 			}else{

	// 				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
	// 				var id_optid = optid.substring(0,optid.search("_"));
	// 			}

	// 			let data=selrowData('#'+dialog_uom_recv.gridname);
	// 			$("#jqGrid2 input#"+id_optid+"_uom_recv").val(data.uomcode);
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				$("#jqGrid2 input[name='qty']").focus();
	// 				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
	// 			}
	// 		}
			
	// 	},{
	// 		title:"Select UOM Code For Item",
	// 		open:function(obj_){
	// 			dialog_uom_recv.urlParam.filterCol=['compcode','recstatus'];
	// 			dialog_uom_recv.urlParam.filterVal=['session.compcode','ACTIVE'];
	// 		},
	// 		close: function(){
	// 			// $(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
	// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
	// 			// 	.next()
	// 			// 	.find("input[type=text]").focus();
	// 		}
	// 	},'urlParam', 'radio', 'tab' 	
	// );
	// dialog_uom_recv.makedialog(false);

	var dialog_tax = new ordialog(
		'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
		{	colModel:
			[
				{label:'Tax Code', name:'taxcode', width:200, classes:'pointer', canSearch:true, or_search:true},
				{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
				{label:'Rate', name:'rate', width:100, classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(event){

				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				let data=selrowData('#'+dialog_tax.gridname);
				$("#jqGrid2 #"+id_optid+"_uom_rate").val(data['rate']);
				$("#jqGrid2 input#"+id_optid+"_taxcode").val(data.taxcode);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid2 input[name='taxamt']").focus();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
			
		},{
			title:"Select Tax Code For Item",
			open:function(obj_){

				dialog_tax.urlParam.filterCol=['compcode','recstatus'];
				dialog_tax.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(){
				// $(dialog_tax.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
			}
		},'urlParam', 'radio', 'tab' 	
	);
	dialog_tax.makedialog(false);

	function cari_gstpercent(id){
		let data = $('#jqGrid2').jqGrid ('getRowData', id);
		$("#jqGrid2 #"+id+"_pouom_gstpercent").val(data.rate);
	}

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'SL_idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
		},
		gridComplete: function(){
			
		},
	})
	jqgrid_label_align_right("#jqGrid_selection");
	cbselect.on();

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	////////////////////////////////////Pager Hide//////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	//$("#pg_jqGridPager3 table").hide();

	function if_cancel_hide(){
		if(selrowData('#jqGrid').SL_recstatus.trim().toUpperCase() == 'CANCELLED'){
			$('#jqGrid3_panel').collapse('hide');
			$('#ifcancel_show').text(' - CANCELLED');
			$('#panel_jqGrid3').attr('data-target','-');
		}else{
			$('#jqGrid3_panel').collapse('show');
			$('#ifcancel_show').text('');
			$('#panel_jqGrid3').attr('data-target','#jqGrid3_panel');
		}
	}

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	function check_cust_rules(grid,data){
		var cust_val =  true;
		Object.keys(data).every(function(v,i){
			cust_val = cust_rules('', $(grid).jqGrid('getGridParam','colNames')[i]);
			if(cust_val[0] == false){
				return false;
			}return true
		});
		return cust_val;
	}
	
});

function populate_form(obj){
	//panel header
	$('#QuoteNo_show').text(obj.SL_quoteno);
	$('#CustName_show').text(obj.dm_name);

	if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
		$('td#glyphicon-plus,td#glyphicon-edit').hide();
	}else{
		$('td#glyphicon-plus,td#glyphicon-edit').show();
	}
}

function empty_form(){
	$('#QuoteNo_show').text('');
	$('#CustName_show').text('');
}

function reset_all_error(){

}

function get_billtype(mycurrency2){
	this.param={
		action:'get_value_default',
		url:"util/get_value_default",
		field: ['*'],
		filterCol:['compcode','billtype'],
		filterVal:['session.compcode',$("#formdata input[name='SL_hdrtype']").val()],
		table_name:'hisdb.billtymst',
		table_id:'idno'
	}

	$.get( this.param.url+"?"+$.param(this.param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				let data_ = data.rows[0];
				var rowids = $('#jqGrid2').jqGrid('getDataIDs');
				// rowids.forEach(function(e,i){
				// 	$('#jqGrid2').jqGrid('setRowData', e, {billtypeamt:data_.amount,billtypeperct:data_.percent_});

				// });

				$('#pricebilltype').val(data_.price);
				$("#jqGrid2 input[name='billtypeperct']").val(data_.percent_);
				$("#jqGrid2 input[name='billtypeamt']").val(data_.amount);
				mycurrency2.formatOn();
			}
		});
}

function formatterstatus_tick2(cellvalue, option, rowObject) {
	if (cellvalue == '1') {
		return `<span class="fa fa-check"></span>`;
	}else{
		return '';
	}
}


function unformatstatus_tick2(cellvalue, option, rowObject) {
	if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
		return '1';
	}else{
		return '0';
	}
}

function fail_msg_func(fail_msg_div=null){
	this.fail_msg_div = (fail_msg_div!=null)?fail_msg_div:'div#fail_msg';
	this.fail_msg_array=[];
	this.add_fail=function(fail_msg){
		let found=false;
		this.fail_msg_array.forEach(function(e,i){
			if(e.id == fail_msg.id){
				e.msg=fail_msg.msg;
				found=true;
			}
		});
		if(!found){
			this.fail_msg_array.push(fail_msg);
		}
		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.pop_fail();
	}
	this.del_fail=function(fail_msg){
		var new_msg_array = this.fail_msg_array.filter(function(e,i){
			if(e.id == fail_msg.id){
				return false;
			}
			return true;
		});

		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.fail_msg_array = new_msg_array;
		this.pop_fail();
	}
	this.clear_fail=function(){
		this.fail_msg_array=[];
		this.pop_fail();
	}
	this.pop_fail=function(){
		var self=this;
		$(self.fail_msg_div).html('');
		this.fail_msg_array.forEach(function(e,i){
			$(self.fail_msg_div).append("<li>"+e.msg+"</li>");
		});
	}
}