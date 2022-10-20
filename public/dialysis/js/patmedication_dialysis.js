
$(document).ready(function () {
	$('i#own_med_add').popup();

	$('#own_med_add').click(function(){
		if($('#patmedication_tbl').data('addmode') == false && !$('#save_dialysis').is("[disabled]") ){
			$.confirm({
			    title: 'Confirm',
			    content: "Are you sure you want to <span class='error'>add user's own medicine?</error>",
			    buttons: {
			        confirm:{
			        	btnClass: 'btn-blue',
			        	action: function () {

							$('#patmedication_trx_tbl_idno').val('ownmed');
				        	$('#patmedication_trx_tbl tbody tr').removeClass('blue');
							$('#patmedication_tbl').data('addmode',true);
						    patmedication_tbl.row.add({
						        idno : 99999999999,
								chg_code : 'edit',
								chg_desc : 'edit',
								dos_desc : 'edit',
								fre_desc : 'edit',
								quantity : 'edit',
								enteredby : '',
								verifiedby : '',
								status : '',
								ownmed : '0'
						    }).draw(true);

							pop_item_select_patmedication();
				        }

			        },
			        cancel: {
			        	action: function () {
							
				        },
			        }
			    }

			});
		    
	    }
	});
	

});

var patmedication_trx_tbl = $('#patmedication_trx_tbl').DataTable({
	"ordering": false,
	"ajax": "",
	"sDom": "",
	"paging":false,
    "columns": [
        {'data': 'id'},
        {'data': 'mrn'},
        {'data': 'episno'},
        {'data': 'chg_desc', 'width': '100%'},
        {'data': 'chg_code'},
        {'data': 'quantity'},
        {'data': 'ins_code'},
        {'data': 'dos_code'},
        {'data': 'fre_code'},
        {'data': 'ins_desc'},
        {'data': 'dos_desc'},
        {'data': 'fre_desc'}
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11], visible: false},
    ],
    "drawCallback": function( settings ) {
    // 	if($('#toggle_daily').attr('aria-expanded')=='false' && !$('#save_dialysis').is("[disabled]")){
    // 		alert('checking');
    // 		if(patmedication_trx_tbl.rows().count() > 0){
				// $('#complete_dialysis').prop('disabled',true);
    // 		}else{
				// $('#complete_dialysis').prop('disabled',false);
    // 		}
    // 	}
    }
});

$('#patmedication_trx_tbl tbody').on('click', 'tr', function () {
	var data = patmedication_trx_tbl.row( this ).data();
    if(data != undefined && $('#patmedication_tbl').data('addmode') == false && !$('#save_dialysis').is("[disabled]") ){
	    $('#patmedication_trx_tbl tbody tr').removeClass('blue');
	    $(this).addClass('blue');
		$('#patmedication_trx_tbl_idno').val(data.id);
		$('#patmedication_tbl').data('addmode',true);
		$('#patmedication_tbl').data('ownmed',true);
	    patmedication_tbl.row.add({
	        idno : 99999999999,
			chg_code : data.chg_code,
			chg_desc : data.chg_desc,
			dos_desc : data.dos_desc,
			fre_desc : data.fre_desc,
			quantity : data.quantity,
			enteredby : '',
			verifiedby : '',
			status : '',
			ownmed : '0'
	    }).draw(true);
    }
});

var patmedication_tbl = $('#patmedication_tbl').DataTable({
	"ordering": true,
	"ajax": "",
	"sDom": "",
	"paging":false,"autoWidth": false,
    "columns": [
        {'data': 'idno'},
        {'data': 'chg_code'},
        {'data': 'ownmed'},
        {'data': 'chg_desc','width': '20%'},
        {'data': 'dos_desc','width': '8%'},
        {'data': 'fre_desc','width': '8%'},
        {'data': 'quantity','width': '6%'},
        {'data': 'enteredby','width': '12%'},
        {'data': 'verifiedby','width': '13%'},
        {'data': 'status','width': '15%'}
    ],
    order: [[0, 'desc']],
    columnDefs: [
    	// {targets: [8], className: 'text-center' },
    	{targets: [1,2,3,4,5,6,7,8,9], orderable: false },
        {targets: [0,1,2], visible: false},
        {targets: 3,
        	createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == 'edit' ) {
					$(td).html('');
					$(td).append(`<input type="hidden" name="patmedication_chgcode" id="patmedication_chgcode">
									<span id="patmedication_chg_desc" ></span>`);
				}
   			}
   		},{targets: 4,
        	createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == 'edit' ) {
					$(td).html('');
					$(td).append(`<span id="patmedication_dos_desc" ></span>`);
				}
   			}
   		},{targets: 5,
        	createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == 'edit' ) {
					$(td).html('');
					$(td).append(`<span id="patmedication_fre_desc" ></span>`);
				}
   			}
   		},
        {targets: 6,
        	createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == 'edit' ) {
					$(td).html('');
					$(td).append(`<input type="number" min="1" name="patmedication_quantity" id="patmedication_quantity" value="1" class="purplebg" style="width:40px;max-width:150px;">`);
				}
   			}
   		},
        {targets: 7,
        	createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == '' ) {
					$(td).append(`<input type="text" name="patmedication_enteredby" id="patmedication_enteredby" value="`+$('#user_name').val()+`" class="purplebg" style="max-width:90px;width:-webkit-fill-available;" readonly>`);
				}
   			}
   		},
        {targets: 8,
        	createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == '' ) {
					$(td).append(`<div class="ui action input tiny" style="width: -webkit-fill-available;">
						  <input type="text" class='small' name="patmedication_verifiedby" id="patmedication_verifiedby" style="max-width:70px;width:-webkit-fill-available;" class="purplebg" readonly>
						  <button class="ui button tiny" type="button" id="verified_btn_patmedication">Verifiy</button>
						</div>`
					);

					$('#patmedication_tbl').off('click','button#verified_btn_patmedication');
					$('#patmedication_tbl').on('click','button#verified_btn_patmedication', function(){
						emptyFormdata([],'form#verify_form');
				  		$('#verify_btn').off();
				  		$('#verify_btn').on('click',function(){
							if($("form#verify_form").valid()) {
				  				verifyuser_medication();
							}
				  		});
				  		$('#password_mdl').modal('show');
				  		$('body,#password_mdl').addClass('scrolling');
				  		$('#verify_error').hide();
					});
				}
   			}
   		},
        {targets: 9,
        	createdCell: function (td, cellData, rowData, row, col) {
				if(cellData == '') {
					$(td).append(`<button class="ui tiny primary button" id="patmedication_save" type="button" >Save</button>
								  <button class="ui tiny red button" id="patmedication_cancel" type="button" >Cancel</button>
									`);

					$('#patmedication_tbl').off('click','button#patmedication_save');
					$('#patmedication_tbl').on('click','button#patmedication_save', function(){

						if($('#patmedication_trx_tbl_idno').val() == 'ownmed' && $('#patmedication_quantity').val().trim() == ''){
							alert('Entered quantity value');
						}else if($('#patmedication_enteredby').val().trim() == '' || $('#patmedication_verifiedby').val().trim() == ''){
							alert('Entered all field before click save');
						}else{
    						$('#patmedication_trx_tbl tbody tr').removeClass('blue');
							$('#patmedication_tbl').data('addmode',false);

							var param = {
								action: 'patmedication_save',
								oper: 'add',
								mrn:$("#mrn").val(),
								episno:$("#episno").val(),
								date:$("#visit_date").val()
							}

							var obj = {
								_token: $("#_token").val(),
								chgtrx_idno: $('#patmedication_trx_tbl_idno').val(),
								verifiedby: $('#patmedication_verifiedby').val().trim()
							}

							if($('#patmedication_trx_tbl_idno').val() == 'ownmed'){
								obj.quantity = $('#patmedication_quantity').val();
								param.oper = 'ownmed';
								obj.chgcode = $('#patmedication_chgcode').val();
							}

							$.post( "./dialysis/form?"+$.param(param),obj, function( data ){
								if(data.success == 'success'){
									load_patmedication($("#mrn").val(),$("#episno").val(),$("#visit_date").val());
									load_patmedication_trx($("#mrn").val(),$("#episno").val(),$("#visit_date").val());
								}
							},'json');
						}
					});

					$('#patmedication_tbl').off('click','button#patmedication_cancel');
					$('#patmedication_tbl').on('click','button#patmedication_cancel', function(){
						$('#patmedication_tbl').data('addmode',false);
						load_patmedication($("#mrn").val(),$("#episno").val(),$("#visit_date").val());
						load_patmedication_trx($("#mrn").val(),$("#episno").val(),$("#visit_date").val());
					});
				}else{
					if(rowData.ownmed == '1' && $('#user_groupid').val().trim().toUpperCase() == 'ADMIN'){
						$(td).html(`<i class="check icon green"></i><a class="circular mini red ui button right floated" onclick="delete_ownmed('`+rowData.idno+`')">Delete</a>`);
					}else{
						$(td).html(`<i class="check icon green"></i>`);
					}
				}
   			}
   		},
    ],
    drawCallback: function( settings ) {

    }
});

function delete_ownmed(idno){
	if($('#patmedication_tbl').data('addmode') == false && !$('#save_dialysis').is("[disabled]") ){
		$.confirm({
		    title: 'Confirm',
		    content: "Are you sure you want to delete this item?",
		    buttons: {
		        confirm:{
		        	btnClass: 'btn-blue',
		        	action: function () {

						var param = {
							action: 'delete_ownmed',
							_token: $("#_token").val(),
							mrn: $("#mrn").val(),
							episno: $("#episno").val(),
							dialysis_episode_idno: $('#dialysis_episode_idno').val(),
							idno: idno
						}

						$.post( "./dialysis/form",param, function( data ){
						},'json').fail(function(data) {
				            alert(data.responseText);
				        }).done(function(data){
							load_patmedication($("#mrn").val(),$("#episno").val(),$("#visit_date").val());
				        });

			        }

		        },
		        cancel: {
		        	action: function () {
						
			        },
		        }
		    }

		});
	}
		
}

function load_patmedication_trx(mrn,episno,date){
	patmedicationParam={
		action:'get_table_patmedication_trx',
		mrn:mrn,
		episno:episno,
		date:date
	}

	$('#patmedication_tbl').data('addmode',false);
	patmedication_trx_tbl.ajax.async = false;
	patmedication_trx_tbl.ajax.url( "./dialysis/table?"+$.param(patmedicationParam) ).load(function(data){
		if(patmedication_trx_tbl.rows().count() > 0 && !$('#save_dialysis').is("[disabled]")){
			$('#complete_dialysis').prop('disabled',true);
		}else if(!$('#save_dialysis').is("[disabled]")){
			$('#complete_dialysis').prop('disabled',false);
		}
	});
} 

function load_patmedication(mrn,episno,date){
	patmedicationParam={
		action:'get_table_patmedication',
		mrn:mrn,
		episno:episno,
		date:date
	}

	patmedication_tbl.ajax.async = false;
	patmedication_tbl.ajax.url( "./dialysis/table?"+$.param(patmedicationParam) ).load();
} 

function verifyuser_medication(){
	var param={
		action:'verifyuser',
		username:$('#username_verify').val(),
		password:$('#password_verify').val(),
    };

    $.get( "./verifyuser_dialysis?"+$.param(param), function( data ) {

    },'json').done(function(data) {
    	if(data.success == 'fail'){
  			$('#verify_error').show();
    	}else{
    		$('#patmedication_verifiedby').val($('#username_verify').val());
  			$('#verify_error').hide();
  			$('#password_mdl').modal('hide');
    	}
    }).fail(function(data){
        alert('error verify');
    });
}


function pop_item_select_patmedication(){ 
    var selecter = null;
    var title="Item selector";
        
    var act = "get_ownmed";

	$('#mdl_item_selector').modal({
		'closable':false,
		onHidden : function(){
	        $('#tbl_item_select').html('');
	        selecter.destroy();
	    },
	}).modal('show');
	$('body,#mdl_item_selector').addClass('scrolling');
    
    selecter = $('#tbl_item_select').DataTable( {
            "ajax": "./dialysis/table?action=" + act,
            "ordering": false,
            "lengthChange": false,
            "info": true,
            "pagingType" : "numbers",
            "columns": [
                        {'data': 'code'}, 
                        {'data': 'description'},
                        {'data': 'doseqty'},
                        {'data': 'dosecode'},
                        {'data': 'dosecode_'},
                        {'data': 'freqcode'},
                        {'data': 'freqcode_'},
                        {'data': 'instruction'},
                        {'data': 'instruction_'},
                       ],

            "columnDefs": [ {
	            	"width": "20%",
	                "targets": 0,
	                "data": "code",
	                "render": function ( data, type, row, meta ) {
	                    return data;
	                }
	              },{
	                "targets": 2,visible: false,searchable: false,
	              },{
	                "targets": 3,visible: false,searchable: false,
	              },{
	                "targets": 4,visible: false,searchable: false,
	              },{
	                "targets": 5,visible: false,searchable: false,
	              },{
	                "targets": 6,visible: false,searchable: false,
	              },{
	                "targets": 7,visible: false,searchable: false,
	              },{
	                "targets": 8,visible: false,searchable: false,
	              }

            ],

            "initComplete": function(oSettings, json) {
		        delay(function(){
                	$('div.dataTables_filter input', selecter.table().container()).get(0).focus();
	        	}, 10 );
            },
    });


    
    // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
    $('#tbl_item_select tbody').on('click', 'tr', function () {
        item = selecter.row( this ).data();
        console.log(item);
		
		$('input#patmedication_chgcode').val(item["code"]);
		$('span#patmedication_chg_desc').text(item["description"]);
		$('span#patmedication_dos_desc').text(item["dosecode"]);
		$('span#patmedication_fre_desc').text(item["freqcode"]);
		$('input#patmedication_quantity').val(item["doseqty"]);


        // $('input[name='+type+'][optid='+rowid+']').val(item["code"]);
        // $('input[name='+type+'][optid='+rowid+']').parent().next().html(item["description"]);
        // if(type == "chgcode"){
	       //  $('input[name=quantity][optid='+rowid+']').val(item["doseqty"]);

	       //  $('input[name=dosecode][optid='+rowid+']').val(item["dosecode"]);
	       //  $('input[name=dosecode][optid='+rowid+']').parent().next().html(item["dosecode_"]);

	       //  $('input[name=freqcode][optid='+rowid+']').val(item["freqcode"]);
	       //  $('input[name=freqcode][optid='+rowid+']').parent().next().html(item["freqcode_"]);

	       //  $('input[name=inscode][optid='+rowid+']').val(item["instruction"]);
	       //  $('input[name=inscode][optid='+rowid+']').parent().next().html(item["instruction_"]);
        // }
        $('#mdl_item_selector').modal('hide');
    });
}