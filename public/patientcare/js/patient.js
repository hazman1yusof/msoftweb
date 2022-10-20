$(document).ready(function() {

    $("#hiddenload").show();

    var table = $('#example').DataTable({
        "columns": [
            { "name": "id" },
            { "name": "username" },
            { "name": "status" },
            { "name": "type" },
            { "name": "email" },
            { "name": "company" },
            { "name": "note", "visible": false }
        ],
    	"select": 'single', 
        "order": [[ 0, 'desc' ]],
    	"createdRow": function( row, data, dataIndex){
            if( data[2] ==  'Inactive'){
                $(row).addClass('negative');
            }
        },
        "initComplete": function(settings, json) {
        } 
    });

    table.on( 'select', function ( e, dt, type, indexes ) {
	    if ( type === 'row' ) {
	        var data = table.rows( indexes ).data()
	        var status = data[0][2];
	        if(status == 'Inactive'){
	        	$('#delete').text("Activate");
	        }else{
	        	$('#delete').text("Deactivate");
	        }
	    }
	});

    $('.ui.modal').modal();

    $('#add').click(function(){
    	$('#add_modal').modal('setting', 'closable', false).modal('show');
    });

    $('#edit').click(function(){
    	$('#edit_modal').modal('setting', 'closable', false).modal('show');
    	let tabledata = table.rows( { selected: true } ).data()[0];

    	$("#form_edit input[name='username']").val(tabledata[1]);
    	$("#form_edit input[name='email']").val(tabledata[4]);
    	$("#form_edit input[name='company']").val(tabledata[5]);
    	$("#form_edit textarea[name='note']").val(tabledata[6]);

    	$("#form_edit").attr('action',"/customer/"+tabledata[0]);
    });

    $('#delete').click(function(){

    	if(table.rows( '.selected' ).any()){
	    	let tabledata = table.rows( { selected: true } ).data()[0];
	    	$("#form_delete").attr('action',"/customer/"+tabledata[0]);
	    	$("#form_delete").submit();

    	}else{
    		alert('please select row');
    	}
    });

	$('#form').form({
      fields: {
        username   : ['minLength[5]', 'empty'],
        password   : ['minLength[5]', 'empty'],
        email   : ['email', 'empty']
      }
    });

    $('#form_edit').form({
      fields: {
        username   : ['minLength[5]', 'empty'],
        email   : ['email', 'empty']
      }
    });

    $("input[name='password']").popup();

    $("#form input[name='username']").blur(function(){
    	$("input[name='password']").val($(this).val());
    });
} );