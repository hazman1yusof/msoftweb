var addnotes_epno = $('#addnotes_epno').DataTable({
    "ajax": "",
    "sDom": "",
    "paging":false,"aaSorting": [],
    "columns": [
            {'data': 'adddate'},
            {'data': 'addtime'},
            {'data': 'adduser'}
    ]
});

$(document).ready(function () {

    $('#addnotes_epno tbody').on('click', 'tr', function () { 
        var data = addnotes_epno.row( this ).data();
        if(data == undefined){
            return;
        }
        addnotes_epno.$('tr.active').removeClass('active');
        $(this).addClass('active');
        populate_epno_docinfo(data,'#form_epno_addnotes');
    });

	$('#my_a_dtin').click(function(){
		var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
		if(selrow != null){
			$('#mdl_docinfo').modal('show');
		}else{
			alert('Please select episode first')
		}
	});

	$("#mdl_docinfo").on("shown.bs.modal", function(){
		$('span#spanttl-docinfo').text(' ( '+bootgrid_last_row.Name+' - '+bootgrid_last_row.MRN+' - '+bootgrid_last_row.Episno+' )');
        emptyFormdata_div('#form_epno_addnotes');
        emptyFormdata_div('#form_epno_vitstate');
        emptyFormdata_div('#form_epno_diagnose');
		$('#admdoctor_text').text(bootgrid_last_row.q_doctorname);
        addnotes_epno.ajax.url( "./pat_enq/table?action=addnotes_epno&mrn="+bootgrid_last_row.MRN+"&episno="+bootgrid_last_row.Episno)
        .load(function(){
        	$('#addnotes_epno tbody tr:eq(0)').click();
        })
		$('#mrn_epno_docinfo').val(bootgrid_last_row.MRN);
		$('#episno_epno_docinfo').val(bootgrid_last_row.Episno);
		epno_addnotes_btnstate('default');
		init_vs_diag(bootgrid_last_row.MRN,bootgrid_last_row.Episno);
	});

	$('#add_epno_addnotes').click(function(){
        emptyFormdata_div('#form_epno_addnotes');
		epno_addnotes_btnstate('add_edit')
	});

	$('#save_epno_addnotes').click(function(){
		save_epno_addnotes();
	});

	$('#cancel_epno_addnotes').click(function(){
		epno_addnotes_btnstate('default')
	});


	$('#add_epno_vitstate').click(function(){
        emptyFormdata_div('#form_epno_vitstate');
		epno_vitstate_btnstate('add_edit');
	});

	$('#edit_epno_vitstate').click(function(){
		epno_vitstate_btnstate('add_edit');
	});

	$('#save_epno_vitstate').click(function(){
		save_epno_vitstate();
	});

	$('#cancel_epno_vitstate').click(function(){
		epno_vitstate_btnstate('default');
	});


	$('#add_epno_diagnose').click(function(){
        emptyFormdata_div('#form_epno_diagnose');
		epno_diagnose_btnstate('add_edit')
	});

	$('#edit_epno_diagnose').click(function(){
		epno_diagnose_btnstate('add_edit')
	});

	$('#save_epno_diagnose').click(function(){
		save_epno_diagnose();
	});

	$('#cancel_epno_diagnose').click(function(){
		epno_diagnose_btnstate('default')
	});

	$('#form_epno_vitstate input[name=height],#form_epno_vitstate input[name=weight]').blur(function(){
		calc_bmi_docinfo();
	});

});

function epno_addnotes_btnstate(state){
    switch(state){
        case 'all_disabled':
            $('#add_epno_addnotes').prop('disabled',true);
            $('#save_epno_addnotes').prop('disabled',true);
            $('#cancel_epno_addnotes').prop('disabled',true);
            break;
        case 'default':
            $('#add_epno_addnotes').prop('disabled',false);
            $('#save_epno_addnotes').prop('disabled',true);
            $('#cancel_epno_addnotes').prop('disabled',true);
            disableForm('#form_epno_addnotes');
            break;
        case 'add_edit':
            $('#add_epno_addnotes').prop('disabled',true);
            $('#save_epno_addnotes').prop('disabled',false);
            $('#cancel_epno_addnotes').prop('disabled',false);
            enableForm('#form_epno_addnotes');
            break;
    }
}

function epno_vitstate_btnstate(state){
    switch(state){
        case 'all_disabled':
            $('#add_epno_vitstate').prop('disabled',true);
            $('#edit_epno_vitstate').prop('disabled',true);
            $('#save_epno_vitstate').prop('disabled',true);
            $('#cancel_epno_vitstate').prop('disabled',true);
            break;
        case 'default':
            $('#add_epno_vitstate').prop('disabled',false);
            $('#edit_epno_vitstate').prop('disabled',false);
            $('#save_epno_vitstate').prop('disabled',true);
            $('#cancel_epno_vitstate').prop('disabled',true);
            disableForm('#form_epno_vitstate');
            break;
        case 'add_edit':
            $('#add_epno_vitstate').prop('disabled',true);
            $('#edit_epno_vitstate').prop('disabled',true);
            $('#save_epno_vitstate').prop('disabled',false);
            $('#cancel_epno_vitstate').prop('disabled',false);
            enableForm('#form_epno_vitstate');
            break;
    }
}

function epno_diagnose_btnstate(state){
    switch(state){
        case 'all_disabled':
            $('#add_epno_diagnose').prop('disabled',true);
            $('#edit_epno_diagnose').prop('disabled',true);
            $('#save_epno_diagnose').prop('disabled',true);
            $('#cancel_epno_diagnose').prop('disabled',true);
            break;
        case 'default':
            $('#add_epno_diagnose').prop('disabled',false);
            $('#edit_epno_diagnose').prop('disabled',false);
            $('#save_epno_diagnose').prop('disabled',true);
            $('#cancel_epno_diagnose').prop('disabled',true);
            disableForm('#form_epno_diagnose');
            break;
        case 'add_edit':
            $('#add_epno_diagnose').prop('disabled',true);
            $('#edit_epno_diagnose').prop('disabled',true);
            $('#save_epno_diagnose').prop('disabled',false);
            $('#cancel_epno_diagnose').prop('disabled',false);
            enableForm('#form_epno_diagnose');
            break;
    }
}

function save_epno_addnotes(){
    if($('#form_epno_addnotes').valid()){
        epno_addnotes_btnstate('all_disabled');

        var _token = $('#csrf_token').val();
        let serializedForm = $("#form_epno_addnotes").serializeArray();
        let obj = {
            'action': 'save_epno_addnotes',
            'doctorcode': bootgrid_last_row.admdoctor,
            'mrn':bootgrid_last_row.MRN,
            'episno': bootgrid_last_row.Episno,
            '_token': _token,
        };
        
        $.post('./pat_enq/form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
            
        },'json').fail(function(data) {
            alert('ERROR');
            epno_addnotes_btnstate('default');
            addnotes_epno.ajax.url( "./pat_enq/table?action=addnotes_epno&mrn="+bootgrid_last_row.MRN+"&episno="+bootgrid_last_row.Episno)
	        .load(function(){
	        	$('#addnotes_epno tbody tr:eq(0)').click();
	        })
        }).done(function(data){
            epno_addnotes_btnstate('default');
            addnotes_epno.ajax.url( "./pat_enq/table?action=addnotes_epno&mrn="+bootgrid_last_row.MRN+"&episno="+bootgrid_last_row.Episno)
	        .load(function(){
	        	$('#addnotes_epno tbody tr:eq(0)').click();
	        })
        });
    }
}

function save_epno_vitstate(){
    if($('#form_epno_vitstate').valid()){
        epno_vitstate_btnstate('all_disabled');

        var _token = $('#csrf_token').val();
        let serializedForm = $("#form_epno_vitstate").serializeArray();
        let obj = {
            'action': 'save_epno_vitstate',
            'mrn':bootgrid_last_row.MRN,
            'episno': bootgrid_last_row.Episno,
            '_token': _token,
        };
        
        $.post('./pat_enq/form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
            
        },'json').fail(function(data) {
            alert('ERROR');
            epno_vitstate_btnstate('default');
        }).done(function(data){
            epno_vitstate_btnstate('default');
        });
    }
}

function save_epno_diagnose(){
    if($('#form_epno_diagnose').valid()){
        epno_diagnose_btnstate('all_disabled');

        var _token = $('#csrf_token').val();
        let serializedForm = $("#form_epno_diagnose").serializeArray();
        let obj = {
            'action': 'save_epno_diagnose',
            'mrn':bootgrid_last_row.MRN,
            'episno': bootgrid_last_row.Episno,
            '_token': _token,
        };
        
        $.post('./pat_enq/form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
            
        },'json').fail(function(data) {
            alert('ERROR');
            epno_diagnose_btnstate('default');
        }).done(function(data){
            epno_diagnose_btnstate('default');
        });
    }
}

function init_vs_diag(mrn,episno){
	var param={
		action:'init_vs_diag',
		url: './pat_enq/table',
		mrn:mrn,
		episno:episno
	}
	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		check_editadd_vs(data);
		check_editadd_diag(data);
	});
}

function populate_epno_docinfo(data,form){
	var form = form;
	var except = [];

	$.each(data, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else if( except != undefined && except.indexOf(index) === -1){
			input.val(decodeEntities(value));
		}
	});
}

function check_editadd_vs(data){
	$('#add_epno_vitstate,#edit_epno_vitstate').hide();
	if(data.pathealth == null){
		$('#add_epno_vitstate').show();
		return false;
	}
	if(data.pathealth.height!=null||data.pathealth.weight!=null||data.pathealth.bp_sys1!=null||data.pathealth.pulse!=null||data.pathealth.temperature!=null||data.pathealth.respiration!=null||data.pathealth.colorblind!=null||data.pathealth.visionl!=null||data.pathealth.visionr!=null){
		$('#edit_epno_vitstate').show();
		populate_epno_docinfo(data.pathealth,'#form_epno_vitstate');
		calc_bmi_docinfo();
	}else{
		$('#add_epno_vitstate').show();
	}
	epno_vitstate_btnstate('default');
}

function check_editadd_diag(data){
	$('#add_epno_diagnose,#edit_epno_diagnose').hide();
	if(data.episode.diagprov!=null||data.episode.diagfinal!=null||data.episode.procedure!=null){
		$('#edit_epno_diagnose').show();
		populate_epno_docinfo(data.episode,'#form_epno_diagnose');
	}else{
		$('#add_epno_diagnose').show();
	}
	epno_diagnose_btnstate('default');
}

function calc_bmi_docinfo(){
	let h = $('#form_epno_vitstate input[name=height]').val().trim();
	let w = $('#form_epno_vitstate input[name=weight]').val().trim();
	$('#form_epno_vitstate input[name=bmi]').val('');

	if(h=='')return false;
	if(w=='')return false;

	let bmi = parseFloat(w)/(parseFloat(h/100)*parseFloat(h/100));

	$('#form_epno_vitstate input[name=bmi]').val(bmi.toFixed(2));
}
