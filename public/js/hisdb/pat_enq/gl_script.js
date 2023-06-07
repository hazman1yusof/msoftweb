$(document).ready(function () {
	$('#my_a_gtlr').click(function(){
		var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
		if(selrow != null){
			autoinsert_rowdata_gl(rowdata_episodelist[selrow-1]);
		    $('#newgl-textmrn').text($('#mrn_show_episodelist').text());
		    $('#newgl-textname').text($('#name_show_episodelist').text());
			$('#mdl_new_gl').modal('show');
		}else{
			alert('Please select episode first')
		}
	});

	$('#mdl_new_gl').on('hidden.bs.modal', function (e) {
        $("#glform").trigger('reset');
	});

	$('#select_gl_tab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	    let selected_tab = $(e.target).text();
	    $('#newgl-gltype').val(selected_tab);
	    onchg_gltype();
	});
});



function onchg_gltype(){
    let selected_tab = $('#newgl-gltype').val();
    $('#newgl-effdate').off('change');
    $('#newgl-effdate,#newgl-expdate').val('');
    $('#newgl-visitno_div,#newgl-expdate_div,#newgl-effdate_div').show();
    $('#newgl-effdate_div,#newgl-expdate_div,#newgl-visitno_div').removeClass('form-mandatory');

    switch(selected_tab){
        case 'Multi Volume':
            console.log(moment().format('YYYY-MM-DD'));
            $('#newgl-effdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl-effdate,#newgl-visitno').prop('required',true).addClass('form-mandatory');
            $('#newgl-expdate').prop('required',false);
            $('#newgl-expdate_div').hide();
            break;
        case 'Multi Date':
            $('#newgl-effdate,#newgl-expdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl-effdate,#newgl-expdate').prop('required',true).addClass('form-mandatory');
            $('#newgl-visitno').prop('required',false);
            $('#newgl-visitno_div').hide();
            break;
        case 'Open':
            $('#newgl-effdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl-effdate').prop('required',true).addClass('form-mandatory');
            $('#newgl-visitno,#newgl-expdate').prop('required',false);
            $('#newgl-visitno_div,#newgl-expdate_div').hide();
            break;
        case 'Single Use':
            $('#newgl-effdate,#newgl-expdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl-effdate').prop('required',true).addClass('form-mandatory');
            $('#newgl-visitno_div').hide();

            $('#newgl-effdate').on('change',function(){
                $('#newgl-expdate').val($(this).val());
            });

            break;
        case 'Limit Amount':
            $('#newgl-effdate,#newgl-expdate,#newgl-visitno').prop('required',false);
            break;
        case 'Monthly Amount':
            $('#newgl-effdate,#newgl-expdate,#newgl-visitno').prop('required',false);
            break;
    }
}

function autoinsert_rowdata_gl(selrowdata){
	let obj_param = {
       action:'loadgl',
       mrn:selrowdata.mrn,
       episno:selrowdata.episno
   };

    $.get( "pat_enq/table?"+$.param(obj_param), function( data ) {
        
    },'json').done(function(data) {
        if(data.data != null){
		    $('#newgl-staffid').val(data.data.staffid);
		    $('#newgl-name').val(data.data.name);
		    $('#txt_newgl_corpcomp').val(data.data.debtor_name);
		    $('#hid_newgl_corpcomp').val(data.data.debtorcode);
		    $('#txt_newgl_occupcode').val(data.data.occup_desc);
		    $('#hid_newgl_occupcode').val(data.data.occupcode);
		    $('#txt_newgl_relatecode').val(data.data.relate_desc);
		    $('#hid_newgl_relatecode').val(data.data.relatecode);
		    $('#newgl-childno').val(data.data.childno);
		    $('#newgl-gltype').val(data.data.gltype);
		    $('#newgl-effdate').val(data.data.startdate);
		    $('#newgl-expdate').val(data.data.enddate);
		    $('#newgl-visitno').val(data.data.visitno);
		    $('#newgl-case').val(data.data.case);
		    $('#newgl-refno').val(data.data.refno);
		    $('#newgl-ourrefno').val(data.data.ourrefno);
		    $('#newgl-remark').val(data.data.remark);

		    $('#select_gl_tab a[href="'+data.data.gltype+'"]').tab('show');
		    onchg_gltype();
        }
    });

}