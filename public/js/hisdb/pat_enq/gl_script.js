$(document).ready(function () {
	$('#my_a_gtlr').click(function(){
		var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
		if(selrow != null){
			autoinsert_rowdata_gl_epno(rowdata_episodelist[selrow-1]);
		    $('#newgl_epno-textmrn').text($('#mrn_show_episodelist').text());
		    $('#newgl_epno-textname').text($('#name_show_episodelist').text());
			$('#mdl_new_gl_epno').modal('show');
		}else{
			alert('Please select episode first')
		}
	});

	$('#mdl_new_gl_epno').on('hidden.bs.modal', function (e) {
        $("#glform").trigger('reset');
	});

	$('#select_gl_tab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	    let selected_tab = $(e.target).text();
	    $('#newgl_epno-gltype').val(selected_tab);
	    onchg_gltype();
	});
});



function onchg_gltype(){
    let selected_tab = $('#newgl_epno-gltype').val();
    $('#newgl_epno-effdate').off('change');
    $('#newgl_epno-effdate,#newgl_epno-expdate').val('');
    $('#newgl_epno-visitno_div,#newgl_epno-expdate_div,#newgl_epno-effdate_div').show();
    $('#newgl_epno-effdate_div,#newgl_epno-expdate_div,#newgl_epno-visitno_div').removeClass('form-mandatory');

    switch(selected_tab){
        case 'Multi Volume':
            console.log(moment().format('YYYY-MM-DD'));
            $('#newgl_epno-effdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl_epno-effdate,#newgl_epno-visitno').prop('required',true).addClass('form-mandatory');
            $('#newgl_epno-expdate').prop('required',false);
            $('#newgl_epno-expdate_div').hide();
            break;
        case 'Multi Date':
            $('#newgl_epno-effdate,#newgl_epno-expdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl_epno-effdate,#newgl_epno-expdate').prop('required',true).addClass('form-mandatory');
            $('#newgl_epno-visitno').prop('required',false);
            $('#newgl_epno-visitno_div').hide();
            break;
        case 'Open':
            $('#newgl_epno-effdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl_epno-effdate').prop('required',true).addClass('form-mandatory');
            $('#newgl_epno-visitno,#newgl_epno-expdate').prop('required',false);
            $('#newgl_epno-visitno_div,#newgl_epno-expdate_div').hide();
            break;
        case 'Single Use':
            $('#newgl_epno-effdate,#newgl_epno-expdate').val(moment().format('YYYY-MM-DD'));
            $('#newgl_epno-effdate').prop('required',true).addClass('form-mandatory');
            $('#newgl_epno-visitno_div').hide();

            $('#newgl_epno-effdate').on('change',function(){
                $('#newgl_epno-expdate').val($(this).val());
            });

            break;
        case 'Limit Amount':
            $('#newgl_epno-effdate,#newgl_epno-expdate,#newgl_epno-visitno').prop('required',false);
            break;
        case 'Monthly Amount':
            $('#newgl_epno-effdate,#newgl_epno-expdate,#newgl_epno-visitno').prop('required',false);
            break;
    }
}

function autoinsert_rowdata_gl_epno(selrowdata){

	let obj_param = {
       action:'loadgl',
       mrn:selrowdata.mrn,
       episno:selrowdata.episno
   };

    $.get( "pat_enq/table?"+$.param(obj_param), function( data ) {
        
    },'json').done(function(data) {
        if(data.data != null){
		    $('#newgl_epno-staffid').val(data.data.staffid);
		    $('#newgl_epno-name').val(data.data.name);
		    $('#txt_newgl_epno_corpcomp').val(data.data.debtor_name);
		    $('#hid_newgl_epno_corpcomp').val(data.data.debtorcode);
		    $('#txt_newgl_epno_occupcode').val(data.data.occup_desc);
		    $('#hid_newgl_epno_occupcode').val(data.data.occupcode);
		    $('#txt_newgl_epno_relatecode').val(data.data.relate_desc);
		    $('#hid_newgl_epno_relatecode').val(data.data.relatecode);
		    $('#newgl_epno-childno').val(data.data.childno);
		    $('#newgl_epno-gltype').val(data.data.gltype);
		    $('#newgl_epno-effdate').val(data.data.startdate);
		    $('#newgl_epno-expdate').val(data.data.enddate);
		    $('#newgl_epno-visitno').val(data.data.visitno);
		    $('#newgl_epno-case').val(data.data.case);
		    $('#newgl_epno-refno').val(data.data.refno);
		    $('#newgl_epno-ourrefno').val(data.data.ourrefno);
		    $('#newgl_epno-remark').val(data.data.remark);

		    $('#select_gl_tab a[href="'+data.data.gltype+'"]').tab('show');
		    onchg_gltype();
        }
    });

}