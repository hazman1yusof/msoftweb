$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

    var DataTable = $('#job_queue').DataTable({
        ajax: './APAgeingDtl_Report/table?action=job_queue',
        pageLength: 10,
        orderMulti: false,
        responsive: true,
        scrollY: 500,
        processing: true,
        serverSide: true,
        paging: true,
        columns: [
            { data: 'idno' ,visible:false,orderable: false},
            { data: 'compcode' ,visible:false,orderable: false},
            { data: 'page' ,visible:false,orderable: false},
            { data: 'filename',orderable: false},
            { data: 'process' ,visible:false,orderable: false},
            { data: 'status' ,orderable: false},
            { data: 'adduser',orderable: false},
            { data: 'adddate' ,orderable: false},
            { data: 'finishdate',orderable: false},
            { data: 'remarks',orderable: false,visible:false},
            { data: 'download',orderable: false},
        ],
        columnDefs: [
            {targets: 10,
                createdCell: function (td, cellData, rowData, row, col) {
                    if(rowData.status == 'DONE'){
                        $(td).append(`<a class='btn btn-sm btn-default' target="_blank" href='./APAgeingDtl_Report/table?action=download&idno=`+rowData.idno+`'><i class='fa fa-download'></i></span>`);
                    }
                }
            },
        ],
        drawCallback: function( settings ) {
            $('#job_queue_filter > label').hide();
            if(!$('#refresh_dtable').length){
                $('#job_queue_filter').append(`<button id='refresh_dtable'><i class='fa fa-refresh'></i></button>`);
                $('#refresh_dtable').click(function(){
                    DataTable.ajax.reload();
                });
            }
        }
    }).on('preXhr.dt', function ( e, settings, data ) {
    }).on('xhr.dt', function ( e, settings, json, xhr ) {
    });

    $("#genreport input[name='suppcode_from']").change(function(){
		$("#genreportpdf input[name='suppcode_from']").val($(this).val());
	});
	$("#genreport input[name='suppcode_to']").change(function(){
		$("#genreportpdf input[name='suppcode_to']").val($(this).val());
	});
	$("#genreport input[name='date_ag']").change(function(){
		$("#genreportpdf input[name='date_ag']").val($(this).val());
	});

	$('#excelgen1').click(function(){
		$('#excelgen1').attr('disabled',true);
        let href = './APAgeingDtl_Report/showExcel?type='+$('#type').val()+'&suppcode_from='+$('#suppcode_from').val()+'&suppcode_to='+$("#suppcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val();

        $.get( href, function( data ) {
        }).fail(function(data) {
        }).success(function(data){
			$('#excelgen1').attr('disabled',false);
            DataTable.ajax.reload();
        });

        delay(function(){
            DataTable.ajax.reload();
        }, 4000 ); 
	});

    /////////////////////////////////////dialog handler///////////////////////////////
	var suppcode_from = new ordialog(
		'suppcode_from','material.supplier','#suppcode_from','errorField',
		{	
			colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Supplier Name',name:'name',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
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
			title:"Select Creditor",
			open: function(){
				suppcode_from.urlParam.filterCol=['compcode','recstatus'];
				suppcode_from.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('suppcode_to',errorField)!==-1){
						errorField.splice($.inArray('suppcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	suppcode_from.makedialog(true);

	var suppcode_to = new ordialog(
		'suppcode_to','material.supplier','#suppcode_to','errorField',
		{	
			colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Supplier Name',name:'name',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
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
			title:"Select Creditor",
			open: function(){
				suppcode_to.urlParam.filterCol=['compcode','recstatus'];
				suppcode_to.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('suppcode_to',errorField)!==-1){
						errorField.splice($.inArray('suppcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	suppcode_to.makedialog(true);
});