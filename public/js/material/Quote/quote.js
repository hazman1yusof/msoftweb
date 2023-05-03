var quote_data = null;
var oper = null;
var quotetbl = $('#quote').DataTable( {
	columns: [
		{'data': 'idno', "width": "10%"},
    	{'data': 'compcode'},
    	{'data': 'subject', "width": "90%"},
    	{'data': 'dept'},
	],
    columnDefs: [
		{targets: [1,2,3], orderable: false },
        {targets: [1,3], visible: false},
        {targets: 2,
        	createdCell: function (td, cellData, rowData, row, col) {
        		let excess=0;
        		rowData.all_attach.forEach(function(o,i){
        			if(i<3){
						$(td).prepend(`<a class="ui circular blue2 button right floated all_attach" target="_blank" href="./uploads/`+o.attachment+`">`+o.filename+`</a>`);
        			}else{
        				excess=excess+1;
        			}
				});
				if(excess>0){
					$(td).prepend(`<a class="ui circular blue2 button right floated all_attach" > +`+excess+`</a>`);
				}
   			}
   		}
    ],
    ajax: './quotation/table?action=maintable'
});

$(document).ready(function () {

	$("form#formdata").validate({
		ignore: ['idno'],
		debug: true,
	  	onfocusout: false,
	  	invalidHandler: function(event, validator) {
	  		$(validator.errorList[0].element).focus();
	  	},
	  	errorPlacement: function(error, element) {
	      error.insertAfter(element);
	  }
	});

    $('#add_quote').click(function(){
        oper = 'add';
        show_modal();
    });

    $("#click").on("click",function(){
        $("#file").click();
    });

    $('#file').on("change", function(){
        let filename = $(this).val();
        uploadfile();
    });

    $('#quote tbody').on('click', 'tr', function () {
    	$('#quote tr').removeClass('active');
    	$(this).addClass('active');
    });

    $('#quote tbody').on('dblclick', 'tr', function () {
    	$('#quote tr').removeClass('active');
    	$(this).addClass('active');
        quote_data = quotetbl.row( this ).data();
        oper = 'edit';
        show_modal();
    });
      
});

function show_modal(){
	$('#quote_modal').modal({
		autofocus : false,
	    closable : false,
	    onShow: function(){
	    	if(oper == 'edit'){
	    		pop_form();
	    	}
	    },
	    onHide : function(){
			$('#all_attach').html('');
	    	emptyFormdata([],'form#formdata');
	    	$('#particulars').text('');
	    	$('input').removeClass('error');
	    	$('label.error').remove();
	    },
	    onApprove : function() {
	    	save_hdr();
	    	return false;
	    }
	}).modal('show');
}

function pop_form(){
	$('#idno').val(quote_data.idno);
	$('#subject').val(quote_data.subject);
	$('#particulars').text(quote_data.particulars);
	quote_data.all_attach.forEach(function(o,i){
		$('#all_attach').append(`<a class="ui circular blue2 button all_attach" target="_blank" href="./uploads/`+o.attachment+`">`+o.filename+`</a>`)
	})
}

function uploadfile(){
	var formData = new FormData();
	formData.append('file', $('#file')[0].files[0]);
	formData.append('_token', $("#_token").val());

	if($('#idno').val() != ''){
		formData.append('hdr_idno', $("#idno").val());
	}

	$.ajax({
	  	url: './quotation/form?oper=uploadfile',
		type: 'POST',
		data: formData,
		dataType: 'json', 
		async: false,
		cache: false,
		contentType: false,
		enctype: 'multipart/form-data',
		processData: false,
	}).done(function(msg) {
		make_all_attachment(msg.all_attach);
    	$('#idno').val(msg.hdr_idno);
  	});
}

function make_all_attachment(all_attach){
	$('#all_attach').html('');

	all_attach.forEach(function(o,i){
		$('#all_attach').append(`<a class="ui circular blue2 button all_attach" target="_blank" href="./uploads/`+o.attachment+`">`+o.filename+`</a>`)
	});
}

function save_hdr(){
	if(!$("form#formdata").valid()){
		return false;
	}

	var serializedForm =  $('form#formdata').serialize();

	$.post('./quotation/form?oper=add',serializedForm, function( data ) {
	},'json').fail(function(data) {
		$('#quote_modal').modal('hide');
	}).done(function(data){
		$('#quote_modal').modal('hide');
		quotetbl.ajax.async = false;
		quotetbl.ajax.url("./quotation/table?action=maintable").load();
	});
}